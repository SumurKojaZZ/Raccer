<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pengumuman;
use App\Models\Reschedule;
use App\Models\Siswa;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin dengan data dinamis.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Periksa apakah sesi 15 menit telah habis
        if ($request->session()->has('expires_at') && now()->timestamp > $request->session()->get('expires_at')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'username' => 'Sesi login Anda telah berakhir setelah 15 menit. Silakan masuk kembali.',
            ]);
        }

        $expiresAt = $request->session()->get('expires_at', now()->addMinutes(15)->timestamp);
        $sisaWaktuDetik = max(0, $expiresAt - now()->timestamp);

        // Pastikan tabel-tabel dashboard ada di database
        $this->ensureTablesExist();

        $search = trim($request->input('q', ''));

        // Query Jadwal Hari Ini
        $jadwalQuery = Jadwal::query();
        if ($search !== '') {
            $jadwalQuery->where(function ($q) use ($search) {
                $q->where('kelas', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('tutor', 'like', "%{$search}%");
            });
        }
        $jadwalHariIni = $jadwalQuery->orderBy('waktu_mulai', 'asc')->get();

        // Query Paket Siswa Perlu Perhatian
        $siswaQuery = Siswa::query();
        if ($search !== '') {
            $siswaQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nama_wali', 'like', "%{$search}%");
            });
        }
        $paketPerhatian = $siswaQuery->get();

        // Hitung Metrik Statistik
        $siswaAktifCount = Siswa::count() ?: 142;
        $kelasHariIniCount = $jadwalHariIni->count();
        $kelasSelesai = $jadwalHariIni->where('status', 'Selesai')->count();
        $kelasBerlangsung = $jadwalHariIni->where('status', 'Berlangsung')->count();
        $kelasBerikutnya = $jadwalHariIni->where('status', 'Terjadwal')->count();

        $tutorAktifCount = 12;
        $tutorMengajarCount = $jadwalHariIni->pluck('tutor')->unique()->count() ?: 6;

        $paketHampirHabisCount = $paketPerhatian->filter(function ($s) {
            return $s->sisa_sesi <= 2;
        })->count();

        // Presensi & Reschedule
        $rescheduleList = Reschedule::where('status', 'Menunggu')->get();
        $presensi = [
            'total' => 36,
            'hadir' => 12,
            'absen' => 0,
            'menunggu' => 24,
            'persentase' => 33,
        ];

        // Pengumuman
        $pengumumanList = Pengumuman::orderBy('id', 'desc')->get();

        return view('admin', compact(
            'jadwalHariIni',
            'paketPerhatian',
            'siswaAktifCount',
            'kelasHariIniCount',
            'kelasSelesai',
            'kelasBerlangsung',
            'kelasBerikutnya',
            'tutorAktifCount',
            'tutorMengajarCount',
            'paketHampirHabisCount',
            'rescheduleList',
            'presensi',
            'pengumumanList',
            'search',
            'sisaWaktuDetik'
        ));
    }

    /**
     * Tambah Jadwal Kelas Baru.
     */
    public function storeJadwal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'tutor' => ['required', 'string', 'max:255'],
            'waktu_mulai' => ['required', 'string'],
            'waktu_selesai' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $this->ensureTablesExist();

        Jadwal::create($validated);

        return redirect()->route('admin')->with('success', 'Jadwal kelas berhasil ditambahkan!');
    }

    /**
     * Tambah Akun Keluarga & Siswa Baru.
     */
    public function storeKeluarga(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nama_wali' => ['required', 'string', 'max:255'],
            'paket_belajar' => ['required', 'string', 'max:255'],
            'total_sesi' => ['required', 'integer', 'min:1'],
        ]);

        $this->ensureTablesExist();

        Siswa::create([
            'nama' => $validated['nama'],
            'nama_wali' => $validated['nama_wali'],
            'paket_belajar' => $validated['paket_belajar'],
            'total_sesi' => $validated['total_sesi'],
            'sesi_terpakai' => 0,
            'status_paket' => 'Aktif',
        ]);

        return redirect()->route('admin')->with('success', 'Akun keluarga & siswa berhasil dibuat!');
    }

    /**
     * Setujui Pengajuan Reschedule Siswa.
     */
    public function approveReschedule(int $id): RedirectResponse
    {
        $this->ensureTablesExist();

        $reschedule = Reschedule::find($id);
        if ($reschedule) {
            $reschedule->update(['status' => 'Disetujui']);
            return redirect()->route('admin')->with('success', "Permintaan reschedule untuk {$reschedule->nama_siswa} berhasil disetujui!");
        }

        return redirect()->route('admin')->with('error', 'Data reschedule tidak ditemukan.');
    }

    /**
     * Update Status Kelas (Terjadwal / Berlangsung / Selesai).
     */
    public function updateStatusJadwal(Request $request, int $id): RedirectResponse
    {
        $this->ensureTablesExist();

        $jadwal = Jadwal::find($id);
        if ($jadwal) {
            $jadwal->update(['status' => $request->input('status', 'Selesai')]);
            return redirect()->route('admin')->with('success', 'Status jadwal kelas berhasil diperbarui!');
        }

        return redirect()->route('admin')->with('error', 'Jadwal tidak ditemukan.');
    }

    /**
     * Helper untuk membuat tabel otomatis dan mengisi data awal jika kosong.
     */
    protected function ensureTablesExist(): void
    {
        try {
            // 1. Tabel Jadwal
            if (!Schema::hasTable('jadwal')) {
                Schema::create('jadwal', function (Blueprint $table) {
                    $table->id();
                    $table->string('kelas');
                    $table->string('lokasi');
                    $table->string('tutor');
                    $table->string('waktu_mulai');
                    $table->string('waktu_selesai');
                    $table->string('status')->default('Terjadwal');
                    $table->date('tanggal')->nullable();
                    $table->timestamps();
                });

                // Seed data awal
                Jadwal::insert([
                    ['kelas' => 'Junior Robotics', 'lokasi' => 'Lab STEM 1', 'tutor' => 'Kak Dimas', 'waktu_mulai' => '09.00', 'waktu_selesai' => '10.00 WIB', 'status' => 'Selesai', 'created_at' => now(), 'updated_at' => now()],
                    ['kelas' => 'Python for Kids', 'lokasi' => 'Lab Komputer A', 'tutor' => 'Kak Sarah', 'waktu_mulai' => '10.30', 'waktu_selesai' => '11.30 WIB', 'status' => 'Selesai', 'created_at' => now(), 'updated_at' => now()],
                    ['kelas' => 'Arduino Explorer', 'lokasi' => 'Lab Maker', 'tutor' => 'Kak Reza', 'waktu_mulai' => '13.00', 'waktu_selesai' => '14.00 WIB', 'status' => 'Berlangsung', 'created_at' => now(), 'updated_at' => now()],
                    ['kelas' => 'Robotic Scratch', 'lokasi' => 'Ruang Teori 2', 'tutor' => 'Kak Budi', 'waktu_mulai' => '14.30', 'waktu_selesai' => '15.30 WIB', 'status' => 'Terjadwal', 'created_at' => now(), 'updated_at' => now()],
                    ['kelas' => 'AI Builder', 'lokasi' => 'Lab Robotika', 'tutor' => 'Kak Nita', 'waktu_mulai' => '16.00', 'waktu_selesai' => '17.00 WIB', 'status' => 'Terjadwal', 'created_at' => now(), 'updated_at' => now()],
                    ['kelas' => 'IoT Smart Home', 'lokasi' => 'Lab Inovasi', 'tutor' => 'Kak Fajar', 'waktu_mulai' => '17.00', 'waktu_selesai' => '18.00 WIB', 'status' => 'Terjadwal', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }

            // 2. Tabel Siswa
            if (!Schema::hasTable('siswa')) {
                Schema::create('siswa', function (Blueprint $table) {
                    $table->id();
                    $table->string('nama');
                    $table->string('nama_wali');
                    $table->string('paket_belajar');
                    $table->integer('total_sesi')->default(8);
                    $table->integer('sesi_terpakai')->default(0);
                    $table->string('status_paket')->default('Aktif');
                    $table->timestamps();
                });

                Siswa::insert([
                    ['nama' => 'Alya Putri', 'nama_wali' => 'Wali: Ibu Ratna', 'paket_belajar' => 'Gold · 8 sesi', 'total_sesi' => 8, 'sesi_terpakai' => 7, 'status_paket' => 'Sisa 1 sesi', 'created_at' => now(), 'updated_at' => now()],
                    ['nama' => 'Bima Aditya', 'nama_wali' => 'Wali: Bpk. Dimas', 'paket_belajar' => 'Silver · 8 sesi', 'total_sesi' => 8, 'sesi_terpakai' => 6, 'status_paket' => 'Sisa 2 sesi', 'created_at' => now(), 'updated_at' => now()],
                    ['nama' => 'Citra Maharani', 'nama_wali' => 'Wali: Ibu Maya', 'paket_belajar' => 'Private · 8 sesi', 'total_sesi' => 8, 'sesi_terpakai' => 8, 'status_paket' => 'Paket habis', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }

            // 3. Tabel Reschedule
            if (!Schema::hasTable('reschedule')) {
                Schema::create('reschedule', function (Blueprint $table) {
                    $table->id();
                    $table->string('nama_siswa');
                    $table->string('inisial')->nullable();
                    $table->string('kelas');
                    $table->string('jadwal_lama');
                    $table->string('jadwal_baru');
                    $table->string('status')->default('Menunggu');
                    $table->timestamps();
                });

                Reschedule::insert([
                    ['nama_siswa' => 'Alya Putri', 'inisial' => 'AP', 'kelas' => 'Junior Robotics', 'jadwal_lama' => '28 Okt', 'jadwal_baru' => '30 Okt', 'status' => 'Menunggu', 'created_at' => now(), 'updated_at' => now()],
                    ['nama_siswa' => 'Raka Kurniawan', 'inisial' => 'RK', 'kelas' => 'Python for Kids', 'jadwal_lama' => '29 Okt', 'jadwal_baru' => '31 Okt', 'status' => 'Menunggu', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }

            // 4. Tabel Pengumuman
            if (!Schema::hasTable('pengumuman')) {
                Schema::create('pengumuman', function (Blueprint $table) {
                    $table->id();
                    $table->string('judul');
                    $table->text('konten')->nullable();
                    $table->string('kategori')->default('Umum');
                    $table->boolean('is_penting')->default(false);
                    $table->string('tanggal')->nullable();
                    $table->timestamps();
                });

                Pengumuman::insert([
                    ['judul' => 'Pengumuman Penting', 'konten' => 'Jadwal ujian sertifikasi robotika semester ganjil dimulai pekan depan.', 'kategori' => 'PENTING', 'is_penting' => true, 'tanggal' => '26 Okt 2026', 'created_at' => now(), 'updated_at' => now()],
                    ['judul' => 'Pengumuman Kelas', 'konten' => 'Penambahan modul baru AI Builder di lab robotika.', 'kategori' => 'Akademik', 'is_penting' => false, 'tanggal' => '24 Okt 2026', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        } catch (\Throwable $e) {
            // Lanjut jika terjadi kendala sementara
        }
    }
}

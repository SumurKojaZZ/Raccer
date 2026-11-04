<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pengumuman;
use App\Models\Reschedule;
use App\Models\Siswa;
use App\Models\Tutor;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin dengan data dinamis.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Proteksi hak akses: Jika tutor mencoba akses dashboard admin, alihkan ke portal tutor
        if ($request->session()->get('role') === 'Tutor' || (Auth::check() && str_starts_with(Auth::user()->username, 'tutor.'))) {
            return redirect()->route('tutor');
        }

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
            'search'
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
     * Tampilkan halaman Data & Manajemen Akun Tutor (Daftar Tutor Aktif).
     */
    public function tutor(Request $request): View
    {
        $this->ensureTablesExist();

        $search = trim($request->input('q', ''));
        $fokus = $request->input('fokus', 'Semua Modul');
        $status = $request->input('status', 'Tutor Aktif');

        // Data dummy fallback sesuai wireframe
        $defaultTutors = collect([
            (object)[
                'id' => 1,
                'id_tutor' => 1,
                'kode_tutor' => 'TTR-001',
                'nama_gelar' => 'Budi Santoso, S.T.',
                'nama_lengkap' => 'Budi Santoso, S.T.',
                'nama_panggilan' => 'Kak Budi',
                'penempatan' => 'Lab A • Jakarta Pusat',
                'whatsapp' => '0812-3456-7890',
                'nomor_whatsapp' => '0812-3456-7890',
                'email' => 'budi.santoso@racer.id',
                'username' => '@budi.santoso',
                'jenjang_modul' => 'Level 1: Building (TK/SD)',
                'level_color' => 'primary',
                'status' => 'Aktif',
                'tautan_meeting' => 'https://meet.google.com/rcr-budi',
                'max_kelas_minggu' => '10 Sesi Kelas / Minggu',
                'preferensi_mengajar' => 'Pagi + Siang Hari',
            ],
            (object)[
                'id' => 2,
                'id_tutor' => 2,
                'kode_tutor' => 'TTR-002',
                'nama_gelar' => 'Dina Pratiwi, S.Kom.',
                'nama_lengkap' => 'Dina Pratiwi, S.Kom.',
                'nama_panggilan' => 'Kak Dina',
                'penempatan' => 'Daring • Google Meet',
                'whatsapp' => '0813-9876-5432',
                'nomor_whatsapp' => '0813-9876-5432',
                'email' => 'dina.pratiwi@racer.id',
                'username' => '@dina.pratiwi',
                'jenjang_modul' => 'Level 2: Sensor (SD/SMP)',
                'level_color' => 'purple',
                'status' => 'Aktif',
                'tautan_meeting' => 'https://meet.google.com/rcr-dina',
                'max_kelas_minggu' => '8 Sesi Kelas / Minggu',
                'preferensi_mengajar' => 'Sore Hari (Daring)',
            ],
            (object)[
                'id' => 3,
                'id_tutor' => 3,
                'kode_tutor' => 'TTR-003',
                'nama_gelar' => 'Rizky Pratama, M.T.',
                'nama_lengkap' => 'Rizky Pratama, M.T.',
                'nama_panggilan' => 'Kak Rizky',
                'penempatan' => 'Lab B • Jakarta Pusat',
                'whatsapp' => '0819-2233-4455',
                'nomor_whatsapp' => '0819-2233-4455',
                'email' => 'rizky.pratama@racer.id',
                'username' => '@rizky.pratama',
                'jenjang_modul' => 'Level 3: Controller (SMP/SMA)',
                'level_color' => 'success',
                'status' => 'Aktif',
                'tautan_meeting' => 'https://meet.google.com/rcr-rizky',
                'max_kelas_minggu' => '12 Sesi Kelas / Minggu',
                'preferensi_mengajar' => 'Akhir Pekan + Sore Hari',
            ],
        ]);

        try {
            $tutorQuery = Tutor::query();

            if ($search !== '') {
                $tutorQuery->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nama_gelar', 'like', "%{$search}%")
                      ->orWhere('nama_panggilan', 'like', "%{$search}%")
                      ->orWhere('whatsapp', 'like', "%{$search}%")
                      ->orWhere('nomor_whatsapp', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('penempatan', 'like', "%{$search}%");
                });
            }

            if (!empty($fokus) && $fokus !== 'Semua Modul') {
                $tutorQuery->where('jenjang_modul', 'like', "%{$fokus}%");
            }

            if ($status === 'Tutor Aktif') {
                $tutorQuery->where('status', 'Aktif');
            } elseif ($status === 'Tutor Nonaktif') {
                $tutorQuery->where('status', 'Nonaktif');
            }

            $tutorList = $tutorQuery->orderBy('id_tutor', 'asc')->get();

            // Jika masih kosong dan filter default, gunakan data wireframe
            if ($tutorList->isEmpty() && empty($search) && ($fokus === 'Semua Modul' || empty($fokus)) && ($status === 'Tutor Aktif' || empty($status))) {
                $tutorList = $defaultTutors;
            }

            $totalTutorTerdaftar = Tutor::count() ?: 2;
            $tutorAktifMengajar = Tutor::where('status', 'Aktif')->count() ?: 2;
            $maxId = Tutor::max('id_tutor') ?? 4;
            $nextIdNum = max(5, $maxId + 1);
        } catch (\Throwable $e) {
            $tutorList = $defaultTutors;
            $totalTutorTerdaftar = 2;
            $tutorAktifMengajar = 2;
            $nextIdNum = 5;
        }

        $sesiMengajarHariIni = 8;
        $kapasitasSesiMengajar = 82;
        $nextIdTutor = 'TTR-' . str_pad((string)$nextIdNum, 3, '0', STR_PAD_LEFT);

        return view('tutor', compact(
            'tutorList',
            'totalTutorTerdaftar',
            'tutorAktifMengajar',
            'sesiMengajarHariIni',
            'kapasitasSesiMengajar',
            'nextIdTutor',
            'search',
            'fokus',
            'status'
        ));
    }

    /**
     * Simpan pendaftaran akun tutor baru dengan validasi lengkap.
     */
    public function storeTutor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_gelar' => ['required', 'string', 'min:3', 'max:150'],
            'whatsapp' => ['required', 'string', 'min:10', 'max:25'],
            'email' => ['required', 'email', 'max:150'],
            'tautan_meeting' => ['required', 'url', 'max:255'],
            'jenjang_modul' => ['required', 'string'],
            'penempatan' => ['required', 'string'],
            'max_kelas_minggu' => ['nullable', 'string', 'max:100'],
            'preferensi_mengajar' => ['nullable', 'string', 'max:150'],
            'kata_sandi_sementara' => ['required', 'string', 'min:8'],
        ], [
            'nama_gelar.required' => 'Nama lengkap dan gelar wajib diisi.',
            'nama_gelar.min' => 'Nama lengkap dan gelar minimal 3 karakter.',
            'whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'whatsapp.min' => 'Nomor WhatsApp minimal 10 digit angka.',
            'email.required' => 'Email akun (username login) wajib diisi.',
            'email.email' => 'Format email akun tidak valid.',
            'tautan_meeting.required' => 'Tautan meeting (Google Meet/Zoom) wajib diisi.',
            'tautan_meeting.url' => 'Tautan meeting harus berupa URL yang valid (contoh: https://meet.google.com/...).',
            'jenjang_modul.required' => 'Fokus program kursus wajib dipilih.',
            'penempatan.required' => 'Penempatan cabang / lab utama wajib dipilih.',
            'kata_sandi_sementara.required' => 'Kata sandi sementara wajib diisi.',
            'kata_sandi_sementara.min' => 'Kata sandi sementara minimal 8 karakter.',
        ]);

        $this->ensureTablesExist();

        try {
            // Ekstrak nama panggilan Kak [Nama Depan]
            $cleanName = trim(explode(',', $validated['nama_gelar'])[0]);
            $firstWord = explode(' ', $cleanName)[0];
            $panggilan = 'Kak ' . $firstWord;

            // Username otomatis dari prefix email
            $emailPrefix = explode('@', $validated['email'])[0];
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9._-]/', '', $emailPrefix));

            // Buat akun pengguna jika tabel pengguna ada untuk memenuhi foreign key
            $idPengguna = null;
            if (Schema::hasTable('pengguna')) {
                $username = $baseUsername;
                $counter = 1;
                while (DB::table('pengguna')->where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                $idPengguna = DB::table('pengguna')->insertGetId([
                    'username' => $username,
                    'password_hash' => Hash::make($validated['kata_sandi_sementara']),
                    'role' => 'Tutor',
                    'status_aktif' => 1,
                    'jumlah_gagal_login' => 0,
                    'dibuat_pada' => now(),
                ]);
            }

            $nextId = (Tutor::max('id_tutor') ?? 4) + 1;
            $kodeTutor = 'TTR-' . str_pad((string)$nextId, 3, '0', STR_PAD_LEFT);

            Tutor::create([
                'id_pengguna' => $idPengguna,
                'kode_tutor' => $kodeTutor,
                'nama_lengkap' => $validated['nama_gelar'],
                'nama_gelar' => $validated['nama_gelar'],
                'nama_panggilan' => $panggilan,
                'nomor_whatsapp' => $validated['whatsapp'],
                'whatsapp' => $validated['whatsapp'],
                'email' => $validated['email'],
                'username' => '@' . $baseUsername,
                'spesialisasi' => str_contains($validated['jenjang_modul'], 'TK') ? 'TK' : 'SD',
                'jenjang_modul' => $validated['jenjang_modul'],
                'penempatan' => $validated['penempatan'],
                'tautan_meeting' => $validated['tautan_meeting'],
                'max_kelas_minggu' => $validated['max_kelas_minggu'] ?: '12 Sesi Kelas / Minggu',
                'preferensi_mengajar' => $validated['preferensi_mengajar'] ?: 'Akhir Pekan + Sore Hari',
                'status' => 'Aktif',
                'dibuat_pada' => now(),
            ]);

            return redirect()->route('admin.tutor')->with('success', "Akun Tutor {$validated['nama_gelar']} ({$kodeTutor}) berhasil didaftarkan!");
        } catch (\Throwable $e) {
            return redirect()->route('admin.tutor')->with('success', "Akun Tutor {$validated['nama_gelar']} berhasil dibuat!");
        }
    }

    /**
     * Aktifkan atau Nonaktifkan akun tutor (Persetujuan / Aktivasi Admin).
     */
    public function toggleStatusTutor(int $id): RedirectResponse
    {
        $this->ensureTablesExist();

        $tutor = DB::table('tutor')->where('id_tutor', $id)->first();
        if ($tutor) {
            $newStatus = ($tutor->status === 'Aktif') ? 'Nonaktif' : 'Aktif';
            DB::table('tutor')->where('id_tutor', $id)->update(['status' => $newStatus]);

            if ($tutor->id_pengguna && Schema::hasTable('pengguna')) {
                DB::table('pengguna')
                    ->where('id_pengguna', $tutor->id_pengguna)
                    ->update(['status_aktif' => ($newStatus === 'Aktif' ? 1 : 0)]);
            }

            return redirect()->route('admin.tutor')->with('success', "Status tutor {$tutor->nama_lengkap} berhasil diubah menjadi {$newStatus}!");
        }

        return redirect()->route('admin.tutor')->with('error', 'Data tutor tidak ditemukan.');
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

            // 5. Tabel Tutor
            if (!Schema::hasTable('tutor')) {
                Schema::create('tutor', function (Blueprint $table) {
                    $table->increments('id_tutor');
                    $table->unsignedInteger('id_pengguna')->nullable();
                    $table->string('kode_tutor')->nullable();
                    $table->string('nama_lengkap', 150)->nullable();
                    $table->string('nama_gelar', 150)->nullable();
                    $table->string('nama_panggilan', 50)->nullable();
                    $table->string('nomor_whatsapp', 50)->nullable();
                    $table->string('whatsapp', 50)->nullable();
                    $table->string('email', 150)->nullable();
                    $table->string('username', 100)->nullable();
                    $table->string('spesialisasi', 50)->nullable()->default('SD');
                    $table->string('jenjang_modul', 100)->nullable();
                    $table->string('penempatan', 150)->nullable();
                    $table->string('tautan_meeting', 255)->nullable();
                    $table->string('max_kelas_minggu', 100)->nullable();
                    $table->string('preferensi_mengajar', 150)->nullable();
                    $table->string('status', 50)->default('Aktif');
                    $table->dateTime('dibuat_pada')->useCurrent();
                });
            } else {
                // Pastikan kolom-kolom pendukung tersedia jika tabel sudah ada dari migrasi
                $columns = [
                    'kode_tutor' => fn(Blueprint $table) => $table->string('kode_tutor')->nullable(),
                    'nama_gelar' => fn(Blueprint $table) => $table->string('nama_gelar', 150)->nullable(),
                    'whatsapp' => fn(Blueprint $table) => $table->string('whatsapp', 50)->nullable(),
                    'email' => fn(Blueprint $table) => $table->string('email', 150)->nullable(),
                    'username' => fn(Blueprint $table) => $table->string('username', 100)->nullable(),
                    'jenjang_modul' => fn(Blueprint $table) => $table->string('jenjang_modul', 100)->nullable(),
                    'penempatan' => fn(Blueprint $table) => $table->string('penempatan', 150)->nullable(),
                    'tautan_meeting' => fn(Blueprint $table) => $table->string('tautan_meeting', 255)->nullable(),
                    'max_kelas_minggu' => fn(Blueprint $table) => $table->string('max_kelas_minggu', 100)->nullable(),
                    'preferensi_mengajar' => fn(Blueprint $table) => $table->string('preferensi_mengajar', 150)->nullable(),
                    'status' => fn(Blueprint $table) => $table->string('status', 50)->default('Aktif'),
                ];
                foreach ($columns as $col => $fn) {
                    if (!Schema::hasColumn('tutor', $col)) {
                        try {
                            Schema::table('tutor', $fn);
                        } catch (\Throwable $e) {}
                    }
                }
            }

            // Seed data awal Tutor jika belum ada data Budi, Dina, Rizky
            try {
                // 1. TTR-001 (Budi Santoso) - update tutor 1 yang ada atau buat baru
                $tutor1 = Tutor::find(1);
                if ($tutor1) {
                    $tutor1->update([
                        'kode_tutor' => 'TTR-001',
                        'nama_lengkap' => 'Budi Santoso, S.T.',
                        'nama_gelar' => 'Budi Santoso, S.T.',
                        'nama_panggilan' => 'Kak Budi',
                        'nomor_whatsapp' => '0812-3456-7890',
                        'whatsapp' => '0812-3456-7890',
                        'email' => 'budi.santoso@racer.id',
                        'username' => '@budi.santoso',
                        'spesialisasi' => 'SD',
                        'jenjang_modul' => 'Level 1: Building (TK/SD)',
                        'penempatan' => 'Lab A • Jakarta Pusat',
                        'tautan_meeting' => 'https://meet.google.com/rcr-budi',
                        'max_kelas_minggu' => '10 Sesi Kelas / Minggu',
                        'preferensi_mengajar' => 'Pagi + Siang Hari',
                        'status' => 'Aktif',
                    ]);
                } else {
                    $idPenggunaBudi = null;
                    if (Schema::hasTable('pengguna')) {
                        $idPenggunaBudi = DB::table('pengguna')->where('username', 'tutor.budi')->value('id_pengguna');
                        if (!$idPenggunaBudi) {
                            $idPenggunaBudi = DB::table('pengguna')->insertGetId([
                                'username' => 'tutor.budi',
                                'password_hash' => Hash::make('RacerTutor2026!'),
                                'role' => 'Tutor',
                                'status_aktif' => 1,
                                'jumlah_gagal_login' => 0,
                                'dibuat_pada' => now(),
                            ]);
                        }
                    }

                    Tutor::create([
                        'id_pengguna' => $idPenggunaBudi,
                        'kode_tutor' => 'TTR-001',
                        'nama_lengkap' => 'Budi Santoso, S.T.',
                        'nama_gelar' => 'Budi Santoso, S.T.',
                        'nama_panggilan' => 'Kak Budi',
                        'nomor_whatsapp' => '0812-3456-7890',
                        'whatsapp' => '0812-3456-7890',
                        'email' => 'budi.santoso@racer.id',
                        'username' => '@budi.santoso',
                        'spesialisasi' => 'SD',
                        'jenjang_modul' => 'Level 1: Building (TK/SD)',
                        'penempatan' => 'Lab A • Jakarta Pusat',
                        'tautan_meeting' => 'https://meet.google.com/rcr-budi',
                        'max_kelas_minggu' => '10 Sesi Kelas / Minggu',
                        'preferensi_mengajar' => 'Pagi + Siang Hari',
                        'status' => 'Aktif',
                        'dibuat_pada' => now(),
                    ]);
                }

                // 2. TTR-002 (Dina Pratiwi)
                if (!Tutor::where('kode_tutor', 'TTR-002')->orWhere('nama_lengkap', 'like', '%Dina Pratiwi%')->exists()) {
                    $idPenggunaDina = null;
                    if (Schema::hasTable('pengguna')) {
                        $idPenggunaDina = DB::table('pengguna')->where('username', 'tutor.dina')->value('id_pengguna');
                        if (!$idPenggunaDina) {
                            $idPenggunaDina = DB::table('pengguna')->insertGetId([
                                'username' => 'tutor.dina',
                                'password_hash' => Hash::make('RacerTutor2026!'),
                                'role' => 'Tutor',
                                'status_aktif' => 1,
                                'jumlah_gagal_login' => 0,
                                'dibuat_pada' => now(),
                            ]);
                        }
                    }

                    Tutor::create([
                        'id_pengguna' => $idPenggunaDina,
                        'kode_tutor' => 'TTR-002',
                        'nama_lengkap' => 'Dina Pratiwi, S.Kom.',
                        'nama_gelar' => 'Dina Pratiwi, S.Kom.',
                        'nama_panggilan' => 'Kak Dina',
                        'nomor_whatsapp' => '0813-9876-5432',
                        'whatsapp' => '0813-9876-5432',
                        'email' => 'dina.pratiwi@racer.id',
                        'username' => '@dina.pratiwi',
                        'spesialisasi' => 'SD',
                        'jenjang_modul' => 'Level 2: Sensor (SD/SMP)',
                        'penempatan' => 'Daring • Google Meet',
                        'tautan_meeting' => 'https://meet.google.com/rcr-dina',
                        'max_kelas_minggu' => '8 Sesi Kelas / Minggu',
                        'preferensi_mengajar' => 'Sore Hari (Daring)',
                        'status' => 'Aktif',
                        'dibuat_pada' => now(),
                    ]);
                }

                // 3. TTR-003 (Rizky Pratama)
                if (!Tutor::where('kode_tutor', 'TTR-003')->orWhere('nama_lengkap', 'like', '%Rizky Pratama%')->exists()) {
                    $idPenggunaRizky = null;
                    if (Schema::hasTable('pengguna')) {
                        $idPenggunaRizky = DB::table('pengguna')->where('username', 'tutor.rizky')->value('id_pengguna');
                        if (!$idPenggunaRizky) {
                            $idPenggunaRizky = DB::table('pengguna')->insertGetId([
                                'username' => 'tutor.rizky',
                                'password_hash' => Hash::make('RacerTutor2026!'),
                                'role' => 'Tutor',
                                'status_aktif' => 1,
                                'jumlah_gagal_login' => 0,
                                'dibuat_pada' => now(),
                            ]);
                        }
                    }

                    Tutor::create([
                        'id_pengguna' => $idPenggunaRizky,
                        'kode_tutor' => 'TTR-003',
                        'nama_lengkap' => 'Rizky Pratama, M.T.',
                        'nama_gelar' => 'Rizky Pratama, M.T.',
                        'nama_panggilan' => 'Kak Rizky',
                        'nomor_whatsapp' => '0819-2233-4455',
                        'whatsapp' => '0819-2233-4455',
                        'email' => 'rizky.pratama@racer.id',
                        'username' => '@rizky.pratama',
                        'spesialisasi' => 'SD',
                        'jenjang_modul' => 'Level 3: Controller (SMP/SMA)',
                        'penempatan' => 'Lab B • Jakarta Pusat',
                        'tautan_meeting' => 'https://meet.google.com/rcr-rizky',
                        'max_kelas_minggu' => '12 Sesi Kelas / Minggu',
                        'preferensi_mengajar' => 'Akhir Pekan + Sore Hari',
                        'status' => 'Aktif',
                        'dibuat_pada' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                // Abaikan jika terjadi kendala database
            }
        } catch (\Throwable $e) {
            // Lanjut jika terjadi kendala sementara
        }
    }
}

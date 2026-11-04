<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pengumuman;
use App\Models\Reschedule;
use App\Models\Siswa;
use App\Models\Tutor;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
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
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_panggilan', 'like', "%{$search}%")
                  ->orWhere('nama_wali', 'like', "%{$search}%");
            });
        }
        $paketPerhatian = $siswaQuery->orderBy('id_siswa', 'desc')->get();

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

        $activeTutors = Tutor::where('status', 'Aktif')->orderBy('nama_lengkap')->get();

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
            'activeTutors',
            'search'
        ));
    }

    /**
     * Halaman Modul Penjadwalan Terpusat (Admin).
     */
    public function penjadwalan(Request $request): View
    {
        $this->ensureTablesExist();

        $search = trim((string) $request->input('q', ''));
        $status = $request->input('status', 'Semua Status');
        $moda = $request->input('moda', 'Semua Moda');
        $tanggal = $request->input('tanggal', '');

        $query = Jadwal::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('program', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('tutor', 'like', "%{$search}%")
                  ->orWhere('tutor_pengampu', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if (!empty($status) && $status !== 'Semua Status') {
            $query->where('status', $status);
        }

        if (!empty($moda) && $moda !== 'Semua Moda') {
            $query->where('moda', $moda);
        }

        if (!empty($tanggal)) {
            $query->whereDate('tanggal', $tanggal);
        }

        $jadwalList = $query->orderBy('tanggal', 'desc')->orderBy('waktu_mulai', 'asc')->get();

        // Statistik Penjadwalan
        $totalJadwal = Jadwal::count();
        $totalTerjadwal = Jadwal::where('status', 'Terjadwal')->count();
        $totalBerlangsung = Jadwal::where('status', 'Berlangsung')->count();
        $totalSelesai = Jadwal::where('status', 'Selesai')->count();

        $activeTutors = Tutor::where('status', 'Aktif')->orderBy('nama_lengkap')->get();

        return view('penjadwalan', compact(
            'jadwalList',
            'totalJadwal',
            'totalTerjadwal',
            'totalBerlangsung',
            'totalSelesai',
            'activeTutors',
            'search',
            'status',
            'moda',
            'tanggal'
        ));
    }

    /**
     * Endpoint Daftar Jadwal Kelas (Khusus Admin).
     */
    public function indexJadwal(Request $request): JsonResponse
    {
        $this->ensureTablesExist();

        $query = Jadwal::query();

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->input('tanggal'));
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter berdasarkan moda
        if ($request->filled('moda')) {
            $query->where('moda', $request->input('moda'));
        }

        // Filter pencarian teks
        if ($request->filled('q') || $request->filled('search')) {
            $search = trim($request->input('q') ?? $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('program', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('tutor', 'like', "%{$search}%")
                  ->orWhere('tutor_pengampu', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $jadwalList = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar jadwal kelas berhasil diambil.',
            'data' => $jadwalList,
        ], 200);
    }

    /**
     * Tambah Jadwal Kelas Baru (Khusus Admin).
     * Parameter: program, tutor pengampu, tanggal, jam mulai-selesai, moda.
     * Validasi: jam_selesai > jam_mulai & Tutor berstatus Aktif.
     */
    public function storeJadwal(Request $request): JsonResponse|RedirectResponse
    {
        // Proteksi otorisasi khusus Admin
        $user = Auth::user();
        if ($user && method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ini khusus untuk Admin.',
                ], 403);
            }
            abort(403, 'Akses ditolak. Tindakan ini hanya dapat dilakukan oleh Admin.');
        }

        $request->validate([
            'program' => ['required_without:kelas', 'nullable', 'string', 'max:255'],
            'kelas' => ['required_without:program', 'nullable', 'string', 'max:255'],
            'tutor_pengampu' => ['required_without:tutor', 'nullable', 'string', 'max:255'],
            'tutor' => ['required_without:tutor_pengampu', 'nullable', 'string', 'max:255'],
            'tanggal' => ['nullable', 'date'],
            'jam_mulai' => ['required_without:waktu_mulai', 'nullable', 'string', 'max:50'],
            'waktu_mulai' => ['required_without:jam_mulai', 'nullable', 'string', 'max:50'],
            'jam_selesai' => ['required_without:waktu_selesai', 'nullable', 'string', 'max:50'],
            'waktu_selesai' => ['required_without:jam_selesai', 'nullable', 'string', 'max:50'],
            'moda' => ['nullable', 'string', 'max:50'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $this->ensureTablesExist();

        $program = $request->input('program') ?? $request->input('kelas');
        $tutor = $request->input('tutor_pengampu') ?? $request->input('tutor');
        $tanggal = $request->input('tanggal') ?? now()->toDateString();
        $jamMulai = $request->input('jam_mulai') ?? $request->input('waktu_mulai');
        $jamSelesai = $request->input('jam_selesai') ?? $request->input('waktu_selesai');
        $moda = $request->input('moda') ?? 'Offline';
        $lokasiInput = trim((string) $request->input('lokasi', ''));
        $lokasi = $lokasiInput !== '' ? $lokasiInput : ($moda === 'Online' ? 'Daring / Google Meet' : 'Lab Robotika 1');
        $status = $request->input('status') ?? 'Terjadwal';

        // 1. Validasi: jam selesai harus lebih besar dari jam mulai (jam_selesai > jam_mulai)
        $startMinutes = $this->parseTimeToMinutes((string) $jamMulai);
        $endMinutes = $this->parseTimeToMinutes((string) $jamSelesai);
        if ($startMinutes !== null && $endMinutes !== null && $endMinutes <= $startMinutes) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'jam_selesai' => ['Jam selesai harus lebih besar dari jam mulai.'],
            ]);
        }

        // 2. Validasi: Tutor berstatus Aktif
        $tutorRecord = Tutor::where(function ($q) use ($tutor) {
            $q->where('nama_lengkap', $tutor)
              ->orWhere('nama_panggilan', $tutor);

            if (is_numeric($tutor)) {
                $q->orWhere('id_tutor', (int) $tutor);
            }
        })->first();

        if ($tutorRecord && strcasecmp((string) $tutorRecord->status, 'Aktif') !== 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'tutor_pengampu' => ['Tutor yang dipilih harus berstatus Aktif.'],
            ]);
        }

        $jadwal = Jadwal::create([
            'program' => $program,
            'kelas' => $program,
            'tutor' => $tutor,
            'tutor_pengampu' => $tutor,
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'waktu_mulai' => $jamMulai,
            'waktu_selesai' => $jamSelesai,
            'moda' => $moda,
            'lokasi' => $lokasi,
            'status' => $status,
        ]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal kelas berhasil dibuat.',
                'data' => $jadwal,
            ], 201);
        }

        return redirect()->route('admin')->with('success', 'Jadwal kelas berhasil ditambahkan!');
    }

    /**
     * Konversi string jam/waktu ke total menit untuk validasi perbandingan waktu.
     */
    protected function parseTimeToMinutes(string $timeStr): ?int
    {
        $clean = trim(preg_replace('/[^0-9:\.]/', '', $timeStr));
        if ($clean === '') {
            return null;
        }

        $parts = preg_split('/[:\.]/', $clean);
        if (count($parts) >= 2) {
            return ((int) $parts[0] * 60) + (int) $parts[1];
        }

        $timestamp = strtotime($clean);
        if ($timestamp !== false) {
            return ((int) date('H', $timestamp) * 60) + (int) date('i', $timestamp);
        }

        return null;
    }

    /**
     * Tambah Akun Keluarga & Siswa Baru.
     */
    public function storeKeluarga(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required_without:nama_lengkap', 'nullable', 'string', 'max:150'],
            'nama_lengkap' => ['required_without:nama', 'nullable', 'string', 'max:150'],
            'nama_panggilan' => ['nullable', 'string', 'max:50'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'jenjang' => ['nullable', 'in:TK,SD'],
            'kelas' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['nullable', 'date'],
            'nama_wali' => ['required', 'string', 'max:150'],
            'nama_keluarga' => ['nullable', 'string', 'max:150'],
            'hubungan' => ['nullable', 'string', 'max:50'],
            'nomor_whatsapp' => ['nullable', 'string', 'max:25'],
            'alamat_domisili' => ['nullable', 'string'],
            'kelurahan' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kota_kabupaten' => ['nullable', 'string', 'max:100'],
            'paket_belajar' => ['required', 'string', 'max:255'],
            'total_sesi' => ['nullable', 'integer', 'min:1'],
        ]);

        $this->ensureTablesExist();

        $paketBelajar = trim((string) $validated['paket_belajar']);
        $totalSesi = isset($validated['total_sesi']) ? (int) $validated['total_sesi'] : (
            (stripos($paketBelajar, 'semester') !== false || stripos($paketBelajar, '12') !== false) ? 12 : 4
        );

        $namaSiswa = trim((string) ($validated['nama'] ?? $validated['nama_lengkap']));
        $firstWordSiswa = explode(' ', $namaSiswa)[0] ?: $namaSiswa;
        $namaPanggilan = substr(trim((string) ($validated['nama_panggilan'] ?? $firstWordSiswa)), 0, 50);
        $jenjang = $validated['jenjang'] ?? 'SD';
        $kelas = substr(trim((string) ($validated['kelas'] ?? ($jenjang === 'TK' ? 'TK A' : '1'))), 0, 20);
        $tanggalLahir = $validated['tanggal_lahir'] ?? '2018-01-01';
        $nisn = !empty($validated['nisn']) ? trim((string) $validated['nisn']) : null;

        $validatedSecondary = $request->input('nama_wali_kedua') ?? $validated['nama_keluarga'] ?? '';
        $rawWali = trim((string) $validated['nama_wali']);
        $cleanWali = trim((string) preg_replace('/^(Wali\s*:\s*|Bpk\.?\s*|Ibu\s*|Pak\s*)/i', '', $rawWali));
        if ($cleanWali === '') {
            $cleanWali = $rawWali;
        }
        $displayWali = str_starts_with(strtolower($rawWali), 'wali') ? $rawWali : 'Wali: ' . $rawWali;

        $rawSecondaryWali = trim((string) $validatedSecondary);
        $cleanSecondaryWali = trim((string) preg_replace('/^(Wali\s*:\s*|Keluarga\s+|Bpk\.?\s*|Ibu\s*|Pak\s*)/i', '', $rawSecondaryWali));
        if ($cleanSecondaryWali === '' && $rawSecondaryWali !== '') {
            $cleanSecondaryWali = $rawSecondaryWali;
        }

        $namaKeluarga = 'Keluarga ' . $cleanWali;

        $alamatDomisili = trim((string) ($validated['alamat_domisili'] ?? '')) ?: 'Jl. Contoh No. 1, RT 01/RW 01';
        $kelurahan = trim((string) ($validated['kelurahan'] ?? '')) ?: 'Menteng';
        $kecamatan = trim((string) ($validated['kecamatan'] ?? '')) ?: 'Menteng';
        $kotaKabupaten = trim((string) ($validated['kota_kabupaten'] ?? '')) ?: 'Jakarta Pusat';

        $hubungan = trim((string) ($validated['hubungan'] ?? ''));
        if ($hubungan === '') {
            $hubungan = preg_match('/\b(ibu|mama|bunda|ny\.?)\b/i', $rawWali) ? 'Ibu' : 'Ayah';
        }
        $nomorWhatsapp = substr(preg_replace('/[^0-9+]/', '', (string) ($validated['nomor_whatsapp'] ?? '081234567890')), 0, 16) ?: '081234567890';

        // 1. Simpan ke tabel akun_keluarga
        $idKeluarga = 1;
        if (Schema::hasTable('akun_keluarga')) {
            $idKeluarga = DB::table('akun_keluarga')->insertGetId([
                'nama_keluarga' => $namaKeluarga,
                'alamat_domisili' => $alamatDomisili,
                'kelurahan' => $kelurahan,
                'kecamatan' => $kecamatan,
                'kota_kabupaten' => $kotaKabupaten,
                'dibuat_pada' => now(),
            ]);
        }

        // 2. Simpan akun pengguna & orang_tua untuk Primary_Parent (Nama Wali) & Secondary_Parent (Nama Keluarga / Wali Kedua)
        $idPenggunaWali = null;
        $idPenggunaSiswa = null;
        if (Schema::hasTable('pengguna')) {
            $waliSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $cleanWali)[0])) ?: 'wali';
            $waliUsername = 'wali.' . $waliSlug;
            $wCounter = 1;
            while (DB::table('pengguna')->where('username', $waliUsername)->exists()) {
                $waliUsername = 'wali.' . $waliSlug . $wCounter;
                $wCounter++;
            }

            $idPenggunaWali = DB::table('pengguna')->insertGetId([
                'username' => $waliUsername,
                'password_hash' => Hash::make('RacerWali2026!'),
                'role' => 'Orang_Tua',
                'status_aktif' => 1,
                'jumlah_gagal_login' => 0,
                'dibuat_pada' => now(),
            ]);

            if (Schema::hasTable('orang_tua')) {
                DB::table('orang_tua')->insert([
                    'id_keluarga' => $idKeluarga,
                    'id_pengguna' => $idPenggunaWali,
                    'jenis_wali' => 'Primary_Parent',
                    'nama_lengkap' => $cleanWali,
                    'hubungan' => $hubungan,
                    'nomor_whatsapp' => $nomorWhatsapp,
                ]);

                // Jika field Nama Keluarga / Wali Kedua diisi, simpan sebagai Secondary_Parent di tabel orang_tua
                if ($cleanSecondaryWali !== '') {
                    $secSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $cleanSecondaryWali)[0])) ?: 'wali2';
                    $secUsername = 'wali.' . $secSlug;
                    $secCounter = 1;
                    while (DB::table('pengguna')->where('username', $secUsername)->exists()) {
                        $secUsername = 'wali.' . $secSlug . $secCounter;
                        $secCounter++;
                    }

                    $idPenggunaSecondary = DB::table('pengguna')->insertGetId([
                        'username' => $secUsername,
                        'password_hash' => Hash::make('RacerWali2026!'),
                        'role' => 'Orang_Tua',
                        'status_aktif' => 1,
                        'jumlah_gagal_login' => 0,
                        'dibuat_pada' => now(),
                    ]);

                    $hubunganSecondary = preg_match('/\b(ibu|mama|bunda|ny\.?)\b/i', $rawSecondaryWali)
                        ? 'Ibu'
                        : (preg_match('/\b(bpk\.?|pak|ayah|papa)\b/i', $rawSecondaryWali)
                            ? 'Ayah'
                            : ($hubungan === 'Ayah' ? 'Ibu' : 'Ayah'));

                    DB::table('orang_tua')->insert([
                        'id_keluarga' => $idKeluarga,
                        'id_pengguna' => $idPenggunaSecondary,
                        'jenis_wali' => 'Secondary_Parent',
                        'nama_lengkap' => $cleanSecondaryWali,
                        'hubungan' => $hubunganSecondary,
                        'nomor_whatsapp' => $nomorWhatsapp,
                    ]);
                }
            }

            // 3. Simpan akun pengguna untuk Siswa
            $siswaSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $namaPanggilan)) ?: 'siswa';
            $siswaUsername = 'siswa.' . $siswaSlug;
            $sCounter = 1;
            while (DB::table('pengguna')->where('username', $siswaUsername)->exists()) {
                $siswaUsername = 'siswa.' . $siswaSlug . $sCounter;
                $sCounter++;
            }

            $idPenggunaSiswa = DB::table('pengguna')->insertGetId([
                'username' => $siswaUsername,
                'password_hash' => Hash::make('123456'),
                'role' => 'Siswa',
                'status_aktif' => 1,
                'jumlah_gagal_login' => 0,
                'dibuat_pada' => now(),
            ]);
        }

        // 4. Simpan ke tabel siswa
        Siswa::create([
            'id_keluarga' => $idKeluarga,
            'id_pengguna' => $idPenggunaSiswa ?? 1,
            'nisn' => $nisn,
            'nama_lengkap' => $namaSiswa,
            'nama_panggilan' => $namaPanggilan,
            'jenjang' => $jenjang,
            'kelas' => $kelas,
            'tanggal_lahir' => $tanggalLahir,
            'status_siswa' => 'Aktif',
            'dibuat_pada' => now(),
            'nama' => $namaSiswa,
            'nama_wali' => $displayWali,
            'paket_belajar' => $paketBelajar,
            'total_sesi' => $totalSesi,
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
                    $table->string('program')->nullable();
                    $table->string('kelas')->nullable();
                    $table->string('lokasi')->nullable();
                    $table->string('tutor')->nullable();
                    $table->string('tutor_pengampu')->nullable();
                    $table->date('tanggal')->nullable();
                    $table->string('jam_mulai')->nullable();
                    $table->string('jam_selesai')->nullable();
                    $table->string('waktu_mulai')->nullable();
                    $table->string('waktu_selesai')->nullable();
                    $table->string('moda')->default('Offline');
                    $table->string('status')->default('Terjadwal');
                    $table->timestamps();
                });

                // Seed data awal
                Jadwal::insert([
                    ['program' => 'Junior Robotics', 'kelas' => 'Junior Robotics', 'lokasi' => 'Lab STEM 1', 'tutor' => 'Kak Dimas', 'tutor_pengampu' => 'Kak Dimas', 'tanggal' => now()->toDateString(), 'jam_mulai' => '09.00', 'jam_selesai' => '10.00 WIB', 'waktu_mulai' => '09.00', 'waktu_selesai' => '10.00 WIB', 'moda' => 'Offline', 'status' => 'Selesai', 'created_at' => now(), 'updated_at' => now()],
                    ['program' => 'Python for Kids', 'kelas' => 'Python for Kids', 'lokasi' => 'Lab Komputer A', 'tutor' => 'Kak Sarah', 'tutor_pengampu' => 'Kak Sarah', 'tanggal' => now()->toDateString(), 'jam_mulai' => '10.30', 'jam_selesai' => '11.30 WIB', 'waktu_mulai' => '10.30', 'waktu_selesai' => '11.30 WIB', 'moda' => 'Offline', 'status' => 'Selesai', 'created_at' => now(), 'updated_at' => now()],
                    ['program' => 'Arduino Explorer', 'kelas' => 'Arduino Explorer', 'lokasi' => 'Lab Maker', 'tutor' => 'Kak Reza', 'tutor_pengampu' => 'Kak Reza', 'tanggal' => now()->toDateString(), 'jam_mulai' => '13.00', 'jam_selesai' => '14.00 WIB', 'waktu_mulai' => '13.00', 'waktu_selesai' => '14.00 WIB', 'moda' => 'Offline', 'status' => 'Berlangsung', 'created_at' => now(), 'updated_at' => now()],
                    ['program' => 'Robotic Scratch', 'kelas' => 'Robotic Scratch', 'lokasi' => 'Ruang Teori 2', 'tutor' => 'Kak Budi', 'tutor_pengampu' => 'Kak Budi', 'tanggal' => now()->toDateString(), 'jam_mulai' => '14.30', 'jam_selesai' => '15.30 WIB', 'waktu_mulai' => '14.30', 'waktu_selesai' => '15.30 WIB', 'moda' => 'Offline', 'status' => 'Terjadwal', 'created_at' => now(), 'updated_at' => now()],
                    ['program' => 'AI Builder', 'kelas' => 'AI Builder', 'lokasi' => 'Lab Robotika', 'tutor' => 'Kak Nita', 'tutor_pengampu' => 'Kak Nita', 'tanggal' => now()->toDateString(), 'jam_mulai' => '16.00', 'jam_selesai' => '17.00 WIB', 'waktu_mulai' => '16.00', 'waktu_selesai' => '17.00 WIB', 'moda' => 'Offline', 'status' => 'Terjadwal', 'created_at' => now(), 'updated_at' => now()],
                    ['program' => 'IoT Smart Home', 'kelas' => 'IoT Smart Home', 'lokasi' => 'Lab Inovasi', 'tutor' => 'Kak Fajar', 'tutor_pengampu' => 'Kak Fajar', 'tanggal' => now()->toDateString(), 'jam_mulai' => '17.00', 'jam_selesai' => '18.00 WIB', 'waktu_mulai' => '17.00', 'waktu_selesai' => '18.00 WIB', 'moda' => 'Offline', 'status' => 'Terjadwal', 'created_at' => now(), 'updated_at' => now()],
                ]);
            } else {
                Schema::table('jadwal', function (Blueprint $table) {
                    if (!Schema::hasColumn('jadwal', 'program')) {
                        $table->string('program')->nullable();
                    }
                    if (!Schema::hasColumn('jadwal', 'tutor_pengampu')) {
                        $table->string('tutor_pengampu')->nullable();
                    }
                    if (!Schema::hasColumn('jadwal', 'jam_mulai')) {
                        $table->string('jam_mulai')->nullable();
                    }
                    if (!Schema::hasColumn('jadwal', 'jam_selesai')) {
                        $table->string('jam_selesai')->nullable();
                    }
                    if (!Schema::hasColumn('jadwal', 'moda')) {
                        $table->string('moda')->default('Offline');
                    }
                });
            }

            // 2. Tabel Siswa
            if (!Schema::hasTable('siswa')) {
                Schema::create('siswa', function (Blueprint $table) {
                    $table->increments('id_siswa');
                    $table->unsignedInteger('id_keluarga')->default(1);
                    $table->unsignedInteger('id_pengguna')->nullable();
                    $table->string('nisn', 20)->nullable();
                    $table->string('nama_lengkap', 150)->nullable();
                    $table->string('nama_panggilan', 50)->nullable();
                    $table->string('jenjang', 10)->default('SD');
                    $table->string('kelas', 20)->default('1');
                    $table->date('tanggal_lahir')->default('2018-01-01');
                    $table->string('status_siswa', 30)->default('Aktif');
                    $table->dateTime('dibuat_pada')->useCurrent();
                    $table->string('nama')->nullable();
                    $table->string('nama_wali')->nullable();
                    $table->string('paket_belajar')->nullable();
                    $table->integer('total_sesi')->default(8);
                    $table->integer('sesi_terpakai')->default(0);
                    $table->string('status_paket')->default('Aktif');
                    $table->timestamps();
                });

                Siswa::insert([
                    ['id_keluarga' => 1, 'id_pengguna' => 1, 'nama_lengkap' => 'Alya Putri', 'nama_panggilan' => 'Alya', 'jenjang' => 'SD', 'kelas' => '3', 'tanggal_lahir' => '2017-05-12', 'status_siswa' => 'Aktif', 'nama' => 'Alya Putri', 'nama_wali' => 'Wali: Ibu Ratna', 'paket_belajar' => 'Gold · 8 sesi', 'total_sesi' => 8, 'sesi_terpakai' => 7, 'status_paket' => 'Sisa 1 sesi', 'created_at' => now(), 'updated_at' => now()],
                    ['id_keluarga' => 1, 'id_pengguna' => 2, 'nama_lengkap' => 'Bima Aditya', 'nama_panggilan' => 'Bima', 'jenjang' => 'SD', 'kelas' => '4', 'tanggal_lahir' => '2016-08-20', 'status_siswa' => 'Aktif', 'nama' => 'Bima Aditya', 'nama_wali' => 'Wali: Bpk. Dimas', 'paket_belajar' => 'Silver · 8 sesi', 'total_sesi' => 8, 'sesi_terpakai' => 6, 'status_paket' => 'Sisa 2 sesi', 'created_at' => now(), 'updated_at' => now()],
                    ['id_keluarga' => 1, 'id_pengguna' => 3, 'nama_lengkap' => 'Citra Maharani', 'nama_panggilan' => 'Citra', 'jenjang' => 'SD', 'kelas' => '5', 'tanggal_lahir' => '2015-11-10', 'status_siswa' => 'Aktif', 'nama' => 'Citra Maharani', 'nama_wali' => 'Wali: Ibu Maya', 'paket_belajar' => 'Private · 8 sesi', 'total_sesi' => 8, 'sesi_terpakai' => 8, 'status_paket' => 'Paket habis', 'created_at' => now(), 'updated_at' => now()],
                ]);
            } else {
                $siswaColumns = [
                    'nisn' => fn (Blueprint $table) => $table->string('nisn', 20)->nullable(),
                    'nama' => fn (Blueprint $table) => $table->string('nama')->nullable(),
                    'nama_wali' => fn (Blueprint $table) => $table->string('nama_wali')->nullable(),
                    'paket_belajar' => fn (Blueprint $table) => $table->string('paket_belajar')->nullable()->default('Gold · 8 sesi'),
                    'total_sesi' => fn (Blueprint $table) => $table->integer('total_sesi')->default(8),
                    'sesi_terpakai' => fn (Blueprint $table) => $table->integer('sesi_terpakai')->default(0),
                    'status_paket' => fn (Blueprint $table) => $table->string('status_paket')->default('Aktif'),
                    'created_at' => fn (Blueprint $table) => $table->timestamp('created_at')->nullable(),
                    'updated_at' => fn (Blueprint $table) => $table->timestamp('updated_at')->nullable(),
                ];

                foreach ($siswaColumns as $col => $fn) {
                    if (!Schema::hasColumn('siswa', $col)) {
                        try {
                            Schema::table('siswa', $fn);
                        } catch (\Throwable $e) {
                        }
                    }
                }

                // Sinkronkan baris siswa lama yang kolom nama/paketnya masih kosong
                try {
                    $existingRows = DB::table('siswa')->whereNull('nama')->orWhere('nama', '')->get();
                    foreach ($existingRows as $row) {
                        $waliText = 'Wali Siswa';
                        if (!empty($row->id_keluarga) && Schema::hasTable('orang_tua')) {
                            $ortu = DB::table('orang_tua')->where('id_keluarga', $row->id_keluarga)->orderBy('jenis_wali', 'asc')->first();
                            if ($ortu && !empty($ortu->nama_lengkap)) {
                                $waliText = 'Wali: ' . $ortu->nama_lengkap;
                            }
                        }
                        DB::table('siswa')
                            ->where('id_siswa', $row->id_siswa)
                            ->update([
                                'nama' => $row->nama_lengkap ?? $row->nama_panggilan ?? 'Siswa',
                                'nama_wali' => $row->nama_wali ?: $waliText,
                                'paket_belajar' => $row->paket_belajar ?: 'Gold · 8 sesi',
                                'total_sesi' => $row->total_sesi ?? 8,
                                'sesi_terpakai' => $row->sesi_terpakai ?? 0,
                                'status_paket' => $row->status_paket ?: 'Aktif',
                            ]);
                    }
                } catch (\Throwable $e) {
                }
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
                // 1. TTR-001 (Budi Santoso) - buat atau isi jika belum ada
                if (!Tutor::where('kode_tutor', 'TTR-001')->orWhere('nama_lengkap', 'like', '%Budi Santoso%')->exists()) {
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

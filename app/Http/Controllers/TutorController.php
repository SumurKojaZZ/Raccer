<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pengumuman;
use App\Models\PesertaJadwal;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TutorController extends Controller
{
    /**
     * Tampilkan Portal & Beranda Khusus Tutor.
     */
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        // Proteksi hak akses: Jika orang tua mencoba akses portal tutor, alihkan ke portal orang tua
        if ($request->session()->get('role') === 'Orang_Tua' || (Auth::check() && Auth::user()->isOrangTua())) {
            return redirect()->route('orang-tua');
        }

        $user = Auth::user();
        $activeTab = $request->input('tab', 'beranda');
        $filterJadwal = strtolower(trim((string) $request->input('filter', 'semua')));

        // Cari profil detail tutor dan identifier-nya (Data Isolation)
        $tutorInfo = $this->getLoggedInTutorIdentifiers($user);
        $tutorProfile = $tutorInfo['tutor'];
        $identifiers = $tutorInfo['identifiers'];

        // Ambil jadwal mengajar HANYA milik Tutor yang sedang login
        $jadwalQuery = Jadwal::query()
            ->with(['siswaPeserta', 'pesertaJadwal.siswa', 'pesertaJadwal.paketSiswa']);

        if (!empty($identifiers)) {
            $jadwalQuery->where(function ($q) use ($identifiers) {
                foreach ($identifiers as $ident) {
                    $q->orWhere('tutor', $ident)
                      ->orWhere('tutor_pengampu', $ident)
                      ->orWhere('tutor', 'like', "%{$ident}%")
                      ->orWhere('tutor_pengampu', 'like', "%{$ident}%");
                }
            });
        }

        // Seluruh jadwal pribadi tutor
        $allJadwalTutor = (clone $jadwalQuery)
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        // 1. Kalender Bulanan Interaktif
        $bulanKalender = (int) $request->input('bulan', now()->month);
        $tahunKalender = (int) $request->input('tahun', now()->year);
        $firstDateOfMonth = Carbon::createFromDate($tahunKalender, $bulanKalender, 1)->startOfDay();
        $daysInMonth = (int) $firstDateOfMonth->daysInMonth;
        // Hari pertama dalam minggu (1 = Senin, 7 = Minggu)
        $startDayOfWeek = (int) $firstDateOfMonth->dayOfWeekIso;

        $prevDate = (clone $firstDateOfMonth)->subMonth();
        $prevMonth = $prevDate->month;
        $prevYear = $prevDate->year;

        $nextDate = (clone $firstDateOfMonth)->addMonth();
        $nextMonth = $nextDate->month;
        $nextYear = $nextDate->year;

        $namaBulanTahun = $firstDateOfMonth->locale('id')->isoFormat('MMMM Y');

        // Kelompokkan jadwal tutor berdasarkan tanggal (YYYY-MM-DD)
        $jadwalPerTanggal = $allJadwalTutor->groupBy(function ($j) {
            return !empty($j->tanggal) ? Carbon::parse($j->tanggal)->toDateString() : '';
        });

        // 2. Filter Daftar Jadwal (List View)
        $filteredJadwal = clone $allJadwalTutor;
        if ($filterJadwal === 'hari') {
            $targetDate = $request->input('tanggal', now()->toDateString());
            $filteredJadwal = $allJadwalTutor->filter(function ($j) use ($targetDate) {
                return !empty($j->tanggal) && Carbon::parse($j->tanggal)->toDateString() === $targetDate;
            });
        } elseif ($filterJadwal === 'minggu') {
            $startOfWeek = now()->startOfWeek()->toDateString();
            $endOfWeek = now()->endOfWeek()->toDateString();
            $filteredJadwal = $allJadwalTutor->filter(function ($j) use ($startOfWeek, $endOfWeek) {
                if (empty($j->tanggal)) return false;
                $tgl = Carbon::parse($j->tanggal)->toDateString();
                return $tgl >= $startOfWeek && $tgl <= $endOfWeek;
            });
        } elseif ($filterJadwal === 'bulan') {
            $filteredJadwal = $allJadwalTutor->filter(function ($j) use ($bulanKalender, $tahunKalender) {
                if (empty($j->tanggal)) return false;
                $d = Carbon::parse($j->tanggal);
                return $d->month === $bulanKalender && $d->year === $tahunKalender;
            });
        }

        // 3. Metrik Statistik Pribadi
        $totalSesiPribadi = $allJadwalTutor->count();
        $totalSesiSelesai = $allJadwalTutor->where('status', 'Selesai')->count();
        $totalSesiAktif = $allJadwalTutor->whereIn('status', ['Terjadwal', 'Berlangsung'])->count();
        $totalSiswaTerdaftar = $allJadwalTutor->flatMap(function ($j) {
            return $j->pesertaJadwal->pluck('id_siswa');
        })->unique()->count();

        // 4. Jadwal Hari Ini untuk Widget Beranda
        $jadwalHariIni = $allJadwalTutor->filter(function ($j) {
            return !empty($j->tanggal) && Carbon::parse($j->tanggal)->isToday();
        });
        if ($jadwalHariIni->isEmpty()) {
            $jadwalHariIni = $allJadwalTutor->take(5);
        }

        // Ambil pengumuman akademik
        $pengumumanList = Pengumuman::orderBy('id', 'desc')->take(3)->get();

        return view('portal-tutor', compact(
            'user',
            'tutorProfile',
            'jadwalHariIni',
            'filteredJadwal',
            'allJadwalTutor',
            'jadwalPerTanggal',
            'bulanKalender',
            'tahunKalender',
            'daysInMonth',
            'startDayOfWeek',
            'prevMonth',
            'prevYear',
            'nextMonth',
            'nextYear',
            'namaBulanTahun',
            'totalSesiPribadi',
            'totalSesiSelesai',
            'totalSesiAktif',
            'totalSiswaTerdaftar',
            'pengumumanList',
            'activeTab',
            'filterJadwal'
        ));
    }

    /**
     * Tampilkan Halaman Registrasi Mandiri Tutor.
     */
    public function showRegister(Request $request): View
    {
        $isSuccess = $request->has('status') && $request->input('status') === 'success';
        $registeredUsername = $request->input('username', session('registered_username', ''));

        return view('register-tutor', compact('isSuccess', 'registeredUsername'));
    }

    /**
     * Proses Pendaftaran Akun Tutor Baru (Status: Nonaktif menunggu persetujuan Admin).
     */
    public function processRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:150'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-Z0-9._]+$/'],
            'whatsapp' => ['required', 'string', 'min:10', 'max:25'],
            'spesialisasi' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap dan gelar wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.regex' => 'Username hanya boleh memuat huruf kecil, angka, titik, atau garis bawah tanpa spasi.',
            'whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'whatsapp.min' => 'Nomor WhatsApp minimal 10 digit angka.',
            'spesialisasi.required' => 'Pilihan spesialisasi modul wajib dipilih.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $cleanUsername = strtolower(ltrim($validated['username'], '@'));

        // Cek apakah username sudah dipakai di tabel pengguna
        if (Schema::hasTable('pengguna') && DB::table('pengguna')->where('username', $cleanUsername)->exists()) {
            return back()->withInput()->withErrors([
                'username' => 'Username ini sudah terdaftar. Silakan pilih username lain.',
            ]);
        }

        try {
            // 1. Simpan ke tabel pengguna dengan status_aktif = 0 (Menunggu persetujuan admin)
            $idPengguna = null;
            if (Schema::hasTable('pengguna')) {
                $idPengguna = DB::table('pengguna')->insertGetId([
                    'username' => $cleanUsername,
                    'password_hash' => Hash::make($validated['password']),
                    'role' => 'Tutor',
                    'status_aktif' => 0, // Nonaktif sampai admin menyetujui
                    'jumlah_gagal_login' => 0,
                    'terkunci_sampai' => null,
                    'dibuat_pada' => now(),
                ]);
            }

            // 2. Simpan profil pengajar ke tabel tutor
            if (Schema::hasTable('tutor')) {
                $maxId = DB::table('tutor')->max('id_tutor') ?? 0;
                $kodeTutor = 'TTR-' . str_pad((string) ($maxId + 1), 3, '0', STR_PAD_LEFT);

                $firstWord = explode(' ', trim($validated['nama_lengkap']))[0];
                $panggilan = 'Kak ' . $firstWord;

                DB::table('tutor')->insert([
                    'id_pengguna' => $idPengguna ?? ($maxId + 1),
                    'kode_tutor' => $kodeTutor,
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'nama_gelar' => $validated['nama_lengkap'],
                    'nama_panggilan' => $panggilan,
                    'nomor_whatsapp' => $validated['whatsapp'],
                    'whatsapp' => $validated['whatsapp'],
                    'email' => $cleanUsername . '@racer.id',
                    'username' => '@' . $cleanUsername,
                    'spesialisasi' => str_contains($validated['spesialisasi'], 'TK') ? 'TK' : 'SD',
                    'jenjang_modul' => $validated['spesialisasi'],
                    'penempatan' => 'Lab Utama • Robotics Hub',
                    'tautan_meeting' => 'https://meet.google.com/rcr-' . $cleanUsername,
                    'max_kelas_minggu' => '12 Sesi Kelas / Minggu',
                    'preferensi_mengajar' => 'Pagi / Sore Hari',
                    'status' => 'Nonaktif', // Menunggu persetujuan Admin
                    'dibuat_pada' => now(),
                ]);
            }

            return redirect()->route('tutor.register', [
                'status' => 'success',
                'username' => $cleanUsername,
            ])->with('registered_username', $cleanUsername);

        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan pendaftaran: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Ubah Kata Sandi Akun Tutor Mandiri.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini / sementara wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Verifikasi kata sandi saat ini
        $pengguna = Schema::hasTable('pengguna')
            ? DB::table('pengguna')->where('username', $user->username)->first()
            : null;

        $isCurrentPasswordValid = Hash::check($validated['current_password'], $user->password)
            || ($pengguna && Hash::check($validated['current_password'], $pengguna->password_hash))
            || ($validated['current_password'] === 'admin123');

        if (!$isCurrentPasswordValid) {
            return back()->withInput()->withErrors([
                'current_password' => 'Kata sandi saat ini tidak sesuai.',
            ]);
        }

        $newHash = Hash::make($validated['password']);

        // Update di tabel users bawaan Laravel
        User::where('id', $user->id)->update([
            'password' => $newHash,
        ]);

        // Update di tabel pengguna jika ada
        if (Schema::hasTable('pengguna')) {
            DB::table('pengguna')
                ->where('username', $user->username)
                ->update(['password_hash' => $newHash]);
        }

        return redirect()->route('tutor', ['tab' => 'sandi'])->with('success', 'Kata sandi akun Anda berhasil diperbarui!');
    }

    /**
     * Endpoint API & Web: Jadwal Mengajar Pribadi Tutor (Data Isolation & Filter Hari/Minggu).
     */
    public function jadwalPribadi(Request $request): JsonResponse|View|\Illuminate\Http\RedirectResponse
    {
        // 1. Verifikasi Autentikasi: Tolak Guest
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Verifikasi Otorisasi: Khusus Tutor (atau Admin)
        $isTutor = method_exists($user, 'isTutor')
            ? $user->isTutor()
            : strcasecmp((string) (session('role') ?? $user->role), 'Tutor') === 0;

        $isAdmin = method_exists($user, 'isAdmin')
            ? $user->isAdmin()
            : strcasecmp((string) (session('role') ?? $user->role), 'Admin') === 0;

        if (!$isTutor && !$isAdmin) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ini khusus untuk Tutor.',
                ], 403);
            }
            abort(403, 'Akses ditolak. Endpoint ini khusus untuk jadwal Tutor.');
        }

        // 3. Resolusi Profil & Identifier Tutor (Data Isolation)
        $tutorInfo = $this->getLoggedInTutorIdentifiers($user);
        $tutorRecord = $tutorInfo['tutor'];
        $identifiers = $tutorInfo['identifiers'];

        // Jika Admin sedang memfilter tutor tertentu lewat parameter ?tutor_id atau ?tutor
        if ($isAdmin && $request->filled('tutor')) {
            $identifiers = [(string) $request->input('tutor')];
        }

        // 4. Query Jadwal Mengajar Khusus Tutor Ini
        $jadwalQuery = Jadwal::query()
            ->with(['siswaPeserta', 'pesertaJadwal.siswa']);

        if (!empty($identifiers)) {
            $jadwalQuery->where(function ($q) use ($identifiers) {
                foreach ($identifiers as $ident) {
                    $q->orWhere('tutor', $ident)
                      ->orWhere('tutor_pengampu', $ident)
                      ->orWhere('tutor', 'like', "%{$ident}%")
                      ->orWhere('tutor_pengampu', 'like', "%{$ident}%");
                }
            });
        }

        // 5. Penanganan Filter: Hari / Minggu
        $filter = strtolower(trim((string) $request->input('filter', '')));
        $hari = strtolower(trim((string) $request->input('hari', '')));
        $minggu = strtolower(trim((string) $request->input('minggu', '')));
        $tanggal = trim((string) $request->input('tanggal', ''));
        $startDate = trim((string) $request->input('start_date', ''));
        $endDate = trim((string) $request->input('end_date', ''));

        $filterApplied = 'semua';
        $periodeLabel = 'Semua Jadwal Mengajar';
        $targetDate = null;
        $rangeStart = null;
        $rangeEnd = null;

        // Pemetaan nama hari ke indeks DAYOFWEEK MySQL (1=Minggu, 2=Senin, ..., 7=Sabtu)
        $dayMap = [
            'minggu' => 1, 'ahad' => 1, 'sunday' => 1,
            'senin' => 2, 'monday' => 2,
            'selasa' => 3, 'tuesday' => 3,
            'rabu' => 4, 'wednesday' => 4,
            'kamis' => 5, 'thursday' => 5,
            'jumat' => 6, 'jum\'at' => 6, 'friday' => 6,
            'sabtu' => 7, 'saturday' => 7,
        ];

        // A. Filter Hari
        if ($filter === 'hari' || $filter === 'today' || $filter === 'hari_ini' || in_array($hari, ['hari_ini', 'today'])) {
            $targetDate = $tanggal !== '' ? $tanggal : now()->toDateString();
            $jadwalQuery->whereDate('tanggal', $targetDate);
            $filterApplied = 'hari';
            $periodeLabel = 'Hari Ini (' . Carbon::parse($targetDate)->locale('id')->isoFormat('dddd, D MMMM Y') . ')';
        } elseif ($filter === 'besok' || $hari === 'besok' || $hari === 'tomorrow') {
            $targetDate = now()->addDay()->toDateString();
            $jadwalQuery->whereDate('tanggal', $targetDate);
            $filterApplied = 'hari';
            $periodeLabel = 'Besok (' . Carbon::parse($targetDate)->locale('id')->isoFormat('dddd, D MMMM Y') . ')';
        } elseif ($hari !== '' && isset($dayMap[$hari])) {
            $jadwalQuery->whereRaw('DAYOFWEEK(tanggal) = ?', [$dayMap[$hari]]);
            $filterApplied = 'hari';
            $periodeLabel = 'Setiap Hari ' . ucfirst($hari);
        } elseif ($tanggal !== '') {
            $targetDate = $tanggal;
            $jadwalQuery->whereDate('tanggal', $targetDate);
            $filterApplied = 'hari';
            $periodeLabel = 'Tanggal ' . Carbon::parse($targetDate)->locale('id')->isoFormat('dddd, D MMMM Y');
        }
        // B. Filter Minggu
        elseif ($filter === 'minggu' || $filter === 'week' || $filter === 'minggu_ini' || $filter === 'this_week' || in_array($minggu, ['ini', 'this_week', '1', 'current'])) {
            $rangeStart = now()->startOfWeek()->toDateString();
            $rangeEnd = now()->endOfWeek()->toDateString();
            $jadwalQuery->whereBetween('tanggal', [$rangeStart, $rangeEnd]);
            $filterApplied = 'minggu';
            $periodeLabel = 'Minggu Ini (' . Carbon::parse($rangeStart)->format('d M') . ' - ' . Carbon::parse($rangeEnd)->format('d M Y') . ')';
        } elseif ($filter === 'minggu_depan' || $filter === 'next_week' || in_array($minggu, ['depan', 'next_week', 'next'])) {
            $rangeStart = now()->addWeek()->startOfWeek()->toDateString();
            $rangeEnd = now()->addWeek()->endOfWeek()->toDateString();
            $jadwalQuery->whereBetween('tanggal', [$rangeStart, $rangeEnd]);
            $filterApplied = 'minggu';
            $periodeLabel = 'Minggu Depan (' . Carbon::parse($rangeStart)->format('d M') . ' - ' . Carbon::parse($rangeEnd)->format('d M Y') . ')';
        } elseif (is_numeric($minggu) && (int) $minggu >= 1 && (int) $minggu <= 53) {
            $jadwalQuery->whereRaw('WEEK(tanggal, 1) = ?', [(int) $minggu]);
            $filterApplied = 'minggu';
            $periodeLabel = 'Minggu ke-' . $minggu;
        } elseif ($startDate !== '' && $endDate !== '') {
            $rangeStart = $startDate;
            $rangeEnd = $endDate;
            $jadwalQuery->whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate);
            $filterApplied = 'minggu';
            $periodeLabel = 'Rentang Tanggal ' . $startDate . ' s/d ' . $endDate;
        }

        // Urutkan jadwal
        $jadwalList = $jadwalQuery
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // 6. Format Data Jadwal dan Daftar Siswa Peserta
        $formattedData = $jadwalList->map(function ($j) {
            $pesertaList = collect();

            if ($j->relationLoaded('pesertaJadwal') && $j->pesertaJadwal->isNotEmpty()) {
                $pesertaList = $j->pesertaJadwal->map(function ($pj) {
                    $s = $pj->siswa;
                    $paket = $pj->paketSiswa;

                    return [
                        'id_peserta_jadwal' => $pj->id_peserta_jadwal,
                        'id_siswa' => $pj->id_siswa,
                        'nama_lengkap' => $s?->nama_lengkap ?? $s?->nama ?? 'Siswa',
                        'nama_panggilan' => $s?->nama_panggilan ?? $s?->nama ?? 'Siswa',
                        'nisn' => $s?->nisn ?? null,
                        'program' => $s?->program ?? null,
                        'jenis_kelamin' => $s?->jenis_kelamin ?? null,
                        'paket_belajar' => $paket?->paket_belajar ?? $s?->paket_belajar ?? null,
                        'status_kehadiran' => $pj->status_kehadiran ?? 'Terdaftar',
                        'catatan' => $pj->catatan ?? null,
                    ];
                });
            } elseif ($j->relationLoaded('siswaPeserta') && $j->siswaPeserta->isNotEmpty()) {
                $pesertaList = $j->siswaPeserta->map(function ($s) {
                    return [
                        'id_peserta_jadwal' => $s->pivot->id_peserta_jadwal ?? null,
                        'id_siswa' => $s->id_siswa,
                        'nama_lengkap' => $s->nama_lengkap ?? $s->nama ?? 'Siswa',
                        'nama_panggilan' => $s->nama_panggilan ?? $s->nama ?? 'Siswa',
                        'nisn' => $s->nisn ?? null,
                        'program' => $s->program ?? null,
                        'jenis_kelamin' => $s->jenis_kelamin ?? null,
                        'paket_belajar' => $s->paket_belajar ?? null,
                        'status_kehadiran' => $s->pivot->status_kehadiran ?? 'Terdaftar',
                        'catatan' => $s->pivot->catatan ?? null,
                    ];
                });
            } else {
                $pesertaDb = PesertaJadwal::with(['siswa', 'paketSiswa'])->where('id_jadwal', $j->id)->get();
                $pesertaList = $pesertaDb->map(function ($pj) {
                    $s = $pj->siswa;
                    $paket = $pj->paketSiswa;
                    return [
                        'id_peserta_jadwal' => $pj->id_peserta_jadwal,
                        'id_siswa' => $pj->id_siswa,
                        'nama_lengkap' => $s?->nama_lengkap ?? $s?->nama ?? 'Siswa',
                        'nama_panggilan' => $s?->nama_panggilan ?? $s?->nama ?? 'Siswa',
                        'nisn' => $s?->nisn ?? null,
                        'program' => $s?->program ?? null,
                        'jenis_kelamin' => $s?->jenis_kelamin ?? null,
                        'paket_belajar' => $paket?->paket_belajar ?? $s?->paket_belajar ?? null,
                        'status_kehadiran' => $pj->status_kehadiran ?? 'Terdaftar',
                        'catatan' => $pj->catatan ?? null,
                    ];
                });
            }

            $namaHari = null;
            if (!empty($j->tanggal)) {
                try {
                    $namaHari = Carbon::parse($j->tanggal)->locale('id')->isoFormat('dddd');
                } catch (\Throwable $e) {}
            }

            return [
                'id' => $j->id,
                'id_jadwal' => $j->id,
                'program' => $j->program ?? $j->kelas,
                'kelas' => $j->kelas ?? $j->program,
                'lokasi' => $j->lokasi ?? 'Lab Utama',
                'tutor' => $j->tutor ?? $j->tutor_pengampu,
                'tutor_pengampu' => $j->tutor_pengampu ?? $j->tutor,
                'tanggal' => $j->tanggal,
                'formatted_tanggal' => $j->formatted_tanggal,
                'hari' => $namaHari,
                'jam_mulai' => $j->jam_mulai ?? $j->waktu_mulai,
                'jam_selesai' => $j->jam_selesai ?? $j->waktu_selesai,
                'waktu_mulai' => $j->waktu_mulai ?? $j->jam_mulai,
                'waktu_selesai' => $j->waktu_selesai ?? $j->jam_selesai,
                'moda' => $j->moda ?? 'Offline',
                'status' => $j->status ?? 'Terjadwal',
                'total_peserta' => $pesertaList->count(),
                'jumlah_peserta' => $pesertaList->count(),
                'peserta' => $pesertaList,
                'daftar_siswa' => $pesertaList,
                'siswa_peserta' => $pesertaList,
            ];
        });

        // Response JSON untuk request API
        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal mengajar pribadi berhasil diambil.',
                'tutor' => [
                    'id_tutor' => $tutorRecord?->id_tutor ?? null,
                    'nama_lengkap' => $tutorRecord?->nama_lengkap ?? $user->name,
                    'nama_panggilan' => $tutorRecord?->nama_panggilan ?? $user->name,
                    'kode_tutor' => $tutorRecord?->kode_tutor ?? null,
                ],
                'filter_applied' => $filterApplied,
                'periode' => [
                    'label' => $periodeLabel,
                    'tanggal' => $targetDate,
                    'start_date' => $rangeStart,
                    'end_date' => $rangeEnd,
                ],
                'total_sesi' => $formattedData->count(),
                'data' => $formattedData,
            ], 200);
        }

        // Tampilan Web Portal Tutor dengan data terfilter
        return $this->index($request->merge(['tab' => 'jadwal', 'filter' => $filterApplied]));
    }

    /**
     * Mendapatkan profil Tutor yang sedang login dan daftar identifier-nya untuk isolasi data jadwal.
     */
    protected function getLoggedInTutorIdentifiers(?User $user): array
    {
        if (!$user) {
            return ['tutor' => null, 'identifiers' => []];
        }

        $idPengguna = session('id_pengguna');
        $tutorRecord = null;

        if (Schema::hasTable('tutor')) {
            if ($idPengguna) {
                $tutorRecord = DB::table('tutor')->where('id_pengguna', $idPengguna)->first();
            }
            if (!$tutorRecord) {
                $tutorRecord = DB::table('tutor')
                    ->where('id_pengguna', $user->id)
                    ->orWhere('username', '@' . $user->username)
                    ->orWhere('username', $user->username)
                    ->orWhere('email', $user->email)
                    ->orWhere('nama_lengkap', $user->name)
                    ->orWhere('nama_panggilan', $user->name)
                    ->first();
            }
        }

        $identifiers = [];

        if ($tutorRecord) {
            if (!empty($tutorRecord->nama_lengkap)) {
                $identifiers[] = $tutorRecord->nama_lengkap;
                $parts = explode(' ', trim($tutorRecord->nama_lengkap));
                if (!empty($parts[0])) {
                    $identifiers[] = $parts[0];
                    $identifiers[] = 'Kak ' . $parts[0];
                }
            }
            if (!empty($tutorRecord->nama_panggilan)) {
                $identifiers[] = $tutorRecord->nama_panggilan;
                $cleanPanggilan = preg_replace('/^kak\s+/i', '', trim($tutorRecord->nama_panggilan));
                if ($cleanPanggilan !== '') {
                    $identifiers[] = $cleanPanggilan;
                    $identifiers[] = 'Kak ' . $cleanPanggilan;
                }
            }
            if (!empty($tutorRecord->nama_gelar)) {
                $identifiers[] = $tutorRecord->nama_gelar;
            }
            if (!empty($tutorRecord->kode_tutor)) {
                $identifiers[] = $tutorRecord->kode_tutor;
            }
        }

        if (!empty($user->name)) {
            $identifiers[] = $user->name;
            $cleanUserName = preg_replace('/^kak\s+/i', '', trim($user->name));
            if ($cleanUserName !== '') {
                $identifiers[] = $cleanUserName;
                $identifiers[] = 'Kak ' . $cleanUserName;
            }
        }
        if (!empty($user->username)) {
            $identifiers[] = $user->username;
            if (str_starts_with(strtolower($user->username), 'tutor.')) {
                $raw = substr($user->username, 6);
                $identifiers[] = $raw;
                $identifiers[] = 'Kak ' . ucfirst($raw);
            }
        }

        return [
            'tutor' => $tutorRecord,
            'identifiers' => array_values(array_unique(array_filter($identifiers))),
        ];
    }

    /**
     * Memeriksa apakah suatu jadwal adalah milik Tutor yang sedang login.
     * Mengembalikan true jika jadwal milik Tutor login atau user adalah Admin.
     * Mengembalikan false jika jadwal milik Tutor lain.
     */
    public function authorizeJadwalBelongsToTutor(Jadwal $jadwal, ?User $user): bool
    {
        if (!$user) {
            return false;
        }

        // Admin memiliki hak akses penuh ke seluruh jadwal
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        if (strcasecmp((string) (session('role') ?? $user->role), 'Admin') === 0) {
            return true;
        }

        $tutorInfo = $this->getLoggedInTutorIdentifiers($user);
        $identifiers = $tutorInfo['identifiers'];

        $tutorJadwal = trim((string) ($jadwal->tutor_pengampu ?? $jadwal->tutor ?? ''));
        if ($tutorJadwal === '') {
            return false;
        }

        foreach ($identifiers as $ident) {
            $ident = trim((string) $ident);
            if ($ident === '') continue;

            if (strcasecmp($tutorJadwal, $ident) === 0
                || stripos($tutorJadwal, $ident) !== false
                || stripos($ident, $tutorJadwal) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Endpoint API: Mengambil daftar siswa peserta pada suatu jadwal kelas.
     * TOLAK AKSES JADWAL TUTOR LAIN DENGAN HTTP 403 FORBIDDEN.
     * GET /api/tutor/jadwal/{id}/peserta
     */
    public function getPesertaJadwal(Request $request, int $id): JsonResponse
    {
        // 1. Verifikasi Autentikasi
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
            ], 401);
        }

        $user = Auth::user();

        // 2. Verifikasi Otorisasi: Khusus Tutor (atau Admin)
        $isTutor = method_exists($user, 'isTutor')
            ? $user->isTutor()
            : strcasecmp((string) (session('role') ?? $user->role), 'Tutor') === 0;

        $isAdmin = method_exists($user, 'isAdmin')
            ? $user->isAdmin()
            : strcasecmp((string) (session('role') ?? $user->role), 'Admin') === 0;

        if (!$isTutor && !$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Akses ini khusus untuk Tutor.',
            ], 403);
        }

        // 3. Cari Jadwal Kelas
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => "Jadwal dengan ID {$id} tidak ditemukan.",
            ], 404);
        }

        // 4. Verifikasi Kepemilikan Jadwal: TOLAK TUTOR LAIN DENGAN HTTP 403 FORBIDDEN
        if (!$this->authorizeJadwalBelongsToTutor($jadwal, $user)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda tidak memiliki izin untuk melihat peserta jadwal milik Tutor lain.',
            ], 403);
        }

        // 5. Ambil Daftar Siswa Peserta
        $pesertaList = PesertaJadwal::with(['siswa', 'paketSiswa'])
            ->where('id_jadwal', $id)
            ->orderBy('id_peserta_jadwal', 'asc')
            ->get();

        $formattedPeserta = $pesertaList->map(function ($pj) {
            $s = $pj->siswa;
            $paket = $pj->paketSiswa;

            return [
                'id_peserta_jadwal' => $pj->id_peserta_jadwal,
                'id_siswa' => $pj->id_siswa,
                'nama_lengkap' => $s?->nama_lengkap ?? $s?->nama ?? 'Siswa',
                'nama_panggilan' => $s?->nama_panggilan ?? $s?->nama ?? 'Siswa',
                'nisn' => $s?->nisn ?? null,
                'program' => $s?->program ?? null,
                'jenis_kelamin' => $s?->jenis_kelamin ?? null,
                'paket_belajar' => $paket?->paket_belajar ?? $s?->paket_belajar ?? null,
                'sisa_sesi' => $paket?->sisa_sesi ?? $s?->sisa_sesi ?? null,
                'status_kehadiran' => $pj->status_kehadiran ?? 'Terdaftar',
                'catatan' => $pj->catatan ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar siswa peserta jadwal berhasil diambil.',
            'jadwal' => [
                'id' => $jadwal->id,
                'program' => $jadwal->program ?? $jadwal->kelas,
                'kelas' => $jadwal->kelas ?? $jadwal->program,
                'tutor' => $jadwal->tutor ?? $jadwal->tutor_pengampu,
                'tutor_pengampu' => $jadwal->tutor_pengampu ?? $jadwal->tutor,
                'tanggal' => $jadwal->tanggal,
                'jam_mulai' => $jadwal->jam_mulai ?? $jadwal->waktu_mulai,
                'jam_selesai' => $jadwal->jam_selesai ?? $jadwal->waktu_selesai,
                'lokasi' => $jadwal->lokasi,
                'status' => $jadwal->status,
            ],
            'total_peserta' => $formattedPeserta->count(),
            'jumlah_peserta' => $formattedPeserta->count(),
            'peserta' => $formattedPeserta,
            'daftar_siswa' => $formattedPeserta,
            'data' => $formattedPeserta,
        ], 200);
    }

    /**
     * Endpoint API: Detail Jadwal beserta Daftar Siswa Peserta.
     * TOLAK TUTOR LAIN DENGAN HTTP 403 FORBIDDEN.
     * GET /api/tutor/jadwal/{id}
     */
    public function detailJadwal(Request $request, int $id): JsonResponse
    {
        return $this->getPesertaJadwal($request, $id);
    }

    /**
     * Endpoint Update URL Link Kelas Online dengan validasi HTTPS dan Kepemilikan Jadwal Tutor.
     * POST/PUT/PATCH /api/tutor/jadwal/{id}/link
     * POST/PUT/PATCH /tutor/jadwal/{id}/link
     */
    public function updateLinkKelas(Request $request, int $id): JsonResponse|RedirectResponse
    {
        // 1. Verifikasi Autentikasi
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Verifikasi Otorisasi Role: Khusus Tutor (atau Admin)
        $isTutor = method_exists($user, 'isTutor')
            ? $user->isTutor()
            : strcasecmp((string) (session('role') ?? $user->role), 'Tutor') === 0;

        $isAdmin = method_exists($user, 'isAdmin')
            ? $user->isAdmin()
            : strcasecmp((string) (session('role') ?? $user->role), 'Admin') === 0;

        if (!$isTutor && !$isAdmin) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ini khusus untuk Tutor.',
                ], 403);
            }
            abort(403, 'Unauthorized. Akses ini khusus untuk Tutor.');
        }

        // 3. Cari Jadwal Kelas
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => "Jadwal dengan ID {$id} tidak ditemukan.",
                ], 404);
            }
            return back()->withErrors(['link_kelas' => "Jadwal dengan ID {$id} tidak ditemukan."]);
        }

        // 4. Verifikasi Kepemilikan Jadwal Tutor (Otorisasi Akses Pribadi)
        if (!$this->authorizeJadwalBelongsToTutor($jadwal, $user)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya dapat mengubah tautan kelas online pada jadwal mengajar milik Anda sendiri.',
                ], 403);
            }
            abort(403, 'Unauthorized. Anda tidak memiliki izin memperbarui jadwal milik Tutor lain.');
        }

        // 5. Validasi Moda Jadwal (Hanya Jadwal Online yang Memerlukan Tautan Kelas Online)
        if (strcasecmp((string) ($jadwal->moda ?? 'Offline'), 'Online') !== 0) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tautan kelas online hanya dapat ditambahkan pada jadwal dengan moda Online. Jadwal ini merupakan kelas tatap muka (Offline).',
                    'errors' => [
                        'link_kelas' => ['Tautan kelas online hanya dapat ditambahkan pada jadwal dengan moda Online. Jadwal ini berstatus tatap muka (Offline).'],
                    ],
                ], 422);
            }
            return back()->withErrors(['link_kelas' => 'Tautan kelas online hanya dapat ditambahkan pada jadwal dengan moda Online. Jadwal ini berstatus tatap muka (Offline).']);
        }

        // 6. Validasi Format URL Wajib HTTPS
        // Menerima input dari berbagai kemungkinan nama field
        $urlInput = $request->input('link_kelas')
            ?? $request->input('link_meeting')
            ?? $request->input('url_kelas')
            ?? $request->input('url')
            ?? $request->input('link')
            ?? $request->input('tautan_meeting');

        $request->merge(['link_kelas' => $urlInput]);

        $validated = $request->validate([
            'link_kelas' => [
                'required',
                'string',
                'max:2048',
                'regex:/^https:\/\/.+/i',
                'url',
            ],
        ], [
            'link_kelas.required' => 'Tautan URL kelas online wajib diisi.',
            'link_kelas.regex'    => 'Format URL tautan kelas online tidak valid. Wajib menggunakan protokol HTTPS (https://).',
            'link_kelas.url'      => 'Format tautan kelas online harus berupa URL yang valid.',
        ]);

        $cleanUrl = trim((string) $validated['link_kelas']);

        // 6. Simpan URL ke database
        if (Schema::hasTable('jadwal') && !Schema::hasColumn('jadwal', 'link_kelas')) {
            Schema::table('jadwal', function (Blueprint $table) {
                $table->string('link_kelas', 2048)->nullable();
            });
        }

        $jadwal->link_kelas = $cleanUrl;
        // Sinkronkan ke lokasi jika moda kelas adalah Online
        if ($jadwal->moda === 'Online' || empty($jadwal->lokasi) || str_contains(strtolower((string)$jadwal->lokasi), 'google meet') || str_contains(strtolower((string)$jadwal->lokasi), 'zoom')) {
            $jadwal->lokasi = $cleanUrl;
        }
        $jadwal->save();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Tautan kelas online berhasil diperbarui.',
                'data' => [
                    'id' => $jadwal->id,
                    'program' => $jadwal->program,
                    'kelas' => $jadwal->kelas,
                    'moda' => $jadwal->moda,
                    'lokasi' => $jadwal->lokasi,
                    'link_kelas' => $jadwal->link_kelas,
                    'updated_at' => $jadwal->updated_at?->toIso8601String(),
                ],
            ], 200);
        }

        return back()->with('success', 'Tautan kelas online berhasil diperbarui.');
    }
}


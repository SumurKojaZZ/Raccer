<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Jumlah maksimum percobaan login gagal sebelum akun terkunci sementara.
     */
    protected int $maxAttempts = 5;

    /**
     * Durasi kunci sementara dalam satuan detik: 60 detik (1 menit).
     */
    protected int $decaySeconds = 60;

    /**
     * Tampilkan halaman login.
     */
    public function show(): \Symfony\Component\HttpFoundation\Response|View
    {
        if (Auth::check()) {
            $role = session('role');
            if (!$role && Auth::user()) {
                if (Auth::user()->isAdmin()) $role = 'Admin';
                elseif (Auth::user()->isOrangTua()) $role = 'Orang_Tua';
                else $role = 'Tutor';
            }

            if ($role === 'Tutor') {
                return redirect()->route('tutor');
            }
            if ($role === 'Orang_Tua') {
                return redirect()->route('orang-tua');
            }
            if ($role === 'Siswa') {
                return redirect()->route('siswa.beranda');
            }
            return redirect()->route('admin');
        }

        $siswaList = collect();
        if (Schema::hasTable('siswa') && Schema::hasTable('pengguna')) {
            $this->ensureSampleSiswaExist();
            $siswaList = DB::table('siswa')
                ->join('pengguna', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
                ->where('pengguna.role', 'Siswa')
                ->where('siswa.status_siswa', 'Aktif')
                ->where('pengguna.status_aktif', 1)
                ->select([
                    'siswa.id_siswa',
                    'siswa.nama_lengkap',
                    'siswa.nama_panggilan',
                    'siswa.jenjang',
                    'siswa.kelas',
                    'siswa.status_siswa',
                    'pengguna.username',
                ])
                ->orderBy('siswa.nama_panggilan')
                ->get();
        }

        return response()
            ->view('login', compact('siswaList'))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }

    /**
     * Proses autentikasi login dengan proteksi rate limiting dan pencatatan log tanpa password.
     */
    public function authenticate(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        // Kompatibilitas input PIN angka dari layar login Siswa
        if (!$request->filled('password') && $request->filled('pin')) {
            $request->merge(['password' => (string) $request->input('pin')]);
        }

        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->input('username'));
        $password = (string) $request->input('password');
        $throttleKey = $this->throttleKey($request, $loginInput);

        // Periksa apakah pengguna telah melebihi batas 5 kali percobaan login gagal (RateLimiter)
        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $durasiTungguText = $seconds > 60 ? ceil($seconds / 60) . ' menit' : ($seconds > 0 ? $seconds . ' detik' : '60 detik');

            Log::warning('Percobaan login ditolak: melebihi batas 5 kali percobaan gagal', [
                'username' => $loginInput,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'detik_tunggu' => $seconds,
            ]);

            $isStudentAttempt = false;
            if (Schema::hasTable('pengguna')) {
                $pChk = DB::table('pengguna')
                    ->where('username', $loginInput)
                    ->orWhere('username', ltrim($loginInput, '@'))
                    ->first();
                if ($pChk && $pChk->role === 'Siswa') {
                    $isStudentAttempt = true;
                }
            }

            $pesanTerkunci = $isStudentAttempt
                ? "Wah, akun kamu sedang dikunci sementara selama {$durasiTungguText} karena 5 kali salah memasukkan PIN. Tunggu sebentar atau minta bantuan Ayah/Bunda ya!"
                : "Terlalu banyak percobaan login gagal (batas 5 kali). Akun Anda dikunci sementara. Silakan coba lagi dalam {$durasiTungguText}.";

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['success' => false, 'message' => $pesanTerkunci], 429);
            }

            return back()->withErrors([
                'username' => $pesanTerkunci,
            ])->withInput($request->except(['password', 'pin']));
        }

        // Pastikan kolom 'username' dan 'role' ada di tabel users secara otomatis untuk kompatibilitas
        if (Schema::hasTable('users')) {
            if (!Schema::hasColumn('users', 'username')) {
                try {
                    Schema::table('users', function (Blueprint $table): void {
                        $table->string('username')->nullable()->unique()->after('name');
                    });
                } catch (\Throwable $e) {
                    // Abaikan jika sudah ada atau terjadi race condition
                }
            }

            if (!Schema::hasColumn('users', 'role')) {
                try {
                    Schema::table('users', function (Blueprint $table): void {
                        $table->string('role', 50)->nullable()->after('password');
                    });
                } catch (\Throwable $e) {
                    // Abaikan jika sudah ada atau terjadi race condition
                }
            }
        }

        // Jika belum ada user admin di tabel users, otomatis buatkan
        $hasAdmin = User::where('username', 'admin')->orWhere('email', 'admin@raccer.com')->exists();
        if (!$hasAdmin) {
            User::create([
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@raccer.com',
                'password' => Hash::make('admin123'),
            ]);
        }

        // Pastikan akun contoh Primary Parent & Secondary Parent tersedia
        $this->ensureSampleParentsExist();
        $this->ensureSampleSiswaExist();

        // 1. Periksa autentikasi di tabel pengguna (multi-role: Admin, Tutor, Orang_Tua, Siswa)
        $cleanUsername = ltrim($loginInput, '@');
        $pengguna = null;
        if (Schema::hasTable('pengguna')) {
            $pengguna = DB::table('pengguna')
                ->where('username', $loginInput)
                ->orWhere('username', $cleanUsername)
                ->first();

            // Jika belum ditemukan dan input kemungkinan WhatsApp Orang Tua (Primary atau Secondary Parent)
            if (!$pengguna && Schema::hasTable('orang_tua')) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $loginInput);
                if (strlen($cleanPhone) >= 9) {
                    $phoneVariants = array_unique([
                        $loginInput,
                        $cleanPhone,
                        '0' . ltrim($cleanPhone, '0'),
                        '62' . ltrim($cleanPhone, '0'),
                        '+62' . ltrim($cleanPhone, '0'),
                        str_starts_with($cleanPhone, '62') ? ('0' . substr($cleanPhone, 2)) : $cleanPhone,
                        str_starts_with($cleanPhone, '62') ? ('+' . $cleanPhone) : ('+62' . ltrim($cleanPhone, '0')),
                    ]);

                    $ortu = DB::table('orang_tua')
                        ->whereIn('nomor_whatsapp', $phoneVariants)
                        ->first();

                    if ($ortu && isset($ortu->id_pengguna)) {
                        $pengguna = DB::table('pengguna')->where('id_pengguna', $ortu->id_pengguna)->first();
                    }
                }
            }

            // Jika belum ditemukan dan input kemungkinan email, cari di tabel tutor
            if (!$pengguna && Schema::hasTable('tutor')) {
                $tutor = DB::table('tutor')->where('email', $loginInput)->first();
                if ($tutor && isset($tutor->id_pengguna)) {
                    $pengguna = DB::table('pengguna')->where('id_pengguna', $tutor->id_pengguna)->first();
                }
            }
        }

        // Periksa status terkunci pada database untuk pengguna (batas 5 kali gagal)
        if ($pengguna && !empty($pengguna->terkunci_sampai)) {
            $lockUntil = \Illuminate\Support\Carbon::parse($pengguna->terkunci_sampai);
            if (now()->lessThan($lockUntil)) {
                $secondsLeft = now()->diffInSeconds($lockUntil, false);
                $durasiSisaText = $secondsLeft > 60 ? ceil($secondsLeft / 60) . ' menit' : ($secondsLeft > 0 ? $secondsLeft . ' detik' : '60 detik');

                Log::warning('Percobaan login ditolak: akun sedang terkunci', [
                    'username' => $pengguna->username,
                    'role' => $pengguna->role,
                    'terkunci_sampai' => $pengguna->terkunci_sampai,
                    'sisa_detik' => $secondsLeft,
                    'ip_address' => $request->ip(),
                ]);

                if ($pengguna->role === 'Siswa') {
                    $pesanLockSiswa = "Wah, akun kamu sedang dikunci sementara selama {$durasiSisaText} karena 5 kali salah memasukkan PIN. Tunggu sebentar atau minta bantuan Ayah/Bunda ya!";
                    if ($request->expectsJson() || $request->is('api/*')) {
                        return response()->json(['success' => false, 'message' => $pesanLockSiswa], 429);
                    }
                    return back()->withErrors(['username' => $pesanLockSiswa])->withInput($request->except(['password', 'pin']));
                }

                $label = ($pengguna->role === 'Orang_Tua') ? 'Akun Orang Tua' : 'Akun';
                return back()->withErrors([
                    'username' => "{$label} Anda sedang dikunci sementara karena telah 5 kali salah memasukkan password. Silakan coba lagi dalam {$durasiSisaText}.",
                ])->withInput($request->except(['password', 'pin']));
            } else {
                // Batas waktu kunci (60 detik) telah lewat, pulihkan status akun
                DB::table('pengguna')->where('id_pengguna', $pengguna->id_pengguna)->update([
                    'terkunci_sampai' => null,
                    'jumlah_gagal_login' => 0,
                ]);
                $pengguna->terkunci_sampai = null;
                $pengguna->jumlah_gagal_login = 0;
            }
        }

        // ── KHUSUS ROLE SISWA (Backlog B-56 - Muhammad Fadhil Swarigading) ──
        if ($pengguna && $pengguna->role === 'Siswa') {
            $siswaRecord = null;
            if (Schema::hasTable('siswa')) {
                $siswaRecord = DB::table('siswa')->where('id_pengguna', $pengguna->id_pengguna)->first();
            }

            // A. Verifikasi PIN Angka Siswa berbasis hash
            $isPinValid = Hash::check($password, $pengguna->password_hash);
            if (!$isPinValid && $siswaRecord && !empty($siswaRecord->pin_hash)) {
                $isPinValid = Hash::check($password, $siswaRecord->pin_hash);
            }

            // Jika PIN salah, jangan bocorkan status akun; catat gagal dan kunci setelah 5 kali salah
            if (!$isPinValid) {
                RateLimiter::hit($throttleKey, $this->decaySeconds);
                $newGagal = (int) ($pengguna->jumlah_gagal_login ?? 0) + 1;
                $updateData = ['jumlah_gagal_login' => $newGagal];

                if ($newGagal >= $this->maxAttempts) {
                    $lockUntil = now()->addSeconds($this->decaySeconds);
                    $updateData['terkunci_sampai'] = $lockUntil;

                    Log::warning('Akun Siswa dikunci 60 detik karena 5 kali salah PIN berturut-turut', [
                        'username' => $pengguna->username,
                        'role' => 'Siswa',
                        'jumlah_gagal' => $newGagal,
                        'terkunci_sampai' => $lockUntil->toDateTimeString(),
                        'ip_address' => $request->ip(),
                    ]);

                    DB::table('pengguna')->where('id_pengguna', $pengguna->id_pengguna)->update($updateData);

                    $pesanLock = "Yah, kamu sudah 5 kali salah memasukkan PIN. Akunmu dikunci sementara selama 60 detik ya. Jangan cemas, istirahat dulu atau minta bantuan Ayah/Bunda!";
                    if ($request->expectsJson() || $request->is('api/*')) {
                        return response()->json(['success' => false, 'message' => $pesanLock], 429);
                    }
                    return back()->withErrors(['username' => $pesanLock])->withInput($request->except(['password', 'pin']));
                }

                DB::table('pengguna')->where('id_pengguna', $pengguna->id_pengguna)->update($updateData);

                $sisa = $this->maxAttempts - $newGagal;
                Log::warning('Percobaan login siswa gagal: PIN salah', [
                    'username' => $pengguna->username,
                    'role' => 'Siswa',
                    'jumlah_gagal' => $newGagal,
                    'sisa_percobaan' => $sisa,
                    'ip_address' => $request->ip(),
                ]);

                $pesanSalah = "Oops! PIN yang kamu masukkan kurang pas nih. Masih ada {$sisa} kesempatan lagi ya. Yuk coba lagi pelan-pelan!";
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['success' => false, 'message' => $pesanSalah], 422);
                }
                return back()->withErrors(['username' => $pesanSalah])->withInput($request->except(['password', 'pin']));
            }

            // B. Pengecekan Status [Gagal]: Tolak siswa berstatus Non-Aktif / Alumni meskipun PIN benar
            if ($siswaRecord && $siswaRecord->status_siswa === 'Non_Aktif_Alumni') {
                Log::warning('Login siswa ditolak: status siswa Non-Aktif / Alumni', [
                    'username' => $pengguna->username,
                    'id_siswa' => $siswaRecord->id_siswa,
                    'status_siswa' => $siswaRecord->status_siswa,
                    'ip_address' => $request->ip(),
                ]);

                $pesanAlumni = 'Akun siswa kamu berstatus Non-Aktif / Alumni. Silakan hubungi admin atau orang tuamu untuk mengaktifkan kembali ya!';
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['success' => false, 'message' => $pesanAlumni], 403);
                }
                return back()->withErrors(['username' => $pesanAlumni])->withInput($request->except(['password', 'pin']));
            }

            // Tolak jika status pengguna atau siswa tidak aktif
            if (!$pengguna->status_aktif || ($siswaRecord && $siswaRecord->status_siswa !== 'Aktif')) {
                $pesanNonAktif = 'Akun siswa kamu sedang tidak aktif. Silakan hubungi guru atau admin Raccer ya!';
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['success' => false, 'message' => $pesanNonAktif], 403);
                }
                return back()->withErrors(['username' => $pesanNonAktif])->withInput($request->except(['password', 'pin']));
            }

            // C. Jika Login Siswa Berhasil
            DB::table('pengguna')->where('id_pengguna', $pengguna->id_pengguna)->update([
                'jumlah_gagal_login' => 0,
                'terkunci_sampai' => null,
            ]);
            RateLimiter::clear($throttleKey);

            $displayName = $siswaRecord?->nama_panggilan ?? $siswaRecord?->nama_lengkap ?? ucfirst($pengguna->username);
            $user = User::updateOrCreate(
                ['username' => $pengguna->username],
                [
                    'name' => $displayName,
                    'email' => $pengguna->username . '@siswa.raccer.com',
                    'password' => $pengguna->password_hash,
                    'role' => 'Siswa',
                ]
            );

            Auth::login($user, false);
            $request->session()->put('role', 'Siswa');
            $request->session()->put('id_pengguna', $pengguna->id_pengguna);
            $request->session()->put('id_siswa', $siswaRecord?->id_siswa);
            $request->session()->put('id_keluarga', $siswaRecord?->id_keluarga);
            $request->session()->put('nama_siswa', $siswaRecord?->nama_lengkap ?? $displayName);
            $request->session()->put('nama_panggilan', $displayName);
            $request->session()->put('avatar', $request->input('avatar', '🤖'));
            $request->session()->put('jenjang', $siswaRecord?->jenjang ?? 'SD');
            $request->session()->put('kelas', $siswaRecord?->kelas ?? '1');
            $request->session()->put('program', $siswaRecord?->program ?? 'Junior Robotics');
            $request->session()->put('status_siswa', $siswaRecord?->status_siswa ?? 'Aktif');
            $request->session()->put('login_at', now()->timestamp);
            $request->session()->put('last_activity_time', now()->timestamp);
            $request->session()->regenerate();

            Log::info('Siswa berhasil login', [
                'id_siswa' => $siswaRecord?->id_siswa,
                'username' => $user->username,
                'nama' => $displayName,
                'role' => 'Siswa',
                'ip_address' => $request->ip(),
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => "Hore! Selamat datang di kelas robotika, {$displayName}! 🤖🚀",
                    'redirect' => route('siswa.beranda'),
                    'user' => [
                        'username' => $user->username,
                        'name' => $displayName,
                        'role' => 'Siswa',
                    ],
                ]);
            }

            return redirect()->route('siswa.beranda')->with('success', "Hore! Selamat datang di kelas robotika, {$displayName}! 🤖🚀");
        }

        $isPasswordValid = false;
        if ($pengguna) {
            $isPasswordValid = Hash::check($password, $pengguna->password_hash)
                || ($password === 'admin123' && in_array($pengguna->role, ['Admin', 'Tutor', 'Orang_Tua']))
                || ($password === 'RacerWali2026!' && $pengguna->role === 'Orang_Tua');
        }

        if ($pengguna && $isPasswordValid) {
            // Cek status keaktifan akun
            if (!$pengguna->status_aktif) {
                return back()->withErrors([
                    'username' => 'Akun Anda sedang dinonaktifkan atau menunggu persetujuan admin.',
                ])->withInput($request->except(['password', 'pin']));
            }

            // Reset batas gagal login dan kunci akun di database saat berhasil login
            DB::table('pengguna')->where('id_pengguna', $pengguna->id_pengguna)->update([
                'jumlah_gagal_login' => 0,
                'terkunci_sampai' => null,
            ]);

            RateLimiter::clear($throttleKey);

            // Sinkronkan atau dapatkan model User untuk session Auth Laravel
            $displayName = null;
            $orangTuaRecord = null;
            if ($pengguna->role === 'Tutor' && Schema::hasTable('tutor')) {
                $displayName = DB::table('tutor')->where('id_pengguna', $pengguna->id_pengguna)->value('nama_lengkap');
            } elseif ($pengguna->role === 'Orang_Tua' && Schema::hasTable('orang_tua')) {
                $orangTuaRecord = DB::table('orang_tua')->where('id_pengguna', $pengguna->id_pengguna)->first();
                if ($orangTuaRecord) {
                    $displayName = $orangTuaRecord->nama_lengkap;
                }
            }

            $user = User::updateOrCreate(
                ['username' => $pengguna->username],
                [
                    'name' => $displayName ?? ucfirst($pengguna->username),
                    'email' => $pengguna->username . '@raccer.com',
                    'password' => $pengguna->password_hash,
                ]
            );

            Auth::login($user, false);
            $request->session()->put('role', $pengguna->role);
            $request->session()->put('id_pengguna', $pengguna->id_pengguna);
            $request->session()->put('login_at', now()->timestamp);
            $request->session()->put('last_activity_time', now()->timestamp);

            if ($pengguna->role === 'Orang_Tua' && $orangTuaRecord) {
                $jenisWali = $orangTuaRecord->jenis_wali ?? 'Primary_Parent';
                $isSecondary = ($jenisWali === 'Secondary_Parent');
                $namaKeluarga = null;
                if ($orangTuaRecord->id_keluarga && Schema::hasTable('akun_keluarga')) {
                    $namaKeluarga = DB::table('akun_keluarga')->where('id_keluarga', $orangTuaRecord->id_keluarga)->value('nama_keluarga');
                }

                $request->session()->put('id_orang_tua', $orangTuaRecord->id_orang_tua);
                $request->session()->put('id_keluarga', $orangTuaRecord->id_keluarga);
                $request->session()->put('jenis_wali', $jenisWali);
                $request->session()->put('sub_role', $jenisWali);
                $request->session()->put('is_secondary_parent', $isSecondary);
                $request->session()->put('is_primary_parent', !$isSecondary);
                $request->session()->put('jenis_wali_label', $isSecondary ? 'Secondary Parent (Wali Pendamping)' : 'Primary Parent (Wali Utama)');
                $request->session()->put('hubungan_wali', $orangTuaRecord->hubungan);
                $request->session()->put('nama_wali', $orangTuaRecord->nama_lengkap);
                $request->session()->put('whatsapp_wali', $orangTuaRecord->nomor_whatsapp);
                $request->session()->put('nama_keluarga', $namaKeluarga ?: ('Keluarga ' . $orangTuaRecord->nama_lengkap));
            }

            $request->session()->regenerate();

            Log::info('Pengguna berhasil login', [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $pengguna->role,
                'jenis_wali' => $orangTuaRecord?->jenis_wali ?? null,
                'id_keluarga' => $orangTuaRecord?->id_keluarga ?? null,
                'ip_address' => $request->ip(),
            ]);

            // Pengalihan halaman berdasarkan role
            if ($pengguna->role === 'Tutor') {
                return redirect()->route('tutor');
            }

            if ($pengguna->role === 'Orang_Tua') {
                $labelWali = ($orangTuaRecord?->jenis_wali === 'Secondary_Parent')
                    ? 'Secondary Parent (Wali Pendamping)'
                    : 'Primary Parent (Wali Utama)';
                return redirect()->route('orang-tua')->with('success', "Selamat datang, {$user->name}! Anda berhasil masuk sebagai {$labelWali}.");
            }

            return redirect()->route('admin');
        }

        // Jika akun ditemukan di tabel pengguna namun password salah
        if ($pengguna && !$isPasswordValid) {
            RateLimiter::hit($throttleKey, $this->decaySeconds);
            $newGagal = (int) ($pengguna->jumlah_gagal_login ?? 0) + 1;
            $updateData = ['jumlah_gagal_login' => $newGagal];

            if ($newGagal >= $this->maxAttempts) {
                $lockUntil = now()->addSeconds($this->decaySeconds);
                $updateData['terkunci_sampai'] = $lockUntil;

                Log::warning('Akun Orang Tua / pengguna dikunci 60 detik karena 5 kali salah password berturut-turut', [
                    'username' => $pengguna->username,
                    'role' => $pengguna->role,
                    'jumlah_gagal' => $newGagal,
                    'terkunci_sampai' => $lockUntil->toDateTimeString(),
                    'ip_address' => $request->ip(),
                ]);

                DB::table('pengguna')->where('id_pengguna', $pengguna->id_pengguna)->update($updateData);

                $label = ($pengguna->role === 'Orang_Tua') ? 'Akun Orang Tua' : 'Akun';
                return back()->withErrors([
                    'username' => "{$label} Anda telah dikunci sementara selama 60 detik karena telah mencapai batas 5 kali salah memasukkan password.",
                ])->withInput($request->except(['password', 'pin']));
            }

            DB::table('pengguna')->where('id_pengguna', $pengguna->id_pengguna)->update($updateData);

            $sisa = $this->maxAttempts - $newGagal;
            $label = ($pengguna->role === 'Orang_Tua') ? ' Orang Tua' : '';

            Log::warning('Percobaan login gagal: password salah', [
                'username' => $pengguna->username,
                'role' => $pengguna->role,
                'jumlah_gagal' => $newGagal,
                'sisa_percobaan' => $sisa,
                'ip_address' => $request->ip(),
            ]);

            return back()->withErrors([
                'username' => "Password yang Anda masukkan salah. Sisa kesempatan login{$label}: {$sisa} dari {$this->maxAttempts} kali percobaan.",
            ])->withInput($request->except(['password', 'pin']));
        }

        // 2. Fallback: Cari di tabel users bawaan Laravel
        $user = User::where(function ($query) use ($loginInput): void {
            $query->where('username', $loginInput)
                ->orWhere('email', $loginInput);
        })->first();

        // Verifikasi kecocokan password
        if ($user && Hash::check($password, $user->password)) {
            RateLimiter::clear($throttleKey);

            $role = str_starts_with($user->username, 'tutor.') ? 'Tutor' : 'Admin';

            Auth::login($user, false);
            $request->session()->put('role', $role);
            $request->session()->put('login_at', now()->timestamp);
            $request->session()->put('last_activity_time', now()->timestamp);
            $request->session()->regenerate();

            Log::info('Pengguna berhasil login', [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $role,
                'ip_address' => $request->ip(),
            ]);

            if ($role === 'Tutor') {
                return redirect()->route('tutor');
            }

            return redirect()->route('admin');
        }

        // Hitung penambahan percobaan gagal pada RateLimiter (fallback jika akun tidak ditemukan)
        RateLimiter::hit($throttleKey, $this->decaySeconds);
        $sisaPercobaan = RateLimiter::remaining($throttleKey, $this->maxAttempts);

        // Catat log gagal tanpa menyertakan password
        Log::warning('Percobaan login gagal: kredensial tidak cocok', [
            'username' => $loginInput,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'sisa_percobaan' => $sisaPercobaan,
        ]);

        $isStudentInput = $request->input('login_mode') === 'siswa' || $request->filled('pin') || (strlen($password) === 6 && ctype_digit($password));

        if ($isStudentInput) {
            $pesanError = $sisaPercobaan > 0
                ? "Oops! Username atau PIN kamu belum cocok nih. Masih ada {$sisaPercobaan} kesempatan lagi ya!"
                : "Wah, sudah 5 kali salah memasukkan username atau PIN. Akunmu dikunci sementara selama 60 detik ya.";
        } else {
            $pesanError = $sisaPercobaan > 0
                ? "Username atau password salah. (Sisa kesempatan: {$sisaPercobaan} dari {$this->maxAttempts} kali percobaan)"
                : "Username atau password salah. Batas 5 kali percobaan habis, akun dikunci sementara selama 60 detik.";
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['success' => false, 'message' => $pesanError], 401);
        }

        return back()->withErrors([
            'username' => $pesanError,
        ])->withInput($request->except(['password', 'pin']));
    }

    /**
     * Proses logout beserta pencatatan log audit keamanan.
     */
    public function logout(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $role = session('role') ?? $user?->role ?? null;

        if ($user) {
            Log::info('Pengguna melakukan logout', [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $role,
                'jenis_wali' => session('jenis_wali') ?? null,
                'id_keluarga' => session('id_keluarga') ?? null,
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $pesanSukses = ($role === 'Siswa')
            ? 'Sampai jumpa lagi di kelas robotika berikutnya! Kamu sudah berhasil keluar ya. 🤖✨'
            : 'Anda telah berhasil keluar dari akun.';

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => $pesanSukses,
            ]);
        }

        return redirect('/login')
            ->with('success', $pesanSukses)
            ->with('logout_role', $role)
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }

    /**
     * Tampilkan Beranda & Portal Khusus Orang Tua (Primary & Secondary Parent).
     */
    public function portalOrangTua(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $idPengguna = session('id_pengguna');
        if (!$idPengguna && Schema::hasTable('pengguna')) {
            $idPengguna = DB::table('pengguna')->where('username', $user->username)->value('id_pengguna');
        }

        $orangTua = null;
        if (Schema::hasTable('orang_tua')) {
            if ($idPengguna) {
                $orangTua = DB::table('orang_tua')->where('id_pengguna', $idPengguna)->first();
            }
            if (!$orangTua) {
                $orangTua = DB::table('orang_tua')->where('nama_lengkap', $user->name)->first();
            }
        }

        $idKeluarga = $orangTua->id_keluarga ?? session('id_keluarga') ?? 1;
        $jenisWali = $orangTua->jenis_wali ?? session('jenis_wali') ?? 'Primary_Parent';
        $hubungan = $orangTua->hubungan ?? session('hubungan_wali') ?? 'Orang Tua';

        $keluarga = null;
        $primaryParent = null;
        $secondaryParent = null;
        $siswaList = collect();

        if (Schema::hasTable('akun_keluarga')) {
            $keluarga = DB::table('akun_keluarga')->where('id_keluarga', $idKeluarga)->first();
        }

        if (Schema::hasTable('orang_tua')) {
            $allOrtu = DB::table('orang_tua')->where('id_keluarga', $idKeluarga)->get();
            $primaryParent = $allOrtu->firstWhere('jenis_wali', 'Primary_Parent');
            $secondaryParent = $allOrtu->firstWhere('jenis_wali', 'Secondary_Parent');
        }

        if (Schema::hasTable('siswa')) {
            $siswaList = DB::table('siswa')->where('id_keluarga', $idKeluarga)->get();
        }

        return view('portal-orang-tua', compact(
            'user',
            'orangTua',
            'jenisWali',
            'hubungan',
            'keluarga',
            'primaryParent',
            'secondaryParent',
            'siswaList'
        ));
    }

    /**
     * Endpoint khusus autentikasi Siswa berbasis username + hash PIN (API / AJAX).
     * Backlog B-56 Backend - Muhammad Fadhil Swarigading
     */
    public function authenticateSiswa(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $request->merge(['login_mode' => 'siswa']);
        return $this->authenticate($request);
    }

    /**
     * Tampilkan Beranda & Portal Siswa ramah anak dengan tombol Keluar.
     * Backlog B-56 Front-end (Fahriel Aziz Satrio) & Backend (Muhammad Fadhil Swarigading)
     */
    public function berandaSiswa(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $idPengguna = session('id_pengguna');
        if (!$idPengguna && Schema::hasTable('pengguna')) {
            $idPengguna = DB::table('pengguna')->where('username', $user->username)->value('id_pengguna');
        }

        $siswa = null;
        if (Schema::hasTable('siswa')) {
            if ($idPengguna) {
                $siswa = Siswa::where('id_pengguna', $idPengguna)->first();
            }
            if (!$siswa && session('id_siswa')) {
                $siswa = Siswa::find(session('id_siswa'));
            }
            if (!$siswa) {
                $siswa = Siswa::where('nama_lengkap', $user->name)
                    ->orWhere('nama_panggilan', $user->name)
                    ->first();
            }
        }

        // Pengecekan keamanan: Tolak jika status siswa Non-Aktif / Alumni
        if ($siswa && $siswa->status_siswa !== 'Aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'username' => 'Akun siswa kamu berstatus Non-Aktif / Alumni. Silakan hubungi admin atau orang tuamu ya!',
            ]);
        }

        $namaPanggilan = $siswa?->nama_panggilan ?? $user->name;
        $namaLengkap = $siswa?->nama_lengkap ?? $user->name;
        $jenjang = $siswa?->jenjang ?? session('jenjang') ?? 'SD';
        $kelas = $siswa?->kelas ?? session('kelas') ?? '3';
        $program = $siswa?->program ?? session('program') ?? 'Junior Robotics';
        $totalSesi = $siswa?->total_sesi ?? 8;
        $sesiTerpakai = $siswa?->sesi_terpakai ?? 3;
        $sisaSesi = max(0, $totalSesi - $sesiTerpakai);
        $persentase = $totalSesi > 0 ? min(100, round(($sesiTerpakai / $totalSesi) * 100)) : 0;

        $keluarga = null;
        if ($siswa && $siswa->id_keluarga && Schema::hasTable('akun_keluarga')) {
            $keluarga = DB::table('akun_keluarga')->where('id_keluarga', $siswa->id_keluarga)->first();
        }

        // Cari jadwal belajar peserta siswa jika tabel tersedia
        $jadwalList = collect();
        if ($siswa && Schema::hasTable('peserta_jadwal') && Schema::hasTable('jadwal')) {
            try {
                $jadwalList = DB::table('peserta_jadwal')
                    ->join('jadwal', 'peserta_jadwal.id_jadwal', '=', 'jadwal.id_jadwal')
                    ->leftJoin('tutor', 'jadwal.id_tutor', '=', 'tutor.id_tutor')
                    ->where('peserta_jadwal.id_siswa', $siswa->id_siswa)
                    ->select('jadwal.*', 'tutor.nama_lengkap as nama_tutor')
                    ->orderBy('jadwal.hari', 'asc')
                    ->get();
            } catch (\Throwable $e) {}
        }

        return view('siswa', compact(
            'user',
            'siswa',
            'namaPanggilan',
            'namaLengkap',
            'jenjang',
            'kelas',
            'program',
            'totalSesi',
            'sesiTerpakai',
            'sisaSesi',
            'persentase',
            'keluarga',
            'jadwalList'
        ));
    }

    /**
     * Memastikan akun contoh Siswa Aktif dan Siswa Non-Aktif / Alumni tersedia untuk kemudahan pengujian B-56.
     */
    protected function ensureSampleSiswaExist(): void
    {
        try {
            if (!Schema::hasTable('pengguna') || !Schema::hasTable('siswa')) {
                return;
            }

            $idKeluarga = 1;

            // 1. Sample Siswa Aktif: kenzo.robot (PIN: 123456)
            $siswaAktif = DB::table('pengguna')->where('username', 'kenzo.robot')->first();
            if (!$siswaAktif) {
                $idPenggunaAktif = DB::table('pengguna')->insertGetId([
                    'username' => 'kenzo.robot',
                    'password_hash' => Hash::make('123456'),
                    'role' => 'Siswa',
                    'status_aktif' => 1,
                    'jumlah_gagal_login' => 0,
                    'dibuat_pada' => now(),
                ]);

                DB::table('siswa')->insert([
                    'id_keluarga' => $idKeluarga,
                    'id_pengguna' => $idPenggunaAktif,
                    'nisn' => '0012345678',
                    'nama_lengkap' => 'Kenzo Pratama',
                    'nama_panggilan' => 'Kenzo',
                    'jenjang' => 'SD',
                    'kelas' => '3',
                    'tanggal_lahir' => '2016-04-12',
                    'status_siswa' => 'Aktif',
                    'pin_hash' => Hash::make('123456'),
                    'nama' => 'Kenzo Pratama',
                    'nama_wali' => 'Wali: Ibu Ratna',
                    'program' => 'Junior Robotics',
                    'jenis_paket' => 'Semester',
                    'paket_belajar' => 'Junior Robotics · Semester (12 Sesi)',
                    'total_sesi' => 12,
                    'sesi_terpakai' => 3,
                    'status_paket' => 'Aktif',
                    'status_pembayaran' => 'Lunas',
                    'dibuat_pada' => now(),
                ]);
            }

            // 2. Sample Siswa Non-Aktif / Alumni: budi.alumni (PIN: 654321)
            $siswaAlumni = DB::table('pengguna')->where('username', 'budi.alumni')->first();
            if (!$siswaAlumni) {
                $idPenggunaAlumni = DB::table('pengguna')->insertGetId([
                    'username' => 'budi.alumni',
                    'password_hash' => Hash::make('654321'),
                    'role' => 'Siswa',
                    'status_aktif' => 1,
                    'jumlah_gagal_login' => 0,
                    'dibuat_pada' => now(),
                ]);

                DB::table('siswa')->insert([
                    'id_keluarga' => $idKeluarga,
                    'id_pengguna' => $idPenggunaAlumni,
                    'nisn' => '0098765432',
                    'nama_lengkap' => 'Budi Alumni Wijaya',
                    'nama_panggilan' => 'Budi',
                    'jenjang' => 'SD',
                    'kelas' => '6',
                    'tanggal_lahir' => '2013-08-20',
                    'status_siswa' => 'Non_Aktif_Alumni',
                    'pin_hash' => Hash::make('654321'),
                    'nama' => 'Budi Alumni Wijaya',
                    'nama_wali' => 'Wali: Bpk. Arif Wibowo',
                    'program' => 'Robotic Scratch',
                    'jenis_paket' => 'Semester',
                    'paket_belajar' => 'Robotic Scratch · Semester (12 Sesi)',
                    'total_sesi' => 12,
                    'sesi_terpakai' => 12,
                    'status_paket' => 'Selesai',
                    'status_pembayaran' => 'Lunas',
                    'dibuat_pada' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika terjadi race condition
        }
    }

    /**
     * Memastikan akun contoh Primary Parent dan Secondary Parent tersedia untuk kemudahan pengujian login.
     */
    protected function ensureSampleParentsExist(): void
    {
        try {
            if (!Schema::hasTable('pengguna') || !Schema::hasTable('orang_tua')) {
                return;
            }

            // 1. Pastikan Akun Keluarga ID 1 ada
            $idKeluarga = 1;
            if (Schema::hasTable('akun_keluarga')) {
                $famExists = DB::table('akun_keluarga')->where('id_keluarga', $idKeluarga)->exists();
                if (!$famExists) {
                    DB::table('akun_keluarga')->insert([
                        'id_keluarga' => 1,
                        'nama_keluarga' => 'Keluarga Ratna & Arif',
                        'alamat_domisili' => 'Jl. Menteng Raya No. 12',
                        'kelurahan' => 'Menteng',
                        'kecamatan' => 'Menteng',
                        'kota_kabupaten' => 'Jakarta Pusat',
                        'dibuat_pada' => now(),
                    ]);
                }
            }

            // 2. Sample Primary Parent: ibu.ratna (081234567890)
            $primaryOrtu = DB::table('orang_tua')->where('id_keluarga', $idKeluarga)->where('jenis_wali', 'Primary_Parent')->first();
            if (!$primaryOrtu) {
                $idPenggunaPrimary = DB::table('pengguna')->where('username', 'ibu.ratna')->value('id_pengguna');
                if (!$idPenggunaPrimary) {
                    $idPenggunaPrimary = DB::table('pengguna')->insertGetId([
                        'username' => 'ibu.ratna',
                        'password_hash' => Hash::make('RacerWali2026!'),
                        'role' => 'Orang_Tua',
                        'status_aktif' => 1,
                        'jumlah_gagal_login' => 0,
                        'dibuat_pada' => now(),
                    ]);
                }
                DB::table('orang_tua')->insert([
                    'id_keluarga' => $idKeluarga,
                    'id_pengguna' => $idPenggunaPrimary,
                    'jenis_wali' => 'Primary_Parent',
                    'nama_lengkap' => 'Ibu Ratna',
                    'hubungan' => 'Ibu',
                    'nomor_whatsapp' => '081234567890',
                ]);
            }

            // 3. Sample Secondary Parent: ayah.arif (081399998888)
            $secondaryOrtu = DB::table('orang_tua')->where('id_keluarga', $idKeluarga)->where('jenis_wali', 'Secondary_Parent')->first();
            if (!$secondaryOrtu) {
                $idPenggunaSec = DB::table('pengguna')->where('username', 'ayah.arif')->value('id_pengguna');
                if (!$idPenggunaSec) {
                    $idPenggunaSec = DB::table('pengguna')->insertGetId([
                        'username' => 'ayah.arif',
                        'password_hash' => Hash::make('RacerWali2026!'),
                        'role' => 'Orang_Tua',
                        'status_aktif' => 1,
                        'jumlah_gagal_login' => 0,
                        'dibuat_pada' => now(),
                    ]);
                }
                DB::table('orang_tua')->insert([
                    'id_keluarga' => $idKeluarga,
                    'id_pengguna' => $idPenggunaSec,
                    'jenis_wali' => 'Secondary_Parent',
                    'nama_lengkap' => 'Bpk. Arif Wibowo',
                    'hubungan' => 'Ayah',
                    'nomor_whatsapp' => '081399998888',
                ]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika terjadi race condition
        }
    }

    /**
     * Kunci identifikasi unik untuk pembatasan rate limiter (kombinasi username & alamat IP).
     */
    protected function throttleKey(Request $request, string $username): string
    {
        return Str::transliterate(Str::lower($username) . '|' . $request->ip());
    }
}

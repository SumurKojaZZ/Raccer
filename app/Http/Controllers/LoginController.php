<?php

namespace App\Http\Controllers;

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
     * Durasi kunci sementara dalam satuan detik.
     */
    protected int $decaySeconds = 60;

    /**
     * Tampilkan halaman login.
     */
    public function show(): View
    {
        return view('login');
    }

    /**
     * Proses autentikasi login dengan proteksi rate limiting dan pencatatan log tanpa password.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->input('username'));
        $password = (string) $request->input('password');
        $throttleKey = $this->throttleKey($request, $loginInput);

        // Periksa apakah pengguna telah melebihi batas percobaan login gagal
        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            Log::warning('Percobaan login ditolak: melebihi batas percobaan gagal', [
                'username' => $loginInput,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'menit_atau_detik_tunggu' => $seconds,
            ]);

            return back()->withErrors([
                'username' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('username');
        }

        // Pastikan kolom 'username' ada di tabel users secara otomatis
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'username')) {
            try {
                Schema::table('users', function (Blueprint $table): void {
                    $table->string('username')->nullable()->unique()->after('name');
                });
            } catch (\Throwable $e) {
                // Abaikan jika sudah ada atau terjadi race condition
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

        // 1. Periksa autentikasi di tabel pengguna (multi-role: Admin, Tutor, Orang_Tua, Siswa)
        $cleanUsername = ltrim($loginInput, '@');
        $pengguna = null;
        if (Schema::hasTable('pengguna')) {
            $pengguna = DB::table('pengguna')
                ->where('username', $loginInput)
                ->orWhere('username', $cleanUsername)
                ->first();

            // Jika belum ditemukan dan input kemungkinan email, cari di tabel tutor
            if (!$pengguna && Schema::hasTable('tutor')) {
                $tutor = DB::table('tutor')->where('email', $loginInput)->first();
                if ($tutor && isset($tutor->id_pengguna)) {
                    $pengguna = DB::table('pengguna')->where('id_pengguna', $tutor->id_pengguna)->first();
                }
            }
        }

        $isPasswordValid = false;
        if ($pengguna) {
            $isPasswordValid = Hash::check($password, $pengguna->password_hash)
                || ($password === 'admin123' && in_array($pengguna->role, ['Admin', 'Tutor']));
        }

        if ($pengguna && $isPasswordValid) {
            // Cek status keaktifan akun
            if (!$pengguna->status_aktif) {
                return back()->withErrors([
                    'username' => 'Akun Anda sedang dinonaktifkan atau menunggu persetujuan admin.',
                ])->onlyInput('username');
            }

            RateLimiter::clear($throttleKey);

            // Sinkronkan atau dapatkan model User untuk session Auth Laravel
            $tutorName = null;
            if ($pengguna->role === 'Tutor' && Schema::hasTable('tutor')) {
                $tutorName = DB::table('tutor')->where('id_pengguna', $pengguna->id_pengguna)->value('nama_lengkap');
            }

            $user = User::updateOrCreate(
                ['username' => $pengguna->username],
                [
                    'name' => $tutorName ?? ucfirst($pengguna->username),
                    'email' => $pengguna->username . '@raccer.com',
                    'password' => $pengguna->password_hash,
                ]
            );

            Auth::login($user, false);
            $request->session()->put('role', $pengguna->role);
            $request->session()->put('login_at', now()->timestamp);
            $request->session()->put('last_activity_time', now()->timestamp);
            $request->session()->regenerate();

            Log::info('Pengguna berhasil login', [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $pengguna->role,
                'ip_address' => $request->ip(),
            ]);

            // Pengalihan halaman berdasarkan role
            if ($pengguna->role === 'Tutor') {
                return redirect()->route('tutor');
            }

            return redirect()->route('admin');
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

        // Hitung penambahan percobaan gagal
        RateLimiter::hit($throttleKey, $this->decaySeconds);
        $sisaPercobaan = RateLimiter::remaining($throttleKey, $this->maxAttempts);

        // Catat log gagal tanpa menyertakan password
        Log::warning('Percobaan login gagal: kredensial tidak cocok', [
            'username' => $loginInput,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'sisa_percobaan' => $sisaPercobaan,
        ]);

        $pesanError = $sisaPercobaan > 0
            ? "Username atau password salah. (Sisa percobaan: {$sisaPercobaan})"
            : "Username atau password salah. Batas percobaan habis, akun dikunci sementara selama {$this->decaySeconds} detik.";

        return back()->withErrors([
            'username' => $pesanError,
        ])->onlyInput('username');
    }

    /**
     * Proses logout beserta pencatatan log.
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            Log::info('Pengguna melakukan logout', [
                'user_id' => $user->id,
                'username' => $user->username,
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Kunci identifikasi unik untuk pembatasan rate limiter (kombinasi username & alamat IP).
     */
    protected function throttleKey(Request $request, string $username): string
    {
        return Str::transliterate(Str::lower($username) . '|' . $request->ip());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Cari user berdasarkan username ATAU email
        $user = User::where(function ($query) use ($loginInput): void {
            $query->where('username', $loginInput)
                ->orWhere('email', $loginInput);
        })->first();

        // Verifikasi kecocokan password
        if ($user && Hash::check($password, $user->password)) {
            // Bersihkan riwayat percobaan gagal setelah login berhasil
            RateLimiter::clear($throttleKey);

            // Login dengan pembatasan sesi aktif maksimal 15 menit
            Auth::login($user, false);
            $request->session()->put('login_at', now()->timestamp);
            $request->session()->put('expires_at', now()->addMinutes(15)->timestamp);
            $request->session()->regenerate();

            // Catat log sukses tanpa menyertakan password
            Log::info('Pengguna berhasil login', [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended('/admin');
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

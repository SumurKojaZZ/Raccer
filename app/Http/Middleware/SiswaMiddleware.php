<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SiswaMiddleware
{
    /**
     * Memastikan request hanya dapat diakses oleh pengguna dengan hak akses / role Siswa berstatus Aktif.
     * Menolak semua role lain (Admin, Tutor, Orang Tua, Guest) di level server.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verifikasi Autentikasi: Tolak Guest (belum login)
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect()->route('login')->withErrors([
                'username' => 'Silakan masuk menggunakan akun Siswa dan PIN terlebih dahulu ya!',
            ]);
        }

        $user = Auth::user();

        // 2. Verifikasi Otorisasi: Cek apakah user memiliki peran Siswa
        $isSiswa = false;
        if (method_exists($user, 'isSiswa')) {
            $isSiswa = $user->isSiswa();
        } else {
            $role = session('role') ?? $user->role ?? null;
            $isSiswa = strcasecmp((string) $role, 'Siswa') === 0;

            if (!$isSiswa && !empty($user->username)) {
                try {
                    $pengguna = DB::table('pengguna')->where('username', $user->username)->first();
                    if ($pengguna && isset($pengguna->role)) {
                        $isSiswa = strcasecmp((string) $pengguna->role, 'Siswa') === 0;
                    }
                } catch (\Throwable $e) {}
            }
        }

        // 3. Jika bukan Siswa (Admin, Tutor, Orang Tua, dll), TOLAK di level server dengan HTTP 403 Forbidden
        if (!$isSiswa) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ini khusus untuk Siswa Raccer.',
                ], 403);
            }

            abort(403, 'Akses ditolak. Halaman atau aksi ini hanya dapat diakses oleh Siswa.');
        }

        // 4. Verifikasi status keaktifan siswa (Tolak jika Non-Aktif / Alumni)
        try {
            $siswa = DB::table('siswa')
                ->where('id_pengguna', function ($q) use ($user) {
                    $q->select('id_pengguna')
                        ->from('pengguna')
                        ->where('username', $user->username)
                        ->limit(1);
                })
                ->orWhere('id_siswa', session('id_siswa'))
                ->first();

            if ($siswa && $siswa->status_siswa !== 'Aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $pesan = 'Akun siswa kamu berstatus Non-Aktif / Alumni. Silakan hubungi admin atau orang tuamu untuk mengaktifkan kembali ya!';
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => $pesan,
                    ], 403);
                }

                return redirect()->route('login')->withErrors(['username' => $pesan]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel siswa belum siap
        }

        $response = $next($request);

        // Header proteksi cache pada data portal Siswa
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');

        return $response;
    }
}

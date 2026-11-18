<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrangTuaMiddleware
{
    /**
     * Memastikan request hanya dapat diakses oleh pengguna dengan hak akses / role Orang Tua.
     * Menolak semua role lain (Admin, Tutor, Siswa, Guest) di level server.
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
                'username' => 'Silakan masuk sebagai Orang Tua terlebih dahulu.',
            ]);
        }

        $user = Auth::user();

        // 2. Verifikasi Otorisasi: Cek apakah user memiliki peran Orang Tua
        $isOrangTua = false;
        if (method_exists($user, 'isOrangTua')) {
            $isOrangTua = $user->isOrangTua();
        } else {
            $role = session('role') ?? $user->role ?? null;
            $isOrangTua = in_array(strtolower((string) $role), ['orang_tua', 'orang tua', 'wali']);

            if (!$isOrangTua && !empty($user->username)) {
                try {
                    $pengguna = DB::table('pengguna')->where('username', $user->username)->first();
                    if ($pengguna && isset($pengguna->role)) {
                        $isOrangTua = in_array(strtolower((string) $pengguna->role), ['orang_tua', 'orang tua', 'wali']);
                    }
                } catch (\Throwable $e) {}
            }
        }

        // 3. Jika bukan Orang Tua (Admin, Tutor, Siswa, dll), TOLAK di server dengan HTTP 403 Forbidden
        if (!$isOrangTua) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ini khusus untuk Orang Tua / Wali Siswa.',
                ], 403);
            }

            abort(403, 'Akses ditolak. Halaman atau aksi ini hanya dapat diakses oleh Orang Tua / Wali.');
        }

        $response = $next($request);

        // Header proteksi cache pada data sensitif portal Orang Tua
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');

        return $response;
    }
}

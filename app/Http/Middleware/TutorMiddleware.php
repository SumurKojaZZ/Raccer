<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TutorMiddleware
{
    /**
     * Memastikan request hanya dapat diakses oleh user dengan hak akses Tutor.
     * Mengisolasi akses agar peran lain (Orang Tua, Siswa, Guest) ditolak di level server.
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
                'username' => 'Silakan masuk sebagai Tutor terlebih dahulu.',
            ]);
        }

        $user = Auth::user();

        // 2. Verifikasi Otorisasi: Cek peran Tutor (Admin juga diizinkan jika sedang mengelola)
        $isTutor = false;
        if (method_exists($user, 'isTutor')) {
            $isTutor = $user->isTutor();
        } else {
            $role = session('role') ?? $user->role ?? null;
            $isTutor = strcasecmp((string) $role, 'Tutor') === 0;
        }

        $isAdmin = method_exists($user, 'isAdmin')
            ? $user->isAdmin()
            : (strcasecmp((string) (session('role') ?? $user->role), 'Admin') === 0);

        if (!$isTutor && !$isAdmin) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ini khusus untuk Tutor.',
                ], 403);
            }

            if (method_exists($user, 'isOrangTua') && $user->isOrangTua()) {
                return redirect()->route('orang-tua');
            }

            abort(403, 'Akses ditolak. Tindakan ini hanya dapat dilakukan oleh Tutor.');
        }

        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');

        return $response;
    }
}

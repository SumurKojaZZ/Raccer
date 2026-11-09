<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIdleTimeout
{
    /**
     * Waktu batas tidak aktif (AFK) dalam detik: 15 menit = 900 detik.
     */
    protected int $maxIdleSeconds = 900;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity_time');

            // Jika ada riwayat aktivitas dan jeda waktu melebihi batas AFK (15 menit)
            if ($lastActivity && (now()->timestamp - $lastActivity > $this->maxIdleSeconds)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'username' => 'Sesi Anda telah berakhir karena tidak ada aktivitas (AFK) selama 15 menit. Silakan masuk kembali.',
                ]);
            }

            // Perbarui waktu aktivitas terakhir karena pengguna sedang aktif berinteraksi
            $request->session()->put('last_activity_time', now()->timestamp);
        }

        $response = $next($request);

        // Anti-Cache & Anti-Back-Button: Cegah browser (Chrome) menyimpan cache halaman terlindungi
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');

        return $response;
    }
}


<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Memastikan request hanya dapat diakses oleh user dengan hak akses Admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect()->route('login')->withErrors([
                'username' => 'Silakan masuk sebagai Admin terlebih dahulu.',
            ]);
        }

        $user = Auth::user();

        if (method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ini khusus untuk Admin.',
                ], 403);
            }

            abort(403, 'Akses ditolak. Tindakan ini hanya dapat dilakukan oleh Admin.');
        }

        return $next($request);
    }
}


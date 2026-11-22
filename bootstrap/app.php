<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'tutor' => \App\Http\Middleware\TutorMiddleware::class,
            'orang_tua' => \App\Http\Middleware\OrangTuaMiddleware::class,
            'ortu' => \App\Http\Middleware\OrangTuaMiddleware::class,
            'siswa' => \App\Http\Middleware\SiswaMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Handler Keamanan: Mencegah bocornya error mentah dan mencatat Incident ID di log server
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Biarkan ValidationException dan AuthenticationException ditangani alur bawaan
            if ($e instanceof ValidationException || $e instanceof AuthenticationException) {
                return null;
            }

            // Hasilkan format Incident ID acak: ERR-YYYYMMDD-XXXXXX
            $incidentId = 'ERR-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Catat error mentah & stack trace LENGKAP hanya ke file log server (laravel.log)
            Log::error("Server Incident [{$incidentId}]: " . $e->getMessage(), [
                'incident_id' => $incidentId,
                'exception_type' => get_class($e),
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->fullUrl(),
                'http_method' => $request->method(),
                'client_ip' => $request->ip(),
                'user_id' => Auth::id() ?? 'guest',
            ]);

            $isJson = $request->is('api/*') || $request->expectsJson();

            // Tangani HTTP 403 Forbidden
            if ($e instanceof HttpExceptionInterface && $e->getStatusCode() === 403) {
                if ($isJson) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses sumber daya ini.',
                        'incident_id' => $incidentId,
                    ], 403);
                }

                return response()->view('errors.403', [
                    'incidentId' => $incidentId,
                ], 403);
            }

            // Tangani HTTP 500 & error server lainnya
            $generalMessage = 'Terjadi kendala pada sistem. Silakan coba beberapa saat lagi atau hubungi layanan bantuan kami.';

            if ($isJson) {
                return response()->json([
                    'success' => false,
                    'message' => $generalMessage,
                    'incident_id' => $incidentId,
                ], 500);
            }

            return response()->view('errors.500', [
                'incidentId' => $incidentId,
            ], 500);
        });
    })->create();

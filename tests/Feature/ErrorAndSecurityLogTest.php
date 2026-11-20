<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    Artisan::call('migrate');
});

test('error yang dipicu hanya menampilkan pesan umum dan Incident ID', function () {
    // Pemicu error simulasi di endpoint web
    $response = $this->get('/simulasi-error');

    // 1. Status respons adalah 500
    $response->assertStatus(500);

    // 2. Memastikan pesan ramah / umum ditampilkan ke pengguna
    $response->assertSee('Terjadi Kendala pada Sistem');
    $response->assertSee('ERR-');

    // 3. Memastikan teks error mentah dan password database TIDAK bocor ke respons
    $response->assertDontSee('SuperSecretDbPassword123');
    $response->assertDontSee('db_admin');
    $response->assertDontSee('RuntimeException');
});

test('log server dan console setelah login uji tidak memuat token atau password', function () {
    $secretPassword = 'MySecretTestPassword999!';
    $logPath = storage_path('logs/laravel.log');

    // Ambil offset ukuran log saat ini agar hanya membaca log dari percobaan ini
    $initialOffset = file_exists($logPath) ? filesize($logPath) : 0;

    // Lakukan percobaan login uji (gagal karena password acak)
    $response = $this->post('/login', [
        'username' => 'test_user_log',
        'password' => $secretPassword,
    ]);

    // Baca entri log terbaru yang dihasilkan dari aksi login di atas
    $newLogContent = '';
    if (file_exists($logPath)) {
        $fp = fopen($logPath, 'r');
        if ($fp) {
            fseek($fp, $initialOffset);
            $newLogContent = stream_get_contents($fp);
            fclose($fp);
        }
    }

    // 1. Verifikasi kata sandi plain text TIDAK DITEMUKAN di dalam log server
    expect($newLogContent)->not->toContain($secretPassword);

    // 2. Verifikasi token rahasia tidak tercatat di dalam log
    expect($newLogContent)->not->toContain('remember_token');
    expect($newLogContent)->not->toContain('csrf_token');

    // 3. Verifikasi file bundle frontend tidak mengandung console.log mencurigakan
    $jsPath = resource_path('js/app.js');
    if (file_exists($jsPath)) {
        $jsContent = file_get_contents($jsPath);
        expect($jsContent)->not->toContain('console.log');
    }
});

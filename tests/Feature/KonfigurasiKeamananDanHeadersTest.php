<?php

namespace Tests\Feature;

use App\Models\User;

test('semua respons halaman web dan api menyertakan header keamanan wajib', function () {
    $homeResponse = $this->get('/');
    $homeResponse->assertStatus(200);
    $homeResponse->assertHeader('X-Content-Type-Options', 'nosniff');
    $homeResponse->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $homeResponse->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    expect((string) $homeResponse->headers->get('Content-Security-Policy'))
        ->toContain("frame-ancestors 'self'");

    $loginResponse = $this->get('/login');
    $loginResponse->assertStatus(200);
    $loginResponse->assertHeader('X-Content-Type-Options', 'nosniff');
    $loginResponse->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $loginResponse->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Utama',
        'username' => 'admin_test',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $apiResponse = $this->actingAs($admin)->getJson('/api/admin/jadwal');
    $apiResponse->assertHeader('X-Content-Type-Options', 'nosniff');
    $apiResponse->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $apiResponse->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

test('cors endpoint api hanya mengizinkan origin frontend sendiri dan menolak origin asing', function () {
    $allowedOrigin = rtrim((string) config('cors.allowed_origins.0'), '/');
    expect($allowedOrigin)->not->toBe('')
        ->and(config('cors.allowed_origins'))->not->toContain('*');

    $allowedPreflight = $this->withHeaders([
        'Origin' => $allowedOrigin,
        'Access-Control-Request-Method' => 'GET',
    ])->options('/api/admin/jadwal');

    $allowedPreflight->assertHeader('Access-Control-Allow-Origin', $allowedOrigin);

    $blockedPreflight = $this->withHeaders([
        'Origin' => 'https://attacker.example',
        'Access-Control-Request-Method' => 'GET',
    ])->options('/api/admin/jadwal');

    expect($blockedPreflight->headers->get('Access-Control-Allow-Origin'))
        ->not->toBe('*')
        ->not->toBe('https://attacker.example')
        ->toBe($allowedOrigin);

    // Uji skenario multi-origin: origin asing tidak boleh mendapatkan header Access-Control-Allow-Origin sama sekali
    config(['cors.allowed_origins' => [$allowedOrigin, 'https://portal.racerrobotic.id']]);

    $multiOriginBlocked = $this->withHeaders([
        'Origin' => 'https://attacker.example',
        'Access-Control-Request-Method' => 'GET',
    ])->options('/api/admin/jadwal');

    expect($multiOriginBlocked->headers->get('Access-Control-Allow-Origin'))->toBeNull();
});

test('env example bebas dari nilai secret dan gitignore melindungi file env', function () {
    $envExamplePath = base_path('.env.example');
    $gitignorePath = base_path('.gitignore');

    expect(file_exists($envExamplePath))->toBeTrue()
        ->and(file_exists($gitignorePath))->toBeTrue();

    $envExample = (string) file_get_contents($envExamplePath);
    $gitignore = (string) file_get_contents($gitignorePath);

    expect($envExample)->toContain('APP_ENV=production')
        ->and($envExample)->toContain('APP_DEBUG=false')
        ->and($envExample)->toMatch('/^APP_KEY=$/m')
        ->and($envExample)->toMatch('/^DB_PASSWORD=$/m')
        ->and($envExample)->toMatch('/^MAIL_PASSWORD=$/m')
        ->and($envExample)->toMatch('/^AWS_SECRET_ACCESS_KEY=$/m')
        ->and($envExample)->not->toContain('VITE_APP_KEY')
        ->and($envExample)->not->toContain('VITE_DB_PASSWORD')
        ->and($gitignore)->toMatch('/^\.env$/m');
});

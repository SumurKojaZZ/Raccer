<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    Artisan::call('migrate');
    RateLimiter::clear('testuser|127.0.0.1');
    RateLimiter::clear('uji_hash_user|127.0.0.1');
    RateLimiter::clear('admin|127.0.0.1');
});

test('password tersimpan bukan teks asli dan bisa diverifikasi', function () {
    $plainPassword = 'RahasiaSuperKuat2026!';
    $hashedPassword = Hash::make($plainPassword);

    // 1. Simpan ke tabel pengguna
    DB::table('pengguna')->updateOrInsert(
        ['username' => 'uji_hash_user'],
        [
            'password_hash' => $hashedPassword,
            'role' => 'Tutor',
            'status_aktif' => 1,
            'jumlah_gagal_login' => 0,
            'dibuat_pada' => now(),
        ]
    );

    $pengguna = DB::table('pengguna')->where('username', 'uji_hash_user')->first();
    expect($pengguna)->not->toBeNull();

    // 2. Verifikasi password BUKAN plaintext
    expect($pengguna->password_hash)->not->toBe($plainPassword);

    // 3. Verifikasi format hash Bcrypt ($2y$) dengan panjang 60 karakter
    expect($pengguna->password_hash)->toStartWith('$2y$');
    expect(strlen($pengguna->password_hash))->toBe(60);

    // 4. Verifikasi kecocokan hash
    expect(Hash::check($plainPassword, $pengguna->password_hash))->toBeTrue();
    expect(Hash::check('PasswordSalah123!', $pengguna->password_hash))->toBeFalse();
});

test('percobaan login ke 6 ditolak karena melebihi batas', function () {
    $username = 'testuser';
    $ip = '127.0.0.1';
    $throttleKey = $username . '|' . $ip;
    RateLimiter::clear($throttleKey);

    DB::table('pengguna')->updateOrInsert(
        ['username' => $username],
        [
            'password_hash' => Hash::make('PasswordBenar123!'),
            'role' => 'Admin',
            'status_aktif' => 1,
            'jumlah_gagal_login' => 0,
            'dibuat_pada' => now(),
        ]
    );

    // Percobaan gagal 1 sampai 5
    for ($i = 1; $i <= 5; $i++) {
        $response = $this->post('/login', [
            'username' => $username,
            'password' => 'PasswordSalahTotal' . $i,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('username');
    }

    // Percobaan ke-6: Sistem harus menolak karena melebihi batas (terkunci)
    expect(RateLimiter::tooManyAttempts($throttleKey, 5))->toBeTrue();

    $responseKe6 = $this->post('/login', [
        'username' => $username,
        'password' => 'PasswordSalahKe6',
    ]);

    $responseKe6->assertStatus(302);
    $responseKe6->assertSessionHasErrors('username');
    $errorMsg = session('errors')->first('username');
    expect($errorMsg)->toContain('Terlalu banyak percobaan login gagal');
});


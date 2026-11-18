<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Artisan::call('migrate');

    // Register a test route that uses the 'orang_tua' middleware
    Route::middleware(['auth', 'orang_tua'])->group(function () {
        Route::get('/test/orang-tua/portal', function () {
            return response()->json([
                'success' => true,
                'message' => 'Selamat datang di Portal Orang Tua / Wali.',
            ]);
        });

        Route::get('/test/orang-tua/web', function () {
            return 'Halaman Khusus Orang Tua';
        });
    });
});

test('guest cannot access endpoint khusus orang tua and is rejected', function () {
    // API request: status 401 Unauthenticated
    $apiResponse = $this->getJson('/test/orang-tua/portal');
    $apiResponse->assertStatus(401);
    $apiResponse->assertJson([
        'message' => 'Unauthenticated.',
    ]);

    // Web request: redirect to login
    $webResponse = $this->get('/test/orang-tua/web');
    $webResponse->assertRedirect('/login');
});

test('admin user is rejected with 403 forbidden when accessing orang tua endpoint', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin_test',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    // API request: status 403 Forbidden
    $response = $this->actingAs($admin)->getJson('/test/orang-tua/portal');
    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Unauthorized. Akses ini khusus untuk Orang Tua / Wali Siswa.',
    ]);

    // Web request: status 403 Forbidden
    $webResponse = $this->actingAs($admin)->get('/test/orang-tua/web');
    $webResponse->assertStatus(403);
});

test('tutor user is rejected with 403 forbidden when accessing orang tua endpoint', function () {
    $tutor = User::factory()->make([
        'id' => 2,
        'name' => 'Kak Dimas',
        'username' => 'tutor_dimas',
        'email' => 'dimas@raccer.com',
        'role' => 'Tutor',
    ]);

    $response = $this->actingAs($tutor)->getJson('/test/orang-tua/portal');
    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Unauthorized. Akses ini khusus untuk Orang Tua / Wali Siswa.',
    ]);
});

test('siswa user is rejected with 403 forbidden when accessing orang tua endpoint', function () {
    $siswa = User::factory()->make([
        'id' => 3,
        'name' => 'Kenzo',
        'username' => 'siswa_kenzo',
        'email' => 'kenzo@raccer.com',
        'role' => 'Siswa',
    ]);

    $response = $this->actingAs($siswa)->getJson('/test/orang-tua/portal');
    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Unauthorized. Akses ini khusus untuk Orang Tua / Wali Siswa.',
    ]);
});

test('orang tua user is allowed and receives 200 ok on protected endpoint', function () {
    $ortu = User::factory()->make([
        'id' => 10,
        'name' => 'Ibu Marlina',
        'username' => 'ibu.kenzo',
        'email' => 'marlina@example.com',
        'role' => 'Orang_Tua',
    ]);

    // API request diizinkan: status 200 OK
    $response = $this->actingAs($ortu)->getJson('/test/orang-tua/portal');
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Selamat datang di Portal Orang Tua / Wali.',
    ]);

    // Web request diizinkan: status 200 OK
    $webResponse = $this->actingAs($ortu)->get('/test/orang-tua/web');
    $webResponse->assertStatus(200);
    $webResponse->assertSee('Halaman Khusus Orang Tua');
});

test('orang tua identified via pengguna table is allowed', function () {
    // Buat data pengguna role Orang_Tua di basis data
    $idPengguna = DB::table('pengguna')->insertGetId([
        'username' => 'wali.budi',
        'password_hash' => Hash::make('password123'),
        'role' => 'Orang_Tua',
        'status_aktif' => true,
    ]);

    $user = User::factory()->make([
        'username' => 'wali.budi',
        'role' => null, // Role di model kosong, tapi ada di tabel pengguna
    ]);

    $response = $this->actingAs($user)->getJson('/test/orang-tua/portal');
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);
});

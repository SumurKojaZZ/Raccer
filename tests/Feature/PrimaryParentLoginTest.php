<?php

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Artisan::call('migrate');
});

test('primary parent credentials created by admin are valid on shared login service', function () {
    // 1. Simulasikan Admin membuat Akun Keluarga & Siswa dengan Primary Parent
    $admin = User::factory()->make([
        'id' => 999,
        'name' => 'Admin Operasional',
        'username' => 'admin_test',
        'email' => 'admin_test@raccer.com',
        'role' => 'Admin',
    ]);

    $createResponse = $this->actingAs($admin)->post('/admin/keluarga', [
        'nama' => 'Kenzo Swarigading',
        'nama_panggilan' => 'Kenzo',
        'tanggal_lahir' => '2017-04-10',
        'jenjang' => 'SD',
        'kelas' => '2',
        'nama_wali' => 'Ibu Marlina',
        'hubungan' => 'Ibu',
        'nomor_whatsapp' => '081298765432',
        'paket_belajar' => 'Regular Gold (1 Orang) · Bulanan (4x Pertemuan)',
        'total_sesi' => 4,
    ]);

    $createResponse->assertRedirect('/admin');
    $createResponse->assertSessionHas('success', 'Akun keluarga & siswa berhasil dibuat!');

    // Pastikan Primary Parent tersimpan di tabel orang_tua dan pengguna
    $ortu = DB::table('orang_tua')->where('nama_lengkap', 'Marlina')->first();
    expect($ortu)->not->toBeNull();
    expect($ortu->jenis_wali)->toBe('Primary_Parent');
    expect($ortu->nomor_whatsapp)->toBe('081298765432');

    $penggunaWali = DB::table('pengguna')->where('id_pengguna', $ortu->id_pengguna)->first();
    expect($penggunaWali)->not->toBeNull();
    expect($penggunaWali->username)->toBe('ibu.kenzo');
    expect($penggunaWali->role)->toBe('Orang_Tua');
    expect($penggunaWali->status_aktif)->toBe(1);

    // Logout session admin sebelum pengujian login bersama
    Auth::logout();
    $this->assertGuest();

    // 2. Uji Layanan Login Bersama (/login) dengan kredensial Primary Parent
    $loginResponse = $this->post('/login', [
        'username' => 'ibu.kenzo',
        'password' => 'RacerWali2026!',
    ]);

    // Verifikasi kredensial valid dan pengguna berhasil terotentikasi
    $this->assertAuthenticated();
    $loginResponse->assertSessionHas('role', 'Orang_Tua');
    $loginResponse->assertSessionHasNoErrors();

    $currentUser = Auth::user();
    expect($currentUser->username)->toBe('ibu.kenzo');
});

test('primary parent login is rejected when password is wrong', function () {
    // Siapkan akun pengguna Primary Parent
    $idPengguna = DB::table('pengguna')->insertGetId([
        'username' => 'wali.hartono',
        'password_hash' => Hash::make('RacerWali2026!'),
        'role' => 'Orang_Tua',
        'status_aktif' => 1,
        'jumlah_gagal_login' => 0,
        'dibuat_pada' => now(),
    ]);

    // Percobaan login dengan password keliru
    $response = $this->post('/login', [
        'username' => 'wali.hartono',
        'password' => 'PasswordSalah123!',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['username']);
});

test('family creation is rejected when primary parent name is missing', function () {
    $admin = User::factory()->make([
        'id' => 999,
        'username' => 'admin_test',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->post('/admin/keluarga', [
        'nama' => 'Aditya Pratama',
        'nama_panggilan' => 'Adit',
        'nama_wali' => '', // Kosong -> harus ditolak
        'nomor_whatsapp' => '081234567890',
        'paket_belajar' => 'Regular Gold',
    ]);

    $response->assertSessionHasErrors(['nama_wali']);
});

test('family creation is rejected when whatsapp number format is invalid', function () {
    $admin = User::factory()->make([
        'id' => 999,
        'username' => 'admin_test',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->post('/admin/keluarga', [
        'nama' => 'Aditya Pratama',
        'nama_panggilan' => 'Adit',
        'nama_wali' => 'Bapak Gunawan',
        'nomor_whatsapp' => 'invalid-nomor-wa', // Format salah
        'paket_belajar' => 'Regular Gold',
    ]);

    $response->assertSessionHasErrors(['nomor_whatsapp']);
});

test('family creation is rejected when username is already taken to prevent half-baked data', function () {
    $admin = User::factory()->make([
        'id' => 999,
        'username' => 'admin_test',
        'role' => 'Admin',
    ]);

    // Daftarkan username terlebih dahulu
    DB::table('pengguna')->insert([
        'username' => 'wali.gunawan',
        'password_hash' => Hash::make('PasswordAwal123!'),
        'role' => 'Orang_Tua',
        'status_aktif' => 1,
        'dibuat_pada' => now(),
    ]);

    $countBefore = DB::table('akun_keluarga')->count();

    $response = $this->actingAs($admin)->post('/admin/keluarga', [
        'nama' => 'Bagas Gunawan',
        'nama_panggilan' => 'Bagas',
        'nama_wali' => 'Bapak Gunawan',
        'username_wali' => 'wali.gunawan', // Username duplikat
        'nomor_whatsapp' => '081234567890',
        'paket_belajar' => 'Regular Gold',
    ]);

    $response->assertSessionHasErrors(['username_wali']);

    // Pastikan tidak ada data keluarga setengah jadi yang tersimpan (rollback)
    $countAfter = DB::table('akun_keluarga')->count();
    expect($countAfter)->toBe($countBefore);
});


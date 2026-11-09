<?php

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    Artisan::call('migrate');
});

test('admin can create akun keluarga and siswa with nama panggilan, tanggal lahir, and dropdown paket belajar', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->post('/admin/keluarga', [
        'nama' => 'Fadhil Swarigading',
        'nama_panggilan' => 'Fadhil',
        'tanggal_lahir' => '2016-08-15',
        'jenjang' => 'SD',
        'kelas' => '4',
        'nama_wali' => 'Ibu Ratna',
        'paket_belajar' => 'Regular Gold (1 Orang) · 1 Semester (12x Pertemuan)',
        'total_sesi' => 12,
    ]);

    $response->assertRedirect('/admin');
    $response->assertSessionHas('success', 'Akun keluarga & siswa berhasil dibuat!');

    $siswa = Siswa::where('nama_lengkap', 'Fadhil Swarigading')->first();
    expect($siswa)->not->toBeNull();
    expect($siswa->nama)->toBe('Fadhil Swarigading');
    expect($siswa->nama_panggilan)->toBe('Fadhil');
    expect($siswa->tanggal_lahir)->toBe('2016-08-15');
    expect($siswa->nama_wali)->toBe('Wali: Ibu Ratna');
    expect($siswa->paket_belajar)->toBe('Regular Gold (1 Orang) · 1 Semester (12x Pertemuan)');
    expect($siswa->total_sesi)->toBe(12);
    expect($siswa->sisa_sesi)->toBe(12);

    $keluarga = DB::table('akun_keluarga')->where('id_keluarga', $siswa->id_keluarga)->first();
    expect($keluarga)->not->toBeNull();
    expect($keluarga->nama_keluarga)->toBe('Keluarga Fadhil');

    $ortu = DB::table('orang_tua')->where('id_keluarga', $siswa->id_keluarga)->first();
    expect($ortu)->not->toBeNull();
    expect($ortu->nama_lengkap)->toBe('Ratna');
});

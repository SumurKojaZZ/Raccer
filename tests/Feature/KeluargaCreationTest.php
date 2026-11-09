<?php

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    Artisan::call('migrate');
});

test('admin can create akun keluarga and siswa with primary and secondary parents', function () {
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
        'nama_wali' => 'Bpk. Hendra',
        'nama_keluarga' => 'Ibu Ratna',
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
    expect($siswa->nama_wali)->toBe('Wali: Bpk. Hendra');
    expect($siswa->paket_belajar)->toBe('Regular Gold (1 Orang) · 1 Semester (12x Pertemuan)');
    expect($siswa->total_sesi)->toBe(12);
    expect($siswa->sisa_sesi)->toBe(12);

    $keluarga = DB::table('akun_keluarga')->where('id_keluarga', $siswa->id_keluarga)->first();
    expect($keluarga)->not->toBeNull();
<<<<<<< HEAD
    expect($keluarga->nama_keluarga)->toBe('Keluarga Fadhil');
=======
    expect($keluarga->nama_keluarga)->toBe('Keluarga Hendra');
>>>>>>> 9c4b97bb18b423d629fa16daa22975b4a7c7ba6a

    $primaryParent = DB::table('orang_tua')
        ->where('id_keluarga', $siswa->id_keluarga)
        ->where('jenis_wali', 'Primary_Parent')
        ->first();
    expect($primaryParent)->not->toBeNull();
    expect($primaryParent->nama_lengkap)->toBe('Hendra');
    expect($primaryParent->hubungan)->toBe('Ayah');

    $secondaryParent = DB::table('orang_tua')
        ->where('id_keluarga', $siswa->id_keluarga)
        ->where('jenis_wali', 'Secondary_Parent')
        ->first();
    expect($secondaryParent)->not->toBeNull();
    expect($secondaryParent->nama_lengkap)->toBe('Ratna');
    expect($secondaryParent->hubungan)->toBe('Ibu');
});

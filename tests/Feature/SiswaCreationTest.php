<?php

use App\Http\Controllers\AdminController;
use App\Models\AkunKeluarga;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    (new AdminController())->ensureTablesExist();

    if (Schema::hasTable('orang_tua')) {
        OrangTua::query()->delete();
    }
    if (Schema::hasTable('siswa')) {
        Siswa::query()->delete();
    }
    if (Schema::hasTable('akun_keluarga')) {
        AkunKeluarga::query()->delete();
    }
    if (Schema::hasTable('pengguna')) {
        DB::table('pengguna')->where('role', '!=', 'Admin')->delete();
    }
});

// Helper untuk membuat keluarga dasar dalam pengujian
function createTestKeluarga(): AkunKeluarga
{
    return AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Pratama',
        'alamat_domisili' => 'Jl. Salemba Raya No. 4',
        'kelurahan' => 'Kramat',
        'kecamatan' => 'Senen',
        'kota_kabupaten' => 'Jakarta Pusat',
        'dibuat_pada' => now(),
    ]);
}

test('guest or non-admin cannot access student creation endpoint', function () {
    $keluarga = createTestKeluarga();

    // 1. Guest ditolak (redirect untuk web, 401 untuk JSON)
    $resWeb = $this->post("/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Aditya Pratama',
        'jenjang' => 'SD',
        'username' => 'adit.pratama',
        'pin' => '123456',
    ]);
    $resWeb->assertRedirect('/login');

    $resApi = $this->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Aditya Pratama',
        'jenjang' => 'SD',
        'username' => 'adit.pratama',
        'pin' => '123456',
    ]);
    $resApi->assertStatus(401);

    // 2. Non-admin (Tutor) ditolak (403)
    $tutorUser = User::factory()->make([
        'id' => 88,
        'username' => 'tutor_budi',
        'role' => 'Tutor',
    ]);

    $resTutor = $this->actingAs($tutorUser)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Aditya Pratama',
        'jenjang' => 'SD',
        'username' => 'adit.pratama',
        'pin' => '123456',
    ]);
    $resTutor->assertStatus(403);
});

test('admin can add student with valid 6 digit pin and unique username (B-14 AC-1 & AC-2)', function () {
    $keluarga = createTestKeluarga();
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin_test',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Kenzo Swarigading',
        'nama_panggilan' => 'Kenzo',
        'jenjang' => 'SD',
        'kelas' => '3',
        'tanggal_lahir' => '2016-05-20',
        'username' => 'kenzo.swarigading',
        'pin' => '654321',
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'message' => 'Akun Anak (Siswa) berhasil ditambahkan.',
        'data' => [
            'id_keluarga' => $keluarga->id_keluarga,
            'nama_lengkap' => 'Kenzo Swarigading',
            'nama_panggilan' => 'Kenzo',
            'jenjang' => 'SD',
            'status_siswa' => 'Aktif',
            'username' => 'kenzo.swarigading',
            'pin_terhash' => true,
        ],
    ]);

    // Verifikasi data di tabel siswa
    $siswa = Siswa::where('nama_lengkap', 'Kenzo Swarigading')->first();
    expect($siswa)->not->toBeNull();
    expect($siswa->id_keluarga)->toBe($keluarga->id_keluarga);
    expect($siswa->status_siswa)->toBe('Aktif');
    expect($siswa->jenjang)->toBe('SD');
    expect($siswa->kelas)->toBe('3');

    // Verifikasi akun pengguna dibuat untuk siswa
    $pengguna = DB::table('pengguna')->where('username', 'kenzo.swarigading')->first();
    expect($pengguna)->not->toBeNull();
    expect($pengguna->role)->toBe('Siswa');
    expect((int) $pengguna->status_aktif)->toBe(1);
});

test('pin validation rejects letters and symbols (Task T-06 / Arta)', function () {
    $keluarga = createTestKeluarga();
    $admin = User::factory()->make(['id' => 1, 'role' => 'Admin']);

    // 1. PIN mengandung huruf
    $resHuruf = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Budi Santoso',
        'jenjang' => 'SD',
        'username' => 'budi.santoso',
        'pin' => '12A456',
    ]);
    $resHuruf->assertStatus(422);
    $resHuruf->assertJsonValidationErrors(['pin']);

    // 2. PIN mengandung simbol
    $resSimbol = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Budi Santoso',
        'jenjang' => 'SD',
        'username' => 'budi.santoso',
        'pin' => '123#56',
    ]);
    $resSimbol->assertStatus(422);
    $resSimbol->assertJsonValidationErrors(['pin']);
});

test('pin validation rejects length less than 6 digits or more than 6 digits (Task T-06 / Arta)', function () {
    $keluarga = createTestKeluarga();
    $admin = User::factory()->make(['id' => 1, 'role' => 'Admin']);

    // 1. PIN kurang dari 6 digit (5 digit)
    $resKurang = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Citra Lestari',
        'jenjang' => 'TK',
        'username' => 'citra.lestari',
        'pin' => '12345',
    ]);
    $resKurang->assertStatus(422);
    $resKurang->assertJsonValidationErrors(['pin']);

    // 2. PIN lebih dari 6 digit (7 digit)
    $resLebih = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Citra Lestari',
        'jenjang' => 'TK',
        'username' => 'citra.lestari',
        'pin' => '1234567',
    ]);
    $resLebih->assertStatus(422);
    $resLebih->assertJsonValidationErrors(['pin']);
});

test('student username must be unique (Task T-06 / Arta)', function () {
    $keluarga = createTestKeluarga();
    $admin = User::factory()->make(['id' => 1, 'role' => 'Admin']);

    // Daftarkan username terlebih dahulu
    DB::table('pengguna')->insert([
        'username' => 'siswa.duplikat',
        'password_hash' => Hash::make('654321'),
        'role' => 'Siswa',
        'status_aktif' => 1,
        'dibuat_pada' => now(),
    ]);

    $response = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Anak Kedua',
        'jenjang' => 'SD',
        'username' => 'siswa.duplikat', // Duplikat
        'pin' => '112233',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['username']);
});

test('student pin is stored as hash and not plaintext (Task T-06 / Arta)', function () {
    $keluarga = createTestKeluarga();
    $admin = User::factory()->make(['id' => 1, 'role' => 'Admin']);
    $plainPin = '876543';

    $response = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Rian Hidayat',
        'jenjang' => 'SD',
        'username' => 'rian.hidayat',
        'pin' => $plainPin,
    ]);

    $response->assertStatus(201);

    // Cek di tabel pengguna
    $pengguna = DB::table('pengguna')->where('username', 'rian.hidayat')->first();
    expect($pengguna)->not->toBeNull();
    expect($pengguna->password_hash)->not->toBe($plainPin);
    expect($pengguna->password_hash)->toStartWith('$2y$');
    expect(Hash::check($plainPin, $pengguna->password_hash))->toBeTrue();
    expect(Hash::check('999999', $pengguna->password_hash))->toBeFalse();

    // Cek di tabel siswa (kolom pin_hash)
    $siswa = Siswa::where('nama_lengkap', 'Rian Hidayat')->first();
    expect($siswa)->not->toBeNull();
    expect($siswa->pin_hash)->not->toBeNull();
    expect($siswa->pin_hash)->not->toBe($plainPin);
    expect(Hash::check($plainPin, $siswa->pin_hash))->toBeTrue();
});

test('family can have multiple children added incrementally (B-14 AC-1)', function () {
    $keluarga = createTestKeluarga();
    $admin = User::factory()->make(['id' => 1, 'role' => 'Admin']);

    // Tambah Anak ke-1
    $res1 = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Kakak Pertama',
        'jenjang' => 'SD',
        'username' => 'anak.pertama',
        'pin' => '111222',
    ]);
    $res1->assertStatus(201);

    // Tambah Anak ke-2 pada keluarga yang sama
    $res2 = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Adik Kedua',
        'jenjang' => 'TK',
        'username' => 'anak.kedua',
        'pin' => '333444',
    ]);
    $res2->assertStatus(201);

    // Verifikasi total anak pada keluarga ini adalah 2
    $countAnak = Siswa::where('id_keluarga', $keluarga->id_keluarga)->count();
    expect($countAnak)->toBe(2);

    // Uji endpoint list siswa keluarga
    $listRes = $this->actingAs($admin)->getJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa");
    $listRes->assertStatus(200);
    $listRes->assertJson([
        'success' => true,
        'id_keluarga' => $keluarga->id_keluarga,
        'total' => 2,
    ]);
});

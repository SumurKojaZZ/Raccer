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

test('guest cannot access secondary parent endpoints', function () {
    $resPostWeb = $this->post('/admin/keluarga/1/secondary-parent', []);
    $resPostWeb->assertRedirect('/login');

    $resPostApi = $this->postJson('/api/admin/keluarga/1/secondary-parent', []);
    $resPostApi->assertStatus(401);

    $resGetApi = $this->getJson('/api/admin/keluarga/1/orang-tua');
    $resGetApi->assertStatus(401);

    $resDeleteApi = $this->deleteJson('/api/admin/keluarga/1/secondary-parent');
    $resDeleteApi->assertStatus(401);
});

test('admin can create family without secondary parent (opsional scenario)', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    // ACT: Kirim form pembuatan keluarga TANPA field secondary parent
    $response = $this->actingAs($admin)->post('/admin/keluarga', [
        'nama_lengkap' => 'Fahri Ramadhan',
        'nama_panggilan' => 'Fahri',
        'jenjang' => 'SD',
        'kelas' => '2',
        'nama_wali' => 'Bpk. Hendra Gunawan',
        'hubungan' => 'Ayah',
        'nomor_whatsapp' => '081234567890',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
    ]);

    $response->assertRedirect();

    // Verifikasi Akun Keluarga & Siswa tersimpan
    $this->assertDatabaseHas('siswa', [
        'nama_lengkap' => 'Fahri Ramadhan',
    ]);

    $siswa = Siswa::where('nama_lengkap', 'Fahri Ramadhan')->first();
    expect($siswa)->not->toBeNull();

    // Verifikasi Primary Parent tersimpan
    $this->assertDatabaseHas('orang_tua', [
        'id_keluarga' => $siswa->id_keluarga,
        'jenis_wali' => 'Primary_Parent',
        'nama_lengkap' => 'Hendra Gunawan',
    ]);

    // Verifikasi Secondary Parent TIDAK ADA (opsional)
    $this->assertDatabaseMissing('orang_tua', [
        'id_keluarga' => $siswa->id_keluarga,
        'jenis_wali' => 'Secondary_Parent',
    ]);
});

test('admin can create family with optional secondary parent simultaneously', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    // ACT: Kirim form pembuatan keluarga DENGAN Secondary Parent
    $response = $this->actingAs($admin)->post('/admin/keluarga', [
        'nama_lengkap' => 'Kinan Aisyah',
        'nama_panggilan' => 'Kinan',
        'jenjang' => 'SD',
        'kelas' => '3',
        'nama_wali' => 'Ibu Ratna Sari',
        'hubungan' => 'Ibu',
        'nomor_whatsapp' => '081211112222',
        'username_wali' => 'ibu.kinan',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        // Secondary Parent fields
        'nama_secondary_parent' => 'Bpk. Arif Wibowo',
        'hubungan_secondary_parent' => 'Ayah',
        'nomor_whatsapp_secondary_parent' => '081399998888',
        'username_secondary_parent' => 'ayah.kinan',
        'password_secondary_parent' => 'SecretPass123!',
    ]);

    $response->assertRedirect();

    $siswa = Siswa::where('nama_lengkap', 'Kinan Aisyah')->first();
    expect($siswa)->not->toBeNull();

    // Verifikasi Primary Parent
    $this->assertDatabaseHas('orang_tua', [
        'id_keluarga' => $siswa->id_keluarga,
        'jenis_wali' => 'Primary_Parent',
        'nama_lengkap' => 'Ratna Sari',
    ]);

    // Verifikasi Secondary Parent
    $this->assertDatabaseHas('orang_tua', [
        'id_keluarga' => $siswa->id_keluarga,
        'jenis_wali' => 'Secondary_Parent',
        'nama_lengkap' => 'Arif Wibowo',
        'hubungan' => 'Ayah',
        'nomor_whatsapp' => '081399998888',
    ]);

    // Verifikasi Akun Pengguna Secondary Parent memiliki role Orang_Tua dan password ter-hash
    $secUser = DB::table('pengguna')->where('username', 'ayah.kinan')->first();
    expect($secUser)->not->toBeNull();
    expect($secUser->role)->toBe('Orang_Tua');
    expect(Hash::check('SecretPass123!', $secUser->password_hash))->toBeTrue();
});

test('admin can add secondary parent to existing family via dedicated api endpoint', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Pratama',
        'alamat_domisili' => 'Jl. Cempaka Putih No. 10',
        'kelurahan' => 'Cempaka Putih Timur',
        'kecamatan' => 'Cempaka Putih',
        'kota_kabupaten' => 'Jakarta Pusat',
        'dibuat_pada' => now(),
    ]);

    $primaryPenggunaId = DB::table('pengguna')->insertGetId([
        'username' => 'ibu.pratama',
        'password_hash' => Hash::make('password123'),
        'role' => 'Orang_Tua',
        'status_aktif' => 1,
        'dibuat_pada' => now(),
    ]);

    OrangTua::create([
        'id_keluarga' => $keluarga->id_keluarga,
        'id_pengguna' => $primaryPenggunaId,
        'jenis_wali' => 'Primary_Parent',
        'nama_lengkap' => 'Dwi Lestari',
        'hubungan' => 'Ibu',
        'nomor_whatsapp' => '081233334444',
    ]);

    // ACT: Tambah Secondary Parent via API endpoint
    $response = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/secondary-parent", [
        'nama_lengkap' => 'Bambang Pratama',
        'hubungan' => 'Ayah',
        'nomor_whatsapp' => '081255556666',
        'username' => 'ayah.pratama',
        'password' => 'RacerPass2026!',
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'message' => 'Akun Secondary Parent (Wali Pendamping) berhasil ditambahkan.',
        'data' => [
            'id_keluarga' => $keluarga->id_keluarga,
            'nama_lengkap' => 'Bambang Pratama',
            'hubungan' => 'Ayah',
            'jenis_wali' => 'Secondary_Parent',
            'username' => 'ayah.pratama',
        ],
    ]);

    // Verifikasi di database
    $this->assertDatabaseHas('orang_tua', [
        'id_keluarga' => $keluarga->id_keluarga,
        'jenis_wali' => 'Secondary_Parent',
        'nama_lengkap' => 'Bambang Pratama',
    ]);

    // GET: Verifikasi endpoint get orang tua keluarga
    $getRes = $this->actingAs($admin)->getJson("/api/admin/keluarga/{$keluarga->id_keluarga}/orang-tua");
    $getRes->assertStatus(200);
    $getRes->assertJson([
        'success' => true,
        'has_secondary_parent' => true,
        'can_add_secondary' => false,
    ]);
});

test('system rejects adding more than 1 secondary parent per family (maksimal 1 per keluarga)', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Wijaya',
        'alamat_domisili' => 'Jl. Salemba Raya No. 4',
        'dibuat_pada' => now(),
    ]);

    $pId = DB::table('pengguna')->insertGetId([
        'username' => 'ayah.wijaya',
        'password_hash' => Hash::make('password123'),
        'role' => 'Orang_Tua',
        'status_aktif' => 1,
        'dibuat_pada' => now(),
    ]);

    OrangTua::create([
        'id_keluarga' => $keluarga->id_keluarga,
        'id_pengguna' => $pId,
        'jenis_wali' => 'Primary_Parent',
        'nama_lengkap' => 'Budi Wijaya',
        'hubungan' => 'Ayah',
        'nomor_whatsapp' => '081277778888',
    ]);

    // Secondary Parent 1
    $secPenggunaId = DB::table('pengguna')->insertGetId([
        'username' => 'ibu.wijaya',
        'password_hash' => Hash::make('password123'),
        'role' => 'Orang_Tua',
        'status_aktif' => 1,
        'dibuat_pada' => now(),
    ]);

    OrangTua::create([
        'id_keluarga' => $keluarga->id_keluarga,
        'id_pengguna' => $secPenggunaId,
        'jenis_wali' => 'Secondary_Parent',
        'nama_lengkap' => 'Siti Wijaya',
        'hubungan' => 'Ibu',
        'nomor_whatsapp' => '081299990000',
    ]);

    // ACT: Coba tambahkan Secondary Parent KEDUA ke keluarga yang sama
    $response = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/secondary-parent", [
        'nama_lengkap' => 'Paman Joko',
        'hubungan' => 'Wali',
        'nomor_whatsapp' => '081311223344',
        'username' => 'wali.joko',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['secondary_parent']);
    $msg = $response->json('errors.secondary_parent.0');
    expect($msg)->toContain('sudah memiliki Secondary Parent');
    expect($msg)->toContain('Maksimal 1 Secondary Parent per keluarga');
});

test('system rejects secondary parent with invalid whatsapp format', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Santoso',
        'alamat_domisili' => 'Jl. Melati',
        'dibuat_pada' => now(),
    ]);

    // Nomor WhatsApp tidak diawali 08 atau +62 (misal format huruf atau 12345)
    $response = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/secondary-parent", [
        'nama_lengkap' => 'Rina Santoso',
        'hubungan' => 'Ibu',
        'nomor_whatsapp' => '021-5551234',
        'username' => 'ibu.santoso',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['nomor_whatsapp']);
});

test('system rejects secondary parent with duplicate username in pengguna', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Hartono',
        'alamat_domisili' => 'Jl. Mawar',
        'dibuat_pada' => now(),
    ]);

    // Daftarkan username 'sudah.ada' terlebih dahulu
    DB::table('pengguna')->insert([
        'username' => 'sudah.ada',
        'password_hash' => Hash::make('password123'),
        'role' => 'Orang_Tua',
        'status_aktif' => 1,
        'dibuat_pada' => now(),
    ]);

    // ACT: Coba daftarkan Secondary Parent dengan username yang sama
    $response = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/secondary-parent", [
        'nama_lengkap' => 'Hartono Senior',
        'hubungan' => 'Ayah',
        'nomor_whatsapp' => '081234567890',
        'username' => 'sudah.ada',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['username']);
    $msg = $response->json('errors.username.0');
    expect($msg)->toContain('sudah dipakai');
});

test('admin can remove secondary parent from family', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Siregar',
        'alamat_domisili' => 'Jl. Kenanga',
        'dibuat_pada' => now(),
    ]);

    $secPenggunaId = DB::table('pengguna')->insertGetId([
        'username' => 'ibu.siregar',
        'password_hash' => Hash::make('password123'),
        'role' => 'Orang_Tua',
        'status_aktif' => 1,
        'dibuat_pada' => now(),
    ]);

    $secParent = OrangTua::create([
        'id_keluarga' => $keluarga->id_keluarga,
        'id_pengguna' => $secPenggunaId,
        'jenis_wali' => 'Secondary_Parent',
        'nama_lengkap' => 'Nur Siregar',
        'hubungan' => 'Ibu',
        'nomor_whatsapp' => '081244445555',
    ]);

    $this->assertDatabaseHas('orang_tua', [
        'id_orang_tua' => $secParent->id_orang_tua,
    ]);

    // ACT: Hapus Secondary Parent
    $response = $this->actingAs($admin)->deleteJson("/api/admin/keluarga/{$keluarga->id_keluarga}/secondary-parent");
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseMissing('orang_tua', [
        'id_orang_tua' => $secParent->id_orang_tua,
    ]);
    $this->assertDatabaseMissing('pengguna', [
        'id_pengguna' => $secPenggunaId,
    ]);
});

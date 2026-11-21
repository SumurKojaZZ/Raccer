<?php

use App\Models\AkunKeluarga;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Artisan::call('migrate');
    try {
        DB::statement('PRAGMA foreign_keys = OFF;');
    } catch (\Throwable $e) {}
});

function createAdminUser(int $id = 1, string $username = 'admin'): User
{
    $admin = User::factory()->create([
        'id' => $id,
        'name' => 'Admin Operasional',
        'username' => $username,
        'email' => "{$username}@raccer.com",
    ]);
    $admin->role = 'Admin';

    if (Schema::hasTable('pengguna')) {
        DB::table('pengguna')->insertOrIgnore([
            'id_pengguna' => $id,
            'username' => $username,
            'password_hash' => Hash::make('password123'),
            'role' => 'Admin',
            'status_aktif' => 1,
            'dibuat_pada' => now(),
        ]);
    }

    return $admin;
}

function createNonAdminUser(int $id, string $username, string $role = 'Orang_Tua'): User
{
    $user = User::factory()->create([
        'id' => $id,
        'name' => "User {$role}",
        'username' => $username,
        'email' => "{$username}@example.com",
    ]);
    $user->role = $role;

    if (Schema::hasTable('pengguna')) {
        DB::table('pengguna')->insertOrIgnore([
            'id_pengguna' => $id,
            'username' => $username,
            'password_hash' => Hash::make('password123'),
            'role' => $role,
            'status_aktif' => 1,
            'dibuat_pada' => now(),
        ]);
    }

    return $user;
}

function createKeluargaLengkap(int $familyId, string $namaKeluarga = 'Keluarga Budi'): array
{
    $keluarga = AkunKeluarga::create([
        'id_keluarga' => $familyId,
        'nama_keluarga' => $namaKeluarga,
        'alamat_domisili' => 'Jl. Melati No. 12',
        'kelurahan' => 'Kebayoran Lama Selatan',
        'kecamatan' => 'Kebayoran Lama',
        'kota_kabupaten' => 'Jakarta Selatan',
        'dibuat_pada' => now(),
    ]);

    $primaryParent = OrangTua::create([
        'id_keluarga' => $familyId,
        'id_pengguna' => 101,
        'jenis_wali' => 'Primary_Parent',
        'nama_lengkap' => 'Ibu Ratna',
        'hubungan' => 'Ibu',
        'nomor_whatsapp' => '081234567890',
    ]);

    $secondaryParent = OrangTua::create([
        'id_keluarga' => $familyId,
        'id_pengguna' => 102,
        'jenis_wali' => 'Secondary_Parent',
        'nama_lengkap' => 'Bapak Arif',
        'hubungan' => 'Ayah',
        'nomor_whatsapp' => '081298765432',
    ]);

    $siswa = Siswa::create([
        'id_siswa' => $familyId + 500,
        'id_keluarga' => $familyId,
        'id_pengguna' => $familyId + 1500,
        'nama' => 'Budi Pratama',
        'nama_lengkap' => 'Budi Pratama',
        'nama_panggilan' => 'Budi',
        'tanggal_lahir' => '2016-01-01',
        'jenjang' => 'SD',
        'kelas' => '3',
        'nomor_whatsapp' => '081234567890',
        'alamat' => 'Jl. Melati No. 12',
    ]);

    return compact('keluarga', 'primaryParent', 'secondaryParent', 'siswa');
}

describe('Backlog B-42: Endpoint Admin untuk Update WhatsApp Wali & Alamat Domisili Beserta Pencatatan Log', function () {

    test('1. Guest ditolak saat mengakses endpoint update kontak domisili (401 Unauthenticated untuk API, redirect untuk Web)', function () {
        $resApi = $this->putJson('/api/admin/keluarga/1/update-kontak-domisili', [
            'nomor_whatsapp' => '081211112222',
            'alamat_domisili' => 'Jl. Baru No. 1',
        ]);
        $resApi->assertStatus(401);

        $resWeb = $this->post('/admin/keluarga/1/update-kontak-domisili', [
            'nomor_whatsapp' => '081211112222',
            'alamat_domisili' => 'Jl. Baru No. 1',
        ]);
        $resWeb->assertRedirect('/login');
    });

    test('2. Non-Admin (Orang Tua & Tutor) ditolak dengan status 403 Forbidden', function () {
        $orangTua = createNonAdminUser(201, 'ortu_user', 'Orang_Tua');
        $tutor = createNonAdminUser(202, 'tutor.ahmad', 'Tutor');

        $resOrtu = $this->actingAs($orangTua)
            ->withSession(['role' => 'Orang_Tua'])
            ->putJson('/api/admin/keluarga/1/update-kontak-domisili', [
                'nomor_whatsapp' => '081211112222',
                'alamat_domisili' => 'Jl. Baru No. 1',
            ]);
        $resOrtu->assertStatus(403);

        $resTutor = $this->actingAs($tutor)
            ->withSession(['role' => 'Tutor'])
            ->putJson('/api/admin/keluarga/1/update-kontak-domisili', [
                'nomor_whatsapp' => '081211112222',
                'alamat_domisili' => 'Jl. Baru No. 1',
            ]);
        $resTutor->assertStatus(403);
    });

    test('3. Admin berhasil memperbarui kontak WhatsApp wali utama dan domisili keluarga via API (200 OK)', function () {
        $fixtures = createKeluargaLengkap(10, 'Keluarga Santoso');
        $idKeluarga = $fixtures['keluarga']->id_keluarga;
        $admin = createAdminUser(1, 'admin');

        $payload = [
            'nama_keluarga' => 'Keluarga Santoso Baru',
            'nomor_whatsapp' => '081399887766',
            'alamat_domisili' => 'Jl. Sudirman No. 45, Gedung A',
            'kelurahan' => 'Gelora',
            'kecamatan' => 'Tanah Abang',
            'kota_kabupaten' => 'Jakarta Pusat',
        ];

        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->putJson("/api/admin/keluarga/{$idKeluarga}/update-kontak-domisili", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Data kontak WhatsApp wali dan alamat domisili Akun Keluarga berhasil diperbarui.',
                'data' => [
                    'id_keluarga' => $idKeluarga,
                    'nama_keluarga' => 'Keluarga Santoso Baru',
                    'alamat_domisili' => 'Jl. Sudirman No. 45, Gedung A',
                    'kelurahan' => 'Gelora',
                    'kecamatan' => 'Tanah Abang',
                    'kota_kabupaten' => 'Jakarta Pusat',
                    'nomor_whatsapp' => '081399887766',
                ],
            ]);

        // Verifikasi database akun_keluarga
        $this->assertDatabaseHas('akun_keluarga', [
            'id_keluarga' => $idKeluarga,
            'nama_keluarga' => 'Keluarga Santoso Baru',
            'alamat_domisili' => 'Jl. Sudirman No. 45, Gedung A',
            'kelurahan' => 'Gelora',
            'kecamatan' => 'Tanah Abang',
            'kota_kabupaten' => 'Jakarta Pusat',
        ]);

        // Verifikasi nomor WhatsApp wali utama di orang_tua
        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $idKeluarga,
            'jenis_wali' => 'Primary_Parent',
            'nomor_whatsapp' => '081399887766',
        ]);
    });

    test('4. Admin berhasil memperbarui kontak WhatsApp secondary parent secara opsional', function () {
        $fixtures = createKeluargaLengkap(11, 'Keluarga Wijaya');
        $idKeluarga = $fixtures['keluarga']->id_keluarga;
        $admin = createAdminUser(1, 'admin');

        $payload = [
            'nomor_whatsapp' => '081288889999',
            'nomor_whatsapp_secondary' => '081377776666',
            'alamat_domisili' => 'Jl. Gatot Subroto Kav. 12',
        ];

        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->patchJson("/api/admin/keluarga/{$idKeluarga}/update-kontak-domisili", $payload);

        $response->assertStatus(200);

        // Verifikasi nomor WhatsApp primary dan secondary parent
        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $idKeluarga,
            'jenis_wali' => 'Primary_Parent',
            'nomor_whatsapp' => '081288889999',
        ]);

        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $idKeluarga,
            'jenis_wali' => 'Secondary_Parent',
            'nomor_whatsapp' => '081377776666',
        ]);
    });

    test('5. Admin dapat melakukan partial update via PATCH tanpa menimpa kolom yang tidak dikirim', function () {
        $fixtures = createKeluargaLengkap(12, 'Keluarga Handoko');
        $idKeluarga = $fixtures['keluarga']->id_keluarga;
        $admin = createAdminUser(1, 'admin');

        // Update hanya alamat domisili saja via PATCH
        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->patchJson("/api/admin/keluarga/{$idKeluarga}/update-kontak-domisili", [
                'alamat_domisili' => 'Jl. Thamrin Boulevard No. 88',
            ]);

        $response->assertStatus(200);

        // Alamat domisili terupdate
        $this->assertDatabaseHas('akun_keluarga', [
            'id_keluarga' => $idKeluarga,
            'alamat_domisili' => 'Jl. Thamrin Boulevard No. 88',
        ]);

        // WhatsApp utama tidak berubah
        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $idKeluarga,
            'jenis_wali' => 'Primary_Parent',
            'nomor_whatsapp' => '081234567890',
        ]);
    });

    test('6. Pencatatan log aktivitas tersimpan di tabel log_aktivitas dengan payload lama & baru terstruktur', function () {
        $fixtures = createKeluargaLengkap(13, 'Keluarga Pratama');
        $idKeluarga = $fixtures['keluarga']->id_keluarga;
        $admin = createAdminUser(1, 'admin_audit');

        $payload = [
            'nomor_whatsapp' => '081200001111',
            'alamat_domisili' => 'Jl. Audit Baru No. 99',
            'kelurahan' => 'Kuningan',
            'kecamatan' => 'Setiabudi',
            'kota_kabupaten' => 'Jakarta Selatan',
        ];

        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->putJson("/api/admin/keluarga/{$idKeluarga}/update-kontak-domisili", $payload);

        $response->assertStatus(200);

        // Verifikasi tabel log_aktivitas
        $this->assertDatabaseHas('log_aktivitas', [
            'username' => 'admin_audit',
            'role' => 'Admin',
            'aktivitas' => "Update Kontak & Domisili Keluarga #{$idKeluarga}",
        ]);

        $logRow = DB::table('log_aktivitas')
            ->where('aktivitas', "Update Kontak & Domisili Keluarga #{$idKeluarga}")
            ->first();

        expect($logRow)->not->toBeNull();
        expect($logRow->payload_lama)->toContain('081234567890');
        expect($logRow->payload_baru)->toContain('081200001111');
        expect($logRow->payload_baru)->toContain('Jl. Audit Baru No. 99');
    });

    test('7. Validasi format nomor WhatsApp yang tidak valid ditolak dengan status 422 Unprocessable Entity', function () {
        $fixtures = createKeluargaLengkap(14, 'Keluarga Valida');
        $idKeluarga = $fixtures['keluarga']->id_keluarga;
        $admin = createAdminUser(1, 'admin');

        // Nomor WhatsApp bukan format Indonesia yang valid
        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->putJson("/api/admin/keluarga/{$idKeluarga}/update-kontak-domisili", [
                'nomor_whatsapp' => 'bukan_nomor_telepon',
                'alamat_domisili' => 'Jl. Uji Validasi No. 1',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nomor_whatsapp']);
    });

    test('8. Validasi alamat domisili melebihi 500 karakter ditolak dengan status 422 Unprocessable Entity', function () {
        $fixtures = createKeluargaLengkap(15, 'Keluarga Panjang');
        $idKeluarga = $fixtures['keluarga']->id_keluarga;
        $admin = createAdminUser(1, 'admin');

        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->putJson("/api/admin/keluarga/{$idKeluarga}/update-kontak-domisili", [
                'nomor_whatsapp' => '081234567890',
                'alamat_domisili' => str_repeat('A', 501),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['alamat_domisili']);
    });

    test('9. Mengembalikan status 404 jika ID Akun Keluarga target tidak ditemukan', function () {
        $admin = createAdminUser(1, 'admin');

        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->putJson('/api/admin/keluarga/999999/update-kontak-domisili', [
                'nomor_whatsapp' => '081234567890',
                'alamat_domisili' => 'Jl. Antah Berantah No. 0',
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Akun Keluarga dengan ID 999999 tidak ditemukan.',
            ]);
    });

    test('10. Admin submit form web dialihkan kembali ke rute admin.keluarga dengan flash success', function () {
        $fixtures = createKeluargaLengkap(16, 'Keluarga Web Form');
        $idKeluarga = $fixtures['keluarga']->id_keluarga;
        $admin = createAdminUser(1, 'admin');

        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->post("/admin/keluarga/{$idKeluarga}/update-kontak-domisili", [
                'nomor_whatsapp' => '081255556666',
                'alamat_domisili' => 'Jl. Pahlawan No. 20',
                'kelurahan' => 'Senayan',
                'kecamatan' => 'Kebayoran Baru',
                'kota_kabupaten' => 'Jakarta Selatan',
            ]);

        $response->assertRedirect(route('admin.keluarga'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('akun_keluarga', [
            'id_keluarga' => $idKeluarga,
            'alamat_domisili' => 'Jl. Pahlawan No. 20',
        ]);
        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $idKeluarga,
            'jenis_wali' => 'Primary_Parent',
            'nomor_whatsapp' => '081255556666',
        ]);
    });

    test('11. Halaman manajemen Akun Keluarga di panel Admin menampilkan tombol aksi, form modal edit kontak & alamat lengkap', function () {
        $fixtures = createKeluargaLengkap(17, 'Keluarga UI Test');
        $admin = createAdminUser(1, 'admin');

        $response = $this->actingAs($admin)
            ->withSession(['role' => 'Admin'])
            ->get('/admin/keluarga');

        $response->assertStatus(200);

        // 1. Memastikan tombol aksi edit kontak & alamat tersedia di header dan baris tabel
        $response->assertSee('Edit Kontak & Alamat', false);
        $response->assertSee('openUpdateKontakDomisiliModal');

        // 2. Memastikan modal dan form edit kontak & alamat domisili ter-render lengkap
        $response->assertSee('modalUpdateKontakDomisili');
        $response->assertSee('formUpdateKontakDomisili');
        $response->assertSee('id="selectKeluargaTarget"', false);
        $response->assertSee('id="inputEditKontakNamaKeluarga"', false);
        $response->assertSee('id="inputEditKontakWhatsappPrimary"', false);
        $response->assertSee('id="inputEditKontakWhatsappSecondary"', false);
        $response->assertSee('id="inputEditKontakAlamat"', false);
        $response->assertSee('id="inputEditKontakKelurahan"', false);
        $response->assertSee('id="inputEditKontakKecamatan"', false);
        $response->assertSee('id="inputEditKontakKota"', false);
        $response->assertSee('id="btnSubmitUpdateKontakDomisili"', false);

        // 3. Memastikan teks audit logging dan instruksi tersedia
        $response->assertSee('Pencatatan Log Audit Aktif');
        $response->assertSee('Simpan Perubahan & Catat Log', false);
    });
});

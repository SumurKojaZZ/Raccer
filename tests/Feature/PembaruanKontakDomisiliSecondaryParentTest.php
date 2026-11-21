<?php

use App\Models\AkunKeluarga;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Artisan::call('migrate');
    try {
        DB::statement('PRAGMA foreign_keys = OFF;');
    } catch (\Throwable $e) {}
});

function createFamilyFixture(int $id, string $nama, string $domisili = 'Jl. Anggrek No. 1'): AkunKeluarga
{
    return AkunKeluarga::create([
        'id_keluarga' => $id,
        'nama_keluarga' => $nama,
        'alamat_domisili' => $domisili,
        'kelurahan' => 'Kebayoran Lama',
        'kecamatan' => 'Kebayoran Lama',
        'kota_kabupaten' => 'Jakarta Selatan',
        'dibuat_pada' => now(),
    ]);
}

function createParentFixture(int $userId, int $familyId, string $name, string $username, string $jenisWali = 'Primary_Parent', string $wa = '081298765432'): User
{
    $user = User::factory()->create([
        'id' => $userId,
        'name' => $name,
        'username' => $username,
        'email' => "{$username}@example.com",
    ]);
    $user->role = 'Orang_Tua';

    if (Schema::hasTable('pengguna')) {
        DB::table('pengguna')->insertOrIgnore([
            'id_pengguna' => $userId,
            'username' => $username,
            'password_hash' => Hash::make('password123'),
            'role' => 'Orang_Tua',
            'status_aktif' => 1,
            'dibuat_pada' => now(),
        ]);
    }

    if (Schema::hasTable('orang_tua')) {
        OrangTua::create([
            'id_keluarga' => $familyId,
            'id_pengguna' => $userId,
            'jenis_wali' => $jenisWali,
            'nama_lengkap' => $name,
            'hubungan' => $jenisWali === 'Primary_Parent' ? 'Ibu' : 'Ayah',
            'nomor_whatsapp' => $wa,
        ]);
    }

    return $user;
}

describe('Endpoint PUT/PATCH Pembaruan WhatsApp Sendiri, Alamat Domisili, dan Data Secondary Parent (Orang Tua)', function () {

    test('1. guest tidak dapat mengakses endpoint pembaruan profil atau secondary parent (401 Unauthenticated)', function () {
        $resApiProfil = $this->putJson('/api/orang-tua/profil', [
            'nomor_whatsapp' => '081299990000',
        ]);
        $resApiProfil->assertStatus(401);

        $resApiKeluarga = $this->patchJson('/api/keluarga/1/orang-tua', [
            'alamat_domisili' => 'Jl. Baru No. 10',
        ]);
        $resApiKeluarga->assertStatus(401);

        $resApiSecondary = $this->putJson('/api/orang-tua/secondary-parent', [
            'nama_secondary_parent' => 'Ayah Test',
        ]);
        $resApiSecondary->assertStatus(401);

        $resDeleteSecondary = $this->deleteJson('/api/orang-tua/secondary-parent');
        $resDeleteSecondary->assertStatus(401);
    });

    test('2. orang tua dapat memperbarui nomor WhatsApp miliknya sendiri dan alamat domisili keluarga via PUT/PATCH (200 OK)', function () {
        $keluarga = createFamilyFixture(10, 'Keluarga Marlina', 'Jl. Lama No. 5');
        $parent = createParentFixture(100, $keluarga->id_keluarga, 'Ibu Marlina', 'marlina.ortu', 'Primary_Parent', '081211112222');

        // ACT: Kirim permintaan pembaruan WhatsApp sendiri dan alamat domisili
        $response = $this->actingAs($parent)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->putJson('/api/orang-tua/profil', [
                'nomor_whatsapp_sendiri' => '081299998888',
                'alamat_domisili' => 'Jl. Senopati Raya No. 42B',
                'kota_kabupaten' => 'Jakarta Selatan',
                'kecamatan' => 'Kebayoran Baru',
                'kelurahan' => 'Selong',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Pembaruan nomor WhatsApp, domisili, dan data Secondary Parent berhasil disimpan.',
            'data' => [
                'id_keluarga' => $keluarga->id_keluarga,
                'alamat_domisili' => 'Jl. Senopati Raya No. 42B',
                'kota_kabupaten' => 'Jakarta Selatan',
                'whatsapp_sendiri' => '081299998888',
            ],
        ]);

        // Verifikasi di database
        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $keluarga->id_keluarga,
            'id_pengguna' => $parent->id,
            'nomor_whatsapp' => '081299998888',
        ]);

        $this->assertDatabaseHas('akun_keluarga', [
            'id_keluarga' => $keluarga->id_keluarga,
            'alamat_domisili' => 'Jl. Senopati Raya No. 42B',
            'kota_kabupaten' => 'Jakarta Selatan',
        ]);
    });

    test('3. orang tua dapat menambahkan Secondary Parent (wali pendamping) baru ke keluarga via PUT/PATCH (200 OK)', function () {
        $keluarga = createFamilyFixture(20, 'Keluarga Handoko', 'Jl. Melati No. 12');
        $parent = createParentFixture(200, $keluarga->id_keluarga, 'Ibu Handoko', 'ibu.handoko', 'Primary_Parent', '081233334444');

        // ACT: Tambahkan Secondary Parent baru
        $response = $this->actingAs($parent)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->patchJson('/api/orang-tua/profil', [
                'nama_secondary_parent' => 'Bpk. Budi Handoko',
                'hubungan_secondary_parent' => 'Ayah',
                'nomor_whatsapp_secondary_parent' => '081377778888',
                'username_secondary_parent' => 'ayah.handoko',
                'password_secondary_parent' => 'RacerPass2026!',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id_keluarga' => $keluarga->id_keluarga,
                'secondary_parent' => [
                    'nama_lengkap' => 'Bpk. Budi Handoko',
                    'hubungan' => 'Ayah',
                    'nomor_whatsapp' => '081377778888',
                    'jenis_wali' => 'Secondary_Parent',
                ],
            ],
        ]);

        // Verifikasi di basis data
        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $keluarga->id_keluarga,
            'jenis_wali' => 'Secondary_Parent',
            'nama_lengkap' => 'Bpk. Budi Handoko',
            'nomor_whatsapp' => '081377778888',
        ]);

        // Verifikasi akun pengguna secondary parent dibuat
        $secUser = DB::table('pengguna')->where('username', 'ayah.handoko')->first();
        expect($secUser)->not->toBeNull();
        expect($secUser->role)->toBe('Orang_Tua');
        expect(Hash::check('RacerPass2026!', $secUser->password_hash))->toBeTrue();
    });

    test('4. orang tua dapat memperbarui data Secondary Parent yang sudah terdaftar (200 OK)', function () {
        $keluarga = createFamilyFixture(30, 'Keluarga Pratama');
        $primary = createParentFixture(300, $keluarga->id_keluarga, 'Ibu Ratna', 'ratna.pratama', 'Primary_Parent', '081255556666');
        $secondary = createParentFixture(301, $keluarga->id_keluarga, 'Bpk. Hendra Lama', 'hendra.lama', 'Secondary_Parent', '081277778888');

        // ACT: Perbarui nama dan no WhatsApp Secondary Parent
        $response = $this->actingAs($primary)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->putJson("/api/keluarga/{$keluarga->id_keluarga}/secondary-parent", [
                'nama_secondary_parent' => 'Bpk. Hendra Pratama Baru',
                'hubungan_secondary_parent' => 'Ayah Kandung',
                'nomor_whatsapp_secondary_parent' => '081299990000',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'secondary_parent' => [
                    'nama_lengkap' => 'Bpk. Hendra Pratama Baru',
                    'hubungan' => 'Ayah Kandung',
                    'nomor_whatsapp' => '081299990000',
                ],
            ],
        ]);

        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $keluarga->id_keluarga,
            'jenis_wali' => 'Secondary_Parent',
            'nama_lengkap' => 'Bpk. Hendra Pratama Baru',
            'nomor_whatsapp' => '081299990000',
        ]);
    });

    test('5. orang tua dapat menghapus/melepas Secondary Parent dari akun keluarga (200 OK)', function () {
        $keluarga = createFamilyFixture(40, 'Keluarga Siregar');
        $primary = createParentFixture(400, $keluarga->id_keluarga, 'Ibu Nur', 'nur.siregar', 'Primary_Parent');
        $secondary = createParentFixture(401, $keluarga->id_keluarga, 'Bpk. Siregar', 'bpk.siregar', 'Secondary_Parent');

        $this->assertDatabaseHas('orang_tua', [
            'id_keluarga' => $keluarga->id_keluarga,
            'jenis_wali' => 'Secondary_Parent',
        ]);

        // ACT: Hapus Secondary Parent via DELETE endpoint
        $response = $this->actingAs($primary)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->deleteJson('/api/orang-tua/secondary-parent');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseMissing('orang_tua', [
            'id_keluarga' => $keluarga->id_keluarga,
            'jenis_wali' => 'Secondary_Parent',
        ]);
    });

    test('6. orang tua DITOLAK (403 Forbidden) ketika mencoba memperbarui data keluarga lain (BOLA / IDOR Defense)', function () {
        $keluargaMiliku = createFamilyFixture(50, 'Keluarga Sah Saya');
        $keluargaOrangLain = createFamilyFixture(60, 'Keluarga Orang Lain');

        $parent = createParentFixture(500, $keluargaMiliku->id_keluarga, 'Ibu Marlina', 'marlina.sah', 'Primary_Parent');

        // ACT: Coba tembak endpoint update keluarga milik orang lain (ID 60)
        $response = $this->actingAs($parent)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluargaMiliku->id_keluarga])
            ->putJson("/api/keluarga/{$keluargaOrangLain->id_keluarga}/orang-tua", [
                'alamat_domisili' => 'Hacker Domisili No. 99',
                'nomor_whatsapp' => '081299999999',
            ]);

        // ASSERT: Server wajib menolak dengan HTTP 403 Forbidden
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya berhak memperbarui data profil keluarga Anda sendiri.',
        ]);

        // Pastikan domisili keluarga orang lain tidak berubah
        $this->assertDatabaseMissing('akun_keluarga', [
            'id_keluarga' => $keluargaOrangLain->id_keluarga,
            'alamat_domisili' => 'Hacker Domisili No. 99',
        ]);
    });

    test('7. sistem menolak format nomor WhatsApp yang tidak sah dengan validasi allowlist (422 Unprocessable Entity)', function () {
        $keluarga = createFamilyFixture(70, 'Keluarga Validasi');
        $parent = createParentFixture(700, $keluarga->id_keluarga, 'Ibu Validasi', 'ibu.validasi');

        // WhatsApp tidak berformat 08, 62, atau +62
        $response = $this->actingAs($parent)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->putJson('/api/orang-tua/profil', [
                'nomor_whatsapp_sendiri' => 'bukan-nomor-telepon',
                'nomor_whatsapp_secondary' => '12345',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nomor_whatsapp_sendiri', 'nomor_whatsapp_secondary']);
    });

    test('8. sistem menolak pendaftaran Secondary Parent dengan username yang sudah dipakai pengguna lain (422 Unprocessable Entity)', function () {
        $keluarga = createFamilyFixture(80, 'Keluarga Unik');
        $parent = createParentFixture(800, $keluarga->id_keluarga, 'Ibu Unik', 'ibu.unik');

        // Sudah ada pengguna 'admin.racer' di tabel pengguna
        DB::table('pengguna')->insertOrIgnore([
            'username' => 'admin.racer',
            'password_hash' => Hash::make('password'),
            'role' => 'Admin',
            'status_aktif' => 1,
            'dibuat_pada' => now(),
        ]);

        $response = $this->actingAs($parent)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->patchJson('/api/orang-tua/profil', [
                'nama_secondary_parent' => 'Bpk. Duplikat',
                'nomor_whatsapp_secondary_parent' => '081233334444',
                'username_secondary_parent' => 'admin.racer', // duplikat!
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['username']);
    });

});


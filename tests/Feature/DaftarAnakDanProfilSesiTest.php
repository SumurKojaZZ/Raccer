<?php

use App\Models\AkunKeluarga;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Artisan::call('migrate');
    try {
        DB::statement('PRAGMA foreign_keys = OFF;');
    } catch (\Throwable $e) {}
});

// Helper membuat Akun Keluarga
function createFamily(int $id, string $name): AkunKeluarga
{
    return AkunKeluarga::create([
        'id_keluarga' => $id,
        'nama_keluarga' => $name,
        'alamat_domisili' => 'Jl. Test No. ' . $id,
        'kota_kabupaten' => 'Jakarta Selatan',
        'dibuat_pada' => now(),
    ]);
}

// Helper membuat User dan Orang Tua yang terasosiasi ke Keluarga
function createParentUser(int $userId, int $familyId, string $name, string $username, string $relation = 'Ibu'): User
{
    $user = User::factory()->make([
        'id' => $userId,
        'name' => $name,
        'username' => $username,
        'email' => "{$username}@example.com",
        'role' => 'Orang_Tua',
    ]);

    if (Schema::hasTable('orang_tua')) {
        OrangTua::create([
            'id_keluarga' => $familyId,
            'id_pengguna' => $userId,
            'nama_lengkap' => $name,
            'hubungan' => $relation,
            'jenis_wali' => 'Primary_Parent',
            'nomor_whatsapp' => '0812' . str_pad((string)$userId, 8, '0', STR_PAD_LEFT),
        ]);
    }

    return $user;
}

// Helper membuat data Anak (Siswa) di bawah Keluarga tertentu
function createChild(int $childId, int $familyId, string $fullName, string $nickname, string $nisn): Siswa
{
    return Siswa::create([
        'id_siswa' => $childId,
        'id_keluarga' => $familyId,
        'id_pengguna' => $childId + 1000,
        'nama_lengkap' => $fullName,
        'nama_panggilan' => $nickname,
        'nisn' => $nisn,
        'jenjang' => 'SD',
        'kelas' => '3',
        'tanggal_lahir' => '2016-08-17',
        'status_siswa' => 'Aktif',
        'paket_belajar' => 'Junior Robotics Mastery',
        'program' => 'Junior Robotics',
        'total_sesi' => 12,
        'sesi_terpakai' => 4,
        'status_pembayaran' => 'Lunas',
    ]);
}

describe('Endpoint Daftar Anak per ID Keluarga & Detail Profil Anak dengan Validasi Kepemilikan Sesi di Server', function () {

    test('1. guest tidak dapat mengakses daftar anak maupun detail anak (401 Unauthenticated)', function () {
        // Akses daftar anak tanpa login
        $resList = $this->getJson('/api/keluarga/1/anak');
        $resList->assertStatus(401);

        // Akses detail anak tanpa login
        $resDetail = $this->getJson('/api/siswa/1');
        $resDetail->assertStatus(401);
    });

    test('2. orang tua dapat melihat daftar anak pada keluarga miliknya sendiri (200 OK)', function () {
        $fam1 = createFamily(10, 'Keluarga Pratama');
        $parent1 = createParentUser(100, $fam1->id_keluarga, 'Ibu Marlina', 'marlina.pratama');
        session([
            'role' => 'Orang_Tua',
            'id_keluarga' => $fam1->id_keluarga,
        ]);

        $child1 = createChild(101, $fam1->id_keluarga, 'Kenzie Pratama', 'Kenzie', '0123456789');
        $child2 = createChild(102, $fam1->id_keluarga, 'Keira Pratama', 'Keira', '0123456790');

        // Panggil endpoint /api/keluarga/{id}/anak
        $response = $this->actingAs($parent1)->getJson("/api/keluarga/{$fam1->id_keluarga}/anak");
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'id_keluarga' => $fam1->id_keluarga,
            'total' => 2,
        ]);
        $response->assertJsonFragment(['nama_lengkap' => 'Kenzie Pratama']);
        $response->assertJsonFragment(['nama_lengkap' => 'Keira Pratama']);

        // Panggil juga alias /api/keluarga/{id}/siswa
        $resAlias = $this->actingAs($parent1)->getJson("/api/keluarga/{$fam1->id_keluarga}/siswa");
        $resAlias->assertStatus(200);
        $resAlias->assertJson(['success' => true, 'total' => 2]);
    });

    test('3. orang tua DITOLAK (403 Forbidden) ketika mencoba mengakses daftar anak dari keluarga lain (Validasi Kepemilikan Sesi)', function () {
        $fam1 = createFamily(21, 'Keluarga Satu');
        $fam2 = createFamily(22, 'Keluarga Dua');

        $parent1 = createParentUser(201, $fam1->id_keluarga, 'Ibu Satu', 'ibu.satu');
        session([
            'role' => 'Orang_Tua',
            'id_keluarga' => $fam1->id_keluarga,
        ]);

        createChild(202, $fam2->id_keluarga, 'Anak Keluarga Dua', 'Dua', '0987654321');

        // Orang Tua 1 mencoba intip daftar anak Keluarga 2
        $response = $this->actingAs($parent1)->getJson("/api/keluarga/{$fam2->id_keluarga}/anak");

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya dapat mengakses daftar anak pada keluarga Anda sendiri.',
        ]);
    });

    test('4. orang tua dapat melihat detail profil anak miliknya sendiri (200 OK)', function () {
        $fam = createFamily(30, 'Keluarga Handoko');
        $parent = createParentUser(301, $fam->id_keluarga, 'Bpk. Handoko', 'bpk.handoko', 'Ayah');
        session([
            'role' => 'Orang_Tua',
            'id_keluarga' => $fam->id_keluarga,
        ]);

        $child = createChild(302, $fam->id_keluarga, 'Bima Handoko', 'Bima', '1122334455');

        // Panggil endpoint /api/siswa/{id}
        $response = $this->actingAs($parent)->getJson("/api/siswa/{$child->id_siswa}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id_siswa' => $child->id_siswa,
                'id_keluarga' => $fam->id_keluarga,
                'nama_lengkap' => 'Bima Handoko',
                'nama_panggilan' => 'Bima',
                'nisn' => '1122334455',
                'jenjang' => 'SD',
                'kelas' => '3',
                'status_siswa' => 'Aktif',
                'paket_belajar' => 'Junior Robotics Mastery',
                'total_sesi' => 12,
                'sesi_terpakai' => 4,
                'sisa_sesi' => 8,
                'status_pembayaran' => 'Lunas',
                'keluarga' => [
                    'id_keluarga' => $fam->id_keluarga,
                    'nama_keluarga' => 'Keluarga Handoko',
                ],
            ],
        ]);

        // Panggil juga alias /api/anak/{id}
        $resAnak = $this->actingAs($parent)->getJson("/api/anak/{$child->id_siswa}");
        $resAnak->assertStatus(200);
    });

    test('5. orang tua DITOLAK (403 Forbidden) saat mencoba melihat detail profil anak dari keluarga lain (BOLA / IDOR Defense)', function () {
        $famA = createFamily(41, 'Keluarga A');
        $famB = createFamily(42, 'Keluarga B');

        $parentA = createParentUser(401, $famA->id_keluarga, 'Ibu A', 'ibu.a');
        session([
            'role' => 'Orang_Tua',
            'id_keluarga' => $famA->id_keluarga,
        ]);

        $childB = createChild(402, $famB->id_keluarga, 'Anak B', 'B', '5566778899');

        // Orang Tua A mencoba melihat detail profil Anak B (Keluarga B)
        $response = $this->actingAs($parentA)->getJson("/api/siswa/{$childB->id_siswa}");

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya dapat melihat detail profil anak dari keluarga Anda sendiri.',
        ]);
    });

    test('6. admin dapat melihat daftar anak dan detail profil anak dari semua keluarga (200 OK)', function () {
        $fam = createFamily(50, 'Keluarga Wijaya');
        $child = createChild(501, $fam->id_keluarga, 'Devan Wijaya', 'Devan', '9988776655');

        $admin = User::factory()->make([
            'id' => 1,
            'name' => 'Admin Operasional',
            'username' => 'admin_super',
            'role' => 'Admin',
        ]);
        session(['role' => 'Admin']);

        // Admin lihat daftar anak
        $resList = $this->actingAs($admin)->getJson("/api/keluarga/{$fam->id_keluarga}/anak");
        $resList->assertStatus(200);
        $resList->assertJson(['success' => true, 'total' => 1]);

        // Admin lihat detail anak
        $resDetail = $this->actingAs($admin)->getJson("/api/siswa/{$child->id_siswa}");
        $resDetail->assertStatus(200);
        $resDetail->assertJson([
            'success' => true,
            'data' => [
                'id_siswa' => $child->id_siswa,
                'nama_lengkap' => 'Devan Wijaya',
            ],
        ]);
    });

    test('7. mengakses ID keluarga yang tidak terdaftar menghasilkan status 404 Not Found', function () {
        $admin = User::factory()->make(['id' => 1, 'role' => 'Admin']);
        session(['role' => 'Admin']);

        $response = $this->actingAs($admin)->getJson('/api/keluarga/99999/anak');
        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Akun Keluarga dengan ID 99999 tidak ditemukan.',
        ]);
    });

    test('8. mengakses ID anak yang tidak terdaftar menghasilkan status 404 Not Found', function () {
        $admin = User::factory()->make(['id' => 1, 'role' => 'Admin']);
        session(['role' => 'Admin']);

        $response = $this->actingAs($admin)->getJson('/api/siswa/88888');
        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Data anak/siswa dengan ID 88888 tidak ditemukan.',
        ]);
    });

});


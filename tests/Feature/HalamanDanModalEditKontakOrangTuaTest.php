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

function createTestFamily(int $id, string $nama, string $domisili = 'Jl. Kemang Raya No. 12'): AkunKeluarga
{
    return AkunKeluarga::create([
        'id_keluarga' => $id,
        'nama_keluarga' => $nama,
        'alamat_domisili' => $domisili,
        'kelurahan' => 'Bangka',
        'kecamatan' => 'Mampang Prapatan',
        'kota_kabupaten' => 'Jakarta Selatan',
        'dibuat_pada' => now(),
    ]);
}

function createParentForView(int $userId, int $familyId, string $name, string $username, string $jenisWali = 'Primary_Parent', string $wa = '081298765432'): User
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

describe('Halaman dan Modal Edit Kontak Keluarga & Secondary Parent (Front-end - Fahriel Aziz Satrio)', function () {

    test('1. orang tua dapat melihat komponen modal edit kontak dan modal secondary parent di portal orang tua', function () {
        $keluarga = createTestFamily(10, 'Keluarga Marlina', 'Jl. Tebet Raya No. 25');
        $parent = createParentForView(100, $keluarga->id_keluarga, 'Ibu Marlina', 'marlina.ortu');

        $response = $this->actingAs($parent)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->get('/portal-orang-tua');

        $response->assertStatus(200);

        // 1. Memastikan tombol pemicu modal tampil di halaman utama portal
        $response->assertSee('Ubah Kontak & Domisili', false);
        $response->assertSee('data-bs-target="#modalEditKontakDomisili"', false);
        $response->assertSee('modalEditKontakDomisili');

        // 2. Memastikan modal secondary parent tersedia
        $response->assertSee('modalEditSecondaryParent');
        $response->assertSee('Secondary Parent');

        // 3. Memastikan ada link menuju halaman penuh
        $response->assertSee('/orang-tua/edit-kontak');
    });

    test('2. orang tua dapat mengakses halaman khusus edit kontak dan secondary parent (/orang-tua/edit-kontak) (200 OK)', function () {
        $keluarga = createTestFamily(20, 'Keluarga Pratama', 'Jl. Fatmawati No. 8');
        $parent = createParentForView(200, $keluarga->id_keluarga, 'Ibu Pratama', 'ibu.pratama');

        $response = $this->actingAs($parent)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->get('/orang-tua/edit-kontak');

        $response->assertStatus(200);

        // Memastikan elemen form tampil lengkap di halaman penuh
        $response->assertSee('Pengaturan Kontak & Secondary Parent', false);
        $response->assertSee('Kontak & Domisili Keluarga', false);
        $response->assertSee('Wali Pendamping (Secondary Parent)');
        $response->assertSee('name="nomor_whatsapp_sendiri"', false);
        $response->assertSee('name="alamat_domisili"', false);
        $response->assertSee('name="nama_secondary_parent"', false);
        $response->assertSee('name="nomor_whatsapp_secondary_parent"', false);
        $response->assertSee('Kembali ke Dashboard');
    });

    test('3. tamu belum login dialihkan ke login saat mencoba membuka halaman edit kontak', function () {
        $response = $this->get('/orang-tua/edit-kontak');
        $response->assertRedirect('/login');
    });

    test('4. halaman edit kontak menampilkan data Secondary Parent jika sudah terdaftar dan menyediakan opsi hapus', function () {
        $keluarga = createTestFamily(30, 'Keluarga Handoko');
        $primary = createParentForView(300, $keluarga->id_keluarga, 'Ibu Handoko', 'ibu.handoko', 'Primary_Parent');
        $secondary = createParentForView(301, $keluarga->id_keluarga, 'Bpk. Handoko', 'bpk.handoko', 'Secondary_Parent', '081388889999');

        $response = $this->actingAs($primary)
            ->withSession(['role' => 'Orang_Tua', 'id_keluarga' => $keluarga->id_keluarga])
            ->get('/orang-tua/edit-kontak');

        $response->assertStatus(200);
        $response->assertSee('Bpk. Handoko');
        $response->assertSee('081388889999');
        $response->assertSee('name="hapus_secondary_parent"', false);
    });

});


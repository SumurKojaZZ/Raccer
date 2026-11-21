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

function makeTestKeluarga(int $id, string $nama): AkunKeluarga
{
    return AkunKeluarga::create([
        'id_keluarga' => $id,
        'nama_keluarga' => $nama,
        'alamat_domisili' => 'Jl. Boulevard No. ' . $id,
        'kota_kabupaten' => 'Jakarta Selatan',
        'dibuat_pada' => now(),
    ]);
}

function makeParentUser(int $id, int $idKeluarga, string $name, string $username): User
{
    $user = User::factory()->make([
        'id' => $id,
        'name' => $name,
        'username' => $username,
        'email' => "{$username}@example.com",
        'role' => 'Orang_Tua',
    ]);

    if (Schema::hasTable('orang_tua')) {
        OrangTua::create([
            'id_keluarga' => $idKeluarga,
            'id_pengguna' => $id,
            'nama_lengkap' => $name,
            'hubungan' => 'Ibu',
            'jenis_wali' => 'Primary_Parent',
            'nomor_whatsapp' => '081298765432',
        ]);
    }

    return $user;
}

function makeSiswaRecord(int $id, int $idKeluarga, string $nama, string $panggilan, string $nisn, string $paket = 'Junior Robotics'): Siswa
{
    return Siswa::create([
        'id_siswa' => $id,
        'id_keluarga' => $idKeluarga,
        'id_pengguna' => $id + 2000,
        'nama_lengkap' => $nama,
        'nama_panggilan' => $panggilan,
        'nisn' => $nisn,
        'jenjang' => 'SD',
        'kelas' => '3',
        'tanggal_lahir' => '2016-05-12',
        'status_siswa' => 'Aktif',
        'paket_belajar' => $paket,
        'program' => 'Junior Robotics',
        'total_sesi' => 12,
        'sesi_terpakai' => 4,
        'status_pembayaran' => 'Lunas',
    ]);
}

describe('Komponen Child Switcher & Kartu Profil Anak (Front-end - Fahriel Aziz Satrio)', function () {

    test('1. orang tua dengan beberapa anak dapat melihat komponen child switcher dan kartu profil anak pertama secara default', function () {
        $keluarga = makeTestKeluarga(10, 'Keluarga Marlina');
        $parent = makeParentUser(100, $keluarga->id_keluarga, 'Ibu Marlina', 'marlina.ortu');
        session([
            'role' => 'Orang_Tua',
            'id_keluarga' => $keluarga->id_keluarga,
        ]);

        $anak1 = makeSiswaRecord(101, $keluarga->id_keluarga, 'Kenzo Swarigading', 'Kenzo', '0011223344', 'Junior Robotics Mastery');
        $anak2 = makeSiswaRecord(102, $keluarga->id_keluarga, 'Kenzie Swarigading', 'Kenzie', '0011223355', 'Python for Kids');

        $response = $this->actingAs($parent)->get('/portal-orang-tua');

        $response->assertStatus(200);

        // 1. Memastikan komponen Child Switcher muncul
        $response->assertSee('Child Switcher');
        $response->assertSee('Pilih Profil Anak yang Dipantau');
        $response->assertSee('2 Anak Terdaftar');

        // 2. Memastikan kedua anak tersedia sebagai tombol switcher
        $response->assertSee('data-child-id="101"', false);
        $response->assertSee('data-child-id="102"', false);
        $response->assertSee('Kenzo');
        $response->assertSee('Kenzie');

        // 3. Memastikan Kartu Profil Anak menampilkan informasi komprehensif
        $response->assertSee('panelProfilAnak101', false);
        $response->assertSee('panelProfilAnak102', false);
        $response->assertSee('NISN: <strong>0011223344</strong>', false);
        $response->assertSee('KUOTA SESI PERTEMUAN');
        $response->assertSee('AKUN LOGIN SISWA MANDIRI');
    });

    test('2. memilih anak melalui parameter query (?anak=ID) mengaktifkan profil anak yang dipilih', function () {
        $keluarga = makeTestKeluarga(20, 'Keluarga Handoko');
        $parent = makeParentUser(200, $keluarga->id_keluarga, 'Bpk. Handoko', 'bpk.handoko');
        session([
            'role' => 'Orang_Tua',
            'id_keluarga' => $keluarga->id_keluarga,
        ]);

        $anak1 = makeSiswaRecord(201, $keluarga->id_keluarga, 'Anak Pertama', 'Sulung', '1234567801');
        $anak2 = makeSiswaRecord(202, $keluarga->id_keluarga, 'Anak Kedua', 'Bungsu', '1234567802', 'IoT Smart Home');

        // Mengakses dengan query switcher ?anak=202
        $response = $this->actingAs($parent)->get('/portal-orang-tua?anak=202');

        $response->assertStatus(200);
        $response->assertSee('data-child-id="202"', false);
        $response->assertSee('Bungsu');
        $response->assertSee('1234567802');

        // Memastikan sesi menyimpan active_child_id
        expect(session('active_child_id'))->toBe('202');
    });

    test('3. keluarga dengan anak tunggal menampilkan profil dengan rapi tanpa error switcher', function () {
        $keluarga = makeTestKeluarga(30, 'Keluarga Tunggal');
        $parent = makeParentUser(300, $keluarga->id_keluarga, 'Ibu Ratna', 'ratna.ortu');
        session([
            'role' => 'Orang_Tua',
            'id_keluarga' => $keluarga->id_keluarga,
        ]);

        $anakTunggal = makeSiswaRecord(301, $keluarga->id_keluarga, 'Bintang Pratama', 'Bintang', '9988776655');

        $response = $this->actingAs($parent)->get('/portal-orang-tua');

        $response->assertStatus(200);
        $response->assertSee('1 Anak Terdaftar');
        $response->assertSee('Bintang Pratama');
        $response->assertSee('9988776655');
    });

    test('4. tamu yang belum login dialihkan ke halaman login saat membuka portal orang tua', function () {
        $response = $this->get('/portal-orang-tua');
        $response->assertRedirect('/login');
    });

});


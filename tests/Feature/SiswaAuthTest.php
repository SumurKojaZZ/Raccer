<?php

use App\Http\Controllers\AdminController;
use App\Models\AkunKeluarga;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Artisan::call('migrate');
    (new AdminController())->ensureTablesExist();

    RateLimiter::clear('kenzo.robot|127.0.0.1');
    RateLimiter::clear('kenzo.test|127.0.0.1');
    RateLimiter::clear('alumni.test|127.0.0.1');
    RateLimiter::clear('alumni.budi|127.0.0.1');

    if (Schema::hasTable('siswa')) {
        Siswa::query()->delete();
    }
    if (Schema::hasTable('akun_keluarga')) {
        AkunKeluarga::query()->delete();
    }
    if (Schema::hasTable('pengguna')) {
        DB::table('pengguna')->where('role', 'Siswa')->delete();
    }
});

// Helper untuk membuat siswa dalam testing
function createStudentHelper(string $username, string $pin, string $status = 'Aktif'): array
{
    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Pratama',
        'alamat_domisili' => 'Jl. Salemba Raya No. 4',
        'kelurahan' => 'Kramat',
        'kecamatan' => 'Senen',
        'kota_kabupaten' => 'Jakarta Pusat',
        'dibuat_pada' => now(),
    ]);

    $hashedPin = Hash::make($pin);

    $idPengguna = DB::table('pengguna')->insertGetId([
        'username' => $username,
        'password_hash' => $hashedPin,
        'role' => 'Siswa',
        'status_aktif' => 1,
        'jumlah_gagal_login' => 0,
        'dibuat_pada' => now(),
    ]);

    $siswa = Siswa::create([
        'id_keluarga' => $keluarga->id_keluarga,
        'id_pengguna' => $idPengguna,
        'nisn' => str_pad((string) rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT),
        'nama_lengkap' => 'Kenzo Pratama',
        'nama_panggilan' => 'Kenzo',
        'jenjang' => 'SD',
        'kelas' => '3',
        'tanggal_lahir' => '2016-04-12',
        'status_siswa' => $status,
        'pin_hash' => $hashedPin,
        'nama' => 'Kenzo Pratama',
        'nama_wali' => 'Wali Siswa',
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Bulanan',
        'paket_belajar' => 'Junior Robotics · Bulanan (8 Sesi)',
        'total_sesi' => 8,
        'sesi_terpakai' => 3,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
        'dibuat_pada' => now(),
    ]);

    return [$keluarga, $idPengguna, $siswa];
}

// ─────────────────────────────────────────────────────────────
// [Utama] Siswa berstatus Aktif dapat login menggunakan username dan PIN angka yang valid lalu diarahkan ke beranda Siswa.
// ─────────────────────────────────────────────────────────────
test('siswa berstatus aktif dapat login menggunakan username dan PIN angka yang valid lalu diarahkan ke beranda Siswa (B-56 AC Utama)', function () {
    createStudentHelper('kenzo.test', '123456', 'Aktif');

    // 1. Submit form login dengan username dan PIN
    $response = $this->post('/login', [
        'username' => 'kenzo.test',
        'password' => '123456',
        'login_mode' => 'siswa',
    ]);

    // 2. Diarahkan ke rute beranda Siswa
    $response->assertRedirect('/siswa');
    $this->assertAuthenticated();

    // 3. Akses halaman beranda siswa dan pastikan profil serta tombol Keluar tersedia
    $berandaResponse = $this->get('/siswa');
    $berandaResponse->assertStatus(200);
    $berandaResponse->assertSee('Kenzo');
    $berandaResponse->assertSee('Junior Robotics');
    $berandaResponse->assertSee('Keluar');
});

test('login siswa juga mendukung input dengan field pin secara langsung', function () {
    createStudentHelper('kenzo.test', '123456', 'Aktif');

    $response = $this->post('/login', [
        'username' => 'kenzo.test',
        'pin' => '123456',
        'login_mode' => 'siswa',
    ]);

    $response->assertRedirect('/siswa');
    $this->assertAuthenticated();
});

// ─────────────────────────────────────────────────────────────
// [Gagal] Jika username/PIN salah, tampil pesan error ramah anak dan akun dikunci sementara 15 menit setelah 5 kali gagal berturut-turut.
// ─────────────────────────────────────────────────────────────
test('jika PIN salah, tampil pesan error ramah anak dan sisa kesempatan berkurang (B-56 AC Gagal)', function () {
    createStudentHelper('kenzo.test', '123456', 'Aktif');

    $response = $this->post('/login', [
        'username' => 'kenzo.test',
        'password' => '999999',
        'login_mode' => 'siswa',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('username');
    $errorMsg = session('errors')->first('username');

    // Pesan error ramah anak
    expect($errorMsg)->toContain('PIN yang kamu masukkan kurang pas');
    expect($errorMsg)->toContain('4 kesempatan');
    $this->assertGuest();
});

test('akun siswa dikunci sementara 60 detik setelah 5 kali gagal berturut-turut (B-56 AC Gagal)', function () {
    createStudentHelper('kenzo.test', '123456', 'Aktif');

    // Percobaan gagal 1 sampai 4
    for ($i = 1; $i <= 4; $i++) {
        $res = $this->post('/login', [
            'username' => 'kenzo.test',
            'password' => '00000' . $i,
            'login_mode' => 'siswa',
        ]);
        $res->assertSessionHasErrors('username');
    }

    // Percobaan gagal ke-5: Akun langsung dikunci 60 detik dengan pesan ramah anak
    $res5 = $this->post('/login', [
        'username' => 'kenzo.test',
        'password' => '000005',
        'login_mode' => 'siswa',
    ]);

    $res5->assertSessionHasErrors('username');
    $errorMsg = session('errors')->first('username');
    expect($errorMsg)->toContain('5 kali salah');
    expect($errorMsg)->toContain('60 detik');

    // Verifikasi pada database
    $pengguna = DB::table('pengguna')->where('username', 'kenzo.test')->first();
    expect($pengguna->jumlah_gagal_login)->toBe(5);
    expect($pengguna->terkunci_sampai)->not->toBeNull();

    // Percobaan ke-6 ditolak karena sedang terkunci
    $res6 = $this->post('/login', [
        'username' => 'kenzo.test',
        'password' => '123456', // Meski PIN benar, sedang terkunci
        'login_mode' => 'siswa',
    ]);

    $res6->assertSessionHasErrors('username');
    $errorMsg6 = session('errors')->first('username');
    expect($errorMsg6)->toContain('dikunci sementara');
});

// ─────────────────────────────────────────────────────────────
// [Gagal] Siswa berstatus Non-Aktif/Alumni ditolak saat login meskipun username dan PIN benar.
// ─────────────────────────────────────────────────────────────
test('siswa berstatus Non-Aktif/Alumni ditolak saat login meskipun username dan PIN benar (B-56 AC Gagal)', function () {
    createStudentHelper('alumni.test', '654321', 'Non_Aktif_Alumni');

    $response = $this->post('/login', [
        'username' => 'alumni.test',
        'password' => '654321',
        'login_mode' => 'siswa',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('username');
    $errorMsg = session('errors')->first('username');

    expect($errorMsg)->toContain('Non-Aktif / Alumni');
    $this->assertGuest();
});

// ─────────────────────────────────────────────────────────────
// [Tampilan & Keamanan] Siswa menolak akses ke endpoint role lain.
// ─────────────────────────────────────────────────────────────
test('siswa menolak akses ke endpoint role lain seperti admin, tutor, dan orang tua (B-56 AC Keamanan)', function () {
    createStudentHelper('kenzo.test', '123456', 'Aktif');

    // Login sebagai Siswa
    $this->post('/login', [
        'username' => 'kenzo.test',
        'password' => '123456',
        'login_mode' => 'siswa',
    ]);

    $this->assertAuthenticated();

    // 1. Ditolak dari portal Admin
    $resAdmin = $this->get('/admin');
    $resAdmin->assertStatus(403);

    // 2. Ditolak dari API Admin
    $resApiAdmin = $this->getJson('/api/admin/jadwal');
    $resApiAdmin->assertStatus(403);

    // 3. Ditolak dari portal Tutor
    $resTutor = $this->get('/tutor');
    $resTutor->assertStatus(403);

    // 4. Ditolak dari portal Orang Tua
    $resOrtu = $this->get('/orang-tua');
    $resOrtu->assertStatus(403);
});

test('non-siswa atau guest ditolak mengakses beranda siswa', function () {
    // 1. Guest ditolak (redirect ke login)
    $resGuest = $this->get('/siswa');
    $resGuest->assertRedirect('/login');

    // 2. Admin ditolak dari beranda siswa (403)
    $admin = User::factory()->make([
        'username' => 'admin_super',
        'role' => 'Admin',
    ]);
    $resAdmin = $this->actingAs($admin)->get('/siswa');
    $resAdmin->assertStatus(403);
});

// ─────────────────────────────────────────────────────────────
// Logout Siswa
// ─────────────────────────────────────────────────────────────
test('siswa dapat melakukan logout dan sesi dibersihkan dengan pesan ramah anak', function () {
    createStudentHelper('kenzo.test', '123456', 'Aktif');

    $this->post('/login', [
        'username' => 'kenzo.test',
        'password' => '123456',
        'login_mode' => 'siswa',
    ]);

    $this->assertAuthenticated();

    $response = $this->post('/logout');
    $response->assertRedirect('/login');
    $this->assertGuest();

    $successMsg = session('success');
    expect($successMsg)->toContain('Sampai jumpa lagi');
});

// ─────────────────────────────────────────────────────────────
// Endpoint API Siswa Login (JSON)
// ─────────────────────────────────────────────────────────────
test('endpoint api siswa login bekerja sesuai acceptance criteria', function () {
    createStudentHelper('kenzo.test', '123456', 'Aktif');
    createStudentHelper('alumni.test', '654321', 'Non_Aktif_Alumni');

    // 1. Sukses
    $resSuccess = $this->postJson('/api/siswa/login', [
        'username' => 'kenzo.test',
        'pin' => '123456',
    ]);
    $resSuccess->assertStatus(200);
    $resSuccess->assertJson([
        'success' => true,
    ]);
    expect($resSuccess->json('redirect'))->toContain('siswa');

    Auth::logout();

    // 2. Gagal: Salah PIN
    $resSalah = $this->postJson('/api/siswa/login', [
        'username' => 'kenzo.test',
        'pin' => '000000',
    ]);
    $resSalah->assertStatus(422);
    expect($resSalah->json('message'))->toContain('kurang pas');

    // 3. Gagal: Alumni
    $resAlumni = $this->postJson('/api/siswa/login', [
        'username' => 'alumni.test',
        'pin' => '654321',
    ]);
    $resAlumni->assertStatus(403);
    expect($resAlumni->json('message'))->toContain('Non-Aktif / Alumni');
});

// ─────────────────────────────────────────────────────────────
// [Tampilan Ramah Anak TK] Pilihan Avatar Profil + PIN
// ─────────────────────────────────────────────────────────────
test('halaman login menampilkan pilihan avatar profil siswa aktif untuk kemudahan login anak TK', function () {
    createStudentHelper('kenzo.test', '123456', 'Aktif');

    $response = $this->get('/login?role=siswa');
    $response->assertStatus(200);
    $response->assertSee('Siapa Sobat Robotik?');
    $response->assertSee('Kenzo');
    $response->assertSee('Siswa (PIN Anak)');
});

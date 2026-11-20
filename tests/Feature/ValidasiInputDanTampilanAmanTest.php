<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminController;
use App\Models\AkunKeluarga;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    (new AdminController())->ensureTablesExist();

    if (Schema::hasTable('siswa')) {
        Siswa::query()->delete();
    }
    if (Schema::hasTable('akun_keluarga')) {
        AkunKeluarga::query()->delete();
    }
    if (Schema::hasTable('jadwal')) {
        Jadwal::query()->delete();
    }
    if (Schema::hasTable('pengguna')) {
        DB::table('pengguna')->where('role', '!=', 'Admin')->delete();
    }
});

// Helper untuk membuat user Admin terautentikasi
function getAuthenticatedAdmin(): User
{
    return User::factory()->make([
        'id' => 1,
        'name' => 'Admin Utama',
        'username' => 'admin_test',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);
}

// Helper untuk membuat user Tutor terautentikasi
function getAuthenticatedTutor(): User
{
    $tutorUser = User::factory()->make([
        'id' => 55,
        'name' => 'Kak Dimas Pratama',
        'username' => 'tutor.dimas',
        'email' => 'dimas@raccer.com',
        'role' => 'Tutor',
    ]);

    if (Schema::hasTable('tutor')) {
        Tutor::updateOrCreate(
            ['email' => 'dimas@raccer.com'],
            [
                'id_pengguna' => 55,
                'nama_lengkap' => 'Kak Dimas Pratama',
                'nama_panggilan' => 'Kak Dimas',
                'spesialisasi' => 'Junior Robotics',
                'nomor_telepon' => '081234567890',
                'status' => 'Aktif',
            ]
        );
    }

    return $tutorUser;
}

// ─────────────────────────────────────────────────────────────────────────────
// BAGIAN 2 · TEST 1: Request Langsung Ke Server dengan Data Tidak Valid Ditolak
// ─────────────────────────────────────────────────────────────────────────────

test('request langsung ke server dengan NISN tidak valid (bukan 10 digit angka) ditolak dengan status 422', function () {
    $admin = getAuthenticatedAdmin();
    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Rahardian',
        'dibuat_pada' => now(),
    ]);

    // 1. Uji NISN berisi huruf / karakter acak langsung ke endpoint API
    $responseAlpha = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Bintang Rahardian',
        'jenjang' => 'SD',
        'kelas' => '4',
        'nisn' => 'NISN_SALAH', // Tidak valid: mengandung alfabet
        'username' => 'bintang.rahardian',
        'pin' => '123456',
    ]);

    $responseAlpha->assertStatus(422);
    $responseAlpha->assertJsonValidationErrors(['nisn']);

    // 2. Uji NISN dengan panjang kurang dari 10 digit (misal: 6 digit)
    $responseShort = $this->actingAs($admin)->postJson("/api/admin/keluarga/{$keluarga->id_keluarga}/siswa", [
        'nama_lengkap' => 'Bintang Rahardian',
        'jenjang' => 'SD',
        'kelas' => '4',
        'nisn' => '123456', // Tidak valid: hanya 6 digit
        'username' => 'bintang.rahardian2',
        'pin' => '123456',
    ]);

    $responseShort->assertStatus(422);
    $responseShort->assertJsonValidationErrors(['nisn']);
});

test('request langsung ke server dengan nomor WhatsApp tidak valid ditolak dengan status 422', function () {
    $admin = getAuthenticatedAdmin();
    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Wijaya',
        'dibuat_pada' => now(),
    ]);

    // Request langsung dengan format nomor telepon yang tidak memenuhi regex allowlist
    $response = $this->actingAs($admin)->postJson("/admin/keluarga/{$keluarga->id_keluarga}/secondary-parent", [
        'nama_lengkap' => 'Ibu Ratna Wijaya',
        'hubungan' => 'Ibu',
        'nomor_whatsapp' => '021-TELP-KANTOR', // Tidak valid: nomor kantor/huruf
        'username' => 'ratna.wijaya',
        'password' => 'Password123!',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['nomor_whatsapp']);
});

test('request langsung jadwal dengan jam_selesai lebih awal dari jam_mulai ditolak dengan status 422', function () {
    $admin = getAuthenticatedAdmin();

    if (Schema::hasTable('tutor')) {
        Tutor::updateOrCreate(
            ['nama_lengkap' => 'Kak Dimas Pratama'],
            [
                'id_pengguna' => 99,
                'nama_panggilan' => 'Kak Dimas',
                'spesialisasi' => 'Junior Robotics',
                'status' => 'Aktif',
            ]
        );
    }

    // Mengirim request jam selesai (09:00) yang lebih awal dari jam mulai (10:30)
    $response = $this->actingAs($admin)->postJson('/api/admin/jadwal', [
        'program' => 'Junior Robotics',
        'tutor_pengampu' => 'Kak Dimas Pratama',
        'tanggal' => '2026-11-25',
        'jam_mulai' => '10:30',
        'jam_selesai' => '09:00', // Tidak valid: jam_selesai <= jam_mulai
        'moda' => 'Offline',
        'lokasi' => 'Lab Robotika 1',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['jam_selesai']);
});

test('request aktivasi paket dengan total sesi di luar batas kuota allowlist ditolak dengan status 422', function () {
    $admin = getAuthenticatedAdmin();
    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Santoso',
        'dibuat_pada' => now(),
    ]);

    $siswa = Siswa::create([
        'id_keluarga' => $keluarga->id_keluarga,
        'id_pengguna' => 91,
        'nama_lengkap' => 'Rafi Santoso',
        'nama_panggilan' => 'Rafi',
        'jenjang' => 'SD',
        'kelas' => '2',
        'tanggal_lahir' => '2017-08-10',
        'status_siswa' => 'Aktif',
        'dibuat_pada' => now(),
    ]);

    // Kirim request aktivasi paket dengan total sesi negatif atau melebihi 100 sesi
    $responseInvalidSesi = $this->actingAs($admin)->postJson('/admin/paket-siswa/aktivasi', [
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Bulanan (4 Sesi)',
        'total_sesi' => -5, // Tidak valid: nilai negatif
        'status_pembayaran' => 'Lunas',
    ]);

    $responseInvalidSesi->assertStatus(422);
    $responseInvalidSesi->assertJsonValidationErrors(['total_sesi']);
});

// ─────────────────────────────────────────────────────────────────────────────
// BAGIAN 2 · TEST 2: Teks Berisi Tag <script> Ditampilkan Sebagai Teks Biasa (XSS Prevention)
// ─────────────────────────────────────────────────────────────────────────────

test('teks nama pengguna berisi payload tag script ditampilkan sebagai teks biasa (escaped) dan tidak dieksekusi', function () {
    $tutor = getAuthenticatedTutor();

    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga XSS Test',
        'dibuat_pada' => now(),
    ]);

    // Input berbahaya (XSS injection attempt)
    $xssPayload = "<script>alert('XSS_ATTACK_TEST')</script>";

    // Simpan siswa dengan nama berbahaya ke database
    $siswa = Siswa::create([
        'id_keluarga' => $keluarga->id_keluarga,
        'id_pengguna' => 999,
        'nama_lengkap' => $xssPayload,
        'nama_panggilan' => 'XSS_Kid',
        'jenjang' => 'SD',
        'kelas' => '3',
        'tanggal_lahir' => '2016-01-01',
        'status_siswa' => 'Aktif',
        'dibuat_pada' => now(),
    ]);

    // Buat jadwal mengajar untuk tutor ini
    $jadwal = Jadwal::create([
        'program' => 'Junior Robotics',
        'kelas' => 'Junior Robotics L1',
        'lokasi' => 'Lab Robotika A',
        'tutor' => $tutor->name,
        'tutor_pengampu' => $tutor->name,
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'moda' => 'Offline',
        'status' => 'Terjadwal',
    ]);

    // Daftarkan siswa ke jadwal
    DB::table('peserta_jadwal')->insert([
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa->id_siswa,
        'status_kehadiran' => 'Terdaftar',
        'catatan' => 'Testing XSS Protection',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Akses tampilan web Portal Tutor tab jadwal
    $response = $this->actingAs($tutor)->get(route('tutor', ['tab' => 'jadwal']));

    $response->assertStatus(200);

    // 1. Pastikan tag <script> TIDAK muncul dalam bentuk HTML raw yang dapat dieksekusi
    $response->assertDontSee($xssPayload, false);

    // 2. Pastikan tag telah di-escape menjadi karakter entitas HTML aman (&lt;script&gt;...)
    $response->assertSee('&lt;script&gt;alert(&#039;XSS_ATTACK_TEST&#039;)&lt;/script&gt;', false);
});

// ─────────────────────────────────────────────────────────────────────────────
// BAGIAN 2 · TEST 3 (Tambahan): Query Kebal Terhadap SQL Injection (Parameterized Query)
// ─────────────────────────────────────────────────────────────────────────────

test('query pencarian dan filter kebal terhadap SQL Injection melalui parameterized binding', function () {
    $admin = getAuthenticatedAdmin();

    $keluarga = AkunKeluarga::create([
        'nama_keluarga' => 'Keluarga Normal',
        'dibuat_pada' => now(),
    ]);

    Siswa::create([
        'id_keluarga' => $keluarga->id_keluarga,
        'id_pengguna' => 888,
        'nama_lengkap' => 'Muhammad Bima',
        'nama_panggilan' => 'Bima',
        'jenjang' => 'SD',
        'kelas' => '5',
        'tanggal_lahir' => '2015-03-21',
        'status_siswa' => 'Aktif',
        'dibuat_pada' => now(),
    ]);

    // Payload SQL Injection pada parameter pencarian
    $sqlInjectionPayload = "' OR '1'='1' -- ";

    $response = $this->actingAs($admin)->get("/admin/keluarga?search=" . urlencode($sqlInjectionPayload));

    // Request harus sukses tanpa query error (tidak melempar SQLSTATE[42000] Syntax Error)
    $response->assertStatus(200);

    // SQL Injection tidak boleh membocorkan data yang tidak sesuai keyword string literal
    $response->assertDontSee('SQLSTATE');
});

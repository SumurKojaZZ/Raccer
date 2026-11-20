<?php

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    Artisan::call('migrate');
});

test('guest cannot access endpoint jadwal mengajar tutor', function () {
    $response = $this->getJson('/api/tutor/jadwal');
    $response->assertStatus(401);
});

test('non-tutor role is rejected with 403 when accessing tutor schedule endpoint', function () {
    $ortu = User::factory()->make([
        'id' => 88,
        'name' => 'Ibu Ratna',
        'username' => 'ibu.ratna',
        'email' => 'ratna@example.com',
        'role' => 'Orang_Tua',
    ]);

    $response = $this->actingAs($ortu)->getJson('/api/tutor/jadwal');
    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Unauthorized. Akses ini khusus untuk Tutor.',
    ]);
});

test('tutor only retrieves their personal teaching schedule with strict data isolation', function () {
    // Buat data Tutor Budi di tabel tutor & pengguna
    $idPenggunaBudi = DB::table('pengguna')->insertGetId([
        'username' => 'tutor.budi',
        'password_hash' => bcrypt('password123'),
        'role' => 'Tutor',
        'status_aktif' => 1,
        'dibuat_pada' => now(),
    ]);

    DB::table('tutor')->insert([
        'id_pengguna' => $idPenggunaBudi,
        'nama_lengkap' => 'Budi Santoso, S.T.',
        'nama_panggilan' => 'Kak Budi',
        'nomor_whatsapp' => '081234567890',
        'spesialisasi' => 'SD',
        'status' => 'Aktif',
    ]);

    // Buat jadwal untuk Tutor Budi
    $jadwalBudi = Jadwal::create([
        'program' => 'Junior Robotics Budi',
        'kelas' => 'Junior Robotics Budi',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'moda' => 'Offline',
        'status' => 'Terjadwal',
    ]);

    // Buat jadwal untuk Tutor Lain (Kak Dimas)
    $jadwalDimas = Jadwal::create([
        'program' => 'Python for Kids Dimas',
        'kelas' => 'Python for Kids Dimas',
        'tutor' => 'Kak Dimas',
        'tutor_pengampu' => 'Kak Dimas',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '13:00',
        'jam_selesai' => '14:30',
        'moda' => 'Offline',
        'status' => 'Terjadwal',
    ]);

    $userBudi = User::factory()->make([
        'id' => $idPenggunaBudi,
        'name' => 'Kak Budi',
        'username' => 'tutor.budi',
        'email' => 'tutor.budi@racer.id',
        'role' => 'Tutor',
    ]);

    session(['role' => 'Tutor', 'id_pengguna' => $idPenggunaBudi]);

    $response = $this->actingAs($userBudi)->getJson('/api/tutor/jadwal?filter=semua');

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Jadwal mengajar pribadi berhasil diambil.',
    ]);

    // Pastikan jadwal Tutor Budi ada di hasil
    $response->assertJsonFragment([
        'program' => 'Junior Robotics Budi',
        'tutor_pengampu' => 'Kak Budi',
    ]);

    // Pastikan jadwal Tutor Dimas TIDAK PERNAH bocor ke Tutor Budi
    $response->assertJsonMissing([
        'program' => 'Python for Kids Dimas',
        'tutor_pengampu' => 'Kak Dimas',
    ]);
});

test('tutor schedule endpoint filters accurately by day (filter=hari)', function () {
    $today = now()->toDateString();
    $tomorrow = now()->addDays(2)->toDateString();

    $jadwalHariIni = Jadwal::create([
        'program' => 'Kelas Hari Ini',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => $today,
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'status' => 'Terjadwal',
    ]);

    $jadwalNanti = Jadwal::create([
        'program' => 'Kelas Lusa',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => $tomorrow,
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'status' => 'Terjadwal',
    ]);

    $userBudi = User::factory()->make([
        'id' => 10,
        'name' => 'Kak Budi',
        'username' => 'tutor.budi',
        'role' => 'Tutor',
    ]);

    session(['role' => 'Tutor']);

    $response = $this->actingAs($userBudi)->getJson('/api/tutor/jadwal?filter=hari');

    $response->assertStatus(200);
    $response->assertJsonFragment(['program' => 'Kelas Hari Ini']);
    $response->assertJsonMissing(['program' => 'Kelas Lusa']);
});

test('tutor schedule endpoint filters accurately by week (filter=minggu)', function () {
    $thisWeekDate = now()->startOfWeek()->toDateString();
    $nextMonthDate = now()->addMonths(1)->toDateString();

    $jadwalMingguIni = Jadwal::create([
        'program' => 'Kelas Minggu Ini',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => $thisWeekDate,
        'jam_mulai' => '10:00',
        'jam_selesai' => '11:30',
        'status' => 'Terjadwal',
    ]);

    $jadwalBulanDepan = Jadwal::create([
        'program' => 'Kelas Bulan Depan',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => $nextMonthDate,
        'jam_mulai' => '10:00',
        'jam_selesai' => '11:30',
        'status' => 'Terjadwal',
    ]);

    $userBudi = User::factory()->make([
        'id' => 10,
        'name' => 'Kak Budi',
        'username' => 'tutor.budi',
        'role' => 'Tutor',
    ]);

    session(['role' => 'Tutor']);

    $response = $this->actingAs($userBudi)->getJson('/api/tutor/jadwal?filter=minggu');

    $response->assertStatus(200);
    $response->assertJsonFragment(['program' => 'Kelas Minggu Ini']);
    $response->assertJsonMissing(['program' => 'Kelas Bulan Depan']);
});

test('tutor schedule includes student participants list on each schedule', function () {
    $jadwal = Jadwal::create([
        'program' => 'Junior Robotics Peserta',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '10:00',
        'jam_selesai' => '11:30',
        'status' => 'Terjadwal',
    ]);

    $siswa1 = \App\Models\Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Alya Putri',
        'nama_lengkap' => 'Alya Putri Ramadhani',
        'nisn' => '0012345678',
        'program' => 'Junior Robotics',
    ]);

    $siswa2 = \App\Models\Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Bima Aditya',
        'nama_lengkap' => 'Bima Aditya Pratama',
        'nisn' => '0012345679',
        'program' => 'Junior Robotics',
    ]);

    \App\Models\PesertaJadwal::create([
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa1->id_siswa,
        'status_kehadiran' => 'Terdaftar',
    ]);

    \App\Models\PesertaJadwal::create([
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa2->id_siswa,
        'status_kehadiran' => 'Terdaftar',
    ]);

    $userBudi = User::factory()->make([
        'id' => 10,
        'name' => 'Kak Budi',
        'username' => 'tutor.budi',
        'role' => 'Tutor',
    ]);

    session(['role' => 'Tutor']);

    $response = $this->actingAs($userBudi)->getJson('/api/tutor/jadwal');

    $response->assertStatus(200);
    $response->assertJsonFragment(['nama_lengkap' => 'Alya Putri Ramadhani']);
    $response->assertJsonFragment(['nama_lengkap' => 'Bima Aditya Pratama']);
});

test('tutor can view student participants of their own schedule (200 OK)', function () {
    $jadwal = Jadwal::create([
        'program' => 'Robotics Class Kak Budi',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '09:30',
        'status' => 'Terjadwal',
    ]);

    $siswa = \App\Models\Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Citra Cantika',
        'nama_lengkap' => 'Citra Cantika Maharani',
        'program' => 'Robotics Class Kak Budi',
    ]);

    \App\Models\PesertaJadwal::create([
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa->id_siswa,
        'status_kehadiran' => 'Terdaftar',
    ]);

    $userBudi = User::factory()->make([
        'id' => 10,
        'name' => 'Kak Budi',
        'username' => 'tutor.budi',
        'role' => 'Tutor',
    ]);

    session(['role' => 'Tutor']);

    $response = $this->actingAs($userBudi)->getJson("/api/tutor/jadwal/{$jadwal->id}/peserta");

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'total_peserta' => 1,
    ]);
    $response->assertJsonFragment(['nama_lengkap' => 'Citra Cantika Maharani']);
});

test('tutor is rejected with 403 Forbidden when accessing another tutors schedule participants', function () {
    // Jadwal milik Tutor Dimas
    $jadwalDimas = Jadwal::create([
        'program' => 'Python for Kids Dimas',
        'tutor' => 'Kak Dimas',
        'tutor_pengampu' => 'Kak Dimas',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '13:00',
        'jam_selesai' => '14:30',
        'status' => 'Terjadwal',
    ]);

    // Tutor Budi mencoba mengakses jadwal Tutor Dimas
    $userBudi = User::factory()->make([
        'id' => 10,
        'name' => 'Kak Budi',
        'username' => 'tutor.budi',
        'role' => 'Tutor',
    ]);

    session(['role' => 'Tutor']);

    // 1. Endpoint /api/tutor/jadwal/{id}/peserta ditolak 403
    $responseTutor = $this->actingAs($userBudi)->getJson("/api/tutor/jadwal/{$jadwalDimas->id}/peserta");
    $responseTutor->assertStatus(403);
    $responseTutor->assertJson([
        'success' => false,
    ]);

    // 2. Endpoint detail /api/tutor/jadwal/{id} ditolak 403
    $responseDetail = $this->actingAs($userBudi)->getJson("/api/tutor/jadwal/{$jadwalDimas->id}");
    $responseDetail->assertStatus(403);
    $responseDetail->assertJson([
        'success' => false,
    ]);

    // 3. Endpoint bersama /api/jadwal/{id}/peserta ditolak 403
    $responseShared = $this->actingAs($userBudi)->getJson("/api/jadwal/{$jadwalDimas->id}/peserta");
    $responseShared->assertStatus(403);
    $responseShared->assertJson([
        'success' => false,
    ]);
});

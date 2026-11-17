<?php

use App\Http\Controllers\AdminController;
use App\Models\Jadwal;
use App\Models\PaketSiswa;
use App\Models\PesertaJadwal;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    (new AdminController())->ensureTablesExist();

    if (Schema::hasTable('peserta_jadwal')) {
        PesertaJadwal::query()->delete();
    }
    if (Schema::hasTable('paket_siswa')) {
        PaketSiswa::query()->delete();
    }
    if (Schema::hasTable('siswa')) {
        Siswa::query()->delete();
    }
    if (Schema::hasTable('jadwal')) {
        Jadwal::query()->delete();
    }
});

test('guest cannot access pilih peserta and peserta jadwal endpoints', function () {
    $resWeb = $this->get('/admin/siswa/pilih-peserta');
    $resWeb->assertRedirect('/login');

    $resApi = $this->getJson('/api/admin/siswa/pilih-peserta');
    $resApi->assertStatus(401);

    $resPostApi = $this->postJson('/api/admin/jadwal/1/peserta', ['id_siswa' => 1]);
    $resPostApi->assertStatus(401);
});

test('admin can filter eligible students by program and active package', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    // 1. Siswa 1: Program Junior Robotics, paket aktif 12 sesi (sisa 12) -> LOLOS
    $siswa1 = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Alya Putri',
        'nama_lengkap' => 'Alya Putri Ramadhani',
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 0,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);
    PaketSiswa::create([
        'id_siswa' => $siswa1->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 0,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
        'tanggal_aktivasi' => now(),
    ]);

    // 2. Siswa 2: Program Python for Kids, paket aktif 4 sesi (sisa 4) -> TIDAK LOLOS FILTER PROGRAM JUNIOR ROBOTICS
    $siswa2 = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Bima Aditya',
        'nama_lengkap' => 'Bima Aditya Pratama',
        'program' => 'Python for Kids',
        'jenis_paket' => 'Bulanan',
        'paket_belajar' => 'Bulanan • Python for Kids (4 Sesi)',
        'total_sesi' => 4,
        'sesi_terpakai' => 0,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);
    PaketSiswa::create([
        'id_siswa' => $siswa2->id_siswa,
        'program' => 'Python for Kids',
        'jenis_paket' => 'Bulanan',
        'paket_belajar' => 'Bulanan • Python for Kids (4 Sesi)',
        'total_sesi' => 4,
        'sesi_terpakai' => 0,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
        'tanggal_aktivasi' => now(),
    ]);

    // 3. Siswa 3: Program Junior Robotics, paket habis (12/12 terpakai, sisa 0) -> TIDAK LOLOS (SISA SESI 0)
    $siswa3 = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Citra Maharani',
        'nama_lengkap' => 'Citra Maharani Cantika',
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 12,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);
    PaketSiswa::create([
        'id_siswa' => $siswa3->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 12,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
        'tanggal_aktivasi' => now(),
    ]);

    // 4. Siswa 4: Program Junior Robotics, paket Nonaktif -> TIDAK LOLOS (NONAKTIF)
    $siswa4 = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Doni Nugroho',
        'nama_lengkap' => 'Doni Nugroho Saputra',
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 2,
        'status_paket' => 'Nonaktif',
        'status_pembayaran' => 'Lunas',
    ]);
    PaketSiswa::create([
        'id_siswa' => $siswa4->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 2,
        'status_paket' => 'Nonaktif',
        'status_pembayaran' => 'Lunas',
        'tanggal_aktivasi' => now(),
    ]);

    // ACT: Panggil endpoint pilih peserta untuk program Junior Robotics
    $response = $this->actingAs($admin)->getJson('/api/admin/siswa/pilih-peserta?program=Junior Robotics');

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'total' => 1,
    ]);

    $data = $response->json('data');
    expect($data)->toHaveCount(1);
    expect($data[0]['id_siswa'])->toBe($siswa1->id_siswa);
    expect($data[0]['nama_lengkap'])->toBe('Alya Putri Ramadhani');
    expect($data[0]['program'])->toBe('Junior Robotics');
    expect($data[0]['sisa_sesi'])->toBe(12);
});

test('admin can assign eligible student to schedule and retrieve participant list', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $jadwal = Jadwal::create([
        'program' => 'Junior Robotics',
        'kelas' => 'Junior Robotics - Kelas A',
        'lokasi' => 'Lab STEM 1',
        'tutor' => 'Kak Dimas',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '09.00',
        'jam_selesai' => '10.30 WIB',
        'moda' => 'Offline',
        'status' => 'Terjadwal',
    ]);

    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Alya Putri',
        'nama_lengkap' => 'Alya Putri Ramadhani',
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 1,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);

    $paket = PaketSiswa::create([
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 1,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
        'tanggal_aktivasi' => now(),
    ]);

    // ACT: Assign siswa ke jadwal
    $response = $this->actingAs($admin)->postJson("/api/admin/jadwal/{$jadwal->id}/peserta", [
        'id_siswa' => $siswa->id_siswa,
        'catatan' => 'Peserta konfirmasi hadir',
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
    ]);

    // Verifikasi di database peserta_jadwal
    $this->assertDatabaseHas('peserta_jadwal', [
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa->id_siswa,
        'id_paket_siswa' => $paket->id_paket_siswa,
    ]);

    // GET: Verifikasi endpoint get peserta jadwal
    $getRes = $this->actingAs($admin)->getJson("/api/admin/jadwal/{$jadwal->id}/peserta");
    $getRes->assertStatus(200);
    $getRes->assertJson([
        'success' => true,
        'total_peserta' => 1,
    ]);

    $pesertaList = $getRes->json('data');
    expect($pesertaList)->toHaveCount(1);
    expect($pesertaList[0]['siswa']['nama_lengkap'])->toBe('Alya Putri Ramadhani');
});

test('system rejects student with mismatched program against schedule', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $jadwal = Jadwal::create([
        'program' => 'Junior Robotics',
        'kelas' => 'Junior Robotics - Lab Maker',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '13.00',
        'jam_selesai' => '14.30 WIB',
    ]);

    // Siswa program Python for Kids
    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Bima Aditya',
        'nama_lengkap' => 'Bima Aditya Pratama',
        'program' => 'Python for Kids',
        'jenis_paket' => 'Bulanan',
        'total_sesi' => 4,
        'sesi_terpakai' => 0,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);

    // ACT: Coba daftarkan ke jadwal Junior Robotics
    $response = $this->actingAs($admin)->postJson("/api/admin/jadwal/{$jadwal->id}/peserta", [
        'id_siswa' => $siswa->id_siswa,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['id_siswa']);
    $msg = $response->json('errors.id_siswa.0');
    expect($msg)->toContain('tidak sesuai dengan program jadwal');
});

test('system rejects student with exhausted quota (zero remaining sessions)', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $jadwal = Jadwal::create([
        'program' => 'Junior Robotics',
        'kelas' => 'Junior Robotics - Pagi',
        'tanggal' => now()->toDateString(),
    ]);

    // Siswa dengan sesi terpakai habis (12/12)
    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Citra Maharani',
        'nama_lengkap' => 'Citra Maharani Cantika',
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'total_sesi' => 12,
        'sesi_terpakai' => 12,
        'status_paket' => 'Aktif',
    ]);

    $response = $this->actingAs($admin)->postJson("/api/admin/jadwal/{$jadwal->id}/peserta", [
        'id_siswa' => $siswa->id_siswa,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['id_siswa']);
    $msg = $response->json('errors.id_siswa.0');
    expect($msg)->toContain('kuota sesi telah habis');
});

test('system prevents duplicate student registration in the same schedule', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $jadwal = Jadwal::create([
        'program' => 'Junior Robotics',
        'kelas' => 'Junior Robotics - Sesi 1',
        'tanggal' => now()->toDateString(),
    ]);

    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Alya Putri',
        'nama_lengkap' => 'Alya Putri Ramadhani',
        'program' => 'Junior Robotics',
        'total_sesi' => 12,
        'sesi_terpakai' => 0,
        'status_paket' => 'Aktif',
    ]);

    // Pendaftaran pertama sukses
    $res1 = $this->actingAs($admin)->postJson("/api/admin/jadwal/{$jadwal->id}/peserta", [
        'id_siswa' => $siswa->id_siswa,
    ]);
    $res1->assertStatus(201);

    // Pendaftaran kedua harus ditolak karena duplikasi
    $res2 = $this->actingAs($admin)->postJson("/api/admin/jadwal/{$jadwal->id}/peserta", [
        'id_siswa' => $siswa->id_siswa,
    ]);
    $res2->assertStatus(422);
    $res2->assertJsonValidationErrors(['id_siswa']);
    $msg = $res2->json('errors.id_siswa.0');
    expect($msg)->toContain('sudah terdaftar');
});

test('admin can remove student from schedule', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $jadwal = Jadwal::create([
        'program' => 'Junior Robotics',
        'kelas' => 'Junior Robotics - Siang',
        'tanggal' => now()->toDateString(),
    ]);

    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Alya Putri',
        'nama_lengkap' => 'Alya Putri Ramadhani',
        'program' => 'Junior Robotics',
        'total_sesi' => 12,
        'sesi_terpakai' => 0,
        'status_paket' => 'Aktif',
    ]);

    PesertaJadwal::create([
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa->id_siswa,
        'status_kehadiran' => 'Terdaftar',
    ]);

    $this->assertDatabaseHas('peserta_jadwal', [
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa->id_siswa,
    ]);

    // ACT: Hapus peserta
    $res = $this->actingAs($admin)->deleteJson("/api/admin/jadwal/{$jadwal->id}/peserta/{$siswa->id_siswa}");
    $res->assertStatus(200);
    $res->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseMissing('peserta_jadwal', [
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa->id_siswa,
    ]);
});

test('admin can assign multiple students in batch via array id_siswa', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $jadwal = Jadwal::create([
        'program' => 'Junior Robotics',
        'kelas' => 'Junior Robotics - Batch Class',
        'tanggal' => now()->toDateString(),
    ]);

    $siswa1 = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Alya Putri',
        'nama_lengkap' => 'Alya Putri Ramadhani',
        'program' => 'Junior Robotics',
        'total_sesi' => 12,
        'sesi_terpakai' => 0,
        'status_paket' => 'Aktif',
    ]);

    $siswa2 = Siswa::create([
        'id_keluarga' => 2,
        'nama' => 'Dimas Surya',
        'nama_lengkap' => 'Dimas Surya Pratama',
        'program' => 'Junior Robotics',
        'total_sesi' => 12,
        'sesi_terpakai' => 2,
        'status_paket' => 'Aktif',
    ]);

    // ACT: Batch assign
    $response = $this->actingAs($admin)->postJson("/api/admin/jadwal/{$jadwal->id}/peserta", [
        'id_siswa' => [$siswa1->id_siswa, $siswa2->id_siswa],
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('peserta_jadwal', [
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa1->id_siswa,
    ]);
    $this->assertDatabaseHas('peserta_jadwal', [
        'id_jadwal' => $jadwal->id,
        'id_siswa' => $siswa2->id_siswa,
    ]);
});

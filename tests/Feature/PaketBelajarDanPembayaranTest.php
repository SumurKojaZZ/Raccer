<?php

use App\Models\Siswa;
use App\Models\User;

test('guest cannot access keluarga and paket activation page', function () {
    $response = $this->get('/admin/keluarga');
    $response->assertRedirect('/login');
});

test('authenticated admin can view keluarga page with stats and student list', function () {
    $user = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $response = $this->actingAs($user)->get('/admin/keluarga');

    $response->assertStatus(200);
    $response->assertSee('Akun Keluarga', false);
    $response->assertSee('Manajemen data wali murid', false);
    $response->assertSee('Total Akun Keluarga');
    $response->assertSee('Total Siswa Terdaftar');
    $response->assertSee('Paket Hampir Habis');
    $response->assertSee('Aktivasi WhatsApp');
    $response->assertSee('Aktivasi Paket');
    $response->assertSee('Akun Keluarga');
    $response->assertSee('Alya Putri');
    $response->assertSee('Bima Aditya');
});

test('page displays Belum Bayar and Lunas badges properly', function () {
    $user = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $response = $this->actingAs($user)->get('/admin/keluarga');

    $response->assertStatus(200);
    $response->assertSee('badge-pembayaran-warning', false);
    $response->assertSee('Belum Bayar');
    $response->assertSee('badge-pembayaran-success', false);
    $response->assertSee('Lunas');
});

test('validation error when submitting aktivasi paket form with missing fields', function () {
    $user = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $response = $this->actingAs($user)->post('/admin/keluarga/aktivasi-paket', []);

    $response->assertSessionHasErrors(['id_siswa', 'program', 'jenis_paket', 'total_sesi', 'status_pembayaran']);
});

test('aktivasi paket displays error when student already has active package on the same program', function () {
    $user = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    // Student 1 (Alya Putri) has Junior Robotics with status Aktif
    $response = $this->actingAs($user)->post('/admin/keluarga/aktivasi-paket', [
        'id_siswa' => 1,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'total_sesi' => 12,
        'status_pembayaran' => 'Belum Bayar',
    ]);

    $response->assertRedirect('/admin/keluarga');
    $response->assertSessionHas('error_aktivasi');
});

test('admin can activate paket belajar for student with status Belum Bayar', function () {
    $user = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $response = $this->actingAs($user)->post('/admin/keluarga/aktivasi-paket', [
        'id_siswa' => 1,
        'program' => 'Arduino Explorer',
        'jenis_paket' => 'Semester',
        'total_sesi' => 12,
        'status_pembayaran' => 'Belum Bayar',
        'metode_pembayaran' => 'Transfer Bank BCA',
        'catatan' => 'Menunggu transfer wali',
    ]);

    $response->assertRedirect('/admin/keluarga');
    $response->assertSessionHas('success');
});

test('admin can activate paket belajar for student with status Lunas', function () {
    $user = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $response = $this->actingAs($user)->post('/admin/keluarga/aktivasi-paket', [
        'id_siswa' => 2,
        'program' => 'AI Builder',
        'jenis_paket' => 'Bulanan',
        'total_sesi' => 4,
        'status_pembayaran' => 'Lunas',
        'metode_pembayaran' => 'Transfer Bank Mandiri',
        'catatan' => 'Lunas via Virtual Account',
    ]);

    $response->assertRedirect('/admin/keluarga');
    $response->assertSessionHas('success');
});

test('admin can toggle status pembayaran between Belum Bayar and Lunas', function () {
    $user = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $response = $this->actingAs($user)->post('/admin/keluarga/1/toggle-pembayaran');

    $response->assertRedirect('/admin/keluarga');
    $response->assertSessionHas('success');
});

test('aktivasi paket creates record in paket_siswa table and responds via api', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->postJson('/api/admin/paket-siswa/aktivasi', [
        'id_siswa' => 3,
        'program' => 'IoT Specialist',
        'jenis_paket' => 'Semester',
        'total_sesi' => 12,
        'status_pembayaran' => 'Belum Bayar',
        'metode_pembayaran' => 'Transfer Bank BNI',
        'catatan' => 'Uji coba aktivasi paket API',
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'data' => [
            'program' => 'IoT Specialist',
            'status_pembayaran' => 'Belum Bayar',
            'status_paket' => 'Aktif',
        ],
    ]);
});

test('admin can fetch list of paket_siswa via api endpoint', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->getJson('/api/admin/paket-siswa');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data',
    ]);
});

test('server validator rejects semester package if total sesi is not 12', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
        'role' => 'Admin',
    ]);

    // Test form post
    $response = $this->actingAs($admin)->post('/admin/keluarga/aktivasi-paket', [
        'id_siswa' => 2,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'total_sesi' => 8, // Invalid! Wajib 12
        'status_pembayaran' => 'Belum Bayar',
    ]);

    $response->assertSessionHasErrors(['total_sesi']);

    // Test JSON API
    $apiResponse = $this->actingAs($admin)->postJson('/api/admin/paket-siswa/aktivasi', [
        'id_siswa' => 2,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'total_sesi' => 10, // Invalid! Wajib 12
        'status_pembayaran' => 'Belum Bayar',
    ]);

    $apiResponse->assertStatus(422);
    $apiResponse->assertJsonValidationErrors(['total_sesi']);
});

test('server validator rejects bulanan package if total sesi exceeds 4', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
        'role' => 'Admin',
    ]);

    // Test form post
    $response = $this->actingAs($admin)->post('/admin/keluarga/aktivasi-paket', [
        'id_siswa' => 1,
        'program' => 'Coding Python',
        'jenis_paket' => 'Bulanan',
        'total_sesi' => 6, // Invalid! Maksimal 4
        'status_pembayaran' => 'Belum Bayar',
    ]);

    $response->assertSessionHasErrors(['total_sesi']);

    // Test JSON API
    $apiResponse = $this->actingAs($admin)->postJson('/api/admin/paket-siswa/aktivasi', [
        'id_siswa' => 1,
        'program' => 'Coding Python',
        'jenis_paket' => 'Bulanan',
        'total_sesi' => 8, // Invalid! Maksimal 4
        'status_pembayaran' => 'Belum Bayar',
    ]);

    $apiResponse->assertStatus(422);
    $apiResponse->assertJsonValidationErrors(['total_sesi']);
});

test('server validator accepts valid session counts for semester and bulanan', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
        'role' => 'Admin',
    ]);

    // Valid Semester = 12 sesi
    $semesterResponse = $this->actingAs($admin)->postJson('/api/admin/paket-siswa/aktivasi', [
        'id_siswa' => 4,
        'program' => 'Mechanic Engineering',
        'jenis_paket' => 'Semester',
        'total_sesi' => 12,
        'status_pembayaran' => 'Lunas',
    ]);
    $semesterResponse->assertStatus(201);
    $semesterResponse->assertJsonPath('data.total_sesi', 12);

    // Valid Bulanan = 4 sesi
    $bulananResponse = $this->actingAs($admin)->postJson('/api/admin/paket-siswa/aktivasi', [
        'id_siswa' => 5,
        'program' => 'Drone Operator',
        'jenis_paket' => 'Bulanan',
        'total_sesi' => 4,
        'status_pembayaran' => 'Belum Bayar',
    ]);
    $bulananResponse->assertStatus(201);
    $bulananResponse->assertJsonPath('data.total_sesi', 4);
});

test('admin can fetch rules for paket belajar via api endpoint', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->getJson('/api/admin/aturan-paket');

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);
    $response->assertJsonFragment(['jenis_paket' => 'Semester', 'maks_sesi' => 12]);
    $response->assertJsonFragment(['jenis_paket' => 'Bulanan', 'maks_sesi' => 4]);
});

test('newly created akun keluarga student appears in aktivasi paket dropdown and can be activated', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
        'role' => 'Admin',
    ]);

    // 1. Admin buat akun keluarga & siswa baru dari halaman keluarga
    $createResponse = $this->actingAs($admin)
        ->from('/admin/keluarga')
        ->post('/admin/keluarga', [
            'nama' => 'Kenzie Pratama',
            'nama_panggilan' => 'Kenzie',
            'tanggal_lahir' => '2017-03-22',
            'jenjang' => 'SD',
            'kelas' => '2',
            'nama_wali' => 'Ibu Dian',
            'nomor_whatsapp' => '081299887766',
            'paket_belajar' => 'Regular Gold (1 Orang) · 1 Semester (12x Pertemuan)',
            'total_sesi' => 12,
        ]);

    $createResponse->assertRedirect('/admin/keluarga');
    $createResponse->assertSessionHas('success', 'Akun keluarga & siswa berhasil dibuat!');
    $createResponse->assertSessionHas('open_modal_aktivasi', true);
    $createResponse->assertSessionHas('selected_siswa_nama', 'Kenzie Pratama');

    // 2. Pastikan nama siswa baru muncul di dropdown aktivasi paket pada view
    $viewResponse = $this->actingAs($admin)->get('/admin/keluarga');
    $viewResponse->assertStatus(200);
    $viewResponse->assertSee('Kenzie Pratama');
    $viewResponse->assertSee('Wali: Ibu Dian');

    // 3. Aktivasi paket belajar untuk siswa baru tersebut
    $siswa = Siswa::where('nama_lengkap', 'Kenzie Pratama')->first();
    expect($siswa)->not->toBeNull();

    $aktivasiResponse = $this->actingAs($admin)->post('/admin/keluarga/aktivasi-paket', [
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'total_sesi' => 12,
        'status_pembayaran' => 'Lunas',
        'metode_pembayaran' => 'QRIS',
        'catatan' => 'Lunas via QRIS Gopay',
    ]);

    $aktivasiResponse->assertRedirect('/admin/keluarga');
    $aktivasiResponse->assertSessionHas('success');

    $siswa->refresh();
    expect($siswa->status_paket)->toBe('Aktif');
    expect($siswa->status_pembayaran)->toBe('Lunas');
});

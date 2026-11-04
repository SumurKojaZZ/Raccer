<?php

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Artisan::call('migrate');
});

test('guest cannot access endpoint pembuatan jadwal kelas', function () {
    $response = $this->postJson('/api/admin/jadwal', [
        'program' => 'Junior Robotics',
        'tutor_pengampu' => 'Kak Dimas',
        'tanggal' => '2026-11-10',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'moda' => 'Offline',
    ]);

    $response->assertStatus(401);
});

test('non-admin user is rejected with 403 when creating jadwal', function () {
    $nonAdmin = User::factory()->make([
        'id' => 99,
        'name' => 'Siswa Andi',
        'username' => 'siswa.andi',
        'email' => 'andi@example.com',
        'role' => 'Siswa',
    ]);

    $response = $this->actingAs($nonAdmin)->postJson('/api/admin/jadwal', [
        'program' => 'Junior Robotics',
        'tutor_pengampu' => 'Kak Dimas',
        'tanggal' => '2026-11-10',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'moda' => 'Offline',
    ]);

    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Unauthorized. Akses ini khusus untuk Admin.',
    ]);
});

test('admin can successfully create class schedule via api endpoint', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $payload = [
        'program' => 'Junior Robotics',
        'tutor_pengampu' => 'Kak Dimas',
        'tanggal' => '2026-11-10',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'moda' => 'Offline',
    ];

    $response = $this->actingAs($admin)->postJson('/api/admin/jadwal', $payload);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'message' => 'Jadwal kelas berhasil dibuat.',
        'data' => [
            'program' => 'Junior Robotics',
            'tutor_pengampu' => 'Kak Dimas',
            'tanggal' => '2026-11-10',
            'jam_mulai' => '09:00',
            'jam_selesai' => '10:30',
            'moda' => 'Offline',
        ],
    ]);
});

test('validation fails when required fields are missing', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->postJson('/api/admin/jadwal', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'program',
        'tutor_pengampu',
        'jam_mulai',
        'jam_selesai',
    ]);
});

test('admin can create class schedule via web post route', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->post('/admin/jadwal', [
        'program' => 'Python for Kids',
        'tutor_pengampu' => 'Kak Sarah',
        'tanggal' => '2026-11-12',
        'jam_mulai' => '13:00',
        'jam_selesai' => '14:30',
        'moda' => 'Online',
    ]);

    $response->assertRedirect('/admin');
    $response->assertSessionHas('success', 'Jadwal kelas berhasil ditambahkan!');
});

test('validation fails when jam_selesai is less than or equal to jam_mulai', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->postJson('/api/admin/jadwal', [
        'program' => 'Junior Robotics',
        'tutor_pengampu' => 'Kak Dimas',
        'tanggal' => '2026-11-10',
        'jam_mulai' => '10:00',
        'jam_selesai' => '09:00',
        'moda' => 'Offline',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['jam_selesai']);
});

test('validation fails when tutor has nonaktif status', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    // Request untuk inisialisasi tabel dan data tutor
    $this->actingAs($admin)->getJson('/api/admin/jadwal');

    // Ubah status tutor menjadi Nonaktif
    $tutor = \App\Models\Tutor::first();
    $tutor->update(['status' => 'Nonaktif']);

    $response = $this->actingAs($admin)->postJson('/api/admin/jadwal', [
        'program' => 'Junior Robotics',
        'tutor_pengampu' => $tutor->nama_panggilan,
        'tanggal' => '2026-11-10',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'moda' => 'Offline',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['tutor_pengampu']);
});

test('admin can retrieve list of class schedules via get endpoint', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->getJson('/api/admin/jadwal');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data',
    ]);
});

test('guest cannot access penjadwalan terpusat page', function () {
    $response = $this->get('/admin/penjadwalan');
    $response->assertRedirect('/login');
});

test('admin can view penjadwalan terpusat management page', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'username' => 'admin',
        'email' => 'admin@raccer.com',
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($admin)->get('/admin/penjadwalan');

    $response->assertStatus(200);
    $response->assertSee('Penjadwalan Terpusat');
    $response->assertSee('Daftar Jadwal Kelas Terpusat');
    $response->assertSee('Buat Jadwal');
});

test('formatted tanggal displays Hari ini, Besok, or dd/mm/yyyy relative to calendar', function () {
    $todaySchedule = new Jadwal(['tanggal' => now()->toDateString()]);
    $tomorrowSchedule = new Jadwal(['tanggal' => now()->addDay()->toDateString()]);
    $futureSchedule = new Jadwal(['tanggal' => '2026-12-15']);

    expect($todaySchedule->formatted_tanggal)->toBe('Hari ini');
    expect($tomorrowSchedule->formatted_tanggal)->toBe('Besok');
    expect($futureSchedule->formatted_tanggal)->toBe('15/12/2026');
});


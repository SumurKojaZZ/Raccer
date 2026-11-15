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

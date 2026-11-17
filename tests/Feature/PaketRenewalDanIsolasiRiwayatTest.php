<?php

use App\Http\Controllers\AdminController;
use App\Models\PaketSiswa;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    (new AdminController())->ensureTablesExist();
    if (Schema::hasTable('paket_siswa')) {
        PaketSiswa::query()->delete();
    }
    if (Schema::hasTable('siswa')) {
        Siswa::query()->delete();
    }
});

test('guest cannot access renewal endpoints', function () {
    $response = $this->post('/admin/paket-siswa/renewal', []);
    $response->assertRedirect('/login');

    $apiResponse = $this->postJson('/api/admin/paket-siswa/renewal', []);
    $apiResponse->assertStatus(401);
});

test('admin can renew active package for student via web endpoint with complete history isolation', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    // 1. Arrange: Buat data siswa dengan paket aktif periode pertama (12/12 sesi selesai)
    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Alya Putri',
        'nama_lengkap' => 'Alya Putri Ramadhani',
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 12,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);

    $oldPkg = PaketSiswa::create([
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 12,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
        'catatan' => 'Paket Pertama 2026',
        'tanggal_aktivasi' => now()->subMonths(3),
    ]);

    // 2. Act: Admin melakukan renewal paket baru (Arduino Explorer 12 Sesi)
    $response = $this->actingAs($admin)->post('/admin/paket-siswa/renewal', [
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Arduino Explorer',
        'jenis_paket' => 'Semester',
        'total_sesi' => 12,
        'status_pembayaran' => 'Belum Bayar',
        'metode_pembayaran' => 'Transfer Bank BCA',
        'catatan' => 'Perpanjangan Semester 2',
    ]);

    // 3. Assert Web Response
    $response->assertRedirect('/admin/keluarga');
    $response->assertSessionHas('success');

    // 4. Assert Isolasi Riwayat Paket Lama:
    $oldPkg->refresh();
    expect($oldPkg->status_paket)->toBe('Selesai');
    expect($oldPkg->sesi_terpakai)->toBe(12); // Riwayat pemakaian sesi lama tetap 12 (tidak di-reset atau hilang)
    expect($oldPkg->catatan)->toContain('Diarsipkan saat renewal');

    // 5. Assert Paket Baru Aktif:
    $newPkg = PaketSiswa::where('id_siswa', $siswa->id_siswa)
        ->where('status_paket', 'Aktif')
        ->first();

    expect($newPkg)->not->toBeNull();
    expect($newPkg->id_paket_siswa)->not->toBe($oldPkg->id_paket_siswa);
    expect($newPkg->program)->toBe('Arduino Explorer');
    expect($newPkg->total_sesi)->toBe(12);
    expect($newPkg->sesi_terpakai)->toBe(0); // Paket baru mulai dari 0 sesi
    expect($newPkg->status_pembayaran)->toBe('Belum Bayar');

    // 6. Assert Data Induk Siswa:
    $siswa->refresh();
    expect($siswa->program)->toBe('Arduino Explorer');
    expect($siswa->sesi_terpakai)->toBe(0);
    expect($siswa->status_paket)->toBe('Aktif');
});

test('admin can renew package early before quota finishes and previous sessions remain isolated', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    // Siswa masih punya sisa 2 sesi (10 dari 12 sesi terpakai)
    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Bima Aditya',
        'nama_lengkap' => 'Bima Aditya Pratama',
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 10,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);

    $oldPkg = PaketSiswa::create([
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 10,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
        'tanggal_aktivasi' => now()->subMonths(2),
    ]);

    // Lakukan renewal lebih awal
    $response = $this->actingAs($admin)->post("/admin/paket-siswa/{$siswa->id_siswa}/renewal", [
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'total_sesi' => 12,
        'status_pembayaran' => 'Lunas',
        'metode_pembayaran' => 'QRIS',
        'catatan' => 'Perpanjangan Lanjutan',
    ]);

    $response->assertRedirect('/admin/keluarga');

    $oldPkg->refresh();
    // Karena diperpanjang sebelum 12 sesi habis, status lama menjadi Diperpanjang
    expect($oldPkg->status_paket)->toBe('Diperpanjang');
    expect($oldPkg->sesi_terpakai)->toBe(10); // Riwayat pemakaian 10 sesi tetap terisolasi dengan aman

    $newPkg = PaketSiswa::where('id_siswa', $siswa->id_siswa)
        ->where('status_paket', 'Aktif')
        ->first();

    expect($newPkg->sesi_terpakai)->toBe(0);
    expect($newPkg->total_sesi)->toBe(12);
});

test('admin can renew package via API and receives clean JSON structure with isolated history', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $siswa = Siswa::create([
        'id_keluarga' => 2,
        'nama' => 'Chandra Wijaya',
        'nama_lengkap' => 'Chandra Wijaya Kusuma',
        'program' => 'Python for Kids',
        'jenis_paket' => 'Bulanan',
        'paket_belajar' => 'Bulanan • Python for Kids (4 Sesi)',
        'total_sesi' => 4,
        'sesi_terpakai' => 4,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);

    PaketSiswa::create([
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Python for Kids',
        'jenis_paket' => 'Bulanan',
        'paket_belajar' => 'Bulanan • Python for Kids (4 Sesi)',
        'total_sesi' => 4,
        'sesi_terpakai' => 4,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
        'tanggal_aktivasi' => now()->subMonth(),
    ]);

    $response = $this->actingAs($admin)->postJson('/api/admin/paket-siswa/renewal', [
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Python for Kids',
        'jenis_paket' => 'Bulanan',
        'total_sesi' => 4,
        'status_pembayaran' => 'Lunas',
        'catatan' => 'Renewal Bulan Kedua',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id_siswa',
                'nama_siswa',
                'paket_aktif' => [
                    'id_paket_siswa',
                    'program',
                    'jenis_paket',
                    'total_sesi',
                    'sesi_terpakai',
                    'status_paket',
                    'status_pembayaran',
                ],
                'riwayat_paket_sebelumnya',
                'total_riwayat_paket',
            ],
        ]);

    $responseData = $response->json('data');
    expect($responseData['paket_aktif']['sesi_terpakai'])->toBe(0);
    expect($responseData['total_riwayat_paket'])->toBe(1);
    expect($responseData['riwayat_paket_sebelumnya'][0]['sesi_terpakai'])->toBe(4);
});

test('renewal validation fails when session quota violates package rule', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Dinda Kirana',
        'nama_lengkap' => 'Dinda Kirana Putri',
        'program' => 'Junior Robotics',
        'total_sesi' => 12,
        'status_paket' => 'Aktif',
    ]);

    // Semester wajib 12 sesi (coba submit 8 sesi)
    $responseSemester = $this->actingAs($admin)->postJson('/api/admin/paket-siswa/renewal', [
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'total_sesi' => 8,
        'status_pembayaran' => 'Lunas',
    ]);

    $responseSemester->assertStatus(422)
        ->assertJsonValidationErrors(['total_sesi']);

    // Bulanan maksimal 4 sesi (coba submit 6 sesi)
    $responseBulanan = $this->actingAs($admin)->postJson('/api/admin/paket-siswa/renewal', [
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Bulanan',
        'total_sesi' => 6,
        'status_pembayaran' => 'Lunas',
    ]);

    $responseBulanan->assertStatus(422)
        ->assertJsonValidationErrors(['total_sesi']);
});

test('admin can retrieve complete isolated package history via API', function () {
    $admin = User::factory()->make([
        'id' => 1,
        'name' => 'Admin Operasional',
        'email' => 'admin@racer-robotic.id',
    ]);

    $siswa = Siswa::create([
        'id_keluarga' => 1,
        'nama' => 'Eko Prasetyo',
        'nama_lengkap' => 'Eko Prasetyo Wibowo',
        'program' => 'Junior Robotics',
        'total_sesi' => 12,
        'status_paket' => 'Aktif',
    ]);

    // Buat 1 paket Selesai dan 1 paket Aktif
    PaketSiswa::create([
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Junior Robotics',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Junior Robotics (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 12,
        'status_paket' => 'Selesai',
        'status_pembayaran' => 'Lunas',
    ]);

    PaketSiswa::create([
        'id_siswa' => $siswa->id_siswa,
        'program' => 'Arduino Explorer',
        'jenis_paket' => 'Semester',
        'paket_belajar' => 'Semester • Arduino Explorer (12 Sesi)',
        'total_sesi' => 12,
        'sesi_terpakai' => 2,
        'status_paket' => 'Aktif',
        'status_pembayaran' => 'Lunas',
    ]);

    $response = $this->actingAs($admin)->getJson("/api/admin/siswa/{$siswa->id_siswa}/riwayat-paket");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonPath('data.total_paket_tercatat', 2)
        ->assertJsonPath('data.paket_aktif.program', 'Arduino Explorer')
        ->assertJsonPath('data.riwayat_paket_sebelumnya.0.program', 'Junior Robotics');
});

<?php

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Artisan::call('migrate');

    // Pastikan kolom link_kelas ada di tabel jadwal
    if (Schema::hasTable('jadwal') && !Schema::hasColumn('jadwal', 'link_kelas')) {
        Schema::table('jadwal', function ($table) {
            $table->string('link_kelas', 2048)->nullable();
        });
    }
});

// Helper membuat user Tutor terautentikasi
function createTutorUser(int $id, string $name, string $username): User
{
    return User::factory()->make([
        'id' => $id,
        'name' => $name,
        'username' => $username,
        'email' => "{$username}@racer.id",
        'role' => 'Tutor',
    ]);
}

test('guest cannot update online class link (401 Unauthenticated)', function () {
    $response = $this->postJson('/api/tutor/jadwal/1/link', [
        'link_kelas' => 'https://meet.google.com/abc-defg-hij',
    ]);

    $response->assertStatus(401);
});

test('non-tutor role is rejected with 403 Forbidden when attempting to update link', function () {
    $ortu = User::factory()->make([
        'id' => 77,
        'name' => 'Ibu Ratna',
        'username' => 'ibu.ratna',
        'role' => 'Orang_Tua',
    ]);

    session(['role' => 'Orang_Tua']);

    $response = $this->actingAs($ortu)->postJson('/api/tutor/jadwal/1/link', [
        'link_kelas' => 'https://meet.google.com/abc-defg-hij',
    ]);

    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Unauthorized. Akses ini khusus untuk Tutor.',
    ]);
});

test('updating non-existent schedule ID returns 404 Not Found', function () {
    $tutorBudi = createTutorUser(10, 'Kak Budi', 'tutor.budi');
    session(['role' => 'Tutor']);

    $response = $this->actingAs($tutorBudi)->postJson('/api/tutor/jadwal/99999/link', [
        'link_kelas' => 'https://meet.google.com/abc-defg-hij',
    ]);

    $response->assertStatus(404);
    $response->assertJson([
        'success' => false,
    ]);
});

test('tutor is rejected with 403 Forbidden when attempting to update another tutors schedule', function () {
    // Jadwal milik Tutor Dimas
    $jadwalDimas = Jadwal::create([
        'program' => 'Junior Robotics Dimas',
        'kelas' => 'Junior Robotics Dimas',
        'tutor' => 'Kak Dimas',
        'tutor_pengampu' => 'Kak Dimas',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:30',
        'moda' => 'Online',
        'lokasi' => 'Daring / Google Meet',
        'status' => 'Terjadwal',
    ]);

    // Tutor Budi mencoba mengupdate jadwal milik Tutor Dimas
    $tutorBudi = createTutorUser(20, 'Kak Budi', 'tutor.budi');
    session(['role' => 'Tutor']);

    $response = $this->actingAs($tutorBudi)->postJson("/api/tutor/jadwal/{$jadwalDimas->id}/link", [
        'link_kelas' => 'https://meet.google.com/hacked-by-budi',
    ]);

    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
    ]);

    // Pastikan database TIDAK berubah
    $jadwalDimas->refresh();
    expect($jadwalDimas->link_kelas)->not->toBe('https://meet.google.com/hacked-by-budi');
});

test('updating with non-https URL (http or invalid protocol) is rejected with 422 Unprocessable Entity', function () {
    // Jadwal milik Tutor Budi
    $jadwalBudi = Jadwal::create([
        'program' => 'AI Builder Budi',
        'kelas' => 'AI Builder Budi',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '13:00',
        'jam_selesai' => '14:30',
        'moda' => 'Online',
        'status' => 'Terjadwal',
    ]);

    $tutorBudi = createTutorUser(30, 'Kak Budi', 'tutor.budi');
    session(['role' => 'Tutor']);

    // 1. Uji protokol HTTP biasa (bukan HTTPS) -> Wajib ditolak
    $responseHttp = $this->actingAs($tutorBudi)->postJson("/api/tutor/jadwal/{$jadwalBudi->id}/link", [
        'link_kelas' => 'http://insecure.example.com/meeting',
    ]);
    $responseHttp->assertStatus(422);
    $responseHttp->assertJsonValidationErrors(['link_kelas']);

    // 2. Uji skema javascript / XSS attempt -> Wajib ditolak
    $responseJs = $this->actingAs($tutorBudi)->postJson("/api/tutor/jadwal/{$jadwalBudi->id}/link", [
        'link_kelas' => 'javascript:alert("XSS")',
    ]);
    $responseJs->assertStatus(422);
    $responseJs->assertJsonValidationErrors(['link_kelas']);

    // 3. Uji string acak bukan URL -> Wajib ditolak
    $responsePlain = $this->actingAs($tutorBudi)->postJson("/api/tutor/jadwal/{$jadwalBudi->id}/link", [
        'link_kelas' => 'bukan-url-yang-valid',
    ]);
    $responsePlain->assertStatus(422);
    $responsePlain->assertJsonValidationErrors(['link_kelas']);
});

test('updating online class URL on offline schedule is rejected with 422 Unprocessable Entity', function () {
    // Jadwal Offline milik Tutor Budi di Lab Robotika
    $jadwalOffline = Jadwal::create([
        'program' => 'Junior Robotics Offline',
        'kelas' => 'Junior Robotics Offline',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '13:00',
        'jam_selesai' => '14:30',
        'moda' => 'Offline',
        'lokasi' => 'Lab STEM 2',
        'status' => 'Terjadwal',
    ]);

    $tutorBudi = createTutorUser(35, 'Kak Budi', 'tutor.budi');
    session(['role' => 'Tutor']);

    $response = $this->actingAs($tutorBudi)->postJson("/api/tutor/jadwal/{$jadwalOffline->id}/link", [
        'link_kelas' => 'https://meet.google.com/attempt-offline-class',
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'message' => 'Tautan kelas online hanya dapat ditambahkan pada jadwal dengan moda Online. Jadwal ini merupakan kelas tatap muka (Offline).',
    ]);
    $response->assertJsonValidationErrors(['link_kelas']);

    // Pastikan database tidak berubah
    $jadwalOffline->refresh();
    expect($jadwalOffline->link_kelas)->toBeNull();
    expect($jadwalOffline->lokasi)->toBe('Lab STEM 2');
});

test('tutor successfully updates online class URL for their own schedule with valid https link (200 OK)', function () {
    // Jadwal milik Tutor Budi
    $jadwalBudi = Jadwal::create([
        'program' => 'IoT Smart Home Budi',
        'kelas' => 'IoT Smart Home Budi',
        'tutor' => 'Kak Budi',
        'tutor_pengampu' => 'Kak Budi',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '15:00',
        'jam_selesai' => '16:30',
        'moda' => 'Online',
        'lokasi' => 'Daring / Google Meet',
        'status' => 'Terjadwal',
    ]);

    $tutorBudi = createTutorUser(40, 'Kak Budi', 'tutor.budi');
    session(['role' => 'Tutor']);

    $validHttpsUrl = 'https://meet.google.com/rcr-budi-online';

    // 1. Eksekusi request update via API
    $response = $this->actingAs($tutorBudi)->postJson("/api/tutor/jadwal/{$jadwalBudi->id}/link", [
        'link_kelas' => $validHttpsUrl,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Tautan kelas online berhasil diperbarui.',
        'data' => [
            'id' => $jadwalBudi->id,
            'link_kelas' => $validHttpsUrl,
        ],
    ]);

    // 2. Verifikasi persistensi data di basis data
    $jadwalBudi->refresh();
    expect($jadwalBudi->link_kelas)->toBe($validHttpsUrl);
    expect($jadwalBudi->lokasi)->toBe($validHttpsUrl);

    // 3. Uji juga method PUT
    $newZoomUrl = 'https://zoom.us/j/9876543210';
    $responsePut = $this->actingAs($tutorBudi)->putJson("/api/tutor/jadwal/{$jadwalBudi->id}/link", [
        'link_meeting' => $newZoomUrl,
    ]);

    $responsePut->assertStatus(200);
    $jadwalBudi->refresh();
    expect($jadwalBudi->link_kelas)->toBe($newZoomUrl);
});

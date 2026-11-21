<?php

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Artisan::call('migrate');

    // Pastikan skema tabel jadwal mendukung kolom link_kelas
    if (Schema::hasTable('jadwal') && !Schema::hasColumn('jadwal', 'link_kelas')) {
        Schema::table('jadwal', function ($table) {
            $table->string('link_kelas', 2048)->nullable();
        });
    }
});

// Helper membuat mock user Tutor terautentikasi
function makeTutorUser(int $id, string $name, string $username): User
{
    return User::factory()->make([
        'id' => $id,
        'name' => $name,
        'username' => $username,
        'email' => "{$username}@racer.id",
        'role' => 'Tutor',
    ]);
}

/**
 * --------------------------------------------------------------------------
 * UNIT TEST VALIDASI URL KELAS ONLINE:
 * 1. https sah
 * 2. bukan URL
 * 3. kelas Offline
 * 4. jadwal Tutor lain
 * --------------------------------------------------------------------------
 */

describe('Unit Test Validasi URL Kelas Online', function () {

    // 1. SKENARIO: HTTPS SAH (Lolos Validasi)
    test('1. validasi URL kelas online menerima tautan https yang sah (Google Meet, Zoom, Teams)', function () {
        $aturanValidasi = [
            'link_kelas' => ['required', 'string', 'max:2048', 'regex:/^https:\/\/.+/i', 'url'],
        ];

        $daftarUrlHttpsSah = [
            'https://meet.google.com/abc-defg-hij',
            'https://zoom.us/j/1234567890?pwd=SecurePassword123',
            'https://teams.microsoft.com/l/meetup-join/19%3ameeting_xyz',
            'https://classroom.google.com/c/MzEyMzQ1Nzg5',
            'https://raccer.test/live/robotics-session',
        ];

        foreach ($daftarUrlHttpsSah as $urlSah) {
            $validator = Validator::make(['link_kelas' => $urlSah], $aturanValidasi);
            expect($validator->passes())->toBeTrue("URL HTTPS '{$urlSah}' seharusnya lolos validasi.");
            expect($validator->errors()->has('link_kelas'))->toBeFalse();
        }

        // Uji eksekusi langsung via endpoint untuk jadwal moda Online milik Tutor sendiri
        $jadwalOnline = Jadwal::create([
            'program' => 'AI Robotics Online',
            'kelas' => 'AI Robotics Online',
            'tutor' => 'Kak Budi',
            'tutor_pengampu' => 'Kak Budi',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '09:00',
            'jam_selesai' => '10:30',
            'moda' => 'Online',
            'lokasi' => 'Daring / Google Meet',
            'status' => 'Terjadwal',
        ]);

        $tutorBudi = makeTutorUser(101, 'Kak Budi', 'tutor.budi');
        session(['role' => 'Tutor']);

        $urlValid = 'https://meet.google.com/racer-budi-class';
        $response = $this->actingAs($tutorBudi)->postJson("/api/tutor/jadwal/{$jadwalOnline->id}/link", [
            'link_kelas' => $urlValid,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tautan kelas online berhasil diperbarui.',
            'data' => [
                'id' => $jadwalOnline->id,
                'link_kelas' => $urlValid,
                'moda' => 'Online',
            ],
        ]);

        // Verifikasi persistensi di database
        $jadwalOnline->refresh();
        expect($jadwalOnline->link_kelas)->toBe($urlValid);
        expect($jadwalOnline->lokasi)->toBe($urlValid);
    });

    // 2. SKENARIO: BUKAN URL (Gagal Validasi Input)
    test('2. validasi URL kelas online menolak input yang bukan URL, teks biasa, atau bukan protokol https', function () {
        $aturanValidasi = [
            'link_kelas' => [
                'required',
                'string',
                'max:2048',
                'regex:/^https:\/\/.+/i',
                'url',
            ],
        ];

        $daftarInputBukanUrlSah = [
            'bukan-sebuah-url',                                      // String polos
            'meet.google.com/abc-defg-hij',                         // Tanpa protokol https://
            'http://insecure-meeting.example.com/room',             // Protokol HTTP tidak aman (bukan HTTPS)
            'javascript:alert("XSS")',                              // Skema berbahaya JavaScript
            'ftp://files.example.com/class-link',                   // Protokol FTP
            'data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==', // Data URI scheme
            '',                                                     // String kosong
            '   ',                                                  // Whitespace
            '<script>alert(1)</script>',                            // XSS tag script
        ];

        foreach ($daftarInputBukanUrlSah as $inputInvalid) {
            $validator = Validator::make(['link_kelas' => $inputInvalid], $aturanValidasi);
            expect($validator->fails())->toBeTrue("Input '{$inputInvalid}' seharusnya ditolak oleh validasi URL.");
            expect($validator->errors()->has('link_kelas'))->toBeTrue();
        }

        // Uji eksekusi langsung ke endpoint: wajib ditolak HTTP 422 Unprocessable Entity
        $jadwalOnline = Jadwal::create([
            'program' => 'IoT Smart Devices',
            'kelas' => 'IoT Smart Devices',
            'tutor' => 'Kak Sarah',
            'tutor_pengampu' => 'Kak Sarah',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '11:00',
            'jam_selesai' => '12:30',
            'moda' => 'Online',
            'lokasi' => 'Daring',
            'status' => 'Terjadwal',
        ]);

        $tutorSarah = makeTutorUser(102, 'Kak Sarah', 'tutor.sarah');
        session(['role' => 'Tutor']);

        $responseInvalid = $this->actingAs($tutorSarah)->postJson("/api/tutor/jadwal/{$jadwalOnline->id}/link", [
            'link_kelas' => 'http://insecure-http-link.com',
        ]);

        $responseInvalid->assertStatus(422);
        $responseInvalid->assertJsonValidationErrors(['link_kelas']);

        // Pastikan link_kelas di database tetap null / tidak berubah
        $jadwalOnline->refresh();
        expect($jadwalOnline->link_kelas)->toBeNull();
    });

    // 3. SKENARIO: KELAS OFFLINE (Gagal Validasi Moda/Kondisi Bisnis)
    test('3. validasi menolak penambahan URL kelas online pada jadwal dengan moda Offline', function () {
        // Buat jadwal kelas tatap muka fisik (Offline) di Lab Robotika
        $jadwalOffline = Jadwal::create([
            'program' => 'Junior Robotics Lab',
            'kelas' => 'Junior Robotics Lab',
            'tutor' => 'Kak Reza',
            'tutor_pengampu' => 'Kak Reza',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '13:00',
            'jam_selesai' => '14:30',
            'moda' => 'Offline',
            'lokasi' => 'Lab Robotika 1 (Tatap Muka)',
            'status' => 'Terjadwal',
        ]);

        $tutorReza = makeTutorUser(103, 'Kak Reza', 'tutor.reza');
        session(['role' => 'Tutor']);

        // Mencoba mengirim request update URL online ke jadwal yang bermoda Offline
        $response = $this->actingAs($tutorReza)->postJson("/api/tutor/jadwal/{$jadwalOffline->id}/link", [
            'link_kelas' => 'https://meet.google.com/offline-attempt',
        ]);

        // Server wajib menolak dengan HTTP 422 Unprocessable Entity
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Tautan kelas online hanya dapat ditambahkan pada jadwal dengan moda Online. Jadwal ini merupakan kelas tatap muka (Offline).',
        ]);
        $response->assertJsonValidationErrors(['link_kelas']);

        // Verifikasi integritas data: link_kelas tetap null dan lokasi lab fisik TIDAK tertimpa
        $jadwalOffline->refresh();
        expect($jadwalOffline->link_kelas)->toBeNull();
        expect($jadwalOffline->lokasi)->toBe('Lab Robotika 1 (Tatap Muka)');
    });

    // 4. SKENARIO: JADWAL TUTOR LAIN (Gagal Validasi Otorisasi / IDOR Protection)
    test('4. validasi otorisasi menolak tutor memperbarui tautan kelas online pada jadwal milik tutor lain (403 Forbidden)', function () {
        // Jadwal Online milik Tutor Dimas
        $jadwalDimas = Jadwal::create([
            'program' => 'Python Advanced',
            'kelas' => 'Python Advanced',
            'tutor' => 'Kak Dimas',
            'tutor_pengampu' => 'Kak Dimas',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '15:00',
            'jam_selesai' => '16:30',
            'moda' => 'Online',
            'lokasi' => 'Daring / Google Meet',
            'link_kelas' => 'https://meet.google.com/dimas-original-link',
            'status' => 'Terjadwal',
        ]);

        // Tutor Fajar yang berbeda mencoba mengedit jadwal milik Kak Dimas
        $tutorFajar = makeTutorUser(104, 'Kak Fajar', 'tutor.fajar');
        session(['role' => 'Tutor']);

        $response = $this->actingAs($tutorFajar)->postJson("/api/tutor/jadwal/{$jadwalDimas->id}/link", [
            'link_kelas' => 'https://meet.google.com/fajar-takeover-link',
        ]);

        // Server wajib menolak dengan status HTTP 403 Forbidden
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya dapat mengubah tautan kelas online pada jadwal mengajar milik Anda sendiri.',
        ]);

        // Verifikasi keamanan data: link jadwal milik Kak Dimas tidak berubah
        $jadwalDimas->refresh();
        expect($jadwalDimas->link_kelas)->toBe('https://meet.google.com/dimas-original-link');
    });

});

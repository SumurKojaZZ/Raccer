<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\TutorController;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Route::get('/', function () {
    $pengumumanList = collect();
    try {
        if (Schema::hasTable('pengumuman')) {
            $pengumumanList = Pengumuman::orderBy('id', 'desc')->take(5)->get();
        }
    } catch (\Throwable $e) {
    }

    // Fallback data pengumuman realistis jika tabel masih kosong
    if ($pengumumanList->isEmpty()) {
        $pengumumanList = collect([
            (object) [
                'id' => 1,
                'judul' => 'Pembukaan Pendaftaran Kelas Robotika Gelombang Baru',
                'konten' => 'Pendaftaran program kursus STEM & Robotika untuk tingkat TK, SD, dan SMP telah dibuka. Kuota lab terbatas.',
                'kategori' => 'PENTING',
                'is_penting' => true,
                'tanggal' => '04 Nov 2026',
            ],
            (object) [
                'id' => 2,
                'judul' => 'Pembaruan Modul Controller & AI v2.4',
                'konten' => 'Modul praktikum mikrokontroler dan logika kontrol berbasis Arduino kini tersedia untuk peserta didik Level 3.',
                'kategori' => 'Akademik',
                'is_penting' => false,
                'tanggal' => '02 Nov 2026',
            ],
            (object) [
                'id' => 3,
                'judul' => 'Jadwal Workshop Praktikum Offline Akhir Pekan',
                'konten' => 'Sesi belajar tatap muka di Robotics Hub Lab B Jakarta Pusat berlangsung setiap hari Sabtu & Minggu.',
                'kategori' => 'Workshop',
                'is_penting' => false,
                'tanggal' => '30 Okt 2026',
            ],
        ]);
    }

    return view('home', compact('pengumumanList'));
})->name('home');

Route::get('/home', function () {
    return redirect('/');
});

// ── Login & Logout ─────────────────────────────────────
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// ── Registrasi Mandiri Tutor ───────────────────────────
Route::get('/register-tutor', [TutorController::class, 'showRegister'])->name('tutor.register');
Route::post('/register-tutor', [TutorController::class, 'processRegister'])->name('tutor.register.store');

// ── Admin Protected Routes (Khusus Admin) ──────────────
Route::middleware(['auth', \App\Http\Middleware\CheckIdleTimeout::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/admin/penjadwalan', [AdminController::class, 'penjadwalan'])->name('admin.penjadwalan');
    Route::get('/admin/tutor', [AdminController::class, 'tutor'])->name('admin.tutor');
    Route::post('/admin/tutor', [AdminController::class, 'storeTutor'])->name('admin.tutor.store');
    Route::post('/admin/tutor/{id}/toggle-status', [AdminController::class, 'toggleStatusTutor'])->name('admin.tutor.toggle-status');
    Route::post('/admin/jadwal', [AdminController::class, 'storeJadwal'])->name('admin.jadwal.store');
    Route::get('/admin/keluarga', [AdminController::class, 'keluarga'])->name('admin.keluarga');
    Route::post('/admin/keluarga/aktivasi-paket', [AdminController::class, 'aktivasiPaket'])->name('admin.keluarga.aktivasi-paket');
    Route::post('/admin/paket-siswa/aktivasi', [AdminController::class, 'aktivasiPaket'])->name('admin.paket-siswa.aktivasi');
    Route::post('/admin/paket/aktivasi', [AdminController::class, 'aktivasiPaket'])->name('admin.paket.aktivasi');
    Route::post('/admin/keluarga/renewal-paket', [AdminController::class, 'renewalPaket'])->name('admin.keluarga.renewal-paket');
    Route::post('/admin/paket-siswa/renewal', [AdminController::class, 'renewalPaket'])->name('admin.paket-siswa.renewal');
    Route::post('/admin/paket-siswa/{id}/renewal', [AdminController::class, 'renewalPaket'])->name('admin.paket-siswa.renewal.id');
    Route::get('/admin/siswa/{id}/riwayat-paket', [AdminController::class, 'riwayatPaketSiswa'])->name('admin.siswa.riwayat-paket');
    Route::post('/admin/keluarga/{id}/toggle-pembayaran', [AdminController::class, 'togglePembayaran'])->name('admin.keluarga.toggle-pembayaran');
    Route::post('/admin/keluarga', [AdminController::class, 'storeKeluarga'])->name('admin.keluarga.store');
    Route::post('/admin/reschedule/{id}/approve', [AdminController::class, 'approveReschedule'])->name('admin.reschedule.approve');
    Route::post('/admin/jadwal/{id}/status', [AdminController::class, 'updateStatusJadwal'])->name('admin.jadwal.status');
    Route::get('/admin/aturan-paket', [AdminController::class, 'indexAturanPaket'])->name('admin.aturan-paket');

    // Peserta Jadwal & Pemilihan Peserta Sesuai Program (B-11 - Muhammad Fadhil Swarigading)
    Route::get('/admin/siswa/pilih-peserta', [AdminController::class, 'pilihPeserta'])->name('admin.siswa.pilih-peserta');
    Route::post('/admin/jadwal/{id}/peserta', [AdminController::class, 'storePesertaJadwal'])->name('admin.jadwal.peserta.store');
    Route::delete('/admin/jadwal/{id}/peserta/{id_siswa}', [AdminController::class, 'destroyPesertaJadwal'])->name('admin.jadwal.peserta.destroy');

    // Secondary Parent Opsional Maksimal 1 per Keluarga (B-13b - Muhammad Fadhil Swarigading)
    Route::post('/admin/keluarga/{id}/secondary-parent', [AdminController::class, 'storeSecondaryParent'])->name('admin.keluarga.secondary-parent.store');
    Route::delete('/admin/keluarga/{id}/secondary-parent', [AdminController::class, 'destroySecondaryParent'])->name('admin.keluarga.secondary-parent.destroy');

    // Tambah Akun Anak (Siswa) dengan Username & PIN 6 Digit (B-14 - Muhammad Fadhil Swarigading)
    Route::post('/admin/keluarga/{id}/siswa', [AdminController::class, 'storeSiswa'])->name('admin.keluarga.siswa.store');
    Route::post('/admin/siswa', [AdminController::class, 'storeSiswa'])->name('admin.siswa.store');

    // Update Kontak WhatsApp Wali & Domisili Akun Keluarga (Backlog B-42 - Arta Maruf Rifaa Ulwan)
    Route::match(['post', 'put', 'patch'], '/admin/keluarga/{id}/update-kontak-domisili', [AdminController::class, 'updateKontakDomisiliKeluarga'])->name('admin.keluarga.update-kontak-domisili');
    Route::match(['put', 'patch'], '/admin/keluarga/{id}', [AdminController::class, 'updateKontakDomisiliKeluarga'])->name('admin.keluarga.update');
});

// ── API Admin Endpoint ──────────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/api/admin/jadwal', [AdminController::class, 'indexJadwal'])->name('api.admin.jadwal.index');
    Route::get('/api/jadwal', [AdminController::class, 'indexJadwal'])->name('api.jadwal.index');
    Route::post('/api/admin/jadwal', [AdminController::class, 'storeJadwal'])->name('api.admin.jadwal.store');
    Route::post('/api/jadwal', [AdminController::class, 'storeJadwal'])->name('api.jadwal.store');

    // API Paket Siswa & Pembayaran
    Route::get('/api/admin/paket-siswa', [AdminController::class, 'indexPaketSiswa'])->name('api.admin.paket-siswa.index');
    Route::get('/api/paket-siswa', [AdminController::class, 'indexPaketSiswa'])->name('api.paket-siswa.index');
    Route::post('/api/admin/paket-siswa', [AdminController::class, 'aktivasiPaket'])->name('api.admin.paket-siswa.store');
    Route::post('/api/admin/paket-siswa/aktivasi', [AdminController::class, 'aktivasiPaket'])->name('api.admin.paket-siswa.aktivasi');
    Route::post('/api/admin/paket/aktivasi', [AdminController::class, 'aktivasiPaket'])->name('api.admin.paket.aktivasi');
    Route::post('/api/paket-siswa', [AdminController::class, 'aktivasiPaket'])->name('api.paket-siswa.store');
    Route::post('/api/admin/paket-siswa/{id}/toggle-pembayaran', [AdminController::class, 'togglePembayaran'])->name('api.admin.paket-siswa.toggle-pembayaran');

    // API Renewal Paket Siswa & Riwayat Terisolasi (B-03 Backend - Muhammad Fadhil Swarigading)
    Route::post('/api/admin/paket-siswa/renewal', [AdminController::class, 'renewalPaket'])->name('api.admin.paket-siswa.renewal');
    Route::post('/api/admin/paket-siswa/{id}/renewal', [AdminController::class, 'renewalPaket'])->name('api.admin.paket-siswa.renewal.id');
    Route::post('/api/paket-siswa/renewal', [AdminController::class, 'renewalPaket'])->name('api.paket-siswa.renewal');
    Route::get('/api/admin/siswa/{id}/riwayat-paket', [AdminController::class, 'riwayatPaketSiswa'])->name('api.admin.siswa.riwayat-paket');
    Route::get('/api/siswa/{id}/riwayat-paket', [AdminController::class, 'riwayatPaketSiswa'])->name('api.siswa.riwayat-paket');

    // API Aturan Paket Belajar (Semester=12 sesi, Bulanan maks=4 sesi)
    Route::get('/api/admin/aturan-paket', [AdminController::class, 'indexAturanPaket'])->name('api.admin.aturan-paket.index');
    Route::get('/api/aturan-paket', [AdminController::class, 'indexAturanPaket'])->name('api.aturan-paket.index');

    // API Peserta Jadwal & Filter Program & Paket Aktif (B-11 - Muhammad Fadhil Swarigading)
    Route::get('/api/admin/siswa/pilih-peserta', [AdminController::class, 'pilihPeserta'])->name('api.admin.siswa.pilih-peserta');
    Route::get('/api/siswa/pilih-peserta', [AdminController::class, 'pilihPeserta'])->name('api.siswa.pilih-peserta');
    Route::get('/api/admin/jadwal/{id}/peserta', [AdminController::class, 'getPesertaJadwal'])->name('api.admin.jadwal.peserta.index');
    Route::post('/api/admin/jadwal/{id}/peserta', [AdminController::class, 'storePesertaJadwal'])->name('api.admin.jadwal.peserta.store');
    Route::post('/api/jadwal/{id}/peserta', [AdminController::class, 'storePesertaJadwal'])->name('api.jadwal.peserta.store');
    Route::delete('/api/admin/jadwal/{id}/peserta/{id_siswa}', [AdminController::class, 'destroyPesertaJadwal'])->name('api.admin.jadwal.peserta.destroy');
    Route::delete('/api/jadwal/{id}/peserta/{id_siswa}', [AdminController::class, 'destroyPesertaJadwal'])->name('api.jadwal.peserta.destroy');

    // API Secondary Parent Opsional Maksimal 1 per Keluarga (B-13b - Muhammad Fadhil Swarigading)
    Route::post('/api/admin/keluarga', [AdminController::class, 'storeKeluarga'])->name('api.admin.keluarga.store');
    Route::post('/api/keluarga', [AdminController::class, 'storeKeluarga'])->name('api.keluarga.store');
    Route::get('/api/admin/keluarga/{id}/orang-tua', [AdminController::class, 'getOrangTuaKeluarga'])->name('api.admin.keluarga.orang-tua');
    Route::get('/api/keluarga/{id}/orang-tua', [AdminController::class, 'getOrangTuaKeluarga'])->name('api.keluarga.orang-tua');
    Route::post('/api/admin/keluarga/{id}/secondary-parent', [AdminController::class, 'storeSecondaryParent'])->name('api.admin.keluarga.secondary-parent.store');
    Route::post('/api/keluarga/{id}/secondary-parent', [AdminController::class, 'storeSecondaryParent'])->name('api.keluarga.secondary-parent.store');
    Route::delete('/api/admin/keluarga/{id}/secondary-parent', [AdminController::class, 'destroySecondaryParent'])->name('api.admin.keluarga.secondary-parent.destroy');
    Route::delete('/api/keluarga/{id}/secondary-parent', [AdminController::class, 'destroySecondaryParent'])->name('api.keluarga.secondary-parent.destroy');

    // API Tambah Akun Anak (Siswa) dengan Username & PIN 6 Digit (B-14 - Muhammad Fadhil Swarigading)
    Route::post('/api/admin/keluarga/{id}/siswa', [AdminController::class, 'storeSiswa'])->name('api.admin.keluarga.siswa.store');
    Route::post('/api/keluarga/{id}/siswa', [AdminController::class, 'storeSiswa'])->name('api.keluarga.siswa.store');
    Route::post('/api/admin/siswa', [AdminController::class, 'storeSiswa'])->name('api.admin.siswa.store');
    Route::post('/api/siswa', [AdminController::class, 'storeSiswa'])->name('api.siswa.store');

    // API Update Kontak WhatsApp Wali & Domisili Akun Keluarga Beserta Pencatatan Log (Backlog B-42 - Arta Maruf Rifaa Ulwan)
    Route::get('/api/admin/keluarga/{id}', [AdminController::class, 'showKeluarga'])->name('api.admin.keluarga.show');
    Route::get('/api/keluarga/{id}', [AdminController::class, 'showKeluarga'])->name('api.keluarga.show');
    Route::match(['post', 'put', 'patch'], '/api/admin/keluarga/{id}/update-kontak-domisili', [AdminController::class, 'updateKontakDomisiliKeluarga'])->name('api.admin.keluarga.update-kontak-domisili');
    Route::match(['post', 'put', 'patch'], '/api/keluarga/{id}/update-kontak-domisili', [AdminController::class, 'updateKontakDomisiliKeluarga'])->name('api.keluarga.update-kontak-domisili');
    Route::match(['put', 'patch'], '/api/admin/keluarga/{id}', [AdminController::class, 'updateKontakDomisiliKeluarga'])->name('api.admin.keluarga.update');
    Route::match(['put', 'patch'], '/api/keluarga/{id}', [AdminController::class, 'updateKontakDomisiliKeluarga'])->name('api.keluarga.update');
});

// ── Shared / Orang Tua Authenticated Endpoints (Validasi Kepemilikan Sesi di Server - Muhammad Fadhil Swarigading) ──
Route::middleware(['auth'])->group(function () {
    // API Daftar Anak per ID Keluarga
    Route::get('/api/admin/keluarga/{id}/siswa', [OrangTuaController::class, 'getAnakPerKeluarga'])->name('api.admin.keluarga.siswa.index');
    Route::get('/api/keluarga/{id}/siswa', [OrangTuaController::class, 'getAnakPerKeluarga'])->name('api.keluarga.siswa.index');
    Route::get('/api/admin/keluarga/{id}/anak', [OrangTuaController::class, 'getAnakPerKeluarga'])->name('api.admin.keluarga.anak.index');
    Route::get('/api/keluarga/{id}/anak', [OrangTuaController::class, 'getAnakPerKeluarga'])->name('api.keluarga.anak.index');
    Route::get('/api/orang-tua/keluarga/{id}/anak', [OrangTuaController::class, 'getAnakPerKeluarga'])->name('api.orang-tua.keluarga.anak.index');
    Route::get('/api/orang-tua/anak', [OrangTuaController::class, 'getAnakPerKeluarga'])->name('api.orang-tua.anak.index');

    // Detail Profil Anak
    Route::get('/api/admin/siswa/{id}', [OrangTuaController::class, 'getDetailAnak'])->name('api.admin.siswa.show');
    Route::get('/api/siswa/{id}', [OrangTuaController::class, 'getDetailAnak'])->name('api.siswa.show');
    Route::get('/api/anak/{id}', [OrangTuaController::class, 'getDetailAnak'])->name('api.anak.show');
    Route::get('/api/keluarga/{id}/anak/{id_siswa}', [OrangTuaController::class, 'getDetailAnak'])->name('api.keluarga.anak.show');
    Route::get('/api/keluarga/{id}/siswa/{id_siswa}', [OrangTuaController::class, 'getDetailAnak'])->name('api.keluarga.siswa.show');

    // API Pembaruan WhatsApp Sendiri, Alamat Domisili, dan Data Secondary Parent (PUT/PATCH)
    Route::match(['put', 'patch', 'post'], '/api/orang-tua/profil', [OrangTuaController::class, 'updateProfilDanKeluarga'])->name('api.orang-tua.profil.update');
    Route::match(['put', 'patch', 'post'], '/api/orang-tua/kontak-domisili', [OrangTuaController::class, 'updateProfilDanKeluarga'])->name('api.orang-tua.kontak-domisili.update');
    Route::match(['put', 'patch', 'post'], '/api/keluarga/{id}/orang-tua', [OrangTuaController::class, 'updateProfilDanKeluarga'])->name('api.keluarga.orang-tua.update');
    Route::match(['put', 'patch', 'post'], '/api/keluarga/{id}/profil', [OrangTuaController::class, 'updateProfilDanKeluarga'])->name('api.keluarga.profil.update');
    Route::match(['put', 'patch', 'post'], '/api/keluarga/{id}/secondary-parent', [OrangTuaController::class, 'updateSecondaryParentOrangTua'])->name('api.keluarga.secondary-parent.update');
    Route::match(['put', 'patch', 'post'], '/api/orang-tua/secondary-parent', [OrangTuaController::class, 'updateSecondaryParentOrangTua'])->name('api.orang-tua.secondary-parent.update');
    Route::delete('/api/keluarga/{id}/secondary-parent', [OrangTuaController::class, 'destroySecondaryParentOrangTua'])->name('api.keluarga.secondary-parent.destroy');
    Route::delete('/api/orang-tua/secondary-parent', [OrangTuaController::class, 'destroySecondaryParentOrangTua'])->name('api.orang-tua.secondary-parent.destroy');
});

// ── Tutor Protected Routes (Khusus Portal Tutor) ───────
Route::middleware(['auth', 'tutor', \App\Http\Middleware\CheckIdleTimeout::class])->group(function () {
    Route::get('/tutor', [TutorController::class, 'index'])->name('tutor');
    Route::post('/tutor/password', [TutorController::class, 'updatePassword'])->name('tutor.password.update');
    Route::get('/tutor/jadwal', [TutorController::class, 'jadwalPribadi'])->name('tutor.jadwal');
    Route::get('/tutor/jadwal/{id}', [TutorController::class, 'detailJadwal'])->name('tutor.jadwal.detail');
    Route::get('/tutor/jadwal/{id}/peserta', [TutorController::class, 'getPesertaJadwal'])->name('tutor.jadwal.peserta');
    Route::match(['post', 'put', 'patch'], '/tutor/jadwal/{id}/link', [TutorController::class, 'updateLinkKelas'])->name('tutor.jadwal.update-link');
    Route::match(['post', 'put', 'patch'], '/tutor/jadwal/{id}/link-kelas', [TutorController::class, 'updateLinkKelas']);
});

// ── API Tutor Endpoint (Jadwal Mengajar Pribadi Terisolasi & Filter Hari/Minggu) ──
Route::middleware(['auth', 'tutor'])->group(function () {
    Route::get('/api/tutor/jadwal', [TutorController::class, 'jadwalPribadi'])->name('api.tutor.jadwal.index');
    Route::get('/api/tutor/jadwal-pribadi', [TutorController::class, 'jadwalPribadi'])->name('api.tutor.jadwal-pribadi');
    Route::get('/api/tutor/jadwal/{id}', [TutorController::class, 'detailJadwal'])->name('api.tutor.jadwal.detail');
    Route::get('/api/tutor/jadwal/{id}/peserta', [TutorController::class, 'getPesertaJadwal'])->name('api.tutor.jadwal.peserta');
    Route::match(['post', 'put', 'patch'], '/api/tutor/jadwal/{id}/link', [TutorController::class, 'updateLinkKelas'])->name('api.tutor.jadwal.update-link');
    Route::match(['post', 'put', 'patch'], '/api/tutor/jadwal/{id}/link-kelas', [TutorController::class, 'updateLinkKelas']);
    Route::match(['post', 'put', 'patch'], '/api/tutor/jadwal/{id}/url-online', [TutorController::class, 'updateLinkKelas']);
});

// ── Shared Endpoint Peserta Jadwal (Akses untuk Admin & Tutor Pengampu) ──
Route::middleware(['auth'])->group(function () {
    Route::get('/api/jadwal/{id}/peserta', [AdminController::class, 'getPesertaJadwal'])->name('api.jadwal.peserta.index');
});

// ── Orang Tua Protected Routes (Khusus Portal Orang Tua / Wali) ───────
Route::middleware(['auth', 'orang_tua', \App\Http\Middleware\CheckIdleTimeout::class])->group(function () {
    Route::get('/orang-tua', [LoginController::class, 'portalOrangTua'])->name('orang-tua');
    Route::get('/portal-orang-tua', [LoginController::class, 'portalOrangTua'])->name('portal.orang-tua');
    Route::get('/orang-tua/anak/{id}', [OrangTuaController::class, 'getDetailAnak'])->name('orang-tua.anak.detail');
    Route::get('/orang-tua/edit-kontak', [OrangTuaController::class, 'editKontakKeluarga'])->name('orang-tua.edit-kontak');
    Route::get('/orang-tua/profil/edit', [OrangTuaController::class, 'editKontakKeluarga'])->name('orang-tua.profil.edit');
    Route::match(['put', 'patch', 'post'], '/orang-tua/profil', [OrangTuaController::class, 'updateProfilDanKeluarga'])->name('orang-tua.profil.update');
    Route::match(['put', 'patch', 'post'], '/orang-tua/secondary-parent', [OrangTuaController::class, 'updateSecondaryParentOrangTua'])->name('orang-tua.secondary-parent.update');
    Route::delete('/orang-tua/secondary-parent', [OrangTuaController::class, 'destroySecondaryParentOrangTua'])->name('orang-tua.secondary-parent.destroy');
});

// ── Lupa Password ──────────────────────────────────────
Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::ResetLinkSent
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// ── Reset Password ─────────────────────────────────────
Route::get('/reset-password/{token}', function (string $token) {
    return view('reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])
                ->setRememberToken(Str::random(60));
            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PasswordReset
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.update');

// ── Route Simulasi Error untuk Pengujian Keamanan (Bukti 2) ──
Route::get('/simulasi-error', function () {
    throw new \RuntimeException('Database SQL connection failed: username=db_admin password=SuperSecretDbPassword123');
})->name('simulasi.error');


<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
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
    } catch (\Throwable $e) {}

    // Fallback data pengumuman realistis jika tabel masih kosong
    if ($pengumumanList->isEmpty()) {
        $pengumumanList = collect([
            (object)[
                'id' => 1,
                'judul' => 'Pembukaan Pendaftaran Kelas Robotika Gelombang Baru',
                'konten' => 'Pendaftaran program kursus STEM & Robotika untuk tingkat TK, SD, dan SMP telah dibuka. Kuota lab terbatas.',
                'kategori' => 'PENTING',
                'is_penting' => true,
                'tanggal' => '04 Nov 2026',
            ],
            (object)[
                'id' => 2,
                'judul' => 'Pembaruan Modul Controller & AI v2.4',
                'konten' => 'Modul praktikum mikrokontroler dan logika kontrol berbasis Arduino kini tersedia untuk peserta didik Level 3.',
                'kategori' => 'Akademik',
                'is_penting' => false,
                'tanggal' => '02 Nov 2026',
            ],
            (object)[
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
    Route::get('/admin/tutor', [AdminController::class, 'tutor'])->name('admin.tutor');
    Route::post('/admin/tutor', [AdminController::class, 'storeTutor'])->name('admin.tutor.store');
    Route::post('/admin/tutor/{id}/toggle-status', [AdminController::class, 'toggleStatusTutor'])->name('admin.tutor.toggle-status');
    Route::post('/admin/jadwal', [AdminController::class, 'storeJadwal'])->name('admin.jadwal.store');
    Route::post('/admin/keluarga', [AdminController::class, 'storeKeluarga'])->name('admin.keluarga.store');
    Route::post('/admin/reschedule/{id}/approve', [AdminController::class, 'approveReschedule'])->name('admin.reschedule.approve');
    Route::post('/admin/jadwal/{id}/status', [AdminController::class, 'updateStatusJadwal'])->name('admin.jadwal.status');
});

// ── Tutor Protected Routes (Khusus Portal Tutor) ───────
Route::middleware(['auth', \App\Http\Middleware\CheckIdleTimeout::class])->group(function () {
    Route::get('/tutor', [TutorController::class, 'index'])->name('tutor');
    Route::post('/tutor/password', [TutorController::class, 'updatePassword'])->name('tutor.password.update');
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

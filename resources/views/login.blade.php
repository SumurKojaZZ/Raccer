<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Racer Robotic</title>
    <!-- Google Font: Poppins & Fredoka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
            position: relative;
            background-image: url("{{ asset('assets/background.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .heading-kid {
            font-family: 'Fredoka', 'Poppins', sans-serif;
        }

        /* Tombol Kembali ke Beranda di Pojok Kiri Atas */
        .btn-back-home {
            position: absolute;
            top: 20px;
            left: 24px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 0.55rem 1.2rem;
            border-radius: 50rem;
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            z-index: 20;
        }

        .btn-back-home:hover {
            color: #ffffff;
            background: rgba(13, 110, 253, 0.6);
            border-color: #0d6efd;
            transform: translateX(-3px);
        }

        /* Overlay lembut agar background tetap terlihat dan fokus ke form */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 20, 40, 0.45);
            z-index: 0;
        }

        /* Wrapper Form */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
            margin: auto;
        }

        .login-brand-title {
            font-family: 'Fredoka', 'Poppins', sans-serif;
            font-size: 2.3rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            text-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
        }

        .login-brand-sub {
            font-size: 1.02rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.4;
        }

        /* Card Login */
        .login-card {
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
            border: 2px solid rgba(255, 255, 255, 0.8);
            padding: 2.25rem 2rem;
        }

        /* Mode Switcher Tabs */
        .role-tab-nav {
            background: #f1f5f9;
            padding: 6px;
            border-radius: 18px;
            display: flex;
            gap: 6px;
            margin-bottom: 1.75rem;
            border: 1px solid #e2e8f0;
        }

        .role-tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.65rem 0.5rem;
            border-radius: 14px;
            font-size: 0.92rem;
            font-weight: 700;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.22s ease;
            cursor: pointer;
        }

        .role-tab-btn.active {
            background: #ffffff;
            color: #1e293b;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .role-tab-btn.active.tab-siswa {
            color: #4f46e5;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.15);
        }

        /* Form Labels & Inputs */
        .form-label-custom {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.45rem;
            display: block;
        }

        .form-control-custom {
            font-size: 1.05rem;
            padding: 0.8rem 1.15rem;
            border-radius: 14px;
            border: 2px solid #cbd5e1;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .form-control-custom:focus {
            background: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }

        /* Visual PIN Dots Indicator */
        .pin-dots-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin: 1rem 0 1.25rem;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 18px;
            border: 2px dashed #cbd5e1;
        }

        .pin-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 3px solid #94a3b8;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .pin-dot.filled {
            background: #4f46e5;
            border-color: #4f46e5;
            transform: scale(1.15);
            box-shadow: 0 0 12px rgba(79, 70, 229, 0.5);
        }

        /* Keypad PIN Virtual Ramah Sentuhan (Mobile 390px & Web 1366px) */
        .pin-keypad-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 1.25rem;
        }

        .btn-pin-key {
            height: 64px;
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            background: #ffffff;
            color: #1e293b;
            font-size: 1.6rem;
            font-weight: 700;
            font-family: 'Fredoka', 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transition: all 0.12s ease;
        }

        .btn-pin-key:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
        }

        .btn-pin-key:active,
        .btn-pin-key.key-pressed {
            transform: scale(0.92);
            background: #eef2ff;
            border-color: #6366f1;
            color: #4f46e5;
            box-shadow: 0 1px 3px rgba(79, 70, 229, 0.2);
        }

        .btn-pin-action {
            font-size: 1.15rem;
            background: #f1f5f9;
            color: #475569;
        }

        .btn-pin-action:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-pin-clear {
            color: #ef4444;
            background: #fef2f2;
            border-color: #fee2e2;
        }

        .btn-pin-clear:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Grid Avatar Siswa Ramah Anak (Pilih Profil) */
        .siswa-avatar-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 1.15rem;
            max-height: 290px;
            overflow-y: auto;
            padding: 4px;
        }

        @media (max-width: 480px) {
            .siswa-avatar-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }

        .siswa-avatar-card {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 12px 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            user-select: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        .siswa-avatar-card:hover {
            transform: translateY(-4px) scale(1.03);
            border-color: #6366f1;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.22);
        }

        .siswa-avatar-card:active {
            transform: scale(0.96);
        }

        .siswa-avatar-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease;
        }

        .siswa-avatar-card:hover .siswa-avatar-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .siswa-avatar-name {
            font-family: 'Fredoka', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            color: #1e293b;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .siswa-avatar-class {
            font-size: 0.75rem;
            color: #64748b;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 50rem;
            font-weight: 600;
        }

        /* Header Profil Terpilih */
        .selected-student-pill {
            background: #f8fafc;
            border: 2px solid #e0e7ff;
            border-radius: 20px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        /* Tombol Submit */
        .btn-login-siswa {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #ffffff;
            font-family: 'Fredoka', 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.25rem;
            padding: 0.85rem 1.5rem;
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
            transition: all 0.2s ease;
            width: 100%;
            cursor: pointer;
        }

        .btn-login-siswa:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(79, 70, 229, 0.45);
        }

        .btn-login-submit {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 700;
            font-size: 1.08rem;
            padding: 0.8rem 1.5rem;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(13, 110, 253, 0.35);
            transition: all 0.2s ease;
            color: #fff;
            width: 100%;
        }

        .btn-login-submit:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-2px);
        }

        /* Mobile Adjustments (390px) */
        @media (max-width: 576px) {
            body {
                padding: 1.25rem 0.85rem;
            }

            .login-card {
                padding: 1.6rem 1.25rem;
                border-radius: 22px;
            }

            .login-brand-title {
                font-size: 1.85rem;
            }

            .btn-pin-key {
                height: 58px;
                font-size: 1.5rem;
            }

            .btn-back-home {
                top: 12px;
                left: 12px;
                padding: 0.45rem 0.9rem;
                font-size: 0.82rem;
            }
        }
    </style>
</head>

<body>
    <!-- Tombol Kembali ke Beranda -->
    <a href="{{ route('home') }}" class="btn-back-home" title="Kembali ke Beranda">
        <i class="bi bi-arrow-left"></i>
        <span>Beranda</span>
    </a>

    <div class="login-wrapper">

        <!-- Header Besar di Atas Card -->
        <div class="text-center text-white mb-3">
            <h1 class="login-brand-title mb-1">RACER ROBOTIC</h1>
            <p class="login-brand-sub mb-0">Platform Edukasi STEM, AI & Robotika</p>
        </div>

        <!-- Card Login Multi-Mode -->
        <div class="login-card">

            @php
                // Default ke Login Staf/Pengajar/Wali (Username & Password).
                // Mode Siswa aktif jika ada query ?role=siswa, submit sebelumnya dari mode siswa, atau logout dari akun siswa.
                $isSiswaMode = (request('role') === 'siswa' || old('login_mode') === 'siswa' || session('logout_role') === 'Siswa') && (old('username') !== 'admin');
            @endphp

            <!-- Switcher Tab: Pengajar & Wali vs Siswa (PIN) -->
            <div class="role-tab-nav" role="tablist">
                <button type="button" class="role-tab-btn {{ !$isSiswaMode ? 'active' : '' }}" id="tabBtnGeneral" onclick="switchLoginTab('general')">
                    <i class="bi bi-person-badge"></i>
                    <span>Pengajar & Wali</span>
                </button>
                <button type="button" class="role-tab-btn tab-siswa {{ $isSiswaMode ? 'active' : '' }}" id="tabBtnSiswa" onclick="switchLoginTab('siswa')">
                    <i class="bi bi-robot"></i>
                    <span>Siswa (PIN Anak)</span>
                </button>
            </div>

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="alert alert-success py-2 px-3 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size: 0.92rem;">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size: 0.92rem;">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger flex-shrink-0"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <!-- ================= TAB 1: FORM LOGIN UTAMA (PENGAJAR, WALI, ADMIN, TUTOR) ================= -->
            <div id="sectionLoginGeneral" style="{{ $isSiswaMode ? 'display: none;' : 'display: block;' }}">
                <div class="text-center mb-3">
                    <h2 class="login-title" style="font-size: 1.55rem; font-weight: 700; color: #0f172a;">Masuk Akun</h2>
                    <p class="login-subtitle mb-0 text-muted" style="font-size: 0.88rem;">Masukkan username/email dan password Anda</p>
                </div>

                <form method="POST" action="{{ route('login') }}" id="formLoginGeneral">
                    @csrf
                    <input type="hidden" name="login_mode" value="general">

                    <!-- Input Username / No WhatsApp -->
                    <div class="mb-3">
                        <label for="generalUsername" class="form-label-custom mb-1">Username / No. WhatsApp / Email</label>
                        <input type="text" class="form-control form-control-custom @error('username') is-invalid @enderror"
                            id="generalUsername" name="username" value="{{ old('username') }}"
                            placeholder="Username atau no. WhatsApp (08...)" required autofocus>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">
                            <i class="bi bi-info-circle me-1"></i> Wali murid dapat masuk menggunakan nomor WhatsApp terdaftar.
                        </small>
                    </div>

                    <!-- Input Password -->
                    <div class="mb-3">
                        <label for="generalPassword" class="form-label-custom">Password</label>
                        <input type="password" class="form-control form-control-custom" id="generalPassword" name="password"
                            placeholder="Masukkan password akun" required>
                    </div>

                    <!-- Tombol Masuk General -->
                    <button type="submit" class="btn btn-login-submit mt-1">
                        Masuk
                    </button>

                    <!-- Lupa Password -->
                    <div class="text-center mt-3 pt-1">
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-muted login-link" style="font-size: 0.92rem;">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Daftar Akun Tutor -->
                    <div class="text-center mt-3 pt-3 border-top">
                        <span class="text-muted" style="font-size: 0.92rem;">Ingin mengajar? </span>
                        <a href="{{ route('tutor.register') }}" class="text-decoration-none fw-bold text-primary ms-1" style="font-size: 0.92rem;">
                            Daftar Akun Tutor
                        </a>
                    </div>
                </form>
            </div>

            <!-- ================= TAB 2: FORM LOGIN SISWA (PILIH PROFIL AVATAR + PIN ANGKA) ================= -->
            <div id="sectionLoginSiswa" style="{{ !$isSiswaMode ? 'display: none;' : 'display: block;' }}">
                @php
                    $avatarPresets = [
                        ['emoji' => '🤖', 'bg' => '#eef2ff', 'border' => '#6366f1'],
                        ['emoji' => '🚀', 'bg' => '#fff1f2', 'border' => '#f43f5e'],
                        ['emoji' => '🦁', 'bg' => '#fffbeb', 'border' => '#f59e0b'],
                        ['emoji' => '🦊', 'bg' => '#fff7ed', 'border' => '#f97316'],
                        ['emoji' => '🌟', 'bg' => '#fefce8', 'border' => '#eab308'],
                        ['emoji' => '🏎️', 'bg' => '#eff6ff', 'border' => '#3b82f6'],
                        ['emoji' => '🦖', 'bg' => '#f0fdf4', 'border' => '#22c55e'],
                        ['emoji' => '🦄', 'bg' => '#faf5ff', 'border' => '#a855f7'],
                        ['emoji' => '👾', 'bg' => '#ecfeff', 'border' => '#06b6d4'],
                        ['emoji' => '🐼', 'bg' => '#f8fafc', 'border' => '#64748b'],
                    ];
                    $activeStudents = $siswaList ?? (\Illuminate\Support\Facades\Schema::hasTable('siswa') 
                        ? \Illuminate\Support\Facades\DB::table('siswa')
                            ->join('pengguna', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
                            ->where('pengguna.role', 'Siswa')
                            ->where('siswa.status_siswa', 'Aktif')
                            ->where('pengguna.status_aktif', 1)
                            ->select('siswa.id_siswa', 'siswa.nama_lengkap', 'siswa.nama_panggilan', 'siswa.jenjang', 'siswa.kelas', 'pengguna.username')
                            ->orderBy('siswa.nama_panggilan')
                            ->get() 
                        : collect());

                    $oldStudentUsername = (old('login_mode') === 'siswa' && old('username') !== 'admin') ? old('username') : '';
                    $hasInitialProfile = !empty($oldStudentUsername);
                    $selectedStudentName = 'Siswa';
                    $selectedStudentAvatar = '🤖';
                    $selectedStudentBg = '#eef2ff';
                    $selectedStudentBorder = '#6366f1';

                    foreach ($activeStudents as $idx => $st) {
                        if ($st->username === $oldStudentUsername) {
                            $selectedStudentName = $st->nama_panggilan ?: explode(' ', $st->nama_lengkap)[0];
                            $preset = $avatarPresets[$idx % count($avatarPresets)];
                            $selectedStudentAvatar = $preset['emoji'];
                            $selectedStudentBg = $preset['bg'];
                            $selectedStudentBorder = $preset['border'];
                            break;
                        }
                    }
                @endphp

                <!-- LANGKAH 1: PILIH PROFIL / AVATAR SISWA (RAMAH ANAK TK & SD) -->
                <div id="stepChooseProfile" style="{{ $hasInitialProfile ? 'display: none;' : 'display: block;' }}">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center p-2 rounded-circle mb-2"
                            style="background: #eef2ff; width: 56px; height: 56px; font-size: 1.8rem;">
                            🤖
                        </div>
                        <h2 class="heading-kid fw-bold mb-1 text-dark" style="font-size: 1.55rem;">Siapa Sobat Robotik?</h2>
                        <p class="text-muted mb-0" style="font-size: 0.88rem;">Sentuh foto atau karaktermu untuk mulai belajar ya! 🌟</p>
                    </div>

                    <div class="siswa-avatar-grid" id="avatarGrid">
                        @forelse ($activeStudents as $index => $s)
                            @php
                                $preset = $avatarPresets[$index % count($avatarPresets)];
                                $namaDisplay = $s->nama_panggilan ?: explode(' ', $s->nama_lengkap)[0];
                                $jenjangText = $s->jenjang ?: 'Siswa';
                                if ($s->kelas) $jenjangText .= ' ' . $s->kelas;
                            @endphp
                            <div class="siswa-avatar-card" 
                                onclick="selectSiswaProfile('{{ $s->username }}', '{{ addslashes($namaDisplay) }}', '{{ $preset['emoji'] }}', '{{ $preset['bg'] }}', '{{ $preset['border'] }}')">
                                <div class="siswa-avatar-icon" style="background: {{ $preset['bg'] }}; border: 2px solid {{ $preset['border'] }};">
                                    {{ $preset['emoji'] }}
                                </div>
                                <span class="siswa-avatar-name">{{ $namaDisplay }}</span>
                                <span class="siswa-avatar-class">{{ $jenjangText }}</span>
                            </div>
                        @empty
                            <div class="col-12 text-center py-3 text-muted">
                                <p class="mb-2">Belum ada profil siswa aktif terdaftar.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Opsi Tambahan jika nama belum ada di grid -->
                    <div class="text-center mt-2">
                        <button type="button" class="btn btn-sm btn-link text-muted text-decoration-none" style="font-size: 0.82rem;" onclick="enableManualInput()">
                            <i class="bi bi-keyboard me-1"></i>Nama belum ada di layar? Ketik manual
                        </button>
                    </div>
                </div>

                <!-- LANGKAH 2: MASUKKAN PIN 6 ANGKA MELALUI KEYPAD BESAR -->
                <div id="stepEnterPin" style="{{ $hasInitialProfile ? 'display: block;' : 'display: none;' }}">
                    <!-- Badge Info Profil Siswa yang Terpilih -->
                    <div class="selected-student-pill">
                        <div id="selectedAvatarIcon" class="siswa-avatar-icon mb-0" 
                            style="width: 48px; height: 48px; font-size: 1.7rem; background: {{ $selectedStudentBg }}; border: 2px solid {{ $selectedStudentBorder }};">
                            {{ $selectedStudentAvatar }}
                        </div>
                        <div class="flex-grow-1 text-start overflow-hidden">
                            <div class="text-muted" style="font-size: 0.75rem;">Sobat Robotik:</div>
                            <div class="fw-bold text-dark heading-kid text-truncate" id="selectedAvatarName" style="font-size: 1.15rem;">
                                {{ $selectedStudentName }}
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" onclick="backToProfileChooser()" style="font-size: 0.78rem;" title="Ganti Profil Siswa">
                            <i class="bi bi-arrow-repeat me-1"></i>Ganti
                        </button>
                    </div>

                    <div class="text-center mb-2">
                        <h3 class="heading-kid fw-bold mb-1 text-dark" style="font-size: 1.35rem;">Ketik PIN Rahasiamu</h3>
                        <p class="text-muted mb-0" style="font-size: 0.84rem;">Tekan 6 angka PIN rahasiamu di tombol bawah ya! 👇</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="formLoginSiswa">
                        @csrf
                        <input type="hidden" name="login_mode" value="siswa">
                        <input type="hidden" name="username" id="siswaUsername" value="{{ $oldStudentUsername }}">
                        <input type="hidden" name="avatar" id="siswaAvatarInput" value="{{ $selectedStudentAvatar }}">
                        <input type="hidden" name="password" id="siswaPinInput" value="">
                        <input type="hidden" name="pin" id="siswaPinAliasInput" value="">

                        <!-- Fallback Input Username Manual jika diperlukan -->
                        <div id="manualUsernameWrapper" style="display: none;" class="mb-3">
                            <label for="manualUsernameInput" class="form-label-custom">Username / ID Siswa</label>
                            <input type="text" class="form-control form-control-custom" id="manualUsernameInput" placeholder="Ketik username siswa" oninput="syncManualUsername(this.value)">
                        </div>

                        <!-- Indikator 6 Bulatan PIN -->
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label-custom mb-0">
                                    <i class="bi bi-key-fill text-warning me-1"></i>PIN Rahasia (6 Angka)
                                </label>
                                <small class="text-muted" id="pinLengthLabel">0 / 6 Angka</small>
                            </div>
                            <div class="pin-dots-container" id="pinDotsWrapper" title="Indikator 6 Digit PIN">
                                <div class="pin-dot" id="dot-0"></div>
                                <div class="pin-dot" id="dot-1"></div>
                                <div class="pin-dot" id="dot-2"></div>
                                <div class="pin-dot" id="dot-3"></div>
                                <div class="pin-dot" id="dot-4"></div>
                                <div class="pin-dot" id="dot-5"></div>
                            </div>
                        </div>

                        <!-- Keypad PIN Virtual Ramah Sentuhan (Grid 3x4) -->
                        <div class="pin-keypad-grid" id="pinKeypad">
                            <button type="button" class="btn-pin-key" data-key="1">1</button>
                            <button type="button" class="btn-pin-key" data-key="2">2</button>
                            <button type="button" class="btn-pin-key" data-key="3">3</button>
                            <button type="button" class="btn-pin-key" data-key="4">4</button>
                            <button type="button" class="btn-pin-key" data-key="5">5</button>
                            <button type="button" class="btn-pin-key" data-key="6">6</button>
                            <button type="button" class="btn-pin-key" data-key="7">7</button>
                            <button type="button" class="btn-pin-key" data-key="8">8</button>
                            <button type="button" class="btn-pin-key" data-key="9">9</button>
                            <button type="button" class="btn-pin-key btn-pin-action" data-action="backspace" title="Hapus Satu Digit">
                                <i class="bi bi-backspace-fill"></i>
                            </button>
                            <button type="button" class="btn-pin-key" data-key="0">0</button>
                            <button type="button" class="btn-pin-key btn-pin-action btn-pin-clear" data-action="clear" title="Bersihkan Semua">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <!-- Tombol Masuk Siswa -->
                        <button type="submit" class="btn-login-siswa mt-2" id="btnSubmitSiswa">
                            <i class="bi bi-rocket-takeoff-fill me-2"></i>Mulai Petualangan 🚀
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted d-block" style="font-size: 0.82rem;">
                                <i class="bi bi-info-circle me-1"></i>Lupa PIN? Minta bantuan Ayah atau Bunda ya! 😊
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Link Kembali ke Beranda -->
            <div class="text-center mt-3 pt-2">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1" style="font-size: 0.88rem;">
                    <i class="bi bi-house-door"></i> Kembali ke Beranda Utama
                </a>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Keypad PIN & Tab Switcher (Ramah Anak & Mobile-First) -->
    <script>
        // State PIN Siswa
        let studentPin = '';
        const maxPinLength = 6;

        const pinHiddenInput = document.getElementById('siswaPinInput');
        const pinAliasInput = document.getElementById('siswaPinAliasInput');
        const pinLengthLabel = document.getElementById('pinLengthLabel');
        const btnSubmitSiswa = document.getElementById('btnSubmitSiswa');

        // Fungsi Update Visual Indikator PIN
        function updatePinVisual() {
            for (let i = 0; i < maxPinLength; i++) {
                const dot = document.getElementById(`dot-${i}`);
                if (dot) {
                    if (i < studentPin.length) {
                        dot.classList.add('filled');
                    } else {
                        dot.classList.remove('filled');
                    }
                }
            }

            if (pinLengthLabel) {
                pinLengthLabel.textContent = `${studentPin.length} / ${maxPinLength} Angka`;
                if (studentPin.length === maxPinLength) {
                    pinLengthLabel.classList.add('text-success', 'fw-bold');
                } else {
                    pinLengthLabel.classList.remove('text-success', 'fw-bold');
                }
            }

            if (pinHiddenInput) pinHiddenInput.value = studentPin;
            if (pinAliasInput) pinAliasInput.value = studentPin;
        }

        // Tambah Angka PIN
        function addPinDigit(digit) {
            if (studentPin.length < maxPinLength) {
                studentPin += digit;
                updatePinVisual();
            }
        }

        // Hapus Satu Angka
        function removePinDigit() {
            if (studentPin.length > 0) {
                studentPin = studentPin.slice(0, -1);
                updatePinVisual();
            }
        }

        // Bersihkan Semua PIN
        function clearAllPin() {
            studentPin = '';
            updatePinVisual();
        }

        // Event Listener untuk Tombol Virtual Keypad
        document.querySelectorAll('.btn-pin-key').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const key = this.getAttribute('data-key');
                const action = this.getAttribute('data-action');

                // Efek tactile click
                this.classList.add('key-pressed');
                setTimeout(() => this.classList.remove('key-pressed'), 120);

                if (key !== null) {
                    addPinDigit(key);
                } else if (action === 'backspace') {
                    removePinDigit();
                } else if (action === 'clear') {
                    clearAllPin();
                }
            });
        });

        // Dukungan Hardware Keyboard (Numpad & Angka 0-9)
        window.addEventListener('keydown', function(e) {
            const siswaSection = document.getElementById('sectionLoginSiswa');
            if (!siswaSection || siswaSection.style.display === 'none') {
                return;
            }

            // Jangan tangkap jika sedang mengetik di input username
            if (document.activeElement && document.activeElement.id === 'siswaUsername') {
                return;
            }

            if (e.key >= '0' && e.key <= '9') {
                e.preventDefault();
                addPinDigit(e.key);

                // Highlight tombol keypad virtual
                const targetBtn = document.querySelector(`.btn-pin-key[data-key="${e.key}"]`);
                if (targetBtn) {
                    targetBtn.classList.add('key-pressed');
                    setTimeout(() => targetBtn.classList.remove('key-pressed'), 140);
                }
            } else if (e.key === 'Backspace') {
                e.preventDefault();
                removePinDigit();
            } else if (e.key === 'Escape' || e.key === 'Delete') {
                e.preventDefault();
                clearAllPin();
            }
        });

        // Validasi Submit Form Siswa
        document.getElementById('formLoginSiswa').addEventListener('submit', function(e) {
            const userVal = document.getElementById('siswaUsername').value;
            if (!userVal) {
                e.preventDefault();
                alert('Silakan pilih profil kamu terlebih dahulu ya! 😊');
                backToProfileChooser();
                return;
            }

            if (studentPin.length < maxPinLength) {
                e.preventDefault();
                alert('Silakan masukkan 6 angka PIN rahasiamu terlebih dahulu ya! 😊');
                // Beri efek getar visual pada wrapper dots
                const wrapper = document.getElementById('pinDotsWrapper');
                if (wrapper) {
                    wrapper.style.borderColor = '#ef4444';
                    wrapper.style.background = '#fef2f2';
                    setTimeout(() => {
                        wrapper.style.borderColor = '#cbd5e1';
                        wrapper.style.background = '#f8fafc';
                    }, 1200);
                }
            }
        });

        // Switcher Profil Siswa & Masukkan PIN (Khan Academy Kids / Netflix Style)
        function selectSiswaProfile(username, displayName, emoji, bg, border) {
            document.getElementById('siswaUsername').value = username;
            document.getElementById('selectedAvatarName').textContent = displayName;
            const inputAvatar = document.getElementById('siswaAvatarInput');
            if (inputAvatar) inputAvatar.value = emoji;
            const avatarEl = document.getElementById('selectedAvatarIcon');
            if (avatarEl) {
                avatarEl.textContent = emoji;
                avatarEl.style.background = bg;
                avatarEl.style.borderColor = border;
            }
            document.getElementById('stepChooseProfile').style.display = 'none';
            document.getElementById('stepEnterPin').style.display = 'block';
            clearAllPin();
        }

        function backToProfileChooser() {
            document.getElementById('stepEnterPin').style.display = 'none';
            document.getElementById('stepChooseProfile').style.display = 'block';
            document.getElementById('manualUsernameWrapper').style.display = 'none';
            clearAllPin();
        }

        function enableManualInput() {
            document.getElementById('stepChooseProfile').style.display = 'none';
            document.getElementById('stepEnterPin').style.display = 'block';
            document.getElementById('manualUsernameWrapper').style.display = 'block';
            document.getElementById('selectedAvatarName').textContent = 'Ketik Username';
            const manualInput = document.getElementById('manualUsernameInput');
            if (manualInput) {
                manualInput.focus();
            }
        }

        function syncManualUsername(val) {
            document.getElementById('siswaUsername').value = val;
            document.getElementById('selectedAvatarName').textContent = val || 'Siswa';
        }

        // Switcher Tab Login
        function switchLoginTab(mode) {
            const secSiswa = document.getElementById('sectionLoginSiswa');
            const secGeneral = document.getElementById('sectionLoginGeneral');
            const btnSiswa = document.getElementById('tabBtnSiswa');
            const btnGeneral = document.getElementById('tabBtnGeneral');

            if (mode === 'siswa') {
                secSiswa.style.display = 'block';
                secGeneral.style.display = 'none';
                btnSiswa.classList.add('active');
                btnGeneral.classList.remove('active');
            } else {
                secSiswa.style.display = 'none';
                secGeneral.style.display = 'block';
                btnSiswa.classList.remove('active');
                btnGeneral.classList.add('active');
                const genUser = document.getElementById('generalUsername');
                if (genUser) genUser.focus();
            }
        }

        // Mencegah cache browser (bfcache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>

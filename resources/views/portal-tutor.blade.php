<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Tutor - RACER ROBOTIC Administrasi Akademik</title>
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
            --sidebar-width: 255px;
            --primary-blue: #1e5ee6;
            --primary-hover: #164ec4;
            --bg-canvas: #f4f6fa;
            --card-border: #e6ebf3;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-canvas);
            font-family: var(--bs-body-font-family);
            color: var(--text-dark);
            overflow-x: hidden;
            font-size: 0.9rem;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1030;
            padding: 1.25rem 1rem 1rem 1rem;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.show {
                margin-left: 0;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }

            .main-wrapper {
                margin-left: 0;
            }
        }

        /* Brand Header */
        .brand-logo {
            width: 38px;
            height: 38px;
            background: var(--primary-blue);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.3rem;
            box-shadow: 0 4px 10px rgba(30, 94, 230, 0.25);
        }

        .sidebar-heading {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #94a3b8;
            text-transform: uppercase;
            padding: 0 0.75rem;
            margin-top: 1.2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Nav Links */
        .nav-link-tutor {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.6rem 0.85rem;
            color: #64748b;
            font-weight: 500;
            font-size: 0.88rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 0.2rem;
        }

        .nav-link-tutor:hover {
            color: var(--primary-blue);
            background-color: #f1f5fd;
        }

        .nav-link-tutor.active {
            color: var(--primary-blue);
            background-color: #eaf0fd;
            font-weight: 600;
        }

        .nav-link-tutor i {
            font-size: 1.15rem;
        }

        /* Support Box */
        .support-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 0.9rem;
            border: 1px solid #e5e9f2;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        /* Top Bar */
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #eef2f6;
            padding: 0.85rem 2rem;
        }

        .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.84rem;
        }

        .breadcrumb-item.active {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.84rem;
        }

        /* Avatar Initials */
        .avatar-initials {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #e0edff;
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Stat Cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 1.2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .stat-number {
            font-size: 1.95rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }

        /* Class Schedule Items */
        .schedule-item {
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 1.1rem;
            margin-bottom: 0.85rem;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .schedule-item.active-schedule {
            background-color: #f6f9ff;
            border: 1.5px solid #bdd7fe;
            box-shadow: 0 2px 8px rgba(30, 94, 230, 0.06);
        }

        .badge-level {
            background-color: #eef4ff;
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 0.74rem;
            padding: 0.3rem 0.55rem;
            border-radius: 6px;
        }

        /* Material Resource Card */
        .material-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .material-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.04);
        }

        .material-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #eef4ff;
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Info Akademik Box */
        .info-akademik-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 1.35rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar">
            <div class="flex-grow-1">
                <!-- Brand Header -->
                <div class="d-flex align-items-center gap-2 mb-3 px-1">
                    <div class="brand-logo">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.2px; font-size: 0.95rem;">RACER
                            ROBOTIC</h6>
                        <small class="text-muted" style="font-size: 0.72rem;">Administrasi Akademik</small>
                    </div>
                </div>

                <!-- Navigation Links -->
                <span class="sidebar-heading">MENU PENGAJAR</span>
                <nav class="nav flex-column">
                        <a href="{{ route('tutor', ['tab' => 'beranda']) }}"
                            class="nav-link-tutor {{ ($activeTab ?? 'beranda') === 'beranda' ? 'active' : '' }}">
                            <i class="bi bi-house-door-fill"></i>
                            <span>Beranda Tutor</span>
                        </a>
                            <a href="{{ ($activeTab ?? 'beranda') === 'beranda' ? '#jadwalSection' : route('tutor', ['tab' => 'beranda']) . '#jadwalSection' }}"
                                class="nav-link-tutor">
                                <i class="bi bi-calendar3"></i>
                                <span>Jadwal Kelas Saya</span>
                            </a>
                            <a href="#" class="nav-link-tutor" data-bs-toggle="modal" data-bs-target="#modalPresensi">
                                <i class="bi bi-check2-square"></i>
                                <span>Input Presensi Sesi</span>
                            </a>
                                <a href="{{ ($activeTab ?? 'beranda') === 'beranda' ? '#materiSection' : route('tutor', ['tab' => 'beranda']) . '#materiSection' }}"
                                    class="nav-link-tutor">
                                    <i class="bi bi-book"></i>
                                    <span>Materi & Modul</span>
                                </a>
                                <a href="#" class="nav-link-tutor" data-bs-toggle="modal" data-bs-target="#modalRapor">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>E-Rapor</span>
                                </a>
                                    <a href="{{ route('tutor', ['tab' => 'sandi']) }}"
                                        class="nav-link-tutor mt-3 {{ ($activeTab ?? '') === 'sandi' ? 'active' : '' }}">
                                        <i class="bi bi-shield-lock"></i>
                                        <span>Pengaturan Sandi</span>
                                    </a>
                </nav>
            </div>

            <!-- Bottom Support & Profile -->
            <div>
                <!-- Support Card -->
                <div class="support-card mb-3">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <i class="bi bi-question-circle text-muted fs-6 mt-1"></i>
                        <div>
                            <span class="fw-semibold text-dark" style="font-size: 0.8rem;">Butuh bantuan?</span>
                            <p class="text-muted mb-2" style="font-size: 0.72rem; line-height: 1.35;">
                                Tim akademik siap membantu kegiatan mengajar Anda.
                            </p>
                            <a href="https://wa.me/6281234567890" target="_blank"
                                class="text-primary fw-semibold text-decoration-none" style="font-size: 0.74rem;">
                                Hubungi admin <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tutor Profile Card -->
                <div
                    class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light border border-light-subtle">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-initials">
                            {{ strtoupper(substr(Auth::user()->name ?? 'Ahmad Fauzi', 0, 2)) }}
                        </div>
                        <div style="line-height: 1.2;">
                            <span class="fw-bold d-block text-dark text-truncate"
                                style="max-width: 120px; font-size: 0.82rem;">
                                {{ $tutorProfile->nama_lengkap ?? Auth::user()->name ?? 'Ahmad Fauzi, S.T.' }}
                            </span>
                            <small class="text-muted"
                                style="font-size: 0.7rem;">{{ $tutorProfile->jenjang_modul ?? 'Tutor • Level 3' }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-link text-muted p-1 text-decoration-none" title="Keluar" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiLogout">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="main-wrapper flex-grow-1">
            <!-- Top Navbar -->
            <header class="top-navbar d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light d-lg-none border" id="toggleSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 align-items-center">
                            <li class="breadcrumb-item"><i class="bi bi-house-door me-1 text-muted"></i><a
                                    href="{{ route('tutor') }}">Portal Tutor</a></li>
                            @if (($activeTab ?? 'beranda') === 'sandi')
                                <li class="breadcrumb-item active" aria-current="page">Pengaturan Sandi</li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">Beranda Tutor</li>
                            @endif
                        </ol>
                    </nav>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted fw-medium d-flex align-items-center gap-1" style="font-size: 0.82rem;">
                        <i class="bi bi-geo-alt text-primary"></i> {{ $tutorProfile->penempatan ?? 'Robotics Hub' }}
                    </span>
                    <button class="btn btn-light position-relative p-1 rounded-circle border-0 text-muted"
                        style="width: 34px; height: 34px;">
                        <i class="bi bi-bell fs-6"></i>
                        <span
                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>
                    <div class="dropdown">
                        <button
                            class="btn btn-light bg-white border rounded-pill d-flex align-items-center gap-2 py-1 px-2"
                            data-bs-toggle="dropdown">
                            <div class="avatar-initials" style="width: 26px; height: 26px; font-size: 0.7rem;">
                                {{ strtoupper(substr($tutorProfile->nama_panggilan ?? Auth::user()->name ?? 'AF', 0, 2)) }}
                            </div>
                            <span class="fw-semibold text-dark"
                                style="font-size: 0.82rem;">{{ $tutorProfile->nama_panggilan ?? Auth::user()->name ?? 'Ahmad Fauzi' }}</span>
                            <i class="bi bi-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3"
                            style="font-size: 0.85rem;">
                            <li><a class="dropdown-item" href="#modalGantiSandi" data-bs-toggle="modal"><i
                                        class="bi bi-gear me-2"></i>Pengaturan Akun</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <button type="button" class="dropdown-item text-danger d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiLogout">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Dashboard Body -->
            <main class="p-3 p-lg-4">
                @if (($activeTab ?? 'beranda') === 'sandi')
                    <!-- ================= PANEL 4: UBAH KATA SANDI AKUN ================= -->
                    <div class="d-flex justify-content-center py-4">
                        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5"
                            style="max-width: 520px; width: 100%; background: #ffffff;">
                            <h4 class="fw-bold text-dark mb-1">Ubah Kata Sandi Akun</h4>
                            <p class="text-muted mb-4" style="font-size: 0.86rem;">
                                Perbarui kata sandi secara mandiri untuk menjaga keamanan akun Anda.
                            </p>

                            @if (session('success'))
                                <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 rounded-3 mb-3"
                                    style="font-size: 0.84rem;">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <div>{{ session('success') }}</div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger py-2 px-3 rounded-3 mb-3" style="font-size: 0.84rem;">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('tutor.password.update') }}">
                                @csrf

                                <!-- Kata Sandi Saat Ini / Sementara -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 0.82rem;">Kata Sandi Saat Ini /
                                        Sementara <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" id="inputCurrentPass" name="current_password"
                                            class="form-control rounded-3 pe-5" placeholder="••••••••••••" required>
                                        <button type="button"
                                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none"
                                            onclick="togglePassPortal('inputCurrentPass', 'iconCurrentPass')">
                                            <i class="bi bi-eye" id="iconCurrentPass"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Kata Sandi Baru -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 0.82rem;">Kata Sandi Baru <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" id="inputNewPass" name="password"
                                            class="form-control rounded-3 pe-5" placeholder="••••••••••••" required>
                                        <button type="button"
                                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none"
                                            onclick="togglePassPortal('inputNewPass', 'iconNewPass')">
                                            <i class="bi bi-eye" id="iconNewPass"></i>
                                        </button>
                                    </div>
                                    <div class="text-muted mt-1" style="font-size: 0.74rem;">
                                        Gunakan minimal 8 karakter, dengan kombinasi huruf besar, huruf kecil, dan angka.
                                    </div>
                                </div>

                                <!-- Konfirmasi Kata Sandi Baru -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" style="font-size: 0.82rem;">Konfirmasi Kata Sandi
                                        Baru <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" id="inputConfirmPass" name="password_confirmation"
                                            class="form-control rounded-3 pe-5" placeholder="••••••••••••" required>
                                        <button type="button"
                                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none"
                                            onclick="togglePassPortal('inputConfirmPass', 'iconConfirmPass')">
                                            <i class="bi bi-eye" id="iconConfirmPass"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Alert Keamanan -->
                                <div class="p-3 mb-4 rounded-3 d-flex align-items-start gap-2"
                                    style="background-color: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; font-size: 0.82rem;">
                                    <i class="bi bi-check-circle-fill fs-6 mt-0"></i>
                                    <div>
                                        Jangan bagikan kata sandi Anda kepada siapapun, termasuk pihak yang mengatasnamakan
                                        RACER ROBOTIC.
                                    </div>
                                </div>

                                <!-- Tombol Simpan -->
                                <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold shadow-sm"
                                    style="background-color: var(--primary-blue);">
                                    Simpan Kata Sandi
                                </button>

                                <div class="text-center text-muted mt-2" style="font-size: 0.76rem;">
                                    Gunakan kata sandi baru saat masuk berikutnya.
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Greeting & Date Row -->
                    <div
                        class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">Selamat siang,
                                {{ $tutorProfile->nama_panggilan ?? Auth::user()->name ?? 'Pak Ahmad' }}!</h4>
                            <p class="text-muted mb-0" style="font-size: 0.88rem;">
                                Siap menginspirasi inovator masa depan? Ada <span class="fw-semibold text-primary">2 kelas
                                    lagi</span> hari ini.
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2 bg-white px-3 py-2 rounded-3 border text-dark fw-medium shadow-sm"
                            style="font-size: 0.84rem;">
                            <i class="bi bi-calendar3 text-muted"></i>
                            <span>Sabtu, 31 Oktober 2026</span>
                        </div>
                    </div>

                    <!-- 4 Stat Summary Cards -->
                    <div class="row g-3 mb-4">
                        <!-- Stat 1 -->
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Kelas hari ini</span>
                                    <i class="bi bi-calendar3 text-muted"></i>
                                </div>
                                <div class="d-flex align-items-baseline gap-1 mb-1">
                                    <span class="stat-number">3</span>
                                    <span class="text-muted fw-medium" style="font-size: 0.84rem;">kelas</span>
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.74rem;">1 selesai • 2 akan
                                    datang</small>
                            </div>
                        </div>
                        <!-- Stat 2 -->
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Siswa yang diajar</span>
                                    <i class="bi bi-people text-muted"></i>
                                </div>
                                <div class="d-flex align-items-baseline gap-1 mb-1">
                                    <span class="stat-number">28</span>
                                    <span class="text-muted fw-medium" style="font-size: 0.84rem;">siswa</span>
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.74rem;">Dari 3 kelompok belajar hari
                                    ini</small>
                            </div>
                        </div>
                        <!-- Stat 3 -->
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Sesi minggu ini</span>
                                    <i class="bi bi-bar-chart-line text-muted"></i>
                                </div>
                                <div class="d-flex align-items-baseline gap-1 mb-2">
                                    <span class="stat-number">8</span>
                                    <span class="text-muted fw-medium" style="font-size: 0.84rem;">/ 12 sesi</span>
                                </div>
                                <div class="progress mb-1"
                                    style="height: 6px; background-color: #e2e8f0; border-radius: 4px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 67%; background-color: var(--primary-blue);" aria-valuenow="67"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">67% kapasitas mingguan</small>
                            </div>
                        </div>
                        <!-- Stat 4 -->
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Presensi belum
                                        diinput</span>
                                    <i class="bi bi-clipboard-check text-muted"></i>
                                </div>
                                <div class="d-flex align-items-baseline gap-1 mb-1">
                                    <span class="stat-number">2</span>
                                    <span class="text-muted fw-medium" style="font-size: 0.84rem;">sesi</span>
                                </div>
                                <small class="fw-semibold d-block" style="font-size: 0.74rem; color: #ea580c;">Perlu
                                    dilengkapi hari ini</small>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Row: Schedule (Left 8 cols) & Info Akademik (Right 4 cols) -->
                    <div class="row g-3 mb-4" id="jadwalSection">
                        <!-- Left: Jadwal Kelas Hari Ini -->
                        <div class="col-12 col-lg-8">
                            <div class="bg-white p-3 p-md-4 rounded-4 border shadow-sm h-100">
                                <!-- Card Header -->
                                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.98rem;">Jadwal kelas hari ini
                                        </h6>
                                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-1"
                                            style="font-size: 0.72rem;">3 kelas</span>
                                    </div>
                                    <a href="#" class="text-primary text-decoration-none fw-semibold"
                                        style="font-size: 0.82rem;">
                                        Semua jadwal <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>

                                <!-- Schedule Items List -->
                                <div>
                                    <!-- Class 1: Selesai -->
                                    <div
                                        class="schedule-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="text-center pe-3 border-end" style="min-width: 65px;">
                                                <span class="fw-bold d-block text-dark"
                                                    style="font-size: 0.92rem;">09.00</span>
                                                <small class="text-muted" style="font-size: 0.75rem;">10.30</small>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">
                                                        Controller & AI</h6>
                                                    <span class="badge-level">L3-A • Level 3</span>
                                                </div>
                                                <p class="text-muted mb-1" style="font-size: 0.82rem;">Sesi 05 - Sensor
                                                    ultrasonik & logika kontrol</p>
                                                <div class="d-flex align-items-center gap-3 text-muted"
                                                    style="font-size: 0.75rem;">
                                                    <span><i class="bi bi-geo-alt me-1 text-primary"></i>Lab B - Robotics
                                                        Hub</span>
                                                    <span><i class="bi bi-people me-1"></i>10 siswa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 ms-auto ms-md-0">
                                            <span class="badge px-2 py-1 rounded-pill"
                                                style="background-color: #dcfce7; color: #15803d; font-size: 0.74rem;">Selesai</span>
                                            <button class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1 fw-medium"
                                                style="font-size: 0.8rem;" data-bs-toggle="modal"
                                                data-bs-target="#modalPresensi">
                                                Input presensi
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Class 2: Active / 15 Menit Lagi -->
                                    <div
                                        class="schedule-item active-schedule d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="text-center pe-3 border-end" style="min-width: 65px;">
                                                <span class="fw-bold d-block text-primary"
                                                    style="font-size: 0.92rem;">13.00</span>
                                                <small class="text-muted" style="font-size: 0.75rem;">14.30</small>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">
                                                        Controller & AI</h6>
                                                    <span class="badge-level">L3-B • Level 3</span>
                                                </div>
                                                <p class="text-muted mb-1" style="font-size: 0.82rem;">Sesi 06 - Pemrograman
                                                    robot line follower</p>
                                                <div class="d-flex align-items-center gap-3 text-muted"
                                                    style="font-size: 0.75rem;">
                                                    <span><i class="bi bi-geo-alt me-1 text-primary"></i>Lab B - Robotics
                                                        Hub</span>
                                                    <span><i class="bi bi-people me-1"></i>10 siswa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 ms-auto ms-md-0">
                                            <span class="text-primary fw-semibold" style="font-size: 0.78rem;">15 menit
                                                lagi</span>
                                            <button class="btn btn-primary btn-sm rounded-3 px-3 py-1 fw-semibold shadow-sm"
                                                style="background-color: var(--primary-blue); font-size: 0.8rem;"
                                                data-bs-toggle="modal" data-bs-target="#modalBukaKelas">
                                                Buka kelas
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Class 3: Terjadwal -->
                                    <div
                                        class="schedule-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-0">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="text-center pe-3 border-end" style="min-width: 65px;">
                                                <span class="fw-bold d-block text-dark"
                                                    style="font-size: 0.92rem;">15.00</span>
                                                <small class="text-muted" style="font-size: 0.75rem;">16.30</small>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">
                                                        Controller & AI</h6>
                                                    <span class="badge-level">L3-C • Level 3</span>
                                                </div>
                                                <p class="text-muted mb-1" style="font-size: 0.82rem;">Sesi 03 - Pengenalan
                                                    machine learning</p>
                                                <div class="d-flex align-items-center gap-3 text-muted"
                                                    style="font-size: 0.75rem;">
                                                    <span><i class="bi bi-geo-alt me-1 text-primary"></i>Lab B - Robotics
                                                        Hub</span>
                                                    <span><i class="bi bi-people me-1"></i>8 siswa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 ms-auto ms-md-0">
                                            <span class="badge px-2 py-1 rounded-pill bg-light text-secondary border"
                                                style="font-size: 0.74rem;">Terjadwal</span>
                                            <button class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1 fw-medium"
                                                style="font-size: 0.8rem;" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailSesi">
                                                Detail sesi
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alert Footer -->
                                <div class="mt-3 pt-2 text-muted d-flex align-items-center gap-2"
                                    style="font-size: 0.78rem;">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <span>Pastikan kit robot dan modul siap 10 menit sebelum kelas dimulai.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Info Akademik Card -->
                        <div class="col-12 col-lg-4">
                            <div class="info-akademik-card">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary"
                                            style="width: 32px; height: 32px;">
                                            <i class="bi bi-megaphone-fill" style="font-size: 0.88rem;"></i>
                                        </div>
                                        <span class="text-primary fw-bold text-uppercase"
                                            style="font-size: 0.76rem; letter-spacing: 0.5px;">INFO AKADEMIK</span>
                                    </div>
                                    <p class="text-dark fw-medium mb-3" style="font-size: 0.88rem; line-height: 1.5;">
                                        Modul Controller & AI v2.4 tersedia. Gunakan untuk sesi pekan depan.
                                    </p>
                                </div>
                                <div class="pt-3 border-top">
                                    <a href="#materiSection"
                                        class="text-primary text-decoration-none fw-semibold d-inline-flex align-items-center gap-1"
                                        style="font-size: 0.8rem;">
                                        Lihat panduan pengajaran <i class="bi bi-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Section: Materi untuk Kelas Anda -->
                    <div class="bg-white p-3 p-md-4 rounded-4 border shadow-sm mb-4" id="materiSection">
                        <div
                            class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.98rem;">Materi untuk kelas Anda</h6>
                                <span class="text-muted" style="font-size: 0.82rem;">Controller & AI • Level 3</span>
                            </div>
                            <a href="#" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.82rem;">
                                Buka pustaka materi <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <div class="row g-3">
                            <!-- Material 1 -->
                            <div class="col-12 col-md-4">
                                <div class="material-card">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="material-icon-box">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div>
                                            <span class="text-primary fw-bold d-block text-uppercase"
                                                style="font-size: 0.65rem; letter-spacing: 0.6px;">MODUL AJAR</span>
                                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.88rem;">Robot line
                                                follower</h6>
                                            <small class="text-muted" style="font-size: 0.72rem;">Modul sesi 06 • PDF • 2,4
                                                MB</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-light rounded-circle p-2 text-muted border-0 shadow-none"
                                        title="Download Materi">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Material 2 -->
                            <div class="col-12 col-md-4">
                                <div class="material-card">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="material-icon-box">
                                            <i class="bi bi-pencil-square"></i>
                                        </div>
                                        <div>
                                            <span class="text-primary fw-bold d-block text-uppercase"
                                                style="font-size: 0.65rem; letter-spacing: 0.6px;">LEMBAR KERJA</span>
                                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.88rem;">Worksheet logika
                                                kontrol</h6>
                                            <small class="text-muted" style="font-size: 0.72rem;">Latihan siswa • PDF • 840
                                                KB</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-light rounded-circle p-2 text-muted border-0 shadow-none"
                                        title="Download Worksheet">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Material 3 -->
                            <div class="col-12 col-md-4">
                                <div class="material-card">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="material-icon-box">
                                            <i class="bi bi-cpu"></i>
                                        </div>
                                        <div>
                                            <span class="text-primary fw-bold d-block text-uppercase"
                                                style="font-size: 0.65rem; letter-spacing: 0.6px;">PANDUAN PRAKTIK</span>
                                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.88rem;">Checklist kit
                                                Arduino</h6>
                                            <small class="text-muted" style="font-size: 0.72rem;">Persiapan praktikum • 12
                                                komponen</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-light rounded-circle p-2 text-muted border-0 shadow-none"
                                        title="Download Checklist">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Footer Copyright & Timezone -->
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between text-muted pt-2 pb-3"
                    style="font-size: 0.78rem;">
                    <span>© 2026 RACER ROBOTIC. Bertumbuh bersama teknologi.</span>
                    <span>Semua jadwal dalam Waktu Indonesia Barat (WIB)</span>
                </div>
            </main>
        </div>
    </div>

    <!-- ================= MODALS ================= -->
    <!-- Modal Input Presensi -->
    <div class="modal fade" id="modalPresensi" tabindex="-1" aria-labelledby="modalPresensiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-dark mb-0" id="modalPresensiLabel">Input Presensi Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-2 mb-3 bg-light rounded-3">
                        <small class="text-muted d-block">Kelas: <strong>Controller & AI (L3-A)</strong></small>
                        <small class="text-muted d-block">Sesi 05 - Sensor ultrasonik & logika kontrol (10
                            Siswa)</small>
                    </div>
                    <div class="list-group list-group-flush" style="font-size: 0.88rem;">
                        <label class="list-group-item d-flex align-items-center justify-content-between py-2 border-0">
                            <span>1. Andi Santoso</span>
                            <span class="badge bg-success-subtle text-success">Hadir</span>
                        </label>
                        <label class="list-group-item d-flex align-items-center justify-content-between py-2 border-0">
                            <span>2. Alya Putri</span>
                            <span class="badge bg-success-subtle text-success">Hadir</span>
                        </label>
                        <label class="list-group-item d-flex align-items-center justify-content-between py-2 border-0">
                            <span>3. Bima Aditya</span>
                            <span class="badge bg-success-subtle text-success">Hadir</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary rounded-3 px-4"
                        style="background-color: var(--primary-blue);" data-bs-dismiss="modal">Simpan Presensi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Buka Kelas -->
    <div class="modal fade" id="modalBukaKelas" tabindex="-1" aria-labelledby="modalBukaKelasLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 text-center p-4">
                <div class="modal-body p-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary mx-auto mb-3"
                        style="width: 65px; height: 65px;">
                        <i class="bi bi-play-circle-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1" id="modalBukaKelasLabel">Mulai Sesi Kelas</h5>
                    <p class="text-muted mb-3" style="font-size: 0.88rem;">
                        Controller & AI (L3-B) • Sesi 06 - Pemrograman robot line follower
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-3 px-3"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary rounded-3 px-4"
                            style="background-color: var(--primary-blue);" data-bs-dismiss="modal">Buka
                            Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Sesi -->
    <div class="modal fade" id="modalDetailSesi" tabindex="-1" aria-labelledby="modalDetailSesiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-dark mb-0" id="modalDetailSesiLabel">Detail Sesi 03</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 0.88rem;">
                    <p><strong>Kelas:</strong> Controller & AI (L3-C • Level 3)</p>
                    <p><strong>Topik:</strong> Pengenalan machine learning</p>
                    <p><strong>Waktu:</strong> 15.00 - 16.30 WIB</p>
                    <p><strong>Lokasi:</strong> Lab B - Robotics Hub</p>
                    <p><strong>Jumlah Siswa:</strong> 8 siswa terdaftar</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ganti Sandi -->
    <div class="modal fade" id="modalGantiSandi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content rounded-4 border-0 p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-dark mb-0">Ubah Kata Sandi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.82rem;">Sandi Lama</label>
                        <input type="password" class="form-control rounded-3" placeholder="Masukkan sandi saat ini">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.82rem;">Sandi Baru</label>
                        <input type="password" class="form-control rounded-3" placeholder="Minimal 8 karakter">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary rounded-3 px-4"
                        style="background-color: var(--primary-blue);" data-bs-dismiss="modal">Perbarui Sandi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="modalKonfirmasiLogout" tabindex="-1" aria-labelledby="modalKonfirmasiLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content rounded-4 border-0 shadow-lg text-center p-4">
                <div class="modal-body p-2">
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: #fee2e2; color: #dc2626;">
                            <i class="bi bi-box-arrow-right fs-1"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2" id="modalKonfirmasiLogoutLabel">Konfirmasi Keluar</h5>
                    <p class="text-muted mb-4" style="font-size: 0.9rem; line-height: 1.5;">
                        Apakah Anda yakin ingin keluar dari akun ini? Sesi Anda akan diakhiri.
                    </p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light border bg-white px-3 py-2 rounded-3 text-secondary fw-semibold w-50" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="m-0 w-50">
                            @csrf
                            <button type="submit" class="btn btn-danger px-3 py-2 rounded-3 fw-semibold w-100 shadow-sm" style="background-color: #dc2626; border-color: #dc2626;">
                                Ya, Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Peringatan Sesi Berakhir (AFK) -->
    <div class="modal fade" id="modalSesiAfk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalSesiAfkLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content rounded-4 border-0 shadow-lg text-center p-4">
                <div class="modal-body p-2">
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 75px; height: 75px; background-color: #fff3cd; color: #ffc107;">
                            <i class="bi bi-clock-history fs-1 text-warning"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2" id="modalSesiAfkLabel">Sesi Telah Berakhir</h5>
                    <p class="text-muted mb-4" style="font-size: 0.92rem; line-height: 1.5;">
                        Anda tidak melakukan aktivitas selama <span class="fw-semibold text-danger">15 menit</span>.
                        Demi keamanan akun, sesi Anda telah dinonaktifkan secara otomatis.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold shadow-sm"
                        style="background-color: var(--primary-blue, #1e5ee6);">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle mobile sidebar
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Auto-logout otomatis jika AFK (tidak ada aktivitas) selama 15 menit
        let timerAfk;
        const durasiAfk = 15 * 60 * 1000; // 15 menit
        const aktifitasEvents = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'];

        function resetTimerAfk() {
            clearTimeout(timerAfk);
            timerAfk = setTimeout(handleAfkTimeout, durasiAfk);
        }

        function handleAfkTimeout() {
            aktifitasEvents.forEach(event => {
                window.removeEventListener(event, resetTimerAfk);
            });

            const modalEl = document.getElementById('modalSesiAfk');
            if (modalEl && window.bootstrap) {
                const modalInstance = new bootstrap.Modal(modalEl);
                modalInstance.show();
            }

            fetch("{{ route('logout') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).catch(() => { });
        }

        aktifitasEvents.forEach(event => {
            window.addEventListener(event, resetTimerAfk);
        });

        resetTimerAfk();

        // Toggle Password Show/Hide
        function togglePassPortal(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input && icon) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        }
    </script>
</body>

</html>
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

        /* Calendar & Schedule Styles */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }

        .calendar-header-day {
            text-align: center;
            font-weight: 600;
            font-size: 0.78rem;
            color: #64748b;
            padding: 0.5rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calendar-day-cell {
            min-height: 110px;
            background: #ffffff;
            border: 1px solid #e9edf4;
            border-radius: 10px;
            padding: 0.55rem;
            display: flex;
            flex-direction: column;
            transition: all 0.2s ease;
        }

        .calendar-day-cell:hover {
            border-color: #bcd4fd;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .calendar-day-cell.other-month {
            background-color: #f8fafc;
            opacity: 0.45;
        }

        .calendar-day-cell.is-today {
            border: 2px solid var(--primary-blue);
            background-color: #f7faff;
        }

        .calendar-date-number {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }

        .calendar-day-cell.is-today .calendar-date-number {
            background-color: var(--primary-blue);
            color: #ffffff;
        }

        .calendar-event-badge {
            font-size: 0.72rem;
            padding: 0.3rem 0.45rem;
            border-radius: 6px;
            background-color: #eaf0fd;
            color: #1e5ee6;
            margin-bottom: 0.35rem;
            font-weight: 500;
            line-height: 1.25;
            text-decoration: none;
            display: block;
            border-left: 3px solid var(--primary-blue);
            cursor: pointer;
            transition: transform 0.15s ease;
        }

        .calendar-event-badge:hover {
            transform: scale(1.02);
            background-color: #dbe7fc;
            color: #1142ab;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 1rem;
            font-size: 0.82rem;
            font-weight: 500;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .filter-pill.active {
            background-color: var(--primary-blue);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(30, 94, 230, 0.25);
        }

        .filter-pill.inactive {
            background-color: #ffffff;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .filter-pill.inactive:hover {
            background-color: #f8fafc;
            color: var(--primary-blue);
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
                        <a href="{{ route('tutor', ['tab' => 'jadwal']) }}"
                            class="nav-link-tutor {{ ($activeTab ?? '') === 'jadwal' ? 'active' : '' }}">
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
                            @elseif (($activeTab ?? 'beranda') === 'jadwal')
                                <li class="breadcrumb-item active" aria-current="page">Kalender & Jadwal Mengajar Pribadi</li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">Beranda Tutor</li>
                            @endif
                        </ol>
                    </nav>
                </div>

                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <span class="text-muted fw-medium d-none d-sm-flex align-items-center gap-1" style="font-size: 0.82rem;">
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
                @elseif (($activeTab ?? 'beranda') === 'jadwal')
                    <!-- ================= TAB KALENDER & JADWAL MENGAJAR PRIBADI ================= -->
                    <!-- Header Tab Jadwal -->
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-dark mb-0">Kalender & Jadwal Mengajar Pribadi</h4>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1" style="font-size: 0.76rem;">
                                    <i class="bi bi-shield-check me-1"></i> Data Pribadi Terisolasi
                                </span>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 0.88rem;">
                                Menampilkan jadwal sesi kelas dan daftar siswa peserta yang hanya diampu oleh Anda 
                                (<strong>{{ $tutorProfile->nama_lengkap ?? Auth::user()->name }}</strong>).
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => 'semua']) }}" class="btn btn-sm btn-light border shadow-sm rounded-3">
                                <i class="bi bi-arrow-clockwise me-1"></i> Muat Ulang
                            </a>
                            <button class="btn btn-sm btn-primary rounded-3 px-3" style="background-color: var(--primary-blue);" data-bs-toggle="modal" data-bs-target="#modalPresensi">
                                <i class="bi bi-check2-circle me-1"></i> Presensi Cepat
                            </button>
                        </div>
                    </div>

                    <!-- 4 Kartu Metrik Ringkasan Pengajar -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Total Sesi Saya</span>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 32px; height: 32px;">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                </div>
                                <div class="stat-number">{{ $totalSesiPribadi ?? 0 }}</div>
                                <small class="text-muted" style="font-size: 0.74rem;">Seluruh sesi dialokasikan</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Sesi Aktif / Jadwal</span>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning" style="width: 32px; height: 32px;">
                                        <i class="bi bi-hourglass-split"></i>
                                    </div>
                                </div>
                                <div class="stat-number text-primary">{{ $totalSesiAktif ?? 0 }}</div>
                                <small class="text-muted" style="font-size: 0.74rem;">Terjadwal & berlangsung</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Sesi Selesai</span>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 32px; height: 32px;">
                                        <i class="bi bi-check2-all"></i>
                                    </div>
                                </div>
                                <div class="stat-number text-success">{{ $totalSesiSelesai ?? 0 }}</div>
                                <small class="text-muted" style="font-size: 0.74rem;">Selesai terlaksana</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Siswa Binaan</span>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-info-subtle text-info" style="width: 32px; height: 32px;">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                </div>
                                <div class="stat-number text-info">{{ $totalSiswaTerdaftar ?? 0 }}</div>
                                <small class="text-muted" style="font-size: 0.74rem;">Peserta aktif di kelas Anda</small>
                            </div>
                        </div>
                    </div>

                    <!-- Toolbar Filter & View Switcher -->
                    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4 bg-white">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                            <!-- Filter Tabs / Pills -->
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <span class="text-muted fw-semibold me-1" style="font-size: 0.82rem;"><i class="bi bi-funnel me-1"></i>Filter Jadwal:</span>
                                <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => 'semua', 'bulan' => $bulanKalender, 'tahun' => $tahunKalender]) }}"
                                    class="filter-pill {{ ($filterJadwal ?? 'semua') === 'semua' ? 'active' : 'inactive' }}">
                                    Semua
                                </a>
                                <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => 'hari', 'bulan' => $bulanKalender, 'tahun' => $tahunKalender]) }}"
                                    class="filter-pill {{ ($filterJadwal ?? '') === 'hari' ? 'active' : 'inactive' }}">
                                    <i class="bi bi-sun"></i> Hari Ini
                                </a>
                                <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => 'minggu', 'bulan' => $bulanKalender, 'tahun' => $tahunKalender]) }}"
                                    class="filter-pill {{ ($filterJadwal ?? '') === 'minggu' ? 'active' : 'inactive' }}">
                                    <i class="bi bi-calendar-week"></i> Minggu Ini
                                </a>
                                <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => 'bulan', 'bulan' => $bulanKalender, 'tahun' => $tahunKalender]) }}"
                                    class="filter-pill {{ ($filterJadwal ?? '') === 'bulan' ? 'active' : 'inactive' }}">
                                    <i class="bi bi-calendar-month"></i> Bulan Ini
                                </a>
                            </div>

                            <!-- Switcher Tampilan (Kalender vs Daftar) -->
                            <div class="btn-group p-1 bg-light rounded-3 border" role="group" aria-label="Mode Tampilan">
                                <button type="button" id="btnToggleKalender" class="btn btn-sm btn-white active shadow-sm rounded-2 fw-semibold px-3" style="font-size: 0.82rem;">
                                    <i class="bi bi-calendar3 me-1"></i> Kalender Bulanan
                                </button>
                                <button type="button" id="btnToggleDaftar" class="btn btn-sm btn-light text-muted rounded-2 fw-semibold px-3" style="font-size: 0.82rem;">
                                    <i class="bi bi-list-check me-1"></i> Daftar Jadwal & Siswa
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PANDANGAN 1: KALENDER BULANAN INTERAKTIF -->
                    <div id="containerKalenderView" class="mb-4">
                        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                            <!-- Header Kalender: Bulan, Tahun, Prev/Next -->
                            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <h5 class="fw-bold text-dark mb-0">
                                        <i class="bi bi-calendar-event text-primary me-2"></i>{{ $namaBulanTahun ?? date('F Y') }}
                                    </h5>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.74rem;">WIB (UTC+7)</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => $filterJadwal, 'bulan' => $prevMonth, 'tahun' => $prevYear]) }}"
                                        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1" title="Bulan Sebelumnya">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                    <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => $filterJadwal, 'bulan' => (int)date('n'), 'tahun' => (int)date('Y')]) }}"
                                        class="btn btn-sm btn-light border rounded-3 px-3 py-1" style="font-size: 0.82rem;">
                                        Hari Ini
                                    </a>
                                    <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => $filterJadwal, 'bulan' => $nextMonth, 'tahun' => $nextYear]) }}"
                                        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1" title="Bulan Berikutnya">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Header Nama Hari (Sen - Min) -->
                            <div class="calendar-grid mb-2">
                                <div class="calendar-header-day text-secondary">Sen</div>
                                <div class="calendar-header-day text-secondary">Sel</div>
                                <div class="calendar-header-day text-secondary">Rab</div>
                                <div class="calendar-header-day text-secondary">Kam</div>
                                <div class="calendar-header-day text-secondary">Jum</div>
                                <div class="calendar-header-day text-primary">Sab</div>
                                <div class="calendar-header-day text-danger">Min</div>
                            </div>

                            <!-- Grid Tanggal Kalender -->
                            <div class="calendar-grid">
                                {{-- Sel kosong sebelum hari ke-1 --}}
                                @for ($emptyDay = 1; $emptyDay < $startDayOfWeek; $emptyDay++)
                                    <div class="calendar-day-cell other-month"></div>
                                @endfor

                                {{-- Sel Tanggal Bulan Berjalan --}}
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $tglStr = sprintf('%04d-%02d-%02d', $tahunKalender, $bulanKalender, $day);
                                        $isToday = ($tglStr === date('Y-m-d'));
                                        $dayEvents = $jadwalPerTanggal[$tglStr] ?? collect();
                                    @endphp
                                    <div class="calendar-day-cell {{ $isToday ? 'is-today' : '' }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="calendar-date-number">{{ $day }}</span>
                                            @if ($dayEvents->isNotEmpty())
                                                <span class="badge rounded-pill bg-primary" style="font-size: 0.65rem;">
                                                    {{ $dayEvents->count() }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Daftar Jadwal pada tanggal ini --}}
                                        <div class="calendar-events-list flex-grow-1 overflow-auto" style="max-height: 120px;">
                                            @foreach ($dayEvents as $itemJadwal)
                                                @php
                                                    $pesertaCount = $itemJadwal->pesertaJadwal->count();
                                                    $jamText = ($itemJadwal->jam_mulai ? substr($itemJadwal->jam_mulai, 0, 5) : ($itemJadwal->waktu_mulai ? substr($itemJadwal->waktu_mulai, 0, 5) : '09:00'));
                                                @endphp
                                                <div class="calendar-event-badge" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDetailJadwal{{ $itemJadwal->id }}"
                                                    title="{{ $itemJadwal->kelas ?? $itemJadwal->program }} ({{ $pesertaCount }} Siswa)">
                                                    <div class="fw-bold text-truncate" style="font-size: 0.72rem;">
                                                        <i class="bi bi-clock me-1"></i>{{ $jamText }}
                                                    </div>
                                                    <div class="text-truncate text-dark" style="font-size: 0.7rem;">
                                                        {{ $itemJadwal->kelas ?? $itemJadwal->program ?? 'Kelas Robotika' }}
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between mt-1 text-muted" style="font-size: 0.66rem;">
                                                        <span><i class="bi bi-people me-1"></i>{{ $pesertaCount }} Siswa</span>
                                                        <span class="badge {{ $itemJadwal->moda === 'Online' ? 'bg-info-subtle text-info' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.6rem;">{{ $itemJadwal->moda ?? 'Offline' }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <!-- Petunjuk Legenda Kalender -->
                            <div class="d-flex align-items-center gap-3 mt-4 pt-3 border-top flex-wrap" style="font-size: 0.78rem;">
                                <span class="text-muted fw-semibold">Keterangan:</span>
                                <span class="d-flex align-items-center gap-1 text-muted">
                                    <span class="d-inline-block rounded-circle bg-primary" style="width: 10px; height: 10px;"></span> Hari Ini
                                </span>
                                <span class="d-flex align-items-center gap-1 text-muted">
                                    <span class="d-inline-block rounded" style="width: 12px; height: 12px; background: #eaf0fd; border-left: 3px solid #1e5ee6;"></span> Sesi Kelas Anda
                                </span>
                                <span class="d-flex align-items-center gap-1 text-muted">
                                    <i class="bi bi-info-circle text-primary"></i> Klik pada kotak sesi di kalender untuk melihat rincian dan daftar siswa peserta.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- PANDANGAN 2: DAFTAR JADWAL LENGKAP & DAFTAR SISWA PESERTA -->
                    <div id="containerDaftarView" class="mb-4 d-none">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="bi bi-card-checklist text-primary me-2"></i>Daftar Sesi Mengajar & Siswa Peserta
                            </h5>
                            <span class="text-muted" style="font-size: 0.82rem;">
                                Menampilkan {{ $filteredJadwal->count() }} sesi sesuai filter: <strong>{{ ucfirst($filterJadwal) }}</strong>
                            </span>
                        </div>

                        @if ($filteredJadwal->isEmpty())
                            <div class="card border-0 rounded-4 shadow-sm p-5 text-center bg-white">
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-muted mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-calendar-x fs-3"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Tidak Ada Jadwal Mengajar</h6>
                                <p class="text-muted mb-3" style="font-size: 0.85rem;">
                                    Tidak ditemukan jadwal mengajar aktif untuk tutor <strong>{{ $tutorProfile->nama_lengkap ?? Auth::user()->name }}</strong> pada filter ini.
                                </p>
                                <div>
                                    <a href="{{ route('tutor', ['tab' => 'jadwal', 'filter' => 'semua']) }}" class="btn btn-sm btn-primary rounded-3 px-3" style="background-color: var(--primary-blue);">
                                        Tampilkan Semua Jadwal
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach ($filteredJadwal as $jadwalItem)
                                    @php
                                        $pesertaList = $jadwalItem->pesertaJadwal;
                                        $pesertaCount = $pesertaList->count();
                                        $jamMulaiFormatted = $jadwalItem->jam_mulai ? substr($jadwalItem->jam_mulai, 0, 5) : ($jadwalItem->waktu_mulai ? substr($jadwalItem->waktu_mulai, 0, 5) : '09:00');
                                        $jamSelesaiFormatted = $jadwalItem->jam_selesai ? substr($jadwalItem->jam_selesai, 0, 5) : ($jadwalItem->waktu_selesai ? substr($jadwalItem->waktu_selesai, 0, 5) : '10:30');
                                        $statusClass = match(strtolower((string)$jadwalItem->status)) {
                                            'selesai' => 'bg-secondary-subtle text-secondary',
                                            'berlangsung' => 'bg-warning-subtle text-warning-emphasis',
                                            'dibatalkan' => 'bg-danger-subtle text-danger',
                                            default => 'bg-success-subtle text-success',
                                        };
                                    @endphp
                                    <div class="col-12">
                                        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                                            <!-- Header Jadwal Card -->
                                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 pb-3 border-bottom">
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                        <span class="badge {{ $statusClass }} rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.74rem;">
                                                            {{ $jadwalItem->status ?? 'Terjadwal' }}
                                                        </span>
                                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1" style="font-size: 0.74rem;">
                                                            <i class="bi bi-geo-alt me-1"></i>{{ $jadwalItem->moda ?? 'Offline' }} ({{ $jadwalItem->lokasi ?? 'Lab Robotika' }})
                                                        </span>
                                                        @if (!empty($jadwalItem->link_kelas))
                                                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                                <i class="bi bi-camera-video me-1"></i> Link Kelas Aktif
                                                            </span>
                                                        @endif
                                                        <span class="text-muted" style="font-size: 0.8rem;">
                                                            <i class="bi bi-calendar3 me-1"></i>{{ $jadwalItem->tanggal ? \Carbon\Carbon::parse($jadwalItem->tanggal)->isoFormat('dddd, D MMMM Y') : 'Sesuai Jadwal' }}
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-bold text-dark mb-0">
                                                        {{ $jadwalItem->kelas ?? $jadwalItem->program ?? 'Kelas Robotika' }}
                                                    </h5>
                                                    <small class="text-muted">
                                                        Waktu: <strong>{{ $jamMulaiFormatted }} - {{ $jamSelesaiFormatted }} WIB</strong> • Tutor Pengampu: <strong>{{ $jadwalItem->tutor ?? $tutorProfile->nama_lengkap }}</strong>
                                                    </small>
                                                </div>

                                                <div class="d-flex align-items-center gap-2 mt-2 mt-md-0 flex-wrap">
                                                    @if (!empty($jadwalItem->link_kelas))
                                                        <a href="{{ $jadwalItem->link_kelas }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success rounded-3 px-2 py-1" style="font-size: 0.78rem;" title="Buka Link Kelas Online">
                                                            <i class="bi bi-camera-video me-1"></i> Buka Kelas
                                                        </a>
                                                    @endif
                                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-3" style="font-size: 0.82rem;">
                                                        <i class="bi bi-people-fill text-primary me-1"></i> {{ $pesertaCount }} Siswa Terdaftar
                                                    </span>
                                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#modalDetailJadwal{{ $jadwalItem->id }}">
                                                        <i class="bi bi-pencil-square me-1"></i> Detail & Tautan
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Bagian Daftar Siswa Peserta Terdaftar -->
                                            <div class="pt-3">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <h6 class="fw-semibold text-dark mb-0" style="font-size: 0.88rem;">
                                                        <i class="bi bi-person-lines-fill text-primary me-2"></i>Daftar Siswa Peserta Kelas
                                                    </h6>
                                                    <small class="text-muted" style="font-size: 0.76rem;">Kapasitas Maksimal: 12 Siswa / Sesi</small>
                                                </div>

                                                @if ($pesertaList->isEmpty())
                                                    <div class="p-3 bg-light rounded-3 text-center text-muted" style="font-size: 0.82rem;">
                                                        <i class="bi bi-info-circle me-1"></i> Belum ada siswa yang dialokasikan ke jadwal kelas ini.
                                                    </div>
                                                @else
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.82rem;">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 40px;" class="text-center">No</th>
                                                                    <th>Nama Lengkap Siswa</th>
                                                                    <th>NISN</th>
                                                                    <th>Jenjang & Kelas</th>
                                                                    <th>Paket Siswa</th>
                                                                    <th>Status Kehadiran</th>
                                                                    <th>Catatan</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($pesertaList as $idx => $peserta)
                                                                    @php
                                                                        $siswa = $peserta->siswa;
                                                                        $statusPresensiClass = match(strtolower((string)$peserta->status_kehadiran)) {
                                                                            'hadir' => 'bg-success-subtle text-success',
                                                                            'izin' => 'bg-info-subtle text-info',
                                                                            'sakit' => 'bg-warning-subtle text-warning-emphasis',
                                                                            'alpa' => 'bg-danger-subtle text-danger',
                                                                            default => 'bg-primary-subtle text-primary',
                                                                        };
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="text-center text-muted">{{ $idx + 1 }}</td>
                                                                        <td>
                                                                            <div class="fw-bold text-dark">{{ $siswa->nama_lengkap ?? 'Siswa #'.$peserta->id_siswa }}</div>
                                                                            <small class="text-muted">Panggilan: {{ $siswa->nama_panggilan ?? '-' }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge bg-light text-secondary border font-monospace">
                                                                                {{ $siswa->nisn ?? '-' }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge bg-light text-dark border">
                                                                                {{ $siswa->jenjang ?? 'SD' }} • Kelas {{ $siswa->kelas ?? '3' }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="text-secondary">
                                                                                {{ $peserta->paketSiswa->paket->nama_paket ?? 'Paket Robotika' }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge {{ $statusPresensiClass }} rounded-pill px-2 py-1">
                                                                                {{ $peserta->status_kehadiran ?? 'Terdaftar' }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="text-muted">{{ $peserta->catatan ?: '-' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
                                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.98rem;">
                                            @if (($filterJadwal ?? 'hari') === 'minggu')
                                                Jadwal Mengajar Minggu Ini
                                            @elseif (($filterJadwal ?? 'hari') === 'semua')
                                                Semua Jadwal Mengajar Pribadi
                                            @else
                                                Jadwal Mengajar Hari Ini
                                            @endif
                                        </h6>
                                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-1"
                                            style="font-size: 0.72rem;">{{ $jadwalHariIni->count() }} sesi</span>
                                    </div>
                                    <!-- Filter Pills: Hari Ini vs Minggu Ini vs Semua -->
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Filter Jadwal">
                                        <a href="{{ route('tutor', ['filter' => 'hari']) }}"
                                            class="btn btn-outline-primary {{ ($filterJadwal ?? 'hari') === 'hari' ? 'active' : '' }}"
                                            style="font-size: 0.78rem;">
                                            <i class="bi bi-calendar-day me-1"></i>Hari Ini
                                        </a>
                                        <a href="{{ route('tutor', ['filter' => 'minggu']) }}"
                                            class="btn btn-outline-primary {{ ($filterJadwal ?? 'hari') === 'minggu' ? 'active' : '' }}"
                                            style="font-size: 0.78rem;">
                                            <i class="bi bi-calendar-week me-1"></i>Minggu Ini
                                        </a>
                                        <a href="{{ route('tutor', ['filter' => 'semua']) }}"
                                            class="btn btn-outline-primary {{ ($filterJadwal ?? 'hari') === 'semua' ? 'active' : '' }}"
                                            style="font-size: 0.78rem;">
                                            <i class="bi bi-calendar3 me-1"></i>Semua
                                        </a>
                                    </div>
                                </div>

                                <!-- Schedule Items List -->
                                <div>
                                    @forelse ($jadwalHariIni as $j)
                                        @php
                                            $jamMulai = $j->jam_mulai ?? $j->waktu_mulai ?? '09.00';
                                            $jamSelesai = $j->jam_selesai ?? $j->waktu_selesai ?? '10.30';
                                            $status = $j->status ?? 'Terjadwal';
                                            $pesertaCount = $j->siswaPeserta ? $j->siswaPeserta->count() : 0;
                                            $isCompleted = (strcasecmp($status, 'Selesai') === 0);
                                            $isActive = (strcasecmp($status, 'Berlangsung') === 0);
                                        @endphp
                                        <div class="schedule-item {{ $isActive ? 'active-schedule' : '' }} d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="text-center pe-3 border-end" style="min-width: 65px;">
                                                    <span class="fw-bold d-block {{ $isActive ? 'text-primary' : 'text-dark' }}"
                                                        style="font-size: 0.92rem;">{{ $jamMulai }}</span>
                                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $jamSelesai }}</small>
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">
                                                            {{ $j->program ?? $j->kelas ?? 'Kelas Robotika' }}
                                                        </h6>
                                                        <span class="badge-level">{{ $j->formatted_tanggal ?? $j->tanggal }}</span>
                                                    </div>
                                                    <p class="text-muted mb-1" style="font-size: 0.82rem;">
                                                        Tutor: <strong class="text-dark">{{ $j->tutor_pengampu ?? $j->tutor }}</strong>
                                                        @if ($j->moda)
                                                            • <span class="badge bg-light text-dark border">{{ $j->moda }}</span>
                                                        @endif
                                                    </p>
                                                    <div class="d-flex align-items-center gap-3 text-muted" style="font-size: 0.75rem;">
                                                        <span><i class="bi bi-geo-alt me-1 text-primary"></i>{{ $j->lokasi ?? 'Lab Robotika' }}</span>
                                                        <span><i class="bi bi-people me-1"></i>{{ $pesertaCount }} siswa terdaftar</span>
                                                    </div>

                                                    {{-- Daftar Siswa Peserta Kelas --}}
                                                    @php
                                                        $daftarSiswa = $j->siswaPeserta ?? collect();
                                                        if ($daftarSiswa->isEmpty() && $j->pesertaJadwal) {
                                                            $daftarSiswa = $j->pesertaJadwal->map(fn($pj) => $pj->siswa)->filter();
                                                        }
                                                    @endphp
                                                    @if ($daftarSiswa->isNotEmpty())
                                                        <div class="mt-2 pt-2 border-top d-flex flex-wrap align-items-center gap-1">
                                                            <span class="text-muted fw-semibold me-1" style="font-size: 0.73rem;">
                                                                <i class="bi bi-person-check me-1 text-primary"></i>Peserta:
                                                            </span>
                                                            @foreach ($daftarSiswa as $siswa)
                                                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                                    {{ $siswa->nama_panggilan ?? $siswa->nama_lengkap ?? $siswa->nama }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 ms-auto ms-md-0">
                                                @if ($isCompleted)
                                                    <span class="badge px-2 py-1 rounded-pill"
                                                        style="background-color: #dcfce7; color: #15803d; font-size: 0.74rem;">Selesai</span>
                                                @elseif ($isActive)
                                                    <span class="badge px-2 py-1 rounded-pill bg-primary text-white"
                                                        style="font-size: 0.74rem;">Berlangsung</span>
                                                @else
                                                    <span class="badge px-2 py-1 rounded-pill bg-light text-secondary border"
                                                        style="font-size: 0.74rem;">Terjadwal</span>
                                                @endif
                                                <button class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1 fw-medium"
                                                    style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#modalPresensi">
                                                    Presensi
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-muted">
                                            <i class="bi bi-calendar-x fs-2 d-block text-secondary mb-2"></i>
                                            <p class="mb-0 fw-medium">Tidak ada jadwal mengajar pada periode ini.</p>
                                            <small class="text-muted">Gunakan tombol filter di atas untuk melihat jadwal hari lain atau minggu ini.</small>
                                        </div>
                                    @endforelse
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
                    <h5 class="fw-bold text-dark mb-0">Pengaturan Sandi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <div class="avatar-initials mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.88rem;">
                        Untuk keamanan akun pengajar Anda, Anda dapat memperbarui kata sandi secara berkala di tab pengaturan sandi.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <a href="{{ route('tutor', ['tab' => 'sandi']) }}" class="btn btn-primary rounded-3 px-4" style="background-color: var(--primary-blue);">
                        Buka Pengaturan Sandi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Detail Jadwal Tutor & Siswa Peserta -->
    @if (isset($allJadwalTutor) && $allJadwalTutor->isNotEmpty())
        @foreach ($allJadwalTutor as $modalJadwal)
            @php
                $modalPeserta = $modalJadwal->pesertaJadwal;
                $jamMulai = $modalJadwal->jam_mulai ? substr($modalJadwal->jam_mulai, 0, 5) : ($modalJadwal->waktu_mulai ? substr($modalJadwal->waktu_mulai, 0, 5) : '09:00');
                $jamSelesai = $modalJadwal->jam_selesai ? substr($modalJadwal->jam_selesai, 0, 5) : ($modalJadwal->waktu_selesai ? substr($modalJadwal->waktu_selesai, 0, 5) : '10:30');
            @endphp
            <div class="modal fade" id="modalDetailJadwal{{ $modalJadwal->id }}" tabindex="-1" aria-labelledby="modalDetailJadwalLabel{{ $modalJadwal->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content rounded-4 border-0 p-3 p-md-4">
                        <div class="modal-header border-0 pb-2">
                            <div>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 mb-1" style="font-size: 0.76rem;">
                                    {{ $modalJadwal->status ?? 'Terjadwal' }} • {{ $modalJadwal->moda ?? 'Offline' }}
                                </span>
                                <h5 class="fw-bold text-dark mb-0" id="modalDetailJadwalLabel{{ $modalJadwal->id }}">
                                    {{ $modalJadwal->kelas ?? $modalJadwal->program ?? 'Detail Sesi Kelas Robotika' }}
                                </h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-2">
                            <div class="row g-3 p-3 bg-light rounded-3 mb-3" style="font-size: 0.85rem;">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block" style="font-size: 0.76rem;">Tanggal & Waktu</span>
                                    <strong class="text-dark">
                                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                                        {{ $modalJadwal->tanggal ? \Carbon\Carbon::parse($modalJadwal->tanggal)->isoFormat('dddd, D MMMM Y') : 'Sesuai Jadwal' }}
                                    </strong>
                                    <div class="text-secondary">{{ $jamMulai }} - {{ $jamSelesai }} WIB</div>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block" style="font-size: 0.76rem;">Lokasi / Ruangan & Pengampu</span>
                                    <strong class="text-dark">
                                        <i class="bi bi-geo-alt me-1 text-danger"></i>
                                        {{ $modalJadwal->lokasi ?? 'Lab Robotika' }}
                                    </strong>
                                    <div class="text-secondary">Tutor: {{ $modalJadwal->tutor ?? $tutorProfile->nama_lengkap }}</div>
                                </div>
                            </div>

                            <!-- Box & Form Input / Edit Tautan Kelas Online (Validasi HTTPS & Kepemilikan Tutor) -->
                            <div class="card border rounded-3 mb-3 bg-white shadow-sm overflow-hidden">
                                <div class="card-header bg-light d-flex align-items-center justify-content-between py-2 px-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-camera-video-fill text-primary fs-6"></i>
                                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">Tautan Kelas Online (Google Meet / Zoom)</span>
                                    </div>
                                    <span class="badge {{ $modalJadwal->moda === 'Online' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }} rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        Moda: {{ $modalJadwal->moda ?? 'Offline' }}
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    @if (strcasecmp((string)($modalJadwal->moda ?? 'Offline'), 'Online') !== 0)
                                        <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light text-secondary border">
                                            <div class="rounded-circle bg-white p-2 border shadow-sm text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;">
                                                <i class="bi bi-geo-alt-fill fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">Kelas Tatap Muka (Moda Offline)</div>
                                                <div class="text-muted" style="font-size: 0.78rem;">
                                                    Sesi ini berlangsung secara langsung di ruang <strong>{{ $modalJadwal->lokasi ?? 'Lab Robotika' }}</strong>. Pengaturan tautan meeting online hanya berlaku untuk sesi bermoda <strong>Online</strong>.
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Container Feedback Status Realtime -->
                                        <div id="feedbackLink{{ $modalJadwal->id }}"></div>

                                        @if (!empty($modalJadwal->link_kelas))
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-success-subtle border border-success-subtle mb-3 flex-wrap gap-2" id="previewContainer{{ $modalJadwal->id }}">
                                                <div class="d-flex align-items-center gap-2 text-truncate" style="max-width: 80%;">
                                                    <i class="bi bi-shield-check text-success fs-5"></i>
                                                    <div>
                                                        <div class="text-success fw-bold" style="font-size: 0.72rem;">TAUTAN HTTPS AKTIF</div>
                                                        <a href="{{ $modalJadwal->link_kelas }}" id="previewLink{{ $modalJadwal->id }}" target="_blank" rel="noopener noreferrer" class="text-dark fw-semibold text-truncate d-inline-block text-decoration-underline" style="font-size: 0.82rem; max-width: 100%;">
                                                            {{ $modalJadwal->link_kelas }}
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-1">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 py-1 px-2 btn-copy-link" style="font-size: 0.74rem;" onclick="copyToClipboard('{{ $modalJadwal->link_kelas }}', this)" title="Salin Tautan">
                                                        <i class="bi bi-clipboard"></i> Salin
                                                    </button>
                                                    <a href="{{ $modalJadwal->link_kelas }}" id="previewBtnBuka{{ $modalJadwal->id }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success rounded-3 py-1 px-2" style="font-size: 0.74rem;">
                                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-warning-subtle text-warning-emphasis border border-warning-subtle mb-3" id="previewContainer{{ $modalJadwal->id }}" style="font-size: 0.8rem;">
                                                <i class="bi bi-info-circle-fill"></i>
                                                <span>Tautan kelas online belum diatur. Masukkan URL meeting di bawah ini sebelum sesi dimulai.</span>
                                            </div>
                                        @endif

                                        <form action="{{ route('tutor.jadwal.update-link', $modalJadwal->id) }}" method="POST" class="form-update-link-kelas" data-jadwal-id="{{ $modalJadwal->id }}">
                                            @csrf
                                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">
                                                Input / Edit Tautan Kelas Online:
                                            </label>
                                            <div class="input-group mb-1">
                                                <span class="input-group-text bg-white text-primary border-end-0 rounded-start-3" style="font-size: 0.85rem;">
                                                    <i class="bi bi-link-45deg"></i> https://
                                                </span>
                                                <input type="url" name="link_kelas" id="inputLinkKelas{{ $modalJadwal->id }}" 
                                                       class="form-control rounded-end-3" 
                                                       placeholder="https://meet.google.com/xxx-yyyy-zzz atau https://zoom.us/j/..." 
                                                       value="{{ old('link_kelas', $modalJadwal->link_kelas ?? ($modalJadwal->moda === 'Online' ? $modalJadwal->lokasi : '')) }}" 
                                                       required pattern="https://.*" style="font-size: 0.85rem;">
                                                <button type="submit" class="btn btn-primary rounded-3 px-3 ms-2 text-nowrap d-flex align-items-center gap-1" style="background-color: var(--primary-blue);">
                                                    <i class="bi bi-floppy"></i> <span>Simpan Tautan</span>
                                                </button>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-1 text-muted flex-wrap gap-1" style="font-size: 0.73rem;">
                                                <span><i class="bi bi-shield-lock-fill text-success me-1"></i>Format wajib diawali dengan <code>https://</code></span>
                                                <span>Contoh: Google Meet, Zoom, MS Teams</span>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">
                                    <i class="bi bi-people-fill text-primary me-2"></i>Daftar Siswa Peserta ({{ $modalPeserta->count() }} Terdaftar)
                                </h6>
                                <span class="badge bg-light text-muted border" style="font-size: 0.75rem;">Maksimal 12 Peserta</span>
                            </div>

                            @if ($modalPeserta->isEmpty())
                                <div class="p-3 bg-light rounded-3 text-center text-muted" style="font-size: 0.84rem;">
                                    Belum ada siswa yang didaftarkan ke sesi kelas ini.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.82rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 35px;" class="text-center">No</th>
                                                <th>Nama Siswa</th>
                                                <th>NISN</th>
                                                <th>Jenjang</th>
                                                <th>Status</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($modalPeserta as $num => $p)
                                                @php $s = $p->siswa; @endphp
                                                <tr>
                                                    <td class="text-center text-muted">{{ $num + 1 }}</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $s->nama_lengkap ?? 'Siswa #'.$p->id_siswa }}</div>
                                                        <small class="text-muted">Panggilan: {{ $s->nama_panggilan ?? '-' }}</small>
                                                    </td>
                                                    <td><span class="badge bg-light text-secondary border font-monospace">{{ $s->nisn ?? '-' }}</span></td>
                                                    <td>{{ $s->jenjang ?? 'SD' }} Kelas {{ $s->kelas ?? '3' }}</td>
                                                    <td><span class="badge bg-success-subtle text-success">{{ $p->status_kehadiran ?? 'Terdaftar' }}</span></td>
                                                    <td class="text-muted">{{ $p->catatan ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary rounded-3 px-4" style="background-color: var(--primary-blue);" data-bs-dismiss="modal">Presensi Selesai</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
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

        // Switcher Mode Tampilan: Kalender Bulanan vs Daftar Jadwal
        const btnToggleKalender = document.getElementById('btnToggleKalender');
        const btnToggleDaftar = document.getElementById('btnToggleDaftar');
        const containerKalenderView = document.getElementById('containerKalenderView');
        const containerDaftarView = document.getElementById('containerDaftarView');

        if (btnToggleKalender && btnToggleDaftar && containerKalenderView && containerDaftarView) {
            btnToggleKalender.addEventListener('click', function() {
                containerKalenderView.classList.remove('d-none');
                containerDaftarView.classList.add('d-none');
                btnToggleKalender.classList.add('active', 'btn-white', 'shadow-sm');
                btnToggleKalender.classList.remove('btn-light', 'text-muted');
                btnToggleDaftar.classList.remove('active', 'btn-white', 'shadow-sm');
                btnToggleDaftar.classList.add('btn-light', 'text-muted');
            });

            btnToggleDaftar.addEventListener('click', function() {
                containerDaftarView.classList.remove('d-none');
                containerKalenderView.classList.add('d-none');
                btnToggleDaftar.classList.add('active', 'btn-white', 'shadow-sm');
                btnToggleDaftar.classList.remove('btn-light', 'text-muted');
                btnToggleKalender.classList.remove('active', 'btn-white', 'shadow-sm');
                btnToggleKalender.classList.add('btn-light', 'text-muted');
            });
        }

        // Anti-Back-Button: Cegah tombol Back browser menampilkan halaman dari cache setelah logout
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType('navigation')[0] && window.performance.getEntriesByType('navigation')[0].type === 'back_forward')) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>
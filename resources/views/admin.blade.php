<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RACER ROBOTIC - Administrasi Akademik</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
            --sidebar-width: 260px;
            --primary-blue: #1d68f0;
            --primary-hover: #1555c7;
            --bg-canvas: #f4f7fc;
            --card-border: #e8eef7;
        }

        body {
            background-color: var(--bg-canvas);
            font-family: var(--bs-body-font-family);
            color: #1e293b;
            overflow-x: hidden;
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
            z-index: 1020;
            padding: 1.5rem 1rem;
            overflow-y: auto;
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
                transition: margin 0.3s ease;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }
        }

        /* Brand Header */
        .brand-logo {
            width: 42px;
            height: 42px;
            background: #1d68f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.4rem;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.9rem;
            color: #64748b;
            font-weight: 500;
            font-size: 0.88rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .nav-link-custom:hover {
            color: var(--primary-blue);
            background-color: #f1f5fd;
        }

        .nav-link-custom.active {
            color: var(--primary-blue);
            background-color: #ebf3ff;
            font-weight: 600;
        }

        .nav-link-custom i {
            font-size: 1.15rem;
        }

        /* Tombol Logout Sidebar dengan Efek Hover */
        .btn-logout-sidebar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            background-color: transparent;
            border: 1px solid transparent;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-logout-sidebar:hover {
            color: #dc2626 !important;
            background-color: #fee2e2;
            border-color: #fecaca;
            transform: scale(1.08) translateX(2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
        }

        .btn-logout-sidebar:active {
            transform: scale(0.96);
        }

        /* Hover pada Tombol Konfirmasi Logout di Dalam Modal */
        .btn-confirm-logout {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            color: #ffffff !important;
            transition: all 0.2s ease;
        }

        .btn-confirm-logout:hover {
            background-color: #b91c1c !important;
            border-color: #b91c1c !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(220, 38, 38, 0.35) !important;
        }

        /* Support Box */
        .support-card {
            background-color: #f0f6ff;
            border-radius: 14px;
            padding: 1rem;
            border: 1px dashed #bfdbfe;
        }

        /* Top Bar */
        .top-navbar {
            background: transparent;
            padding: 1.25rem 2rem 0.5rem 2rem;
        }

        .search-container {
            position: relative;
            max-width: 480px;
            width: 100%;
        }

        .search-input {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.65rem 3.5rem 0.65rem 2.6rem;
            font-size: 0.88rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .search-input:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(29, 104, 240, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .search-shortcut {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.15rem 0.4rem;
            font-size: 0.72rem;
            color: #64748b;
            font-family: monospace;
        }

        /* Dashboard Cards */
        .dash-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            padding: 1.35rem;
            height: 100%;
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1.2;
            color: #0f172a;
        }

        .stat-unit {
            font-size: 0.85rem;
            font-weight: 500;
            color: #64748b;
            margin-left: 0.35rem;
        }

        /* Featured Dashed Card (Jadwal) */
        .featured-schedule-card {
            border: 2px dashed #60a5fa !important;
            border-radius: 16px;
            background: #ffffff;
        }

        /* Badges */
        .badge-soft-success {
            background-color: #dcfce7;
            color: #15803d;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.35rem 0.65rem;
        }

        .badge-soft-primary {
            background-color: #dbeafe;
            color: #1d4ed8;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.35rem 0.65rem;
        }

        .badge-soft-warning {
            background-color: #fef3c7;
            color: #b45309;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.35rem 0.65rem;
        }

        .badge-soft-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.35rem 0.65rem;
        }

        .badge-soft-secondary {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.35rem 0.65rem;
        }

        /* Table styling */
        .table-custom th {
            co or: #94a3b8;
            font-size: 0.75rem; 
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 0.75rem;
        }   

        .table-custom td {
            vertical-align: middle;
            font-size: 0.88rem;
            padding: 0.85rem 0.25rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
        }

        .progress-slim {
            height: 6px;
            border-radius: 999px;
            background-color: #e2e8f0;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar d-flex flex-column justify-content-between">
            <div>
                <!-- Brand Header -->
                <div class="d-flex align-items-center gap-2 mb-4 px-1">
                    <div class="brand-logo">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.2px;">RACER ROBOTIC</h6>
                        <small class="text-muted" style="font-size: 0.74rem;">Administrasi Akademik</small>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="mb-3">
                    <small class="text-uppercase text-muted fw-bold px-2 mb-2 d-block"
                        style="font-size: 0.68rem; letter-spacing: 0.8px;">Menu Utama</small>
                    <nav class="nav flex-column">
                        <a href="{{ route('admin') }}" class="nav-link-custom active">
                            <i class="bi bi-grid-fill"></i>
                            <span>Beranda</span>
                        </a>
                        <a href="{{ route('admin.keluarga') }}" class="nav-link-custom">
                            <i class="bi bi-people"></i>
                            <span>Akun Keluarga & Siswa</span>
                        </a>
                        <a href="{{ route('admin.tutor') }}" class="nav-link-custom">
                            <i class="bi bi-person-badge"></i>
                            <span>Data Tutor</span>
                        </a>
                        <a href="{{ route('admin.penjadwalan') }}"
                            class="nav-link-custom {{ request()->routeIs('admin.penjadwalan') ? 'active' : '' }}">
                            <i class="bi bi-calendar4-week"></i>
                            <span>Penjadwalan Terpusat</span>
                        </a>
                        <a href="#tabelPresensi" class="nav-link-custom">
                            <i class="bi bi-clipboard2-check"></i>
                            <span>Presensi & Reschedule</span>
                        </a>
                        <a href="#tabelPengumuman" class="nav-link-custom">
                            <i class="bi bi-megaphone"></i>
                            <span>Pengumuman Lembaga</span>
                        </a>
                        <a href="#" class="nav-link-custom">
                            <i class="bi bi-graph-up"></i>
                            <span>Laporan Bulanan</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Bottom Support & User Profile -->
            <div>
                <!-- Support Banner -->
                <div class="support-card mb-3">
                    <div class="d-flex gap-2">
                        <div class="text-primary mt-1">
                            <i class="bi bi-question-circle-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.85rem;">Butuh bantuan operasional?
                            </h6>
                            <p class="text-muted mb-2" style="font-size: 0.74rem; line-height: 1.3;">Tim support siap
                                membantu Anda.</p>
                            <a href="#" class="text-primary fw-semibold text-decoration-none"
                                style="font-size: 0.76rem;">Hubungi tim support <i class="bi bi-arrow-up-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- User profile info -->
                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin Operasional') }}&background=fee2e2&color=dc2626&bold=true"
                            alt="Avatar" class="rounded-circle" width="36" height="36">
                        <div style="line-height: 1.2;">
                            <div class="fw-bold text-dark text-truncate" style="max-width: 120px; font-size: 0.82rem;">
                                {{ Auth::user()->name ?? 'Admin Operasional' }}
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ Auth::user()->email ??
                                'admin@racer-robotic.id' }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-logout-sidebar" title="Logout" data-bs-toggle="modal"
                        data-bs-target="#modalKonfirmasiLogout">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT WRAPPER ================= -->
        <main class="main-wrapper flex-grow-1">

            <!-- Top Header Search & Controls -->
            <header class="top-navbar d-flex align-items-center justify-content-between flex-wrap gap-3">
                <!-- Mobile toggle & Search bar -->
                <div class="d-flex align-items-center gap-2 flex-grow-1">
                    <button class="btn btn-light d-lg-none border" id="toggleSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <form action="{{ route('admin') }}" method="GET" class="search-container flex-grow-1"
                        style="max-width: 480px;">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="q" class="search-input w-100" value="{{ $search ?? '' }}"
                            placeholder="Cari nama siswa, nama wali, atau tutor...">
                        <span class="search-shortcut">&#8984; K</span>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge border bg-light text-secondary rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.78rem;">
                        <i class="bi bi-calendar-event me-1 text-primary"></i> T.A 2026/2027
                    </span>

                    <button class="btn btn-white bg-white border rounded-circle p-2 position-relative" style="width: 40px; height: 40px;" title="Notifikasi">
                        <i class="bi bi-bell text-secondary"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>

                    <div class="d-flex align-items-center gap-2 ps-2 border-start">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin Operasional') }}&background=fee2e2&color=dc2626&bold=true"
                            alt="Avatar" class="rounded-circle" width="36" height="36">
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark" style="font-size: 0.8rem; line-height: 1.1;">{{ Auth::user()->name ?? 'Admin' }}</div>
                            <small class="text-muted" style="font-size: 0.68rem;">Super Admin</small>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Body -->
            <div class="p-4 pt-2">

                <!-- Flash Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        @if (session('selected_siswa_id'))
                            <a href="{{ route('admin.keluarga', ['open' => 'aktivasi', 'siswa_id' => session('selected_siswa_id')]) }}" class="btn btn-sm btn-success text-white fw-semibold rounded-pill px-3 py-1 shadow-sm">
                                <i class="bi bi-lightning-charge me-1"></i> Aktivasi Paket Sekarang
                            </a>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Welcome Greeting & Action Buttons -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">Selamat Datang,
                            {{ Auth::user()->name ?? 'Administrator' }}!
                        </h3>
                        <p class="text-muted mb-0" style="font-size: 0.88rem;">Pantau operasional kelas dan paket siswa
                            hari ini.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <!-- Tombol Buka Modal Akun Keluarga -->
                        <button class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3"
                            data-bs-toggle="modal" data-bs-target="#modalAkunKeluarga"
                            style="background-color: var(--primary-blue); font-size: 0.88rem;">
                            <i class="bi bi-plus-lg"></i> Akun Keluarga
                        </button>
                        <!-- Tombol Akses Cepat Aktivasi Paket -->
                        <a href="{{ route('admin.keluarga', ['open' => 'aktivasi']) }}"
                            class="btn btn-white bg-white border d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3 text-primary"
                            style="font-size: 0.88rem; border-color: var(--primary-blue) !important;">
                            <i class="bi bi-lightning-charge"></i> Aktivasi Paket
                        </a>
                        <!-- Tombol Buka Modal Buat Jadwal -->
                        <button
                            class="btn btn-white bg-white border d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3 text-dark"
                            data-bs-toggle="modal" data-bs-target="#modalBuatJadwal" style="font-size: 0.88rem;">
                            <i class="bi bi-plus-lg"></i> Buat Jadwal
                        </button>
                    </div>
                </div>

                <!-- 4 Top Metric Cards (Dinamis dari Controller) -->
                <div class="row g-3 mb-4">
                    <!-- Stat 1 -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Siswa
                                Aktif</span>
                            <div class="d-flex align-items-baseline mb-2">
                                <span class="stat-value">{{ $siswaAktifCount }}</span>
                                <span class="stat-unit">siswa</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.76rem;">+12 siswa dari bulan lalu</small>
                        </div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Kelas Hari
                                Ini</span>
                            <div class="d-flex align-items-baseline mb-2">
                                <span class="stat-value">{{ $kelasHariIniCount }}</span>
                                <span class="stat-unit">kelas</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.76rem;">{{ $kelasSelesai }} selesai ·
                                {{ $kelasBerlangsung }} berlangsung · {{ $kelasBerikutnya }}
                                berikutnya</small>
                        </div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Tutor
                                Aktif</span>
                            <div class="d-flex align-items-baseline mb-2">
                                <span class="stat-value">{{ $tutorAktifCount }}</span>
                                <span class="stat-unit">tutor</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.76rem;">{{ $tutorMengajarCount }} tutor
                                mengajar hari ini</small>
                        </div>
                    </div>

                    <!-- Stat 4 -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Paket Hampir
                                Habis</span>
                            <div class="d-flex align-items-baseline mb-2">
                                <span class="stat-value">{{ $paketHampirHabisCount }}</span>
                                <span class="stat-unit">siswa</span>
                            </div>
                            <small class="text-warning fw-semibold"
                                style="font-size: 0.76rem; color: #b45309 !important;">
                                {{ $paketHampirHabisCount }} paket perlu perhatian
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Main Content Split: Left (8 cols) and Right (4 cols) -->
                <div class="row g-4">

                    <!-- ================= LEFT COLUMN ================= -->
                    <div class="col-12 col-xl-8">

                        <!-- Featured Schedule Card (Blue Dashed Border) -->
                        <div class="dash-card featured-schedule-card p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <h5 class="fw-bold mb-0 text-dark">Jadwal Kelas Hari Ini</h5>
                                    <span class="badge-soft-primary px-2 py-1"
                                        style="font-size: 0.75rem;">{{ $kelasHariIniCount }} kelas</span>
                                </div>
                                <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.82rem;">
                                    <i class="bi bi-calendar2-event"></i>
                                    <span>{{ date('l, d F Y') }}</span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 22%;">Waktu</th>
                                            <th style="width: 32%;">Kelas / Lokasi</th>
                                            <th style="width: 26%;">Tutor</th>
                                            <th style="width: 20%;" class="text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($jadwalHariIni as $j)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $j->waktu_mulai }}</div>
                                                    <small class="text-muted"
                                                        style="font-size: 0.75rem;">{{ $j->waktu_selesai }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark">{{ $j->kelas }}</div>
                                                    <small class="text-muted"
                                                        style="font-size: 0.75rem;">{{ $j->lokasi }}</small>
                                                </td>
                                                <td><span class="text-muted">{{ $j->tutor }}</span></td>
                                                <td class="text-end">
                                                    @if ($j->status === 'Selesai')
                                                        <span class="badge-soft-success me-2">Selesai</span>
                                                    @elseif ($j->status === 'Berlangsung')
                                                        <span class="badge-soft-primary me-2">Berlangsung</span>
                                                    @else
                                                        <span class="badge-soft-secondary me-2">Terjadwal</span>
                                                    @endif

                                                    <!-- Tombol Buka Detail & Peserta Jadwal -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2 py-1 me-1"
                                                        onclick="openPesertaModalAdmin({{ $j->id }}, '{{ addslashes($j->kelas ?? $j->program) }}', '{{ addslashes($j->program ?? '') }}', '{{ addslashes($j->tutor ?? '') }}', '{{ addslashes($j->lokasi ?? '') }}', '{{ addslashes($j->tanggal ?? 'Hari Ini') }}', '{{ addslashes($j->waktu_mulai . ' - ' . $j->waktu_selesai) }}', '{{ addslashes($j->moda ?? 'Offline') }}', '{{ addslashes($j->status ?? 'Terjadwal') }}')"
                                                        title="Detail & Peserta Siswa" style="font-size: 0.76rem;">
                                                        <i class="bi bi-people-fill me-1"></i> Peserta
                                                    </button>

                                                    <!-- Dropdown Ubah Status -->
                                                    <div class="dropdown d-inline">
                                                        <button class="btn btn-sm btn-link text-muted p-0"
                                                            data-bs-toggle="dropdown">
                                                            <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                            style="font-size: 0.8rem;">
                                                            <li>
                                                                <h6 class="dropdown-header">Ubah Status</h6>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.jadwal.status', $j->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Berlangsung">
                                                                    <button type="submit" class="dropdown-item">Mulai
                                                                        (Berlangsung)</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.jadwal.status', $j->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Selesai">
                                                                    <button type="submit" class="dropdown-item">Tandai
                                                                        Selesai</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.jadwal.status', $j->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Terjadwal">
                                                                    <button type="submit" class="dropdown-item">Reset ke
                                                                        Terjadwal</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Belum ada jadwal kelas
                                                    hari ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center pt-3 border-top mt-2">
                                <a href="{{ route('admin.penjadwalan') }}"
                                    class="text-primary fw-semibold text-decoration-none d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.85rem;">
                                    Buka penjadwalan terpusat <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Paket Siswa Perlu Perhatian -->
                        <div class="dash-card p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Paket Siswa Perlu Perhatian</h5>
                                    <small class="text-muted">Hubungi wali untuk perpanjangan paket belajar.</small>
                                </div>
                                <a href="#modalAkunKeluarga" data-bs-toggle="modal"
                                    class="text-primary fw-semibold text-decoration-none" style="font-size: 0.85rem;">
                                    + Tambah Siswa <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th>Siswa / Wali</th>
                                            <th>Paket Belajar</th>
                                            <th>Pemakaian</th>
                                            <th class="text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($paketPerhatian as $p)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-dark">
                                                        {{ $p->nama }}
                                                        @if (!empty($p->nama_panggilan))
                                                            <span class="text-muted fw-normal"
                                                                style="font-size: 0.78rem;">({{ $p->nama_panggilan }})</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                        {{ $p->nama_wali }}
                                                        @if (!empty($p->jenjang) || !empty($p->kelas))
                                                            · {{ $p->jenjang }}
                                                            {{ !str_starts_with(strtoupper((string) $p->kelas), strtoupper((string) $p->jenjang)) ? 'Kelas ' . $p->kelas : $p->kelas }}
                                                        @endif
                                                        @if (!empty($p->tanggal_lahir))
                                                            · Lahir:
                                                            {{ \Carbon\Carbon::parse($p->tanggal_lahir)->format('d/m/Y') }}
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $p->paket_belajar }}</span>
                                                </td>
                                                <td style="min-width: 140px;">
                                                    <div class="d-flex justify-content-between mb-1"
                                                        style="font-size: 0.75rem;">
                                                        <span class="text-muted">{{ $p->sesi_terpakai }} dari
                                                            {{ $p->total_sesi }} sesi</span>
                                                    </div>
                                                    <div class="progress progress-slim">
                                                        <div class="progress-bar {{ $p->sisa_sesi === 0 ? 'bg-danger' : 'bg-primary' }}"
                                                            style="width: {{ $p->persentase }}%;"></div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    @if ($p->sisa_sesi === 0)
                                                        <span class="badge-soft-danger me-2">Paket habis</span>
                                                    @elseif ($p->sisa_sesi <= 2)
                                                        <span class="badge-soft-warning me-2">Sisa {{ $p->sisa_sesi }}
                                                            sesi</span>
                                                    @else
                                                        <span class="badge-soft-success me-2">Aktif</span>
                                                    @endif
                                                    <i class="bi bi-three-dots text-muted"></i>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Tidak ada data paket
                                                    siswa.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- ================= RIGHT COLUMN ================= -->
                    <div class="col-12 col-xl-4">

                        <!-- Presensi & Reschedule Card -->
                        <div class="dash-card p-4 mb-4" id="tabelPresensi">
                            <h5 class="fw-bold mb-1 text-dark">Presensi & Reschedule</h5>
                            <small class="text-muted d-block mb-3">Kehadiran hari ini</small>

                            <div class="d-flex align-items-baseline justify-content-between mb-2">
                                <div class="stat-value" style="font-size: 1.85rem;">{{ $presensi['hadir'] }} /
                                    {{ $presensi['total'] }} <span class="stat-unit">siswa</span>
                                </div>
                                <span class="badge-soft-success">{{ $presensi['persentase'] }}% hadir</span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="progress progress-slim mb-3" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: {{ $presensi['persentase'] }}%;">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between text-muted mb-3" style="font-size: 0.74rem;">
                                <span><i class="bi bi-circle-fill text-primary" style="font-size: 0.5rem;"></i>
                                    {{ $presensi['hadir'] }} hadir</span>
                                <span><i class="bi bi-circle-fill text-danger" style="font-size: 0.5rem;"></i>
                                    {{ $presensi['absen'] }} absen</span>
                                <span><i class="bi bi-circle-fill text-secondary" style="font-size: 0.5rem;"></i>
                                    {{ $presensi['menunggu'] }} menunggu</span>
                            </div>

                            <!-- Alert Box -->
                            <div class="alert alert-primary py-2 px-3 d-flex align-items-center gap-2 mb-4"
                                style="background-color: #ebf3ff; border: none; border-radius: 10px; font-size: 0.8rem; color: #1d4ed8;">
                                <i class="bi bi-info-circle"></i>
                                <span>Notifikasi: Presensi kelas otomatis terupdate</span>
                            </div>

                            <!-- Sub-section: Menunggu Persetujuan -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-dark" style="font-size: 0.86rem;">Menunggu persetujuan</span>
                                <span class="badge-soft-warning px-2 py-1"
                                    style="font-size: 0.72rem;">{{ $rescheduleList->count() }} reschedule</span>
                            </div>

                            <!-- List Item Reschedule -->
                            @forelse ($rescheduleList as $res)
                                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">{{ $res->inisial ?? 'RS' }}</div>
                                        <div style="line-height: 1.2;">
                                            <div class="fw-semibold text-dark" style="font-size: 0.84rem;">
                                                {{ $res->nama_siswa }}
                                            </div>
                                            <small class="text-muted" style="font-size: 0.72rem;">{{ $res->jadwal_lama }}
                                                &rarr; {{ $res->jadwal_baru }} · {{ $res->kelas }}</small>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.reschedule.approve', $res->id) }}" method="POST"
                                        class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary py-0 px-2"
                                            style="font-size: 0.72rem;" title="Setujui">
                                            Setujui
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3" style="font-size: 0.8rem;">
                                    Tidak ada pengajuan reschedule pending.
                                </div>
                            @endforelse
                        </div>

                        <!-- Pengumuman Lembaga Card -->
                        <div class="dash-card p-4" id="tabelPengumuman">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-dark">Pengumuman Lembaga</h5>
                                <a href="#" class="text-primary fw-semibold text-decoration-none"
                                    style="font-size: 0.82rem;">
                                    Semua <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            @forelse ($pengumumanList as $item)
                                <div class="py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        @if ($item->is_penting)
                                            <span class="badge bg-primary px-2 py-1" style="font-size: 0.68rem;">
                                                <i class="bi bi-pin-angle-fill me-1"></i>{{ $item->kategori }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1"
                                                style="font-size: 0.68rem;">{{ $item->kategori }}</span>
                                        @endif
                                        <small class="text-muted" style="font-size: 0.72rem;">{{ $item->tanggal }}</small>
                                    </div>
                                    <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.86rem;">{{ $item->judul }}</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.76rem;">{{ $item->konten }}</p>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3" style="font-size: 0.8rem;">
                                    Belum ada pengumuman baru.
                                </div>
                            @endforelse
                        </div>

                    </div>

                </div>

            </div>
        </main>
    </div>

    <!-- ================= MODAL: BUAT JADWAL ================= -->
    <div class="modal fade" id="modalBuatJadwal" tabindex="-1" aria-labelledby="modalBuatJadwalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="modalBuatJadwalLabel">Buat Jadwal Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.jadwal.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Program Kelas</label>
                            <input type="text" name="program" class="form-control rounded-3"
                                placeholder="Contoh: Junior Robotics" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Lokasi / Ruangan</label>
                            <input type="text" name="lokasi" class="form-control rounded-3"
                                placeholder="Contoh: Lab Robotika 1, Lab Maker, atau Tautan Google Meet" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Tutor Pengampu <span
                                    class="badge bg-success-subtle text-success fw-normal ms-1"
                                    style="font-size: 0.72rem;">Aktif</span></label>
                            <select name="tutor_pengampu" class="form-select rounded-3" required>
                                <option value="" disabled selected>Pilih Tutor Pengampu...</option>
                                @if(isset($activeTutors) && count($activeTutors) > 0)
                                    @foreach($activeTutors as $tut)
                                        <option value="{{ $tut->nama_panggilan ?? $tut->nama_lengkap }}">
                                            {{ $tut->nama_gelar ?? $tut->nama_lengkap }} ({{ $tut->nama_panggilan }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="Kak Dimas">Kak Dimas</option>
                                    <option value="Kak Sarah">Kak Sarah</option>
                                    <option value="Kak Reza">Kak Reza</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Tanggal Kelas</label>
                            <input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control rounded-3" value="09:00"
                                    required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control rounded-3" value="10:30"
                                    required>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Moda
                                    Pembelajaran</label>
                                <select name="moda" class="form-select rounded-3">
                                    <option value="Offline" selected>Offline (Tatap Muka)</option>
                                    <option value="Online">Online (Daring / Meet)</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Status</label>
                                <select name="status" class="form-select rounded-3">
                                    <option value="Terjadwal" selected>Terjadwal</option>
                                    <option value="Berlangsung">Berlangsung</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-3"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4"
                            style="background-color: var(--primary-blue);">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL: DETAIL JADWAL & PEMILIHAN PESERTA SISWA ================= -->
    <div class="modal fade" id="modalPesertaJadwalAdmin" tabindex="-1" aria-labelledby="modalPesertaJadwalAdminLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="modalPesertaJadwalAdminLabel">
                            <i class="bi bi-calendar2-week text-primary me-2"></i>Detail Jadwal & Peserta Siswa
                        </h5>
                        <p class="text-muted mb-0" id="pesertaJadwalAdminSubtitle" style="font-size: 0.84rem;">
                            Memuat informasi sesi kelas...
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-3">
                    <!-- Ringkasan Detail Jadwal Kelas -->
                    <div class="card border-0 rounded-3 p-3 mb-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0 !important;">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom">
                            <div>
                                <span class="fw-bold text-dark" id="adminDetailKelasNama" style="font-size: 1rem;">-</span>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2" id="adminDetailProgram">-</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-secondary-subtle text-secondary" id="adminDetailModa">Offline</span>
                                <span class="badge bg-success-subtle text-success" id="adminDetailStatus">Terjadwal</span>
                            </div>
                        </div>
                        <div class="row g-2 text-muted" style="font-size: 0.82rem;">
                            <div class="col-6 col-md-3">
                                <span class="d-block text-secondary" style="font-size: 0.72rem;">TANGGAL</span>
                                <span class="fw-semibold text-dark" id="adminDetailTanggal">-</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="d-block text-secondary" style="font-size: 0.72rem;">WAKTU</span>
                                <span class="fw-semibold text-dark" id="adminDetailWaktu">-</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="d-block text-secondary" style="font-size: 0.72rem;">LOKASI / RUANGAN</span>
                                <span class="fw-semibold text-dark" id="adminDetailLokasi">-</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="d-block text-secondary" style="font-size: 0.72rem;">TUTOR PENGAMPU</span>
                                <span class="fw-semibold text-dark" id="adminDetailTutor">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah Peserta -->
                    <div class="card border-0 bg-light rounded-3 p-3 mb-4">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">
                            <i class="bi bi-person-plus-fill text-primary me-1"></i> Tambah Peserta Sesuai Program & Kuota Aktif
                        </h6>
                        <form id="formTambahPesertaAdmin" onsubmit="submitTambahPesertaAdmin(event)">
                            <input type="hidden" id="currentJadwalIdAdmin">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">Pilih Siswa (Hanya Siswa dengan Program Sesuai & Sisa Sesi > 0)</label>
                                    <select id="selectPilihPesertaAdmin" class="form-select rounded-3" required style="font-size: 0.85rem;">
                                        <option value="">-- Memuat siswa yang memenuhi syarat... --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" id="btnSubmitPesertaAdmin" class="btn btn-primary w-100 rounded-3 fw-semibold py-2" style="background-color: var(--primary-blue); font-size: 0.85rem;">
                                        <i class="bi bi-plus-circle me-1"></i> Daftarkan Peserta
                                    </button>
                                </div>
                            </div>
                            <div id="alertPesertaMsgAdmin" class="mt-2" style="display: none;"></div>
                        </form>
                    </div>

                    <!-- Daftar Peserta Terdaftar -->
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;">
                        <i class="bi bi-card-checklist text-primary me-1"></i> Daftar Peserta Terdaftar (<span id="totalPesertaCountAdmin">0</span>)
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.84rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Program / Paket</th>
                                    <th>Sisa Kuota</th>
                                    <th>Status Kehadiran</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPesertaJadwalAdmin">
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                        Memuat daftar peserta...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL: AKUN KELUARGA & SISWA ================= -->
    <div class="modal fade" id="modalAkunKeluarga" tabindex="-1" aria-labelledby="modalAkunKeluargaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="modalAkunKeluargaLabel">Tambah Akun Keluarga & Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.keluarga.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Data Siswa -->
                            <div class="col-12">
                                <small class="text-uppercase text-primary fw-bold"
                                    style="font-size: 0.72rem; letter-spacing: 0.6px;">Data Siswa (Anak)</small>
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Lengkap
                                    Siswa</label>
                                <input type="text" name="nama" id="inputNamaLengkapSiswa" class="form-control rounded-3"
                                    placeholder="Contoh: Andi Santoso" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" id="inputNamaPanggilanSiswa"
                                    class="form-control rounded-3" placeholder="Contoh: Andi" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control rounded-3"
                                    value="2017-05-12" required>
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Jenjang</label>
                                <select name="jenjang" class="form-select rounded-3" required>
                                    <option value="SD" selected>SD</option>
                                    <option value="TK">TK</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Kelas</label>
                                <input type="text" name="kelas" class="form-control rounded-3" value="3"
                                    placeholder="Contoh: 3 / TK B" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">NISN <span
                                        class="text-muted fw-normal">(Opsional)</span></label>
                                <input type="text" name="nisn" class="form-control rounded-3"
                                    placeholder="Nomor Induk Siswa Nasional">
                            </div>

                            <!-- Komponen Pemilih Tipe Paket & Pengisian Kuota Otomatis -->
                            <div class="col-12 pt-2 border-top">
                                <small class="text-uppercase text-primary fw-bold"
                                    style="font-size: 0.72rem; letter-spacing: 0.6px;">Pilihan Paket Belajar & Kuota Otomatis</small>
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Tipe & Paket Belajar <span class="text-danger">*</span></label>
                                <select name="paket_belajar" id="selectPaketBelajar" class="form-select rounded-3"
                                    required>
                                    <optgroup label="Paket Regular — Per Bulan (4x Pertemuan)">
                                        <option value="Regular Gold (1 Orang) · Bulanan (4x Pertemuan)" data-sesi="4"
                                            selected>Regular - Gold (1 Orang) · Per Bulan (4x Pertemuan)</option>
                                        <option value="Regular Silver (2 Orang) · Bulanan (4x Pertemuan)" data-sesi="4">
                                            Regular - Silver (2 Orang) · Per Bulan (4x Pertemuan)</option>
                                        <option value="Regular Bronze (3 Orang) · Bulanan (4x Pertemuan)" data-sesi="4">
                                            Regular - Bronze (3 Orang) · Per Bulan (4x Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Regular — 1 Semester (12x Pertemuan)">
                                        <option value="Regular Gold (1 Orang) · 1 Semester (12x Pertemuan)"
                                            data-sesi="12">Regular - Gold (1 Orang) · 1 Semester (12x Pertemuan)
                                        </option>
                                        <option value="Regular Silver (2 Orang) · 1 Semester (12x Pertemuan)"
                                            data-sesi="12">Regular - Silver (2 Orang) · 1 Semester (12x Pertemuan)
                                        </option>
                                        <option value="Regular Bronze (3 Orang) · 1 Semester (12x Pertemuan)"
                                            data-sesi="12">Regular - Bronze (3 Orang) · 1 Semester (12x Pertemuan)
                                        </option>
                                    </optgroup>
                                    <optgroup label="Paket Private">
                                        <option value="Private · Bulanan (4x Pertemuan)" data-sesi="4">Paket Private ·
                                            Per Bulan (4x Pertemuan)</option>
                                        <option value="Private · 1 Semester (12x Pertemuan)" data-sesi="12">Paket
                                            Private · 1 Semester (12x Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Online">
                                        <option value="Online · Bulanan (4x Pertemuan)" data-sesi="4">Paket Online · Per
                                            Bulan (4x Pertemuan)</option>
                                        <option value="Online · 1 Semester (12x Pertemuan)" data-sesi="12">Paket Online
                                            · 1 Semester (12x Pertemuan)</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                                    <span>Kuota Sesi</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0" style="font-size: 0.68rem;">Otomatis</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3">
                                        <i class="bi bi-clock-history text-primary"></i>
                                    </span>
                                    <input type="number" name="total_sesi" id="inputTotalSesi" class="form-control bg-light fw-bold text-dark border-start-0 rounded-end-3" value="4" min="1" max="100" readonly style="font-size: 0.9rem;">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="p-2 px-3 rounded-3 d-flex align-items-center gap-2" style="background-color: #f0fdf4; border: 1px solid #bbf7d0;">
                                    <i class="bi bi-check-circle-fill text-success flex-shrink-0" style="font-size: 0.92rem;"></i>
                                    <small id="infoKuotaAdminText" class="text-success fw-medium" style="font-size: 0.78rem;">
                                        Kuota dialokasikan otomatis: <strong>4 sesi</strong> (Paket Bulanan). Sesuai aturan paket belajar.
                                    </small>
                                </div>
                            </div>

                            <!-- Data Akun Keluarga & Primary Parent (Wali) -->
                            <div class="col-12 pt-2 border-top">
                                <small class="text-uppercase text-primary fw-bold"
                                    style="font-size: 0.72rem; letter-spacing: 0.6px;">Data Akun Keluarga & Primary
                                    Parent</small>
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Primary Parent
                                    (Wali) <span class="text-danger">*</span></label>
                                <input type="text" name="nama_wali" id="inputNamaWali" class="form-control rounded-3"
                                    placeholder="Contoh: Ibu Ratna" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Hubungan</label>
                                <select name="hubungan" id="selectHubunganWali" class="form-select rounded-3">
                                    <option value="Ibu" selected>Ibu</option>
                                    <option value="Ayah">Ayah</option>
                                    <option value="Wali">Wali</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Keluarga <span
                                        class="text-muted fw-normal">(Opsional)</span></label>
                                <input type="text" name="nama_keluarga" id="inputNamaKeluarga"
                                    class="form-control rounded-3" placeholder="Contoh: Keluarga Andi (Otomatis)">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">No. WhatsApp Wali
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_whatsapp" class="form-control rounded-3"
                                    value="081234567890" placeholder="08... / +62..." required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Username Login <span
                                        class="text-muted fw-normal">(Opsional)</span></label>
                                <input type="text" name="username_wali" id="inputUsernameWali"
                                    class="form-control rounded-3" placeholder="ibu.andi (Otomatis)">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Password Awal</label>
                                <input type="text" name="password_wali" class="form-control rounded-3"
                                    value="RacerWali2026!">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Alamat
                                    Domisili</label>
                                <input type="text" name="alamat_domisili" class="form-control rounded-3"
                                    value="Jl. Contoh No. 1, RT 02/RW 05" placeholder="Jalan, nomor rumah, RT/RW">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Kelurahan</label>
                                <input type="text" name="kelurahan" class="form-control rounded-3" value="Menteng"
                                    placeholder="Kelurahan">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control rounded-3" value="Menteng"
                                    placeholder="Kecamatan">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Kota /
                                    Kabupaten</label>
                                <input type="text" name="kota_kabupaten" class="form-control rounded-3"
                                    value="Jakarta Pusat" placeholder="Kota / Kabupaten">
                            </div>

                            <!-- Data Secondary Parent (Opsional - Maks. 1 per Keluarga) -->
                            <div class="col-12 mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-uppercase fw-bold text-muted"
                                        style="font-size: 0.72rem; letter-spacing: 0.6px;">Secondary Parent (Wali Pendamping - Opsional)</small>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">Maks. 1 per Keluarga</span>
                                </div>
                                <p class="text-muted mb-2" style="font-size: 0.78rem;">
                                    Pasangan atau wali pendamping opsional agar memiliki akses login memantau anak di portal Orang Tua. Kosongkan jika tidak ada.
                                </p>
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Secondary Parent</label>
                                <input type="text" name="nama_secondary_parent" class="form-control rounded-3"
                                    placeholder="Contoh: Bpk. Arif Wibowo">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Hubungan</label>
                                <select name="hubungan_secondary_parent" class="form-select rounded-3">
                                    <option value="Ayah" selected>Ayah</option>
                                    <option value="Ibu">Ibu</option>
                                    <option value="Wali">Wali</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">No. WhatsApp Secondary Parent</label>
                                <input type="text" name="nomor_whatsapp_secondary_parent" class="form-control rounded-3"
                                    placeholder="081399998888">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Username Login <span class="text-muted fw-normal">(Opsional)</span></label>
                                <input type="text" name="username_secondary_parent" class="form-control rounded-3"
                                    placeholder="ayah.andi (Otomatis)">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Password Awal</label>
                                <input type="text" name="password_secondary_parent" class="form-control rounded-3"
                                    value="RacerWali2026!">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-3"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4"
                            style="background-color: var(--primary-blue);">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="modalKonfirmasiLogout" tabindex="-1" aria-labelledby="modalKonfirmasiLogoutLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content rounded-4 border-0 shadow-lg text-center p-4">
                <div class="modal-body p-2">
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 70px; height: 70px; background-color: #fee2e2; color: #dc2626;">
                            <i class="bi bi-box-arrow-right fs-1"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2" id="modalKonfirmasiLogoutLabel">Konfirmasi Keluar</h5>
                    <p class="text-muted mb-4" style="font-size: 0.9rem; line-height: 1.5;">
                        Apakah Anda yakin ingin keluar dari akun ini? Sesi Anda akan diakhiri.
                    </p>
                    <div class="d-flex gap-2">
                        <button type="button"
                            class="btn btn-light border bg-white px-3 py-2 rounded-3 text-secondary fw-semibold w-50"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="m-0 w-50">
                            @csrf
                            <button type="submit"
                                class="btn btn-confirm-logout px-3 py-2 rounded-3 fw-semibold w-100 shadow-sm">
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
                        style="background-color: var(--primary-blue, #0d6efd);">
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
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Sinkronisasi otomatis Total Sesi & Kuota berdasarkan dropdown Paket Belajar (Bulanan = 4x, Semester = 12x)
        const selectPaketBelajar = document.getElementById('selectPaketBelajar');
        const inputTotalSesi = document.getElementById('inputTotalSesi');
        const infoKuotaAdminText = document.getElementById('infoKuotaAdminText');

        function updateKuotaAdmin() {
            if (!selectPaketBelajar || !inputTotalSesi) return;
            const selectedOption = selectPaketBelajar.options[selectPaketBelajar.selectedIndex];
            let sesi = 4;
            let labelTipe = 'Paket Bulanan';
            if (selectedOption) {
                sesi = selectedOption.getAttribute('data-sesi') || (selectedOption.value.includes('12') || selectedOption.value.toLowerCase().includes('semester') ? 12 : 4);
                labelTipe = (sesi == 12) ? 'Paket 1 Semester' : 'Paket Bulanan';
            }
            inputTotalSesi.value = sesi;
            if (infoKuotaAdminText) {
                infoKuotaAdminText.innerHTML = `Kuota dialokasikan otomatis: <strong>${sesi} sesi</strong> (${labelTipe}). Sesuai aturan paket belajar.`;
            }
        }

        if (selectPaketBelajar) {
            selectPaketBelajar.addEventListener('change', updateKuotaAdmin);
            updateKuotaAdmin();
        }

        // Otomatis isi Nama Panggilan dari kata pertama Nama Lengkap jika Nama Panggilan belum diubah manual
        const inputNamaLengkap = document.getElementById('inputNamaLengkapSiswa');
        const inputNamaPanggilan = document.getElementById('inputNamaPanggilanSiswa');
        if (inputNamaLengkap && inputNamaPanggilan) {
            let manualPanggilan = false;
            inputNamaPanggilan.addEventListener('input', () => {
                manualPanggilan = inputNamaPanggilan.value.trim() !== '';
            });
            inputNamaLengkap.addEventListener('input', () => {
                if (!manualPanggilan) {
                    const firstWord = inputNamaLengkap.value.trim().split(/\s+/)[0] || '';
                    inputNamaPanggilan.value = firstWord;
                }
            });
        }

        // Otomatis isi Nama Keluarga (berdasarkan nama anak) & Username Login (sesuai hubungan: ibu/ayah/wali.<nama_anak>)
        const inputNamaLengkapSiswa = document.getElementById('inputNamaLengkapSiswa');
        const inputNamaPanggilanSiswa = document.getElementById('inputNamaPanggilanSiswa');
        const selectHubunganWali = document.getElementById('selectHubunganWali');
        const inputNamaWali = document.getElementById('inputNamaWali');
        const inputUsernameWali = document.getElementById('inputUsernameWali');
        const inputNamaKeluarga = document.getElementById('inputNamaKeluarga');

        if (inputUsernameWali && inputNamaKeluarga) {
            let manualUsername = false;
            let manualKeluarga = false;

            inputUsernameWali.addEventListener('input', () => {
                manualUsername = inputUsernameWali.value.trim() !== '';
            });

            inputNamaKeluarga.addEventListener('input', () => {
                manualKeluarga = inputNamaKeluarga.value.trim() !== '';
            });

            function getNamaTargetDanSlug() {
                const panggilan = inputNamaPanggilanSiswa ? inputNamaPanggilanSiswa.value.trim() : '';
                if (panggilan) {
                    const slug = panggilan.toLowerCase().replace(/[^a-z0-9]/g, '');
                    return { name: panggilan, slug: slug };
                }

                const lengkap = inputNamaLengkapSiswa ? inputNamaLengkapSiswa.value.trim() : '';
                if (lengkap) {
                    const firstWord = lengkap.split(/\s+/)[0];
                    const slug = firstWord.toLowerCase().replace(/[^a-z0-9]/g, '');
                    return { name: firstWord, slug: slug };
                }

                // Fallback jika nama anak belum diisi tapi nama wali diisi
                const rawWali = inputNamaWali ? inputNamaWali.value.trim() : '';
                if (rawWali) {
                    const cleaned = rawWali.replace(/^(wali\s*:\s*|bpk\.?\s*|ibu\s*|pak\s*|ayah\s*|bunda\s*|mama\s*|papa\s*)/i, '').trim() || rawWali;
                    const firstWord = cleaned.split(/\s+/)[0] || '';
                    const slug = firstWord.toLowerCase().replace(/[^a-z0-9]/g, '');
                    return { name: cleaned, slug: slug };
                }

                return { name: '', slug: '' };
            }

            function syncKeluargaAndUsername() {
                const target = getNamaTargetDanSlug();
                const hubungan = selectHubunganWali ? selectHubunganWali.value.trim().toLowerCase() : 'ibu';
                const prefix = (hubungan === 'ayah' || hubungan === 'wali') ? hubungan : 'ibu';

                if (!manualUsername) {
                    if (target.slug) {
                        inputUsernameWali.value = prefix + '.' + target.slug;
                    } else {
                        inputUsernameWali.value = '';
                    }
                }

                if (!manualKeluarga) {
                    if (target.name) {
                        inputNamaKeluarga.value = 'Keluarga ' + target.name;
                    } else {
                        inputNamaKeluarga.value = '';
                    }
                }
            }

            if (inputNamaLengkapSiswa) inputNamaLengkapSiswa.addEventListener('input', syncKeluargaAndUsername);
            if (inputNamaPanggilanSiswa) inputNamaPanggilanSiswa.addEventListener('input', syncKeluargaAndUsername);
            if (selectHubunganWali) selectHubunganWali.addEventListener('change', syncKeluargaAndUsername);
            if (inputNamaWali) inputNamaWali.addEventListener('input', syncKeluargaAndUsername);
        }

        // Buka modal otomatis jika terdapat hash #modalAkunKeluarga di URL
        if (window.location.hash === '#modalAkunKeluarga') {
            const modalKeluargaEl = document.getElementById('modalAkunKeluarga');
            if (modalKeluargaEl && window.bootstrap) {
                new bootstrap.Modal(modalKeluargaEl).show();
            }
        }

        // ================= DETAIL JADWAL & PEMILIHAN PESERTA SISWA (ADMIN) =================
        let activeAdminJadwalId = null;
        let activeAdminProgram = null;

        async function openPesertaModalAdmin(jadwalId, namaKelas, program, tutor, lokasi, tanggal, waktu, moda, status) {
            activeAdminJadwalId = jadwalId;
            activeAdminProgram = program;

            const currentInput = document.getElementById('currentJadwalIdAdmin');
            if (currentInput) currentInput.value = jadwalId;

            const subtitle = document.getElementById('pesertaJadwalAdminSubtitle');
            if (subtitle) subtitle.textContent = `${namaKelas} • Program: ${program || 'Semua Program'}`;

            // Isi rincian detail jadwal
            if (document.getElementById('adminDetailKelasNama')) document.getElementById('adminDetailKelasNama').textContent = namaKelas || '-';
            if (document.getElementById('adminDetailProgram')) document.getElementById('adminDetailProgram').textContent = program || '-';
            if (document.getElementById('adminDetailModa')) document.getElementById('adminDetailModa').textContent = moda || 'Offline';
            if (document.getElementById('adminDetailStatus')) document.getElementById('adminDetailStatus').textContent = status || 'Terjadwal';
            if (document.getElementById('adminDetailTanggal')) document.getElementById('adminDetailTanggal').textContent = tanggal || '-';
            if (document.getElementById('adminDetailWaktu')) document.getElementById('adminDetailWaktu').textContent = waktu || '-';
            if (document.getElementById('adminDetailLokasi')) document.getElementById('adminDetailLokasi').textContent = lokasi || '-';
            if (document.getElementById('adminDetailTutor')) document.getElementById('adminDetailTutor').textContent = tutor || '-';

            const alertBox = document.getElementById('alertPesertaMsgAdmin');
            if (alertBox) alertBox.style.display = 'none';

            const modalEl = document.getElementById('modalPesertaJadwalAdmin');
            if (modalEl && window.bootstrap) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }

            await Promise.all([
                loadPesertaListAdmin(jadwalId),
                loadEligibleStudentsAdmin(jadwalId, program)
            ]);
        }

        // Helper Sanitasi XSS: Escape karakter berbahaya menjadi entitas teks HTML
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        async function loadPesertaListAdmin(jadwalId) {
            const tbody = document.getElementById('tbodyPesertaJadwalAdmin');
            const totalCount = document.getElementById('totalPesertaCountAdmin');
            if (!tbody) return;
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-3 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat peserta...</td></tr>`;

            try {
                const res = await fetch(`/api/admin/jadwal/${jadwalId}/peserta`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success && data.data) {
                    if (totalCount) totalCount.textContent = data.data.length;
                    if (data.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted"><i class="bi bi-people fs-4 d-block mb-1"></i>Belum ada peserta yang didaftarkan ke jadwal ini.</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = data.data.map(p => {
                        const siswa = p.siswa || {};
                        const paket = p.paket_siswa || {};
                        const sisa = paket.total_sesi ? (paket.total_sesi - paket.sesi_terpakai) : (siswa.total_sesi - siswa.sesi_terpakai);
                        const safeNama = escapeHtml(siswa.nama_lengkap || siswa.nama || 'Siswa');
                        const safeNisn = escapeHtml(siswa.nisn || '-');
                        const safeProgram = escapeHtml(paket.program || siswa.program || '-');
                        const safePaket = escapeHtml(paket.paket_belajar || siswa.paket_belajar || '-');
                        const safeStatus = escapeHtml(p.status_kehadiran || 'Terdaftar');
                        return `
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">${safeNama}</div>
                                    <small class="text-muted">NISN: ${safeNisn}</small>
                                </td>
                                <td>
                                    <div>${safeProgram}</div>
                                    <small class="text-muted">${safePaket}</small>
                                </td>
                                <td>
                                    <span class="badge ${sisa > 2 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'}">
                                        ${sisa} Sesi Tersisa
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">${safeStatus}</span>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1" onclick="hapusPesertaAdmin(${jadwalId}, ${p.id_siswa})" title="Hapus dari jadwal">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                }
            } catch (e) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-3 text-danger">Gagal memuat peserta jadwal.</td></tr>`;
            }
        }

        async function loadEligibleStudentsAdmin(jadwalId, program) {
            const select = document.getElementById('selectPilihPesertaAdmin');
            if (!select) return;
            select.innerHTML = `<option value="">-- Memuat siswa yang memenuhi syarat... --</option>`;

            try {
                const queryUrl = `/api/admin/siswa/pilih-peserta?program=${encodeURIComponent(program || '')}&id_jadwal=${jadwalId}&hanya_sisa_sesi=1`;
                const res = await fetch(queryUrl, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
                const json = await res.json();

                if (json.success && Array.isArray(json.data)) {
                    const available = json.data.filter(s => !s.sudah_terdaftar);
                    if (available.length === 0) {
                        select.innerHTML = `<option value="">-- Tidak ada siswa yang sesuai program / kuota habis --</option>`;
                        return;
                    }

                    select.innerHTML = `<option value="">-- Pilih Siswa (${available.length} siswa memenuhi syarat) --</option>` +
                        available.map(s => `
                            <option value="${s.id_siswa}">
                                ${escapeHtml(s.nama)} (${escapeHtml(s.program)} • Sisa ${s.sisa_sesi} sesi)
                            </option>
                        `).join('');
                }
            } catch (e) {
                select.innerHTML = `<option value="">Gagal memuat data siswa</option>`;
            }
        }

        async function submitTambahPesertaAdmin(event) {
            event.preventDefault();
            const jadwalId = document.getElementById('currentJadwalIdAdmin').value;
            const selectEl = document.getElementById('selectPilihPesertaAdmin');
            const siswaId = selectEl ? selectEl.value : '';
            const alertBox = document.getElementById('alertPesertaMsgAdmin');
            const btnSubmit = document.getElementById('btnSubmitPesertaAdmin');

            if (!siswaId) {
                if (alertBox) {
                    alertBox.className = 'mt-2 alert alert-warning p-2 rounded-3 text-small';
                    alertBox.textContent = 'Silakan pilih siswa terlebih dahulu.';
                    alertBox.style.display = 'block';
                }
                return;
            }

            if (btnSubmit) btnSubmit.disabled = true;
            if (alertBox) alertBox.style.display = 'none';

            try {
                const res = await fetch(`/api/admin/jadwal/${jadwalId}/peserta`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id_siswa: siswaId })
                });
                const data = await res.json();
                if (btnSubmit) btnSubmit.disabled = false;

                if (res.ok && data.success) {
                    if (alertBox) {
                        alertBox.className = 'mt-2 alert alert-success p-2 rounded-3 text-small';
                        alertBox.textContent = data.message || 'Peserta berhasil didaftarkan.';
                        alertBox.style.display = 'block';
                    }
                    await loadPesertaListAdmin(jadwalId);
                    await loadEligibleStudentsAdmin(jadwalId, activeAdminProgram);
                } else {
                    if (alertBox) {
                        alertBox.className = 'mt-2 alert alert-danger p-2 rounded-3 text-small';
                        alertBox.textContent = (data.errors && data.errors.id_siswa ? data.errors.id_siswa[0] : (data.message || 'Gagal menambahkan peserta.'));
                        alertBox.style.display = 'block';
                    }
                }
            } catch (e) {
                if (btnSubmit) btnSubmit.disabled = false;
                if (alertBox) {
                    alertBox.className = 'mt-2 alert alert-danger p-2 rounded-3 text-small';
                    alertBox.textContent = 'Terjadi kesalahan sistem saat menghubungi server.';
                    alertBox.style.display = 'block';
                }
            }
        }

        async function hapusPesertaAdmin(jadwalId, siswaId) {
            if (!confirm('Apakah Anda yakin ingin menghapus peserta ini dari sesi kelas?')) {
                return;
            }

            try {
                const res = await fetch(`/api/admin/jadwal/${jadwalId}/peserta/${siswaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    await loadPesertaListAdmin(jadwalId);
                    await loadEligibleStudentsAdmin(jadwalId, activeAdminProgram);
                } else {
                    alert(data.message || 'Gagal menghapus peserta.');
                }
            } catch (e) {
                alert('Gagal menghubungi server.');
            }
        }

        // Auto-logout otomatis jika AFK (tidak ada aktivitas) selama 15 menit
        let timerAfk;
        const durasiAfk = 15 * 60 * 1000; // 15 menit = 900.000 milidetik
        const aktifitasEvents = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'];

        function resetTimerAfk() {
            clearTimeout(timerAfk);
            timerAfk = setTimeout(handleAfkTimeout, durasiAfk);
        }

        function handleAfkTimeout() {
            // Hentikan pemantauan event aktivitas setelah timeout terjadi
            aktifitasEvents.forEach(event => {
                window.removeEventListener(event, resetTimerAfk);
            });

            // Tampilkan Modal Bootstrap elegan di tengah layar (bukan alert bawaan browser)
            const modalEl = document.getElementById('modalSesiAfk');
            if (modalEl && window.bootstrap) {
                const modalInstance = new bootstrap.Modal(modalEl);
                modalInstance.show();
            }

            // Invalidate sesi di server secara otomatis di background
            fetch("{{ route('logout') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).catch(() => { });
        }

        // Pantau seluruh interaksi pengguna di halaman admin
        aktifitasEvents.forEach(event => {
            window.addEventListener(event, resetTimerAfk, { passive: true });
        });

        // Mulai timer AFK saat halaman pertama kali dimuat
        resetTimerAfk();

        // Anti-Back-Button: Cegah tombol Back browser menampilkan halaman dari cache setelah logout
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType('navigation')[0] && window.performance.getEntriesByType('navigation')[0].type === 'back_forward')) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Keluarga & Siswa - RACER ROBOTIC Administrasi Akademik</title>
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
            position: relative;
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

        .nav-link-custom.active::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 4px;
            bottom: 4px;
            width: 4px;
            background-color: var(--primary-blue);
            border-radius: 0 4px 4px 0;
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
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .dash-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .stat-value {
            font-size: 2.15rem;
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

        /* Filter Controls */
        .filter-select {
            background-color: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 10px;
            padding: 0.55rem 1rem;
            font-size: 0.88rem;
            color: #334155;
            font-weight: 500;
            min-width: 170px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-select:focus {
            border-color: #93c5fd;
            outline: none;
            box-shadow: 0 0 0 3px rgba(29, 104, 240, 0.1);
        }

        /* Table Styling */
        .table-custom {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table-custom thead th {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 700;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 1px solid var(--card-border);
            padding: 0.85rem 1.25rem;
        }

        .table-custom tbody td {
            padding: 1.15rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
        }

        .table-custom tbody tr:hover {
            background-color: #fafbfd;
        }

        /* Badges */
        .badge-soft-success {
            background-color: #dcfce7;
            color: #15803d;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Badge Status Pembayaran */
        .badge-pembayaran {
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.32rem 0.85rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .badge-pembayaran-warning {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-pembayaran-warning:hover {
            background-color: #fde68a;
            color: #92400e;
        }

        .badge-pembayaran-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-pembayaran-success:hover {
            background-color: #bbf7d0;
            color: #166534;
        }

        /* Progress Bar */
        .quota-progress {
            height: 6px;
            border-radius: 9999px;
            background-color: #e2e8f0;
            overflow: hidden;
            width: 140px;
        }

        .quota-progress .progress-bar {
            background-color: var(--primary-blue);
            border-radius: 9999px;
        }

        /* Modal styling */
        .card-jenis-paket {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.95rem 1rem;
            cursor: pointer;
            background-color: #ffffff;
            transition: all 0.2s ease-in-out;
            user-select: none;
        }

        .card-jenis-paket:hover {
            border-color: #93c5fd;
            background-color: #f8fafc;
        }

        .card-jenis-paket.active-paket {
            border-color: #1d68f0 !important;
            background-color: #f8faff;
            box-shadow: 0 0 0 1px #1d68f0;
        }

        .payment-radio-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .payment-radio-card:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
        }

        .payment-radio-card.active-lunas {
            border-color: #10b981;
            background-color: #ecfdf5;
        }

        .payment-radio-card.active-belumbayar {
            border-color: #f59e0b;
            background-color: #fffbeb;
        }

        .modal-section-title {
            color: #1d68f0;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 0.85rem;
        }

        /* Footer */
        footer {
            margin-top: 3rem;
            padding: 1.5rem 0;
            border-top: 1px solid var(--card-border);
            font-size: 0.78rem;
            color: #94a3b8;
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
                        <a href="{{ route('admin') }}" class="nav-link-custom">
                            <i class="bi bi-grid-fill"></i>
                            <span>Beranda</span>
                        </a>
                        <!-- Menu Akun Keluarga & Siswa (AKTIF) -->
                        <a href="{{ route('admin.keluarga') }}" class="nav-link-custom active">
                            <i class="bi bi-people"></i>
                            <span>Akun Keluarga & Siswa</span>
                        </a>
                        <a href="{{ route('admin.tutor') }}" class="nav-link-custom">
                            <i class="bi bi-person-badge"></i>
                            <span>Data Tutor</span>
                        </a>
                        <a href="{{ route('admin.penjadwalan') }}" class="nav-link-custom {{ request()->routeIs('admin.penjadwalan') ? 'active' : '' }}">
                            <i class="bi bi-calendar4-week"></i>
                            <span>Penjadwalan Terpusat</span>
                        </a>
                        <a href="{{ route('admin') }}#tabelPresensi" class="nav-link-custom">
                            <i class="bi bi-clipboard2-check"></i>
                            <span>Presensi & Reschedule</span>
                        </a>
                        <a href="{{ route('admin') }}#tabelPengumuman" class="nav-link-custom">
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
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.85rem;">Butuh bantuan operasional?</h6>
                            <p class="text-muted mb-2" style="font-size: 0.74rem; line-height: 1.3;">Tim support siap membantu Anda.</p>
                            <a href="https://wa.me/6281234567890" target="_blank" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.76rem;">
                                Hubungi tim support <i class="bi bi-arrow-up-right"></i>
                            </a>
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
                            <small class="text-muted" style="font-size: 0.7rem;">{{ Auth::user()->email ?? 'admin@racer-robotic.id' }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-logout-sidebar" title="Logout" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiLogout">
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
                    <form action="{{ route('admin.keluarga') }}" method="GET" class="search-container flex-grow-1" style="max-width: 480px;">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="q" class="search-input w-100"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari nama siswa, nama wali, atau tutor...">
                        <span class="search-shortcut">&#8984; K</span>
                        @if(!empty($statusPaket) && $statusPaket !== 'Semua') <input type="hidden" name="status_paket" value="{{ $statusPaket }}"> @endif
                        @if(!empty($statusPembayaran) && $statusPembayaran !== 'Semua') <input type="hidden" name="status_pembayaran" value="{{ $statusPembayaran }}"> @endif
                        @if(!empty($programFilter) && $programFilter !== 'Semua') <input type="hidden" name="program" value="{{ $programFilter }}"> @endif
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

            <!-- Page Body Content -->
            <div class="p-4 pt-2">

                <!-- Flash Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                        <div class="fw-bold mb-1" style="font-size: 0.88rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terdapat kesalahan input:</div>
                        <ul class="mb-0 ps-3" style="font-size: 0.84rem;">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Page Header: Judul & Subtitle Sesuai Standar Dashboard -->
                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">Akun Keluarga & Siswa</h3>
                    <p class="text-muted mb-0" style="font-size: 0.88rem;">
                        Manajemen data wali murid, pendaftaran akun siswa baru, dan status aktivasi paket belajar.
                    </p>
                </div>

                <!-- 4 Top Metric Cards Sesuai Standar Dash-Card -->
                <div class="row g-3 mb-4">
                    <!-- 1. Total Akun Keluarga -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Total Akun Keluarga</span>
                            <div class="d-flex align-items-baseline">
                                <span class="stat-value">{{ $totalKeluargaCount }}</span>
                                <span class="stat-unit">Keluarga</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.76rem;">Terdaftar di database aktif</small>
                        </div>
                    </div>

                    <!-- 2. Total Siswa Terdaftar -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Total Siswa Terdaftar</span>
                            <div class="d-flex align-items-baseline">
                                <span class="stat-value">{{ $totalSiswaCount }}</span>
                                <span class="stat-unit">Siswa</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.76rem;">Siswa aktif belajar</small>
                        </div>
                    </div>

                    <!-- 3. Paket Hampir Habis -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Paket Hampir Habis</span>
                            <div class="d-flex align-items-baseline">
                                <span class="stat-value text-warning" style="color: #d97706 !important;">{{ $paketHampirHabisCount }}</span>
                                <span class="stat-unit">Siswa</span>
                            </div>
                            <small class="text-warning fw-semibold" style="font-size: 0.76rem; color: #b45309 !important;">
                                {{ $paketHampirHabisCount }} paket perlu renewal
                            </small>
                        </div>
                    </div>

                    <!-- 4. Aktivasi WhatsApp -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Aktivasi WhatsApp</span>
                            <div class="d-flex align-items-baseline">
                                <span class="stat-value" style="color: #10b981 !important;">{{ $aktivasiWhatsappPercent }}%</span>
                                <span class="stat-unit">Terhubung</span>
                            </div>
                            <small class="text-success fw-semibold" style="font-size: 0.76rem; color: #059669 !important;">
                                Notifikasi otomatis aktif
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls & Action Buttons Row -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <!-- Filter Dropdowns -->
                    <form method="GET" action="{{ route('admin.keluarga') }}" id="filterForm" class="d-flex flex-wrap align-items-center gap-2">
                        @if ($search)
                            <input type="hidden" name="q" value="{{ $search }}">
                        @endif

                        <!-- Status Paket -->
                        <select name="status_paket" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="Semua" {{ ($statusPaket ?? 'Semua') === 'Semua' ? 'selected' : '' }}>Status Paket: Semua</option>
                            <option value="Aktif" {{ ($statusPaket ?? '') === 'Aktif' ? 'selected' : '' }}>Status Paket: Aktif</option>
                            <option value="Paket habis" {{ ($statusPaket ?? '') === 'Paket habis' ? 'selected' : '' }}>Status Paket: Selesai / Habis</option>
                        </select>

                        <!-- Status Pembayaran (Core Filter) -->
                        <select name="status_pembayaran" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="Semua" {{ ($statusPembayaran ?? 'Semua') === 'Semua' ? 'selected' : '' }}>Status Pembayaran: Semua</option>
                            <option value="Belum Bayar" {{ ($statusPembayaran ?? '') === 'Belum Bayar' ? 'selected' : '' }}>Status: Belum Bayar</option>
                            <option value="Lunas" {{ ($statusPembayaran ?? '') === 'Lunas' ? 'selected' : '' }}>Status: Lunas</option>
                        </select>

                        <!-- Program -->
                        <select name="program" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="Semua" {{ ($programFilter ?? 'Semua') === 'Semua' ? 'selected' : '' }}>Program: Semua</option>
                            <option value="Junior Robotics" {{ ($programFilter ?? '') === 'Junior Robotics' ? 'selected' : '' }}>Junior Robotics</option>
                            <option value="Python for Kids" {{ ($programFilter ?? '') === 'Python for Kids' ? 'selected' : '' }}>Python for Kids</option>
                            <option value="Robotic Scratch" {{ ($programFilter ?? '') === 'Robotic Scratch' ? 'selected' : '' }}>Robotic Scratch</option>
                            <option value="Arduino Explorer" {{ ($programFilter ?? '') === 'Arduino Explorer' ? 'selected' : '' }}>Arduino Explorer</option>
                            <option value="AI Builder" {{ ($programFilter ?? '') === 'AI Builder' ? 'selected' : '' }}>AI Builder</option>
                        </select>

                        @if ($search || $statusPaket !== 'Semua' || $statusPembayaran !== 'Semua' || $programFilter !== 'Semua')
                            <a href="{{ route('admin.keluarga') }}" class="btn btn-light border btn-sm rounded-3 px-3 py-2 text-muted fw-semibold" title="Reset Filter">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </a>
                        @endif
                    </form>

                    <!-- 2 Action Buttons: + Aktivasi Paket & + Akun Keluarga -->
                    <div class="d-flex align-items-center gap-2">
                        <!-- Tombol 1: + Aktivasi Paket (Outline Button) -->
                        <button type="button" class="btn btn-white bg-white border d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3 text-primary" data-bs-toggle="modal" data-bs-target="#modalAktivasiPaket" style="font-size: 0.88rem; border-color: var(--primary-blue) !important;">
                            <i class="bi bi-plus-lg text-primary"></i> Aktivasi Paket
                        </button>

                        <!-- Tombol 2: Edit/Tambah Keluarga (Solid Primary Button Sesuai Permintaan) -->
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3" data-bs-toggle="modal" data-bs-target="#modalAkunKeluarga" onclick="openTambahKeluargaModal()" style="background-color: var(--primary-blue); font-size: 0.88rem;">
                            <i class="bi bi-plus-lg"></i> Akun Keluarga
                        </button>
                    </div>
                </div>

                <!-- Table Card: Daftar Keluarga & Siswa Sesuai Standar Dash-Card -->
                <div class="dash-card p-0 overflow-hidden mb-4">
                    <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Daftar Keluarga & Siswa</h5>
                                <span class="badge border bg-light text-secondary rounded-pill px-2 py-1 fw-semibold" style="font-size: 0.72rem;">
                                    {{ $totalKeluargaCount }} keluarga
                                </span>
                            </div>
                            <small class="text-muted" style="font-size: 0.82rem;">Kelola data siswa dan paket belajar dalam satu tempat.</small>
                        </div>
                    </div>

                    <!-- Table Responsive -->
                    <div class="table-responsive">
<table class="table table-custom">
                    <thead>
                        <tr>
                            <th>SISWA / WALI</th>
                            <th>PAKET BELAJAR</th>
                            <th>PEMAKAIAN KUOTA</th>
                            <th>STATUS PAKET</th>
                            <th>STATUS PEMBAYARAN</th>
                            <th class="text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaList as $s)
                            <tr>
                                <!-- Kolom 1: Siswa / Wali -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-dark" style="font-size: 0.92rem;">{{ $s->nama }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill py-0 px-2" style="font-size: 0.7rem; font-weight: 500;" title="Lihat Riwayat Paket Siswa"
                                            onclick="openRiwayatPaketModal('{{ $s->id_siswa ?? $s->id }}', '{{ $s->nama }}', '{{ $s->nama_wali }}')">
                                            <i class="bi bi-clock-history"></i> Riwayat
                                        </button>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap-1 mt-1">
                                        <small class="text-muted" style="font-size: 0.78rem;">{{ $s->nama_wali }}</small>
                                        @if(!empty($s->keluarga?->secondaryParent))
                                            <span class="badge border rounded-pill px-2 py-0" style="font-size: 0.68rem; background-color: #f5f3ff; color: #6d28d9; border-color: #ddd6fe !important;" title="Secondary Parent (Wali Pendamping): {{ $s->keluarga->secondaryParent->nama_lengkap }} ({{ $s->keluarga->secondaryParent->hubungan }})">
                                                <i class="bi bi-person-check-fill me-1"></i>+{{ $s->keluarga->secondaryParent->hubungan }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Kolom 2: Paket Belajar -->
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size: 0.88rem;">{{ $s->jenis_paket }} &bull;</div>
                                    <small class="text-muted" style="font-size: 0.78rem;">{{ $s->program }} ({{ $s->total_sesi }} Sesi)</small>
                                </td>

                                <!-- Kolom 3: Pemakaian Kuota -->
                                <td>
                                    <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 0.78rem; width: 140px;">
                                        <span class="text-muted">{{ $s->sesi_terpakai }} dari {{ $s->total_sesi }} sesi</span>
                                    </div>
                                    <div class="quota-progress">
                                        <div class="progress-bar" style="width: {{ $s->persentase }}%;"></div>
                                    </div>
                                </td>

                                <!-- Kolom 4: Status Paket -->
                                <td>
                                    @if ($s->status_paket === 'Paket habis' || $s->sisa_sesi === 0)
                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-1 fw-semibold" style="font-size: 0.78rem;">
                                            Habis
                                        </span>
                                    @else
                                        <span class="badge-soft-success">
                                            <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i> Aktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom 5: Status Pembayaran (Badge Belum Bayar / Lunas) -->
                                <td>
                                    @if (($s->status_pembayaran ?? 'Lunas') === 'Belum Bayar')
                                        <!-- Badge Status: Belum Bayar -->
                                        <form method="POST" action="{{ route('admin.keluarga.toggle-pembayaran', $s->id_siswa ?? $s->id ?? 1) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="badge-pembayaran badge-pembayaran-warning border-0"
                                                title="Klik untuk ubah menjadi Lunas" data-bs-toggle="tooltip">
                                                <i class="bi bi-clock-history"></i> Belum Bayar
                                            </button>
                                        </form>
                                    @else
                                        <!-- Badge Status: Lunas -->
                                        <form method="POST" action="{{ route('admin.keluarga.toggle-pembayaran', $s->id_siswa ?? $s->id ?? 1) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="badge-pembayaran badge-pembayaran-success border-0"
                                                title="Klik untuk ubah menjadi Belum Bayar" data-bs-toggle="tooltip">
                                                <i class="bi bi-check-circle-fill"></i> Lunas
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                <!-- Kolom 6: Aksi Dropdown -->
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle text-muted p-1"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px;">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-2" style="font-size: 0.82rem; min-width: 190px;">
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)"
                                                    onclick="openAktivasiModal('{{ $s->id_siswa ?? $s->id }}', '{{ $s->nama }}', '{{ $s->program }}', '{{ $s->jenis_paket }}', '{{ $s->total_sesi }}', '{{ $s->status_pembayaran }}')">
                                                    <i class="bi bi-lightning-charge text-primary"></i> Aktivasi / Perbarui Paket
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)"
                                                    onclick="openRenewalModal('{{ $s->id_siswa ?? $s->id }}', '{{ $s->nama }}', '{{ $s->program }}', '{{ $s->jenis_paket }}', '{{ $s->total_sesi }}', '{{ $s->sesi_terpakai }}')">
                                                    <i class="bi bi-arrow-repeat text-success"></i> Perpanjang Paket (Renewal)
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)"
                                                    onclick="openRiwayatPaketModal('{{ $s->id_siswa ?? $s->id }}', '{{ $s->nama }}', '{{ $s->nama_wali }}')">
                                                    <i class="bi bi-clock-history text-info"></i> Riwayat Paket Siswa
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)"
                                                    onclick="openTambahAnakModal('{{ $s->id_keluarga ?? 1 }}', '{{ $s->nama_wali }}')">
                                                    <i class="bi bi-person-plus text-primary"></i> Tambah Anak (Siswa) Baru
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)"
                                                    onclick="openSecondaryParentModal('{{ $s->id_keluarga ?? 1 }}', '{{ $s->nama }}', '{{ $s->nama_wali }}')">
                                                    <i class="bi bi-person-badge text-primary" style="color: #6366f1 !important;"></i> Kelola Secondary Parent
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)"
                                                    onclick="openUpdateKontakDomisiliModal('{{ $s->id_keluarga ?? 1 }}', '{{ addslashes($s->nama_keluarga ?? $s->nama_wali ?? 'Keluarga Siswa') }}')">
                                                    <i class="bi bi-geo-alt-fill text-warning"></i> Update WA & Domisili
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)"
                                                    onclick="openEditKeluargaModal('{{ $s->id_siswa ?? $s->id }}', '{{ $s->nama }}', '{{ $s->nama_panggilan ?? '' }}', '{{ $s->jenjang ?? 'SD' }}', '{{ $s->kelas ?? '3' }}', '{{ $s->nisn ?? '' }}', '{{ $s->paket_belajar }}', '{{ $s->nama_wali }}', '{{ $s->nomor_whatsapp ?? '081234567890' }}', '{{ $s->hubungan ?? 'Ibu' }}')">
                                                    <i class="bi bi-pencil-square text-secondary"></i> Edit Data Keluarga
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.keluarga.toggle-pembayaran', $s->id_siswa ?? $s->id ?? 1) }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2">
                                                        <i class="bi bi-arrow-repeat text-warning"></i>
                                                        Ubah ke {{ ($s->status_pembayaran ?? 'Lunas') === 'Lunas' ? 'Belum Bayar' : 'Lunas' }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-muted" href="https://wa.me/6281234567890?text=Halo%20Wali%20{{ urlencode($s->nama) }},%20kami%20dari%20Racer%20Robotic%20ingin%20mengonfirmasi%20paket%20belajar%20anak." target="_blank">
                                                    <i class="bi bi-whatsapp text-success"></i> Hubungi Wali via WA
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                    Tidak ada data keluarga atau siswa yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                    </div>

<!-- Table Footer & Pagination -->
            <div class="p-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-3" style="font-size: 0.82rem;">
                <span class="text-muted">
                    Menampilkan 1-{{ count($siswaList) }} dari {{ $totalSiswaCount }} siswa
                </span>

                <nav aria-label="Pagination">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link rounded-2 me-1" href="#" tabindex="-1" aria-disabled="true">&lt;</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link rounded-2 me-1" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-2" href="#">Berikutnya &gt;</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
                </div>

                <!-- Footer Info -->
                <footer class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <span>&copy; 2026 RACER ROBOTIC. Bertumbuh bersama teknologi.</span>
                    <span>Semua jadwal dalam Waktu Indonesia Barat (WIB)</span>
                </footer>
            </div>
        </main>
    </div>

<div class="modal fade" id="modalAktivasiPaket" tabindex="-1" aria-labelledby="modalAktivasiPaketLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 530px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Modal Header (Sesuai Wireframe Image 1) -->
                <div class="modal-header border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalAktivasiPaketLabel" style="font-size: 1.18rem; letter-spacing: -0.2px;">
                            Aktivasi Paket Belajar Siswa
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.83rem;">
                            Pilih siswa aktif, program kelas, dan status pembayaran awal.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Form Aktivasi Paket -->
                <form method="POST" action="{{ route('admin.keluarga.aktivasi-paket') }}" id="formAktivasiPaket" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="total_sesi" id="aktivasiTotalSesi" value="{{ old('total_sesi', '12') }}">

                    <div class="modal-body p-4 pt-2">
                        <!-- Alert Pesan Error Sesuai Wireframe Image 2 -->
                        <div id="alertAktivasiError" class="alert alert-danger p-3 rounded-3 mb-3 @if(!session('error_aktivasi') && !$errors->has('paket_aktif')) d-none @else d-flex @endif align-items-start gap-2" style="background-color: #fef2f2; border: 1px solid #fecaca;" role="alert">
                            <i class="bi bi-exclamation-triangle flex-shrink-0 mt-0 text-danger" style="font-size: 1.15rem;"></i>
                            <div>
                                <strong class="d-block text-danger fw-bold" style="font-size: 0.86rem;">Gagal Mengaktifkan Paket</strong>
                                <span id="alertAktivasiErrorText" class="text-danger" style="font-size: 0.8rem; line-height: 1.45;">
                                    {{ session('error_aktivasi') ?: ($errors->first('paket_aktif') ?: 'Siswa Alya Putri masih memiliki paket berstatus Aktif pada program Junior Robotics. Selesaikan atau nonaktifkan paket sebelumnya terlebih dahulu.') }}
                                </span>
                                <div class="mt-2 pt-2 border-top border-danger-subtle d-flex align-items-center justify-content-between">
                                    <span class="text-danger fw-normal" style="font-size: 0.76rem;">Ingin memperpanjang kursus siswa ini?</span>
                                    <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 fw-semibold" style="font-size: 0.76rem; border-radius: 6px;" onclick="switchToRenewalFromAktivasi()">
                                        <i class="bi bi-arrow-repeat me-1"></i> Buka Perpanjangan (Renewal)
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if (session('selected_siswa_nama') || (session('open_modal_aktivasi') && session('success')))
                            <div class="alert alert-success d-flex align-items-center gap-2 p-3 rounded-3 mb-3 border-0" style="background-color: #ecfdf5; font-size: 0.84rem;">
                                <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0"></i>
                                <div>
                                    Akun keluarga & siswa <strong>{{ session('selected_siswa_nama') }}</strong> berhasil dibuat! Silakan tentukan paket belajar dan status pembayarannya di bawah ini untuk aktivasi.
                                </div>
                            </div>
                        @endif

                        <!-- 1. Pilih Siswa Aktif -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                Pilih Siswa Aktif <span class="text-danger">*</span>
                            </label>
                            <select name="id_siswa" id="aktivasiIdSiswa" class="form-select rounded-3 @error('id_siswa') is-invalid @enderror" required style="font-size: 0.88rem; padding: 0.65rem 0.9rem;" onchange="checkConflictActivePackage()">
                                @foreach ($allSiswaOptions as $opt)
                                    @php
                                        $optId = $opt->id_siswa ?? $opt->id;
                                        $optNama = $opt->nama ?: ($opt->nama_lengkap ?: 'Siswa #' . $optId);
                                        $targetSelected = old('id_siswa', session('selected_siswa_id', request('siswa_id', $allSiswaOptions->first()->id_siswa ?? 1)));
                                        $isSelected = ($targetSelected == $optId);
                                    @endphp
                                    <option value="{{ $optId }}"
                                        data-nama="{{ $optNama }}"
                                        data-wali="{{ $opt->nama_wali }}"
                                        data-program="{{ $opt->program }}"
                                        data-status-paket="{{ $opt->status_paket ?? 'Menunggu Aktivasi' }}"
                                        data-paket="{{ $opt->jenis_paket }}"
                                        data-pembayaran="{{ $opt->status_pembayaran }}"
                                        {{ $isSelected ? 'selected' : '' }}>
                                        {{ $optNama }} (Wali: {{ preg_replace('/^Wali:\s*/i', '', $opt->nama_wali) }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: 0.76rem;">
                                Silakan pilih siswa aktif.
                            </div>
                        </div>

                        <!-- 2. Program Kelas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                Program Kelas <span class="text-danger">*</span>
                            </label>
                            <select name="program" id="aktivasiProgram" class="form-select rounded-3 @error('program') is-invalid @enderror" required style="font-size: 0.88rem; padding: 0.65rem 0.9rem;" onchange="checkConflictActivePackage()">
                                <option value="Junior Robotics" {{ old('program', 'Junior Robotics') == 'Junior Robotics' ? 'selected' : '' }}>Junior Robotics</option>
                                <option value="Python for Kids" {{ old('program') == 'Python for Kids' ? 'selected' : '' }}>Python for Kids</option>
                                <option value="Robotic Scratch" {{ old('program') == 'Robotic Scratch' ? 'selected' : '' }}>Robotic Scratch</option>
                                <option value="Arduino Explorer" {{ old('program') == 'Arduino Explorer' ? 'selected' : '' }}>Arduino Explorer</option>
                                <option value="AI Builder" {{ old('program') == 'AI Builder' ? 'selected' : '' }}>AI Builder</option>
                                <option value="IoT Smart Home" {{ old('program') == 'IoT Smart Home' ? 'selected' : '' }}>IoT Smart Home</option>
                            </select>
                            <div class="invalid-feedback" style="font-size: 0.76rem;">
                                Silakan tentukan program kelas.
                            </div>
                        </div>

                        <!-- 3. Komponen Pemilih Jenis Paket & Kuota Otomatis -->
                        <div class="row g-2 mb-3">
                            <div class="col-8">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                    Tipe & Paket Belajar <span class="text-danger">*</span>
                                </label>
                                <select name="jenis_paket" id="aktivasiJenisPaket" class="form-select rounded-3 @error('jenis_paket') is-invalid @enderror" required style="font-size: 0.88rem; padding: 0.65rem 0.9rem;" onchange="onAktivasiPaketChange()">
                                    <optgroup label="Paket Regular — Per Bulan (4× Pertemuan)">
                                        <option value="Regular - Gold (1 Orang) · Per Bulan (4x Pertemuan)" data-sesi="4">Regular - Gold (1 Orang) · Per Bulan (4× Pertemuan)</option>
                                        <option value="Regular - Silver (2 Orang) · Per Bulan (4x Pertemuan)" data-sesi="4">Regular - Silver (2 Orang) · Per Bulan (4× Pertemuan)</option>
                                        <option value="Regular - Bronze (3 Orang) · Per Bulan (4x Pertemuan)" data-sesi="4">Regular - Bronze (3 Orang) · Per Bulan (4× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Regular — 1 Semester (12× Pertemuan)">
                                        <option value="Regular - Gold (1 Orang) · 1 Semester (12x Pertemuan)" data-sesi="12" selected>Regular - Gold (1 Orang) · 1 Semester (12× Pertemuan)</option>
                                        <option value="Regular - Silver (2 Orang) · 1 Semester (12x Pertemuan)" data-sesi="12">Regular - Silver (2 Orang) · 1 Semester (12× Pertemuan)</option>
                                        <option value="Regular - Bronze (3 Orang) · 1 Semester (12x Pertemuan)" data-sesi="12">Regular - Bronze (3 Orang) · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Private">
                                        <option value="Paket Private · Per Bulan (4x Pertemuan)" data-sesi="4">Paket Private · Per Bulan (4× Pertemuan)</option>
                                        <option value="Paket Private · 1 Semester (12x Pertemuan)" data-sesi="12">Paket Private · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Online">
                                        <option value="Paket Online · Per Bulan (4x Pertemuan)" data-sesi="4">Paket Online · Per Bulan (4× Pertemuan)</option>
                                        <option value="Paket Online · 1 Semester (12x Pertemuan)" data-sesi="12">Paket Online · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                </select>
                                <div class="invalid-feedback" style="font-size: 0.76rem;">
                                    Silakan pilih jenis paket belajar.
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold text-dark mb-1 d-flex justify-content-between align-items-center" style="font-size: 0.83rem;">
                                    <span>Kuota Sesi</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0" style="font-size: 0.68rem;">Otomatis</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3" style="padding: 0.65rem 0.75rem;">
                                        <i class="bi bi-clock-history text-primary"></i>
                                    </span>
                                    <input type="number" id="previewAktivasiTotalSesi" class="form-control bg-light fw-bold text-dark border-start-0 rounded-end-3" value="12" readonly style="font-size: 0.9rem; padding: 0.65rem 0.75rem;">
                                </div>
                            </div>
                        </div>

                        <!-- Info Kuota Otomatis Sesuai Pilihan Paket -->
                        <div class="p-3 rounded-3 mb-4 d-flex align-items-start gap-2" style="background-color: #f8fafc; border: 1px solid #eef2f6;">
                            <i class="bi bi-info-circle text-secondary flex-shrink-0 mt-0" style="font-size: 1rem;"></i>
                            <span id="infoKuotaText" class="text-secondary" style="font-size: 0.79rem; line-height: 1.45;">
                                Kuota awal otomatis terisi 12 sesi saat paket diaktifkan (berlaku walaupun status Belum Bayar).
                            </span>
                        </div>

                        <!-- 4. Status Pembayaran (Radio Belum Bayar vs Lunas Sesuai Wireframe) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark d-block mb-2" style="font-size: 0.83rem;">
                                Status Pembayaran <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-4">
                                <div class="form-check d-flex align-items-center gap-2 mb-0">
                                    <input class="form-check-input" type="radio" name="status_pembayaran" id="radioStatusBelumBayar" value="Belum Bayar" checked style="cursor: pointer;">
                                    <label class="form-check-label text-dark fw-medium" for="radioStatusBelumBayar" style="font-size: 0.86rem; cursor: pointer;">
                                        Belum Bayar
                                    </label>
                                </div>
                                <div class="form-check d-flex align-items-center gap-2 mb-0">
                                    <input class="form-check-input" type="radio" name="status_pembayaran" id="radioStatusLunas" value="Lunas" style="cursor: pointer;">
                                    <label class="form-check-label text-dark fw-medium" for="radioStatusLunas" style="font-size: 0.86rem; cursor: pointer;">
                                        Lunas
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer (Tombol Batal & Aktifkan Paket Sesuai Wireframe) -->
                    <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border px-4 py-2 rounded-3 fw-semibold text-secondary" data-bs-dismiss="modal" style="font-size: 0.86rem;">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold shadow-sm text-white" style="background-color: var(--primary-blue); font-size: 0.86rem;">
                            Aktifkan Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: FORM TAMBAH / EDIT AKUN KELUARGA & SISWA (Sesuai Wireframe)        -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalAkunKeluarga" tabindex="-1" aria-labelledby="modalAkunKeluargaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 p-4 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold text-dark mb-0" id="modalAkunKeluargaTitle" style="font-size: 1.2rem; letter-spacing: -0.2px;">
                        Tambah Akun Keluarga & Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('admin.keluarga.store') }}" id="formAkunKeluarga" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id_siswa" id="inputEditIdSiswa" value="">

                    <div class="modal-body p-4 pt-2">
                        <!-- 1. DATA SISWA (ANAK) -->
                        <div class="modal-section-title">DATA SISWA (ANAK)</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Nama Lengkap Siswa</label>
                                <input type="text" name="nama" id="inputNamaSiswa" class="form-control rounded-2" placeholder="Contoh: Andi Santoso" required style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" id="inputNamaPanggilan" class="form-control rounded-2" placeholder="Contoh: Andi" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Tanggal Lahir</label>
                                <div class="input-group">
                                    <input type="text" name="tanggal_lahir" id="inputTanggalLahir" class="form-control rounded-start-2" placeholder="12/05/2017" value="12/05/2017" style="font-size: 0.88rem;">
                                    <span class="input-group-text bg-white border-start-0 text-muted rounded-end-2"><i class="bi bi-calendar3"></i></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Jenjang</label>
                                <select name="jenjang" id="inputJenjang" class="form-select rounded-2" style="font-size: 0.88rem;">
                                    <option value="SD" selected>SD</option>
                                    <option value="TK">TK</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Kelas</label>
                                <input type="text" name="kelas" id="inputKelas" class="form-control rounded-2" placeholder="3" value="3" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">NISN (Opsional)</label>
                                <input type="text" name="nisn" id="inputNisn" class="form-control rounded-2" placeholder="Nomor Induk Siswa Nasional" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Username Login Siswa (Opsional)</label>
                                <input type="text" name="username_siswa" id="inputUsernameSiswa" class="form-control rounded-2" placeholder="siswa.andi (Otomatis)" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">PIN Siswa (Tepat 6 Digit Angka)</label>
                                <input type="text" name="pin_siswa" id="inputPinSiswa" class="form-control rounded-2 fw-semibold" placeholder="123456" value="123456" maxlength="6" pattern="[0-9]{6}" style="font-size: 0.88rem; letter-spacing: 2px;">
                            </div>
                        </div>

                        <!-- 2. PILIHAN PAKET BELAJAR & KUOTA OTOMATIS (Sesuai Wireframe Image 2) -->
                        <div class="modal-section-title mt-4">PILIHAN PAKET BELAJAR & KUOTA OTOMATIS</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Tipe & Paket Belajar <span class="text-danger">*</span></label>
                                <select name="paket_belajar" id="inputPaketBelajar" class="form-select rounded-2" style="font-size: 0.88rem; padding: 0.6rem 0.85rem;" onchange="onKeluargaPaketChange()">
                                    <optgroup label="Paket Regular — Per Bulan (4× Pertemuan)">
                                        <option value="Regular - Gold (1 Orang) · Per Bulan (4x Pertemuan)" data-sesi="4" selected>Regular - Gold (1 Orang) · Per Bulan (4× Pertemuan)</option>
                                        <option value="Regular - Silver (2 Orang) · Per Bulan (4x Pertemuan)" data-sesi="4">Regular - Silver (2 Orang) · Per Bulan (4× Pertemuan)</option>
                                        <option value="Regular - Bronze (3 Orang) · Per Bulan (4x Pertemuan)" data-sesi="4">Regular - Bronze (3 Orang) · Per Bulan (4× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Regular — 1 Semester (12× Pertemuan)">
                                        <option value="Regular - Gold (1 Orang) · 1 Semester (12x Pertemuan)" data-sesi="12">Regular - Gold (1 Orang) · 1 Semester (12× Pertemuan)</option>
                                        <option value="Regular - Silver (2 Orang) · 1 Semester (12x Pertemuan)" data-sesi="12">Regular - Silver (2 Orang) · 1 Semester (12× Pertemuan)</option>
                                        <option value="Regular - Bronze (3 Orang) · 1 Semester (12x Pertemuan)" data-sesi="12">Regular - Bronze (3 Orang) · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Private">
                                        <option value="Paket Private · Per Bulan (4x Pertemuan)" data-sesi="4">Paket Private · Per Bulan (4× Pertemuan)</option>
                                        <option value="Paket Private · 1 Semester (12x Pertemuan)" data-sesi="12">Paket Private · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Online">
                                        <option value="Paket Online · Per Bulan (4x Pertemuan)" data-sesi="4">Paket Online · Per Bulan (4× Pertemuan)</option>
                                        <option value="Paket Online · 1 Semester (12x Pertemuan)" data-sesi="12">Paket Online · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium d-flex justify-content-between align-items-center mb-1" style="font-size: 0.82rem;">
                                    <span>Kuota Sesi</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0" style="font-size: 0.68rem;">Otomatis</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0 rounded-start-2">
                                        <i class="bi bi-clock-history text-primary"></i>
                                    </span>
                                    <input type="number" name="total_sesi" id="inputTotalSesiKeluarga" class="form-control bg-light fw-bold text-dark border-start-0 rounded-end-2" value="4" min="1" max="100" readonly style="font-size: 0.88rem;">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="p-2 px-3 rounded-2 d-flex align-items-center gap-2" style="background-color: #f0fdf4; border: 1px solid #bbf7d0;">
                                    <i class="bi bi-check-circle-fill text-success flex-shrink-0" style="font-size: 0.9rem;"></i>
                                    <small id="infoKuotaKeluargaText" class="text-success fw-medium" style="font-size: 0.77rem;">
                                        Kuota dialokasikan otomatis: <strong>4 sesi</strong> (Paket Bulanan). Sesuai aturan paket belajar.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- 3. DATA AKUN KELUARGA & PRIMARY PARENT -->
                        <div class="modal-section-title mt-4">DATA AKUN KELUARGA & PRIMARY PARENT</div>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Nama Primary Parent (Wali) <span class="text-danger">*</span></label>
                                <input type="text" name="nama_wali" id="inputNamaWali" class="form-control rounded-2" placeholder="Contoh: Ibu Ratna" required style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Hubungan</label>
                                <select name="hubungan" id="inputHubungan" class="form-select rounded-2" style="font-size: 0.88rem;">
                                    <option value="Ibu" selected>Ibu</option>
                                    <option value="Ayah">Ayah</option>
                                    <option value="Wali">Wali</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Nama Keluarga (Opsional)</label>
                                <input type="text" name="nama_keluarga" id="inputNamaKeluarga" class="form-control rounded-2" placeholder="Contoh: Keluarga Andi (Otomatis)" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">No. WhatsApp Wali <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_whatsapp" id="inputWhatsappWali" class="form-control rounded-2" placeholder="081234567890" value="081234567890" required style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Username Login (Opsional)</label>
                                <input type="text" name="username_wali" id="inputUsernameWali" class="form-control rounded-2" placeholder="ibu.andi (Otomatis)" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Password Awal</label>
                                <input type="text" name="password_wali" id="inputPasswordWali" class="form-control rounded-2" placeholder="RacerWali2026!" value="RacerWali2026!" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Alamat Domisili</label>
                                <input type="text" name="alamat_domisili" id="inputAlamat" class="form-control rounded-2" placeholder="Jl. Contoh No. 1, RT 02/RW 05" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Kelurahan</label>
                                <input type="text" name="kelurahan" id="inputKelurahan" class="form-control rounded-2" placeholder="Menteng" value="Menteng" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Kecamatan</label>
                                <input type="text" name="kecamatan" id="inputKecamatan" class="form-control rounded-2" placeholder="Menteng" value="Menteng" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Kota / Kabupaten</label>
                                <input type="text" name="kota_kabupaten" id="inputKota" class="form-control rounded-2" placeholder="Jakarta Pusat" value="Jakarta Pusat" style="font-size: 0.88rem;">
                            </div>
                        </div>

                        <!-- 3.5 DATA SECONDARY PARENT (WALI PENDAMPING - OPSIONAL, MAKS. 1 PER KELUARGA) -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="modal-section-title mb-0">SECONDARY PARENT (WALI PENDAMPING - OPSIONAL)</div>
                                <span class="badge bg-light text-muted border" style="font-size: 0.72rem;">Maks. 1 per Keluarga</span>
                            </div>
                            <p class="text-muted mb-3" style="font-size: 0.8rem;">
                                Pasangan atau wali pendamping opsional agar memiliki akses resmi memantau anak di portal Orang Tua. Kosongkan jika tidak ada.
                            </p>
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Nama Secondary Parent</label>
                                    <input type="text" name="nama_secondary_parent" id="inputNamaSecondaryParent" class="form-control rounded-2" placeholder="Contoh: Bpk. Arif Wibowo" style="font-size: 0.88rem;">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Hubungan</label>
                                    <select name="hubungan_secondary_parent" id="inputHubunganSecondaryParent" class="form-select rounded-2" style="font-size: 0.88rem;">
                                        <option value="Ayah" selected>Ayah</option>
                                        <option value="Ibu">Ibu</option>
                                        <option value="Wali">Wali</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">No. WhatsApp Secondary Parent</label>
                                    <input type="text" name="nomor_whatsapp_secondary_parent" id="inputWhatsappSecondaryParent" class="form-control rounded-2" placeholder="081399998888" style="font-size: 0.88rem;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Username Login (Opsional)</label>
                                    <input type="text" name="username_secondary_parent" id="inputUsernameSecondaryParent" class="form-control rounded-2" placeholder="ayah.andi (Otomatis)" style="font-size: 0.88rem;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Password Awal</label>
                                    <input type="text" name="password_secondary_parent" id="inputPasswordSecondaryParent" class="form-control rounded-2" placeholder="RacerWali2026!" value="RacerWali2026!" style="font-size: 0.88rem;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-2 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border px-4 py-2 rounded-3 fw-semibold text-secondary" data-bs-dismiss="modal" style="font-size: 0.88rem;">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold shadow-sm text-white" style="background-color: var(--primary-blue); font-size: 0.88rem;">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH AKUN ANAK (SISWA) DENGAN USERNAME & PIN (Backlog B-14 - Backend: Muhammad Fadhil Swarigading) -->
    <div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-labelledby="modalTambahSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 mb-2 fw-semibold" style="font-size: 0.72rem;">
                            <i class="bi bi-person-plus me-1"></i> Tambah Akun Anak (Siswa)
                        </div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalTambahSiswaLabel" style="font-size: 1.18rem; letter-spacing: -0.2px;">
                            Pendaftaran Siswa Baru
                        </h5>
                        <p class="text-muted mb-0" id="modalTambahSiswaSubtitle" style="font-size: 0.83rem;">
                            Daftarkan akun anak dengan username unik dan PIN angka 6 digit di bawah Akun Keluarga.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('admin.siswa.store') }}" id="formTambahSiswa" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id_keluarga" id="tambahSiswaIdKeluarga" value="">

                    <div class="modal-body p-4 pt-2">
                        <!-- Info Akun Keluarga Terpilih -->
                        <div class="alert alert-light border d-flex align-items-center gap-2 p-2 px-3 rounded-3 mb-3">
                            <i class="bi bi-house-door text-primary"></i>
                            <span class="text-dark fw-semibold" style="font-size: 0.85rem;" id="tambahSiswaNamaKeluarga">Akun Keluarga: -</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" id="tambahSiswaNamaLengkap" class="form-control rounded-2" placeholder="Contoh: Kenzo Pratama" required style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" id="tambahSiswaNamaPanggilan" class="form-control rounded-2" placeholder="Contoh: Kenzo" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Jenjang <span class="text-danger">*</span></label>
                                <select name="jenjang" id="tambahSiswaJenjang" class="form-select rounded-2" required style="font-size: 0.88rem;">
                                    <option value="SD" selected>SD</option>
                                    <option value="TK">TK</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Kelas</label>
                                <input type="text" name="kelas" id="tambahSiswaKelas" class="form-control rounded-2" placeholder="3" value="3" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tambahSiswaTanggalLahir" class="form-control rounded-2" value="2018-01-01" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-12">
                                <hr class="my-1">
                                <div class="modal-section-title my-2" style="font-size: 0.78rem;">KREDENSIAL LOGIN SISWA (PORTAL BELAJAR)</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Username Unik Siswa <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="tambahSiswaUsername" class="form-control rounded-2" placeholder="Contoh: kenzo.pratama" required style="font-size: 0.88rem;">
                                <small class="text-muted" style="font-size: 0.74rem;">Digunakan untuk masuk ke portal Siswa</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">PIN Angka (Tepat 6 Digit) <span class="text-danger">*</span></label>
                                <input type="text" name="pin" id="tambahSiswaPin" class="form-control rounded-2 fw-semibold" placeholder="Contoh: 123456" maxlength="6" pattern="[0-9]{6}" required style="font-size: 0.88rem; letter-spacing: 2px;">
                                <small class="text-muted" style="font-size: 0.74rem;">Wajib 6 digit angka numerik (0-9)</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-2">
                        <button type="button" class="btn btn-light px-4 py-2 rounded-3 text-secondary" data-bs-dismiss="modal" style="font-size: 0.88rem;">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold text-white" style="background-color: var(--primary-blue); font-size: 0.88rem;">
                            <i class="bi bi-check-lg me-1"></i> Daftarkan Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: KELOLA SECONDARY PARENT (WALI PENDAMPING OPSIONAL - Backlog B-13b) -->
    <div class="modal fade" id="modalSecondaryParent" tabindex="-1" aria-labelledby="modalSecondaryParentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="badge rounded-pill px-2 py-1 mb-2 fw-semibold" style="font-size: 0.72rem; background-color: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe;">
                            <i class="bi bi-person-badge me-1"></i> Secondary Parent (Opsional)
                        </div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalSecondaryParentLabel" style="font-size: 1.18rem; letter-spacing: -0.2px;">
                            Wali Pendamping Keluarga
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.83rem;">
                            Pasangan atau wali pendamping agar memiliki akses portal Orang Tua. Maksimal 1 per Akun Keluarga.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Info Akun Keluarga Terpilih -->
                <div class="px-4 pt-2">
                    <div class="alert alert-light border d-flex align-items-center justify-content-between p-2 px-3 rounded-3 mb-0">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-house-door text-primary"></i>
                            <span class="text-dark fw-semibold" style="font-size: 0.85rem;" id="secondaryParentNamaKeluarga">Akun Keluarga: -</span>
                        </div>
                        <span class="badge bg-light text-muted border" id="secondaryParentStatusBadge" style="font-size: 0.72rem;">Memuat...</span>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="secondaryParentLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Memuat data orang tua keluarga...</p>
                </div>

                <!-- STATE A: Secondary Parent SUDAH ADA -->
                <div id="secondaryParentExistingView" class="p-4 pt-3 d-none">
                    <div class="card border rounded-3 p-3 bg-light mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-semibold" style="font-size: 0.72rem;">
                                    <i class="bi bi-check-circle me-1"></i> Aktif Terdaftar
                                </span>
                                <h6 class="fw-bold text-dark mb-0 mt-2" id="existingSecNama" style="font-size: 1rem;">-</h6>
                                <small class="text-muted" id="existingSecHubungan">Hubungan: -</small>
                            </div>
                            <div class="rounded-circle bg-white shadow-sm p-2 text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-person-check fs-5"></i>
                            </div>
                        </div>
                        <hr class="my-2 border-secondary-subtle">
                        <div class="row g-2" style="font-size: 0.82rem;">
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.74rem;">No. WhatsApp:</span>
                                <span class="fw-semibold text-dark" id="existingSecWa">-</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.74rem;">Username Login:</span>
                                <span class="fw-semibold text-dark" id="existingSecUsername">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning py-2 px-3 border-0 rounded-3 d-flex align-items-center gap-2 mb-3" style="font-size: 0.8rem; background-color: #fffbeb; color: #92400e;">
                        <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                        <span>Satu keluarga hanya dapat memiliki maksimal 1 Secondary Parent. Untuk mengganti dengan wali lain, hapus Secondary Parent ini terlebih dahulu.</span>
                    </div>

                    <form id="formHapusSecondaryParent" method="POST" action="" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Secondary Parent ini dari Akun Keluarga?')">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light px-4 py-2 rounded-3 text-secondary" data-bs-dismiss="modal" style="font-size: 0.88rem;">
                                Tutup
                            </button>
                            <button type="submit" class="btn btn-outline-danger px-4 py-2 rounded-3 fw-semibold" style="font-size: 0.88rem;">
                                <i class="bi bi-trash me-1"></i> Hapus Secondary Parent
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STATE B: Secondary Parent BELUM ADA (Form Input Tambah) -->
                <form method="POST" action="" id="formTambahSecondaryParent" class="needs-validation d-none" novalidate>
                    @csrf
                    <div class="modal-body p-4 pt-3">
                        <p class="text-muted mb-3" style="font-size: 0.82rem;">
                            Keluarga ini belum memiliki Secondary Parent. Isi formulir opsional berikut untuk menambahkan wali pendamping.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Nama Secondary Parent <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" id="tambahSecNama" class="form-control rounded-2" placeholder="Contoh: Bpk. Arif Wibowo" required style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Hubungan <span class="text-danger">*</span></label>
                                <select name="hubungan" id="tambahSecHubungan" class="form-select rounded-2" required style="font-size: 0.88rem;">
                                    <option value="Ayah" selected>Ayah</option>
                                    <option value="Ibu">Ibu</option>
                                    <option value="Wali">Wali</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">No. WhatsApp Secondary Parent <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_whatsapp" id="tambahSecWa" class="form-control rounded-2" placeholder="081399998888" required style="font-size: 0.88rem;">
                                <small class="text-muted" style="font-size: 0.74rem;">Format: 08... atau +62... minimal 10 digit angka</small>
                            </div>
                            <div class="col-12">
                                <hr class="my-1">
                                <div class="modal-section-title my-2" style="font-size: 0.78rem;">KREDENSIAL LOGIN PORTAL ORANG TUA (OPSIONAL)</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Username Login (Opsional)</label>
                                <input type="text" name="username" id="tambahSecUsername" class="form-control rounded-2" placeholder="ayah.arif (Otomatis)" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Password Awal</label>
                                <input type="text" name="password" id="tambahSecPassword" class="form-control rounded-2" value="RacerWali2026!" style="font-size: 0.88rem;">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-2 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light px-4 py-2 rounded-3 text-secondary" data-bs-dismiss="modal" style="font-size: 0.88rem;">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold text-white" style="background-color: var(--primary-blue); font-size: 0.88rem;">
                            <i class="bi bi-save me-1"></i> Simpan Secondary Parent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: FORM PERPANJANGAN PAKET (RENEWAL) DENGAN ISOLASI RIWAYAT (Backlog B-03 - Backend: Muhammad Fadhil Swarigading) -->
    <div class="modal fade" id="modalRenewalPaket" tabindex="-1" aria-labelledby="modalRenewalPaketLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 530px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Modal Header -->
                <div class="modal-header border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 mb-2 fw-semibold" style="font-size: 0.72rem;">
                            <i class="bi bi-arrow-repeat me-1"></i> Perpanjangan Paket (Renewal)
                        </div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalRenewalPaketLabel" style="font-size: 1.18rem; letter-spacing: -0.2px;">
                            Perpanjang Paket Belajar Siswa
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.83rem;">
                            Paket baru akan diaktifkan dan riwayat paket lama otomatis diarsipkan secara terpisah.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Form Renewal Paket -->
                <form method="POST" action="{{ route('admin.keluarga.renewal-paket') }}" id="formRenewalPaket" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="total_sesi" id="renewalTotalSesi" value="12">

                    <div class="modal-body p-4 pt-2">
                        <!-- Info Isolasi Riwayat -->
                        <div class="alert alert-info d-flex align-items-start gap-2 p-3 rounded-3 mb-3 border-0" style="background-color: #f0fdf4; border: 1px solid #bbf7d0 !important;">
                            <i class="bi bi-shield-check text-success fs-5 flex-shrink-0 mt-0"></i>
                            <div style="font-size: 0.82rem; color: #166534; line-height: 1.45;">
                                <strong>Isolasi Riwayat Aktif:</strong> Kuota dan sesi terpakai dari paket sebelumnya akan diarsipkan secara aman di database dan tidak akan tertimpa atau hilang.
                            </div>
                        </div>

                        <!-- 1. Pilih Siswa Target Renewal -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                Siswa yang Diperpanjang <span class="text-danger">*</span>
                            </label>
                            <select name="id_siswa" id="renewalIdSiswa" class="form-select rounded-3" required style="font-size: 0.88rem; padding: 0.65rem 0.9rem;" onchange="onRenewalSiswaChange()">
                                @foreach ($allSiswaOptions as $opt)
                                    @php
                                        $optId = $opt->id_siswa ?? $opt->id;
                                        $optNama = $opt->nama ?: ($opt->nama_lengkap ?: 'Siswa #' . $optId);
                                    @endphp
                                    <option value="{{ $optId }}"
                                        data-nama="{{ $optNama }}"
                                        data-program="{{ $opt->program ?? '' }}"
                                        data-jenis="{{ $opt->jenis_paket ?? '' }}"
                                        data-paket="{{ $opt->paket_belajar ?? '' }}"
                                        data-total="{{ $opt->total_sesi ?? 12 }}"
                                        data-terpakai="{{ $opt->sesi_terpakai ?? 0 }}">
                                        {{ $optNama }} ({{ $opt->program ?? 'Robotics' }} - Sisa: {{ max(0, ($opt->total_sesi ?? 12) - ($opt->sesi_terpakai ?? 0)) }} sesi)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ringkasan Paket Sebelumnya yang Akan Diarsipkan -->
                        <div class="p-3 bg-light rounded-3 mb-3 border" style="font-size: 0.8rem;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Paket yang sedang berjalan (Akan Diarsipkan):</span>
                                <a href="javascript:void(0)" onclick="lihatRiwayatDariModalRenewal()" class="text-primary text-decoration-none small fw-semibold">
                                    <i class="bi bi-clock-history me-1"></i> Lihat Semua Riwayat
                                </a>
                            </div>
                            <div class="fw-semibold text-dark d-flex align-items-center justify-content-between">
                                <span id="renewalOldPackageName">Semester • Junior Robotics (12 Sesi)</span>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill" id="renewalOldSessions">12 / 12 Sesi Terpakai</span>
                            </div>
                        </div>

                        <!-- 2. Pilih Program Baru / Lanjutan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                Program Baru / Lanjutan <span class="text-danger">*</span>
                            </label>
                            <select name="program" id="renewalProgram" class="form-select rounded-3" required style="font-size: 0.88rem; padding: 0.65rem 0.9rem;">
                                <option value="Junior Robotics">Junior Robotics (TK & SD Awal)</option>
                                <option value="Arduino Explorer">Arduino Explorer (SD Lanjutan & SMP)</option>
                                <option value="Python for Kids">Python for Kids (Coding Logika)</option>
                                <option value="Robotic Scratch">Robotic Scratch (Dasar Algoritma)</option>
                                <option value="AI Builder">AI Builder (Machine Learning Anak)</option>
                                <option value="IoT Smart Home">IoT Smart Home (Robotika Terapan)</option>
                            </select>
                        </div>

                        <!-- 3. Pilih Jenis Paket Baru -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                Jenis Paket Baru <span class="text-danger">*</span>
                            </label>
                            <select name="jenis_paket" id="renewalJenisPaket" class="form-select rounded-3" required style="font-size: 0.88rem; padding: 0.65rem 0.9rem;" onchange="onRenewalPaketChange()">
                                <option value="Semester" data-sesi="12">Paket 1 Semester • 12 Sesi</option>
                                <option value="Bulanan" data-sesi="4">Paket Per Bulan • 4 Sesi</option>
                            </select>
                            <div class="form-text mt-1 text-primary" id="renewalInfoKuotaText" style="font-size: 0.77rem;">
                                Kuota awal periode baru otomatis 12 sesi (mulai dari 0 sesi terpakai).
                            </div>
                        </div>

                        <!-- 4. Status Pembayaran Perpanjangan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                Status Pembayaran Periode Baru <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="radio-card p-2 text-center w-100 rounded-3 border d-block" for="renewalRadioBelumBayar" style="cursor: pointer;">
                                        <input class="form-check-input me-1" type="radio" name="status_pembayaran" id="renewalRadioBelumBayar" value="Belum Bayar" checked>
                                        <span class="fw-semibold text-warning-emphasis" style="font-size: 0.82rem;">Belum Bayar</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="radio-card p-2 text-center w-100 rounded-3 border d-block" for="renewalRadioLunas" style="cursor: pointer;">
                                        <input class="form-check-input me-1" type="radio" name="status_pembayaran" id="renewalRadioLunas" value="Lunas">
                                        <span class="fw-semibold text-success" style="font-size: 0.82rem;">Lunas</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Catatan Perpanjangan (Opsional) -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                Catatan Perpanjangan (Opsional)
                            </label>
                            <input type="text" name="catatan" id="renewalCatatan" class="form-control rounded-3" placeholder="Contoh: Perpanjangan Semester 2 / Naik Tingkat" style="font-size: 0.85rem;">
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-3 px-3 py-2 fw-medium text-secondary" data-bs-dismiss="modal" style="font-size: 0.84rem;">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success rounded-3 px-4 py-2 fw-semibold text-white" style="font-size: 0.84rem;">
                            <i class="bi bi-arrow-repeat me-1"></i> Simpan Perpanjangan (Renewal)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 4: TABEL DAFTAR RIWAYAT PAKET PADA PROFIL SISWA (Backlog B-03 / T-29 - Front-end: Fahriel Aziz Satrio) -->
    <div class="modal fade" id="modalRiwayatPaketSiswa" tabindex="-1" aria-labelledby="modalRiwayatPaketSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Modal Header -->
                <div class="modal-header border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2 py-1 mb-2 fw-semibold" style="font-size: 0.72rem;">
                            <i class="bi bi-clock-history me-1"></i> Riwayat Paket Siswa (B-03)
                        </div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalRiwayatPaketSiswaLabel" style="font-size: 1.22rem; letter-spacing: -0.2px;">
                            Riwayat Paket Belajar Siswa
                        </h5>
                        <p class="text-muted mb-0" id="riwayatSiswaSubtitle" style="font-size: 0.84rem;">
                            Memuat data profil & riwayat paket...
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 pt-2">
                    <!-- Loading Indicator -->
                    <div id="riwayatLoadingState" class="text-center py-5">
                        <div class="spinner-border text-primary mb-2" role="status"></div>
                        <p class="text-muted small mb-0">Mengambil data paket siswa dari server...</p>
                    </div>

                    <!-- Content Container (Hidden during load) -->
                    <div id="riwayatContentContainer" style="display: none;">
                        <!-- 1. Card Paket Aktif Saat Ini -->
                        <div class="card border rounded-3 mb-4 shadow-none" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-success text-white rounded-pill px-2 py-1 fw-semibold" style="font-size: 0.72rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i> Paket Aktif Saat Ini
                                    </span>
                                    <span id="riwayatStatusPembayaranBadge" class="badge rounded-pill px-2 py-1 fw-semibold" style="font-size: 0.72rem;">-</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" id="riwayatAktifProgram">-</h6>
                                <div class="text-muted small mb-2" id="riwayatAktifJenis">-</div>

                                <!-- Progress Kuota Sesi -->
                                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.78rem;">
                                    <span class="text-muted">Pemakaian Kuota Sesi:</span>
                                    <span class="fw-bold text-dark" id="riwayatAktifSesiText">0 / 0 Sesi</span>
                                </div>
                                <div class="progress" style="height: 7px; border-radius: 4px;">
                                    <div class="progress-bar bg-primary" id="riwayatAktifProgressBar" role="progressbar" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Tabel Riwayat Paket Sebelumnya (Isolasi Riwayat B-03) -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">
                                    <i class="bi bi-archive me-1 text-secondary"></i> Arsip Riwayat Paket Sebelumnya
                                </h6>
                                <small class="text-muted" style="font-size: 0.78rem;">Riwayat paket sebelumnya terisolasi aman dan tidak terhapus.</small>
                            </div>
                            <span class="badge bg-light text-secondary border rounded-pill" id="riwayatTotalSebelumnyaCount">0 paket lama</span>
                        </div>

                        <div class="table-responsive border rounded-3">
                            <table class="table table-sm align-middle mb-0" style="font-size: 0.82rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-2 px-3">NO</th>
                                        <th class="py-2">PROGRAM & PAKET</th>
                                        <th class="py-2">KUOTA SESI</th>
                                        <th class="py-2">STATUS PAKET</th>
                                        <th class="py-2">PEMBAYARAN</th>
                                        <th class="py-2 px-3">TGL AKTIVASI</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayatTableBody">
                                    <!-- Diisi via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-2 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-primary rounded-3 fw-semibold px-3 py-2" id="btnPerpanjangDariRiwayat" style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-repeat me-1"></i> Perpanjang Paket Siswa Ini
                    </button>
                    <button type="button" class="btn btn-light rounded-3 px-4 py-2 text-secondary" data-bs-dismiss="modal" style="font-size: 0.85rem;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts for Modal Interaction & Client-side Validation -->

    <!-- Modal Update Kontak WhatsApp Wali & Domisili Akun Keluarga (Backlog B-42 - Arta Maruf Rifaa Ulwan) -->
    <div class="modal fade" id="modalUpdateKontakDomisili" tabindex="-1" aria-labelledby="modalUpdateKontakDomisiliLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalUpdateKontakDomisiliLabel" style="font-size: 1.2rem; letter-spacing: -0.2px;">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i>Update Kontak WA & Domisili
                        </h5>
                        <p class="text-muted mb-0" id="modalUpdateKontakDomisiliSubtitle" style="font-size: 0.83rem;">
                            Perbarui nomor WhatsApp wali dan rincian alamat domisili Akun Keluarga.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formUpdateKontakDomisili" method="POST" action="">
                    @csrf
                    <input type="hidden" name="id_keluarga" id="inputEditKontakIdKeluarga" value="">

                    <div class="modal-body p-4 pt-2">
                        <!-- Alert Info Audit Logging -->
                        <div class="p-3 mb-3 rounded-3 d-flex align-items-center gap-2" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                            <i class="bi bi-shield-check text-primary fs-5 flex-shrink-0"></i>
                            <div style="font-size: 0.78rem; color: #1e40af;">
                                <strong>Pencatatan Log Audit Aktif:</strong> Setiap perubahan nomor WhatsApp dan alamat domisili akan dicatat secara otomatis dalam log audit sistem demi keamanan data.
                            </div>
                        </div>

                        <!-- Data Form -->
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">
                                    Nama Akun Keluarga <span class="text-muted">(Opsional)</span>
                                </label>
                                <input type="text" name="nama_keluarga" id="inputEditKontakNamaKeluarga" class="form-control rounded-2" placeholder="Contoh: Keluarga Ratna & Arif" maxlength="150" style="font-size: 0.88rem;">
                            </div>

                            <!-- WhatsApp Primary Parent (Wali Utama) -->
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">
                                    No. WhatsApp Wali Utama <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0 rounded-start-2">
                                        <i class="bi bi-whatsapp text-success"></i>
                                    </span>
                                    <input type="text" name="nomor_whatsapp" id="inputEditKontakWhatsappPrimary" class="form-control border-start-0 rounded-end-2" placeholder="081234567890 atau +628..." required maxlength="25" style="font-size: 0.88rem;">
                                </div>
                                <small class="text-muted" style="font-size: 0.72rem;">Primary Parent (Wali Utama)</small>
                            </div>

                            <!-- WhatsApp Secondary Parent (Wali Pendamping) -->
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">
                                    No. WhatsApp Wali Pendamping <span class="text-muted">(Opsional)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0 rounded-start-2">
                                        <i class="bi bi-whatsapp text-secondary"></i>
                                    </span>
                                    <input type="text" name="nomor_whatsapp_secondary" id="inputEditKontakWhatsappSecondary" class="form-control border-start-0 rounded-end-2" placeholder="081399998888 (Opsional)" maxlength="25" style="font-size: 0.88rem;">
                                </div>
                                <small class="text-muted" style="font-size: 0.72rem;">Secondary Parent (Jika tersedia)</small>
                            </div>

                            <!-- Alamat Domisili -->
                            <div class="col-12">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">
                                    Alamat Domisili Lengkap <span class="text-danger">*</span>
                                </label>
                                <textarea name="alamat_domisili" id="inputEditKontakAlamat" rows="3" class="form-control rounded-2" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW..." required maxlength="500" style="font-size: 0.88rem;"></textarea>
                                <div class="d-flex justify-content-between text-muted mt-1" style="font-size: 0.72rem;">
                                    <span>Format allowlist teks terproteksi XSS.</span>
                                    <span>Maks. 500 karakter</span>
                                </div>
                            </div>

                            <!-- Kelurahan, Kecamatan, Kota/Kabupaten -->
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Kelurahan</label>
                                <input type="text" name="kelurahan" id="inputEditKontakKelurahan" class="form-control rounded-2" placeholder="Contoh: Menteng" maxlength="100" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Kecamatan</label>
                                <input type="text" name="kecamatan" id="inputEditKontakKecamatan" class="form-control rounded-2" placeholder="Contoh: Menteng" maxlength="100" style="font-size: 0.88rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Kota / Kabupaten</label>
                                <input type="text" name="kota_kabupaten" id="inputEditKontakKota" class="form-control rounded-2" placeholder="Jakarta Pusat" maxlength="100" style="font-size: 0.88rem;">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-3 px-3 py-2 fw-medium text-secondary" data-bs-dismiss="modal" style="font-size: 0.88rem;">
                            Batal
                        </button>
                        <button type="submit" id="btnSubmitUpdateKontakDomisili" class="btn btn-primary rounded-3 px-4 py-2 fw-semibold d-flex align-items-center gap-2" style="background-color: var(--primary-blue); font-size: 0.88rem;">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan & Catat Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="modalKonfirmasiLogout" tabindex="-1" aria-labelledby="modalKonfirmasiLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content rounded-4 border-0 shadow-lg text-center p-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle p-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-box-arrow-right fs-2"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Konfirmasi Keluar</h5>
                <p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari sistem RACER ROBOTIC? Sesi aktif Anda akan diakhiri.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-medium border" data-bs-dismiss="modal">Batal</button>
                    <a href="{{ route('logout') }}" class="btn btn-confirm-logout rounded-pill px-4 fw-medium shadow-sm">Ya, Keluar</a>
                </div>
            </div>
        </div>
    </div>

<script>
        // Toggle mobile sidebar
        const toggleBtn = document.getElementById("toggleSidebar");
        if (toggleBtn) {
            toggleBtn.addEventListener("click", function () {
                document.querySelector(".sidebar").classList.toggle("show");
            });
        }

        // Inisialisasi Tooltip Bootstrap & Komponen Kuota
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            if (typeof onKeluargaPaketChange === 'function') onKeluargaPaketChange();
            if (typeof onAktivasiPaketChange === 'function') onAktivasiPaketChange();

            // Sinkronisasi otomatis total sesi saat jenis paket dipilih
            const selectJenisPaket = document.getElementById('aktivasiJenisPaket');
            const inputTotalSesi = document.getElementById('aktivasiTotalSesi');

            if (selectJenisPaket && inputTotalSesi) {
                selectJenisPaket.addEventListener('change', function () {
                    const selectedOption = selectJenisPaket.options[selectJenisPaket.selectedIndex];
                    const defaultSesi = selectedOption.getAttribute('data-default-sesi');
                    if (defaultSesi) {
                        inputTotalSesi.value = defaultSesi;
                    }
                });
            }

            // Validasi formulir Bootstrap client-side
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });

        // Pemilihan Jenis Paket Aktivasi (Sinkronisasi Otomatis Total Sesi & Keterangan Kuota)
        function onAktivasiPaketChange() {
            const selectPaket = document.getElementById('aktivasiJenisPaket');
            const inputTotalSesi = document.getElementById('aktivasiTotalSesi');
            const previewTotalSesi = document.getElementById('previewAktivasiTotalSesi');
            const infoText = document.getElementById('infoKuotaText');
            if (!selectPaket) return;

            const opt = selectPaket.options[selectPaket.selectedIndex];
            let sesi = 12;
            if (opt) {
                sesi = opt.getAttribute('data-sesi') || (opt.value.includes('12') || opt.value.toLowerCase().includes('semester') ? 12 : 4);
            }

            if (inputTotalSesi) inputTotalSesi.value = sesi;
            if (previewTotalSesi) previewTotalSesi.value = sesi;
            if (infoText) {
                infoText.textContent = `Kuota awal otomatis terisi ${sesi} sesi saat paket diaktifkan (berlaku walaupun status Belum Bayar).`;
            }
        }

        // Sinkronisasi otomatis Total Sesi & Kuota di Modal Tambah / Edit Keluarga
        function onKeluargaPaketChange() {
            const selectPaket = document.getElementById('inputPaketBelajar');
            const inputTotalSesi = document.getElementById('inputTotalSesiKeluarga');
            const infoText = document.getElementById('infoKuotaKeluargaText');
            if (!selectPaket) return;

            const opt = selectPaket.options[selectPaket.selectedIndex];
            let sesi = 4;
            let labelTipe = 'Paket Bulanan';
            if (opt) {
                sesi = opt.getAttribute('data-sesi') || (opt.value.includes('12') || opt.value.toLowerCase().includes('semester') ? 12 : 4);
                labelTipe = (sesi == 12) ? 'Paket 1 Semester' : 'Paket Bulanan';
            }

            if (inputTotalSesi) inputTotalSesi.value = sesi;
            if (infoText) {
                infoText.innerHTML = `Kuota dialokasikan otomatis: <strong>${sesi} sesi</strong> (${labelTipe}). Sesuai aturan paket belajar.`;
            }
        }

        // Helper kompatibilitas
        function selectJenisPaket(jenis, sesi) {
            const selectPaket = document.getElementById('aktivasiJenisPaket');
            if (selectPaket) {
                for (let i = 0; i < selectPaket.options.length; i++) {
                    const val = selectPaket.options[i].value;
                    if (jenis === 'Semester' && (val.includes('1 Semester') || val.includes('12'))) {
                        selectPaket.selectedIndex = i;
                        break;
                    } else if (jenis === 'Bulanan' && (val.includes('Per Bulan') || val.includes('4'))) {
                        selectPaket.selectedIndex = i;
                        break;
                    }
                }
            }
            onAktivasiPaketChange();
        }

        // Deteksi konflik paket berstatus Aktif pada program yang sama (Sesuai Wireframe Image 2)
        function checkConflictActivePackage() {
            const selectSiswa = document.getElementById('aktivasiIdSiswa');
            const selectProgram = document.getElementById('aktivasiProgram');
            const alertBox = document.getElementById('alertAktivasiError');
            const alertText = document.getElementById('alertAktivasiErrorText');

            if (!selectSiswa || !selectProgram || !alertBox) return false;

            const selectedOption = selectSiswa.options[selectSiswa.selectedIndex];
            if (!selectedOption) return false;

            const namaSiswa = selectedOption.getAttribute('data-nama') || selectedOption.text.split('(')[0].trim();
            const currentProgram = selectedOption.getAttribute('data-program') || '';
            const statusPaket = selectedOption.getAttribute('data-status-paket') || '';
            const targetProgram = selectProgram.value;

            // Jika siswa sudah memiliki paket berstatus Aktif pada program yang sama
            if (statusPaket === 'Aktif' && currentProgram.toLowerCase().trim() === targetProgram.toLowerCase().trim()) {
                alertBox.classList.remove('d-none');
                alertBox.classList.add('d-flex');
                if (alertText) {
                    alertText.textContent = `Siswa ${namaSiswa} masih memiliki paket berstatus Aktif pada program ${targetProgram}. Selesaikan atau nonaktifkan paket sebelumnya terlebih dahulu.`;
                }
                return true;
            } else {
                alertBox.classList.add('d-none');
                alertBox.classList.remove('d-flex');
                return false;
            }
        }

        // Buka modal aktivasi paket dengan data siswa terpilih terisi otomatis
        function openAktivasiModal(idSiswa, nama, program, jenisPaket, totalSesi, statusPembayaran) {
            const selectSiswa = document.getElementById('aktivasiIdSiswa');
            const selectProgram = document.getElementById('aktivasiProgram');
            const selectPaket = document.getElementById('aktivasiJenisPaket');
            const radioBelumBayar = document.getElementById('radioStatusBelumBayar');
            const radioLunas = document.getElementById('radioStatusLunas');

            if (selectSiswa && idSiswa) {
                selectSiswa.value = idSiswa;
            }
            if (selectProgram && program) {
                for (let i = 0; i < selectProgram.options.length; i++) {
                    if (selectProgram.options[i].value === program || program.includes(selectProgram.options[i].value)) {
                        selectProgram.selectedIndex = i;
                        break;
                    }
                }
            }

            if (selectPaket && jenisPaket) {
                let matched = false;
                for (let i = 0; i < selectPaket.options.length; i++) {
                    if (selectPaket.options[i].value === jenisPaket || selectPaket.options[i].value.includes(jenisPaket) || jenisPaket.includes(selectPaket.options[i].value)) {
                        selectPaket.selectedIndex = i;
                        matched = true;
                        break;
                    }
                }
                if (!matched) {
                    for (let i = 0; i < selectPaket.options.length; i++) {
                        if (jenisPaket.toLowerCase().includes('bulan') && selectPaket.options[i].value.toLowerCase().includes('bulan')) {
                            selectPaket.selectedIndex = i;
                            break;
                        } else if (jenisPaket.toLowerCase().includes('semester') && selectPaket.options[i].value.toLowerCase().includes('semester')) {
                            selectPaket.selectedIndex = i;
                            break;
                        }
                    }
                }
                onAktivasiPaketChange();
            } else {
                onAktivasiPaketChange();
            }

            if (statusPembayaran === 'Lunas') {
                if (radioLunas) radioLunas.checked = true;
            } else {
                if (radioBelumBayar) radioBelumBayar.checked = true;
            }

            checkConflictActivePackage();

            const modalEl = document.getElementById('modalAktivasiPaket');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        }

        // Buka otomatis modal jika ada flash pesan error konflik aktivasi atau permintaan aktivasi
        @if (session('open_modal_aktivasi') || request('open') === 'aktivasi' || session('error_aktivasi') || $errors->has('paket_aktif'))
        document.addEventListener('DOMContentLoaded', function () {
            const targetId = '{{ session("selected_siswa_id") ?: request("siswa_id") }}';
            const selectSiswa = document.getElementById('aktivasiIdSiswa');
            if (selectSiswa && targetId) {
                selectSiswa.value = targetId;
            }
            if (typeof onAktivasiPaketChange === 'function') onAktivasiPaketChange();
            if (typeof checkConflictActivePackage === 'function') checkConflictActivePackage();
            const modalEl = document.getElementById('modalAktivasiPaket');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });
        @endif

        // Fungsi Membuka Modal Tambah / Edit Akun Keluarga & Siswa
        function openTambahKeluargaModal() {
            const title = document.getElementById('modalAkunKeluargaTitle');
            const hiddenId = document.getElementById('inputEditIdSiswa');
            if (title) title.textContent = 'Tambah Akun Keluarga & Siswa';
            if (hiddenId) hiddenId.value = '';

            const inputSecNama = document.getElementById('inputNamaSecondaryParent');
            const inputSecHub = document.getElementById('inputHubunganSecondaryParent');
            const inputSecWa = document.getElementById('inputWhatsappSecondaryParent');
            const inputSecUser = document.getElementById('inputUsernameSecondaryParent');
            const inputSecPass = document.getElementById('inputPasswordSecondaryParent');
            if (inputSecNama) inputSecNama.value = '';
            if (inputSecHub) inputSecHub.value = 'Ayah';
            if (inputSecWa) inputSecWa.value = '';
            if (inputSecUser) inputSecUser.value = '';
            if (inputSecPass) inputSecPass.value = 'RacerWali2026!';

            if (typeof onKeluargaPaketChange === 'function') onKeluargaPaketChange();
        }

        function openEditKeluargaModal(idSiswa, nama, panggilan, jenjang, kelas, nisn, paketBelajar, namaWali, wa, hubungan) {
            const title = document.getElementById('modalAkunKeluargaTitle');
            const hiddenId = document.getElementById('inputEditIdSiswa');
            if (title) title.textContent = 'Edit Akun Keluarga & Siswa (' + (nama || 'Siswa') + ')';
            if (hiddenId) hiddenId.value = idSiswa || '';

            const inputSecNama = document.getElementById('inputNamaSecondaryParent');
            const inputSecHub = document.getElementById('inputHubunganSecondaryParent');
            const inputSecWa = document.getElementById('inputWhatsappSecondaryParent');
            const inputSecUser = document.getElementById('inputUsernameSecondaryParent');
            const inputSecPass = document.getElementById('inputPasswordSecondaryParent');
            if (inputSecNama) inputSecNama.value = '';
            if (inputSecHub) inputSecHub.value = 'Ayah';
            if (inputSecWa) inputSecWa.value = '';
            if (inputSecUser) inputSecUser.value = '';
            if (inputSecPass) inputSecPass.value = 'RacerWali2026!';

            const inputNama = document.getElementById('inputNamaSiswa');
            const inputPanggilan = document.getElementById('inputNamaPanggilan');
            const inputJenjang = document.getElementById('inputJenjang');
            const inputKelas = document.getElementById('inputKelas');
            const inputNisn = document.getElementById('inputNisn');
            const inputPaket = document.getElementById('inputPaketBelajar');
            const inputWali = document.getElementById('inputNamaWali');
            const inputWa = document.getElementById('inputWhatsappWali');
            const inputHub = document.getElementById('inputHubungan');

            if (inputNama) inputNama.value = nama || '';
            if (inputPanggilan) inputPanggilan.value = panggilan || (nama ? nama.split(' ')[0] : '');
            if (inputJenjang && jenjang) inputJenjang.value = jenjang;
            if (inputKelas) inputKelas.value = kelas || '3';
            if (inputNisn) inputNisn.value = nisn || '';
            if (inputWali) inputWali.value = (namaWali ? namaWali.replace(/^Wali:\s*/i, '') : '');
            if (inputWa) inputWa.value = wa || '081234567890';
            if (inputHub && hubungan) inputHub.value = hubungan;

            if (inputPaket && paketBelajar) {
                for (let i = 0; i < inputPaket.options.length; i++) {
                    if (inputPaket.options[i].value === paketBelajar || paketBelajar.includes(inputPaket.options[i].value)) {
                        inputPaket.selectedIndex = i;
                        break;
                    }
                }
            }
            if (typeof onKeluargaPaketChange === 'function') onKeluargaPaketChange();

            const modalEl = document.getElementById('modalAkunKeluarga');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        }

        // Fungsi Membuka Modal Tambah Anak (Siswa) Baru — Backlog B-14 (Muhammad Fadhil Swarigading)
        function openTambahAnakModal(idKeluarga, namaWali) {
            const hiddenId = document.getElementById('tambahSiswaIdKeluarga');
            const labelKeluarga = document.getElementById('tambahSiswaNamaKeluarga');
            if (hiddenId) hiddenId.value = idKeluarga || '';
            if (labelKeluarga) labelKeluarga.textContent = 'Akun Keluarga: ' + (namaWali || 'Keluarga #' + idKeluarga);
            const modalEl = document.getElementById('modalTambahSiswa');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        }

        // Fungsi Membuka Modal Kelola Secondary Parent (Wali Pendamping Opsional) — Backlog B-13b
        function openSecondaryParentModal(idKeluarga, namaSiswa, namaWali) {
            const modalEl = document.getElementById('modalSecondaryParent');
            if (!modalEl) return;

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            const lblKeluarga = document.getElementById('secondaryParentNamaKeluarga');
            const badgeStatus = document.getElementById('secondaryParentStatusBadge');
            const loading = document.getElementById('secondaryParentLoading');
            const existingView = document.getElementById('secondaryParentExistingView');
            const formTambah = document.getElementById('formTambahSecondaryParent');
            const formHapus = document.getElementById('formHapusSecondaryParent');

            if (lblKeluarga) lblKeluarga.textContent = 'Akun Keluarga: ' + (namaWali || 'Keluarga #' + idKeluarga) + ' (Siswa: ' + (namaSiswa || '-') + ')';
            if (badgeStatus) {
                badgeStatus.textContent = 'Memuat...';
                badgeStatus.className = 'badge bg-light text-muted border';
            }

            loading.classList.remove('d-none');
            existingView.classList.add('d-none');
            formTambah.classList.add('d-none');

            // Set action URL untuk form
            const storeUrl = "{{ url('/admin/keluarga') }}/" + idKeluarga + "/secondary-parent";
            const deleteUrl = "{{ url('/admin/keluarga') }}/" + idKeluarga + "/secondary-parent";
            if (formTambah) formTambah.action = storeUrl;
            if (formHapus) formHapus.action = deleteUrl;

            // Reset field tambah
            const inpNama = document.getElementById('tambahSecNama');
            const inpHub = document.getElementById('tambahSecHubungan');
            const inpWa = document.getElementById('tambahSecWa');
            const inpUser = document.getElementById('tambahSecUsername');
            const inpPass = document.getElementById('tambahSecPassword');
            if (inpNama) inpNama.value = '';
            if (inpHub) inpHub.value = 'Ayah';
            if (inpWa) inpWa.value = '';
            if (inpUser) inpUser.value = '';
            if (inpPass) inpPass.value = 'RacerWali2026!';

            // Panggil API getOrangTuaKeluarga
            fetch("{{ url('/api/keluarga') }}/" + idKeluarga + "/orang-tua")
                .then(res => res.json())
                .then(data => {
                    loading.classList.add('d-none');
                    if (data && data.success) {
                        if (data.has_secondary_parent && data.secondary_parent) {
                            const sec = data.secondary_parent;
                            if (badgeStatus) {
                                badgeStatus.textContent = 'Secondary Parent Aktif';
                                badgeStatus.className = 'badge bg-success-subtle text-success border border-success-subtle';
                            }
                            const elNama = document.getElementById('existingSecNama');
                            const elHub = document.getElementById('existingSecHubungan');
                            const elWa = document.getElementById('existingSecWa');
                            const elUser = document.getElementById('existingSecUsername');
                            if (elNama) elNama.textContent = sec.nama_lengkap || '-';
                            if (elHub) elHub.textContent = 'Hubungan: ' + (sec.hubungan || '-');
                            if (elWa) elWa.textContent = sec.nomor_whatsapp || '-';
                            if (elUser) elUser.textContent = sec.username || '(ID Pengguna: ' + (sec.id_pengguna || '-') + ')';
                            existingView.classList.remove('d-none');
                        } else {
                            if (badgeStatus) {
                                badgeStatus.textContent = 'Belum Ada';
                                badgeStatus.className = 'badge bg-secondary-subtle text-secondary border border-secondary-subtle';
                            }
                            formTambah.classList.remove('d-none');
                        }
                    } else {
                        if (badgeStatus) badgeStatus.textContent = 'Mode Input';
                        formTambah.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    console.error('Error fetching data orang-tua:', err);
                    loading.classList.add('d-none');
                    if (badgeStatus) badgeStatus.textContent = 'Mode Input';
                    formTambah.classList.remove('d-none');
                });
        }

        // Fungsi Membuka Modal Perpanjangan Paket (Renewal) — Backlog B-03 (Muhammad Fadhil Swarigading)
        function openRenewalModal(idSiswa, nama, program, jenisPaket, totalSesi, sesiTerpakai) {
            const selectSiswa = document.getElementById('renewalIdSiswa');
            const selectProgram = document.getElementById('renewalProgram');
            const selectPaket = document.getElementById('renewalJenisPaket');
            const oldPkgName = document.getElementById('renewalOldPackageName');
            const oldSessions = document.getElementById('renewalOldSessions');

            if (selectSiswa && idSiswa) {
                selectSiswa.value = idSiswa;
            }
            if (selectProgram && program) {
                for (let i = 0; i < selectProgram.options.length; i++) {
                    if (selectProgram.options[i].value === program || program.includes(selectProgram.options[i].value)) {
                        selectProgram.selectedIndex = i;
                        break;
                    }
                }
            }
            if (oldPkgName) {
                oldPkgName.textContent = `${jenisPaket || 'Semester'} • ${program || 'Robotics'} (${totalSesi || 12} Sesi)`;
            }
            if (oldSessions) {
                oldSessions.textContent = `${sesiTerpakai || 0} / ${totalSesi || 12} Sesi Terpakai`;
            }

            onRenewalPaketChange();

            const modalEl = document.getElementById('modalRenewalPaket');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        }

        function onRenewalPaketChange() {
            const selectPaket = document.getElementById('renewalJenisPaket');
            const inputTotalSesi = document.getElementById('renewalTotalSesi');
            const infoText = document.getElementById('renewalInfoKuotaText');
            if (!selectPaket) return;

            const opt = selectPaket.options[selectPaket.selectedIndex];
            const sesi = opt ? (opt.getAttribute('data-sesi') || (opt.value === 'Semester' ? 12 : 4)) : 12;

            if (inputTotalSesi) inputTotalSesi.value = sesi;
            if (infoText) {
                infoText.textContent = `Kuota awal periode baru otomatis ${sesi} sesi (mulai dari 0 sesi terpakai).`;
            }
        }

        function onRenewalSiswaChange() {
            const selectSiswa = document.getElementById('renewalIdSiswa');
            if (!selectSiswa) return;
            const opt = selectSiswa.options[selectSiswa.selectedIndex];
            if (!opt) return;

            const program = opt.getAttribute('data-program');
            const jenis = opt.getAttribute('data-jenis');
            const paket = opt.getAttribute('data-paket');
            const total = opt.getAttribute('data-total');
            const terpakai = opt.getAttribute('data-terpakai');

            const oldPkgName = document.getElementById('renewalOldPackageName');
            const oldSessions = document.getElementById('renewalOldSessions');

            if (oldPkgName) oldPkgName.textContent = paket || `${jenis || 'Semester'} • ${program || 'Robotics'} (${total || 12} Sesi)`;
            if (oldSessions) oldSessions.textContent = `${terpakai || 0} / ${total || 12} Sesi Terpakai`;
        }

        function switchToRenewalFromAktivasi() {
            const modalAktivasiEl = document.getElementById('modalAktivasiPaket');
            const modalAktivasi = bootstrap.Modal.getInstance(modalAktivasiEl);
            if (modalAktivasi) modalAktivasi.hide();

            const selectSiswa = document.getElementById('aktivasiIdSiswa');
            const idSiswa = selectSiswa ? selectSiswa.value : null;

            setTimeout(() => {
                const selectRenewalSiswa = document.getElementById('renewalIdSiswa');
                if (selectRenewalSiswa && idSiswa) {
                    selectRenewalSiswa.value = idSiswa;
                    onRenewalSiswaChange();
                }
                const modalRenewal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRenewalPaket'));
                modalRenewal.show();
            }, 350);
        }

        // ── Modal Update Kontak WhatsApp Wali & Domisili Akun Keluarga (Backlog B-42 - Arta Maruf Rifaa Ulwan) ──
        function openUpdateKontakDomisiliModal(idKeluarga, fallbackNama) {
            const form = document.getElementById('formUpdateKontakDomisili');
            const hiddenId = document.getElementById('inputEditKontakIdKeluarga');
            const subtitle = document.getElementById('modalUpdateKontakDomisiliSubtitle');
            const inputNama = document.getElementById('inputEditKontakNamaKeluarga');
            const inputWaPrimary = document.getElementById('inputEditKontakWhatsappPrimary');
            const inputWaSec = document.getElementById('inputEditKontakWhatsappSecondary');
            const inputAlamat = document.getElementById('inputEditKontakAlamat');
            const inputKel = document.getElementById('inputEditKontakKelurahan');
            const inputKec = document.getElementById('inputEditKontakKecamatan');
            const inputKota = document.getElementById('inputEditKontakKota');

            if (form) {
                form.action = `/admin/keluarga/${idKeluarga}/update-kontak-domisili`;
            }
            if (hiddenId) hiddenId.value = idKeluarga;
            if (subtitle) subtitle.textContent = `Memperbarui data kontak & domisili untuk ${fallbackNama || ('Keluarga #' + idKeluarga)}`;

            // Reset field value
            if (inputNama) inputNama.value = fallbackNama || '';
            if (inputWaPrimary) inputWaPrimary.value = 'Memuat...';
            if (inputWaSec) inputWaSec.value = '';
            if (inputAlamat) inputAlamat.value = 'Memuat alamat domisili...';
            if (inputKel) inputKel.value = '';
            if (inputKec) inputKec.value = '';
            if (inputKota) inputKota.value = '';

            const modalEl = document.getElementById('modalUpdateKontakDomisili');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            // Panggil API showKeluarga / orang-tua
            fetch(`/api/admin/keluarga/${idKeluarga}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.success && data.data) {
                    const d = data.data;
                    if (inputNama) inputNama.value = d.nama_keluarga || fallbackNama || '';
                    if (inputWaPrimary) inputWaPrimary.value = d.nomor_whatsapp || d.primary_parent?.nomor_whatsapp || '';
                    if (inputWaSec) inputWaSec.value = d.nomor_whatsapp_secondary || d.secondary_parent?.nomor_whatsapp || '';
                    if (inputAlamat) inputAlamat.value = d.alamat_domisili || '';
                    if (inputKel) inputKel.value = d.kelurahan || '';
                    if (inputKec) inputKec.value = d.kecamatan || '';
                    if (inputKota) inputKota.value = d.kota_kabupaten || '';
                }
            })
            .catch(() => {
                if (inputWaPrimary && inputWaPrimary.value === 'Memuat...') inputWaPrimary.value = '';
                if (inputAlamat && inputAlamat.value === 'Memuat alamat domisili...') inputAlamat.value = '';
            });
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

        // ── Riwayat Paket Siswa & Profil (Backlog B-03 / T-29 - Fahriel Aziz Satrio) ──
        let currentRiwayatSiswaId = null;
        let currentRiwayatSiswaNama = '';

        function openRiwayatPaketModal(idSiswa, namaSiswa, namaWali) {
            currentRiwayatSiswaId = idSiswa;
            currentRiwayatSiswaNama = namaSiswa || 'Siswa';

            const modalEl = document.getElementById('modalRiwayatPaketSiswa');
            const subtitleEl = document.getElementById('riwayatSiswaSubtitle');
            const loadingEl = document.getElementById('riwayatLoadingState');
            const contentEl = document.getElementById('riwayatContentContainer');
            const tbody = document.getElementById('riwayatTableBody');

            if (subtitleEl) {
                subtitleEl.textContent = `Profil Siswa: ${currentRiwayatSiswaNama} (${namaWali || 'Wali Murid'})`;
            }

            if (loadingEl) {
                loadingEl.innerHTML = '<div class="spinner-border text-primary mb-2" role="status"></div><p class="text-muted small mb-0">Mengambil data paket siswa dari server...</p>';
                loadingEl.style.display = 'block';
            }
            if (contentEl) contentEl.style.display = 'none';
            if (tbody) tbody.innerHTML = '';

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            fetch(`/api/admin/siswa/${idSiswa}/riwayat-paket`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (!res.success || !res.data) {
                    throw new Error(res.message || 'Gagal memuat data riwayat');
                }

                const data = res.data;
                const aktif = data.paket_aktif;
                const riwayat = data.riwayat_paket_sebelumnya || [];

                const progEl = document.getElementById('riwayatAktifProgram');
                const jenisEl = document.getElementById('riwayatAktifJenis');
                const badgeBayar = document.getElementById('riwayatStatusPembayaranBadge');
                const sesiText = document.getElementById('riwayatAktifSesiText');
                const progressBar = document.getElementById('riwayatAktifProgressBar');

                if (aktif) {
                    if (progEl) progEl.textContent = aktif.program || 'Junior Robotics';
                    if (jenisEl) jenisEl.textContent = `${aktif.jenis_paket || 'Semester'} • Total ${aktif.total_sesi || 12} Sesi (Aktivasi: ${aktif.tanggal_aktivasi ? aktif.tanggal_aktivasi.substring(0, 10) : '-'})`;

                    const bayar = aktif.status_pembayaran || 'Lunas';
                    if (badgeBayar) {
                        badgeBayar.style.display = 'inline-block';
                        badgeBayar.textContent = bayar;
                        badgeBayar.className = bayar === 'Lunas' ? 'badge bg-success-subtle text-success rounded-pill px-2 py-1 fw-semibold' : 'badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 fw-semibold';
                    }

                    const terpakai = parseInt(aktif.sesi_terpakai) || 0;
                    const total = parseInt(aktif.total_sesi) || 12;
                    const persen = total > 0 ? Math.min(100, Math.round((terpakai / total) * 100)) : 0;

                    if (sesiText) sesiText.textContent = `${terpakai} / ${total} Sesi (${Math.max(0, total - terpakai)} Sisa)`;
                    if (progressBar) {
                        progressBar.style.width = persen + '%';
                        progressBar.className = persen >= 100 ? 'progress-bar bg-danger' : (persen >= 75 ? 'progress-bar bg-warning' : 'progress-bar bg-primary');
                    }
                } else {
                    if (progEl) progEl.textContent = 'Belum Memiliki Paket Aktif';
                    if (jenisEl) jenisEl.textContent = 'Silakan lakukan aktivasi atau perpanjangan paket belajar baru.';
                    if (badgeBayar) badgeBayar.style.display = 'none';
                    if (sesiText) sesiText.textContent = '-';
                    if (progressBar) progressBar.style.width = '0%';
                }

                const countBadge = document.getElementById('riwayatTotalSebelumnyaCount');
                if (countBadge) countBadge.textContent = `${riwayat.length} paket terdahulu`;

                if (tbody) {
                    if (riwayat.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block text-secondary opacity-50 mb-1"></i>
                                    Belum ada arsip riwayat paket sebelumnya. Paket saat ini adalah paket perdana siswa.
                                </td>
                            </tr>
                        `;
                    } else {
                        let html = '';
                        riwayat.forEach((p, idx) => {
                            const bayarClass = p.status_pembayaran === 'Lunas' ? 'text-success' : 'text-warning-emphasis';
                            const tgl = escapeHtml(p.tanggal_aktivasi ? p.tanggal_aktivasi.substring(0, 10) : (p.created_at ? p.created_at.substring(0, 10) : '-'));
                            const safeProgram = escapeHtml(p.program || '-');
                            const safeJenis = escapeHtml(p.jenis_paket || '-');
                            const safeStatusPaket = escapeHtml(p.status_paket || 'Selesai');
                            const safeStatusBayar = escapeHtml(p.status_pembayaran || 'Lunas');
                            html += `
                                <tr>
                                    <td class="py-2 px-3 fw-bold text-muted">${idx + 1}</td>
                                    <td class="py-2">
                                        <div class="fw-semibold text-dark">${safeProgram}</div>
                                        <small class="text-muted">${safeJenis}</small>
                                    </td>
                                    <td class="py-2">
                                        <span class="badge bg-light text-dark border">${parseInt(p.sesi_terpakai) || 0} / ${parseInt(p.total_sesi) || 0} Sesi</span>
                                    </td>
                                    <td class="py-2">
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1">${safeStatusPaket}</span>
                                    </td>
                                    <td class="py-2 fw-semibold ${bayarClass}">${safeStatusBayar}</td>
                                    <td class="py-2 px-3 text-muted small">${tgl}</td>
                                </tr>
                            `;
                        });
                        tbody.innerHTML = html;
                    }
                }

                if (loadingEl) loadingEl.style.display = 'none';
                if (contentEl) contentEl.style.display = 'block';
            })
            .catch(err => {
                if (loadingEl) {
                    loadingEl.innerHTML = `
                        <div class="text-danger py-4">
                            <i class="bi bi-exclamation-triangle fs-2 d-block mb-2"></i>
                            Gagal memuat riwayat paket siswa. Silakan coba lagi.
                        </div>
                    `;
                }
            });
        }

        function lihatRiwayatDariModalRenewal() {
            const selectSiswa = document.getElementById('renewalIdSiswa');
            if (selectSiswa && selectSiswa.value) {
                const opt = selectSiswa.options[selectSiswa.selectedIndex];
                const idSiswa = selectSiswa.value;
                const nama = opt ? opt.getAttribute('data-nama') : 'Siswa';
                const modalRenewal = bootstrap.Modal.getInstance(document.getElementById('modalRenewalPaket'));
                if (modalRenewal) modalRenewal.hide();
                setTimeout(() => {
                    openRiwayatPaketModal(idSiswa, nama, '');
                }, 350);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const btnPerpanjang = document.getElementById('btnPerpanjangDariRiwayat');
            if (btnPerpanjang) {
                btnPerpanjang.addEventListener('click', function () {
                    const modalRiwayat = bootstrap.Modal.getInstance(document.getElementById('modalRiwayatPaketSiswa'));
                    if (modalRiwayat) modalRiwayat.hide();
                    setTimeout(() => {
                        if (currentRiwayatSiswaId) {
                            openRenewalModal(currentRiwayatSiswaId, currentRiwayatSiswaNama);
                        }
                    }, 350);
                });
            }
        });
    </script>
</body>

</html>


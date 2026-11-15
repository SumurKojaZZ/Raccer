<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Keluarga & Siswa - RACER ROBOTIC Administrasi Akademik</title>
    <!-- Google Font: Plus Jakarta Sans -->
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
            --bg-canvas: #f8fafc;
            --card-border: #e2e8f0;
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
            border-right: 1px solid var(--card-border);
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

        .logo-box {
            width: 36px;
            height: 36px;
            background: var(--primary-blue);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.25rem;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.65rem 1rem;
            color: #475569;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            margin-bottom: 0.25rem;
        }

        .nav-link-custom:hover {
            background-color: #f1f5f9;
            color: var(--primary-blue);
        }

        .nav-link-custom.active {
            background-color: #eff6ff;
            color: var(--primary-blue);
            font-weight: 600;
            position: relative;
        }

        .nav-link-custom.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 15%;
            bottom: 15%;
            width: 4px;
            background-color: var(--primary-blue);
            border-radius: 0 4px 4px 0;
        }

        /* Top Navbar */
        .top-navbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            margin-left: var(--sidebar-width);
        }

        .search-box {
            position: relative;
            width: 380px;
        }

        .search-box input {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 2.5rem 0.5rem 2.2rem;
            font-size: 0.84rem;
            width: 100%;
        }

        .search-box input:focus {
            background-color: #ffffff;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(29, 104, 240, 0.1);
            outline: none;
        }

        .search-box .bi-search {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.88rem;
        }

        .search-box .shortcut-tag {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 0.1rem 0.4rem;
            font-size: 0.7rem;
            border-radius: 4px;
            color: #94a3b8;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        /* Cards */
        .card-custom {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .stat-title {
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 500;
            margin-bottom: 0.35rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .stat-unit {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-left: 0.25rem;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        /* Filter Controls */
        .filter-select {
            background-color: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 8px;
            padding: 0.55rem 1rem;
            font-size: 0.85rem;
            color: #334155;
            font-weight: 500;
            min-width: 170px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            cursor: pointer;
        }

        .filter-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(29, 104, 240, 0.1);
            outline: none;
        }

        /* Buttons */
        .btn-primary-custom {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: #ffffff;
            font-weight: 600;
            padding: 0.55rem 1.25rem;
            border-radius: 8px;
            font-size: 0.86rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #ffffff;
        }

        .btn-outline-custom {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
            font-weight: 600;
            padding: 0.55rem 1.25rem;
            border-radius: 8px;
            font-size: 0.86rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-outline-custom:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
            color: var(--primary-blue);
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

        /* Badge Status Pembayaran - Khas Tugas Frontend Fahriel Aziz Satrio */
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

        /* Status Selection Radio Cards in Modal */
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

        /* Modal Subheader Sesuai Wireframe Tambah / Edit Keluarga */
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

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">
        <!-- Logo & Brand -->
        <div class="d-flex align-items-center gap-2 mb-4 px-2">
            <div class="logo-box">
                <i class="bi bi-robot"></i>
            </div>
            <div>
                <span class="fw-bold d-block text-dark" style="font-size: 0.95rem; letter-spacing: -0.2px;">RACER ROBOTIC</span>
                <small class="text-muted" style="font-size: 0.72rem;">Administrasi Akademik</small>
            </div>
        </div>

        <small class="text-uppercase text-muted fw-bold px-2 mb-2" style="font-size: 0.68rem; letter-spacing: 0.8px;">Menu Utama</small>
        <nav class="nav flex-column mb-auto">
            <a href="{{ route('admin') }}" class="nav-link-custom">
                <i class="bi bi-grid"></i>
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
            <a href="{{ route('admin.penjadwalan') }}" class="nav-link-custom">
                <i class="bi bi-calendar3"></i>
                <span>Penjadwalan Terpusat</span>
            </a>
            <a href="{{ route('admin') }}#presensi" class="nav-link-custom">
                <i class="bi bi-check2-square"></i>
                <span>Presensi & Reschedule</span>
            </a>
            <a href="{{ route('admin') }}#pengumuman" class="nav-link-custom">
                <i class="bi bi-megaphone"></i>
                <span>Pengumuman Lembaga</span>
            </a>
            <a href="#" class="nav-link-custom">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Laporan Bulanan</span>
            </a>
        </nav>

        <!-- Bantuan Operasional Card -->
        <div class="card border-0 rounded-3 p-3 mb-3" style="background-color: #f1f5f9;">
            <div class="d-flex gap-2 mb-2">
                <i class="bi bi-question-circle text-primary"></i>
                <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.78rem;">Butuh bantuan operasional?</h6>
                    <small class="text-muted" style="font-size: 0.72rem;">Tim support siap membantu Anda.</small>
                </div>
            </div>
            <a href="https://wa.me/6281234567890" target="_blank" class="fw-semibold text-primary text-decoration-none" style="font-size: 0.74rem;">
                Hubungi tim support &nearr;
            </a>
        </div>

        <!-- Admin Profile & Logout -->
        <div class="border-top pt-3 d-flex align-items-center justify-content-between px-2">
            <div class="d-flex align-items-center gap-2">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=100"
                    alt="Admin" class="rounded-circle" style="width: 34px; height: 34px; object-fit: cover;">
                <div style="line-height: 1.2;">
                    <span class="fw-semibold text-dark d-block" style="font-size: 0.8rem;">Admin Operasional</span>
                    <small class="text-muted" style="font-size: 0.7rem;">admin@racer-robotic.id</small>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="text-muted text-decoration-none" title="Keluar">
                <i class="bi bi-box-arrow-right fs-5"></i>
            </a>
        </div>
    </aside>

    <!-- ================= TOP NAVBAR ================= -->
    <header class="top-navbar">
        <form method="GET" action="{{ route('admin.keluarga') }}" class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="q" value="{{ $search ?? '' }}"
                placeholder="Cari nama siswa, nama wali, atau tutor...">
            <span class="shortcut-tag">&#8984; K</span>
        </form>

        <div class="d-flex align-items-center gap-3">
            <span class="badge border bg-light text-secondary rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.78rem;">
                <i class="bi bi-calendar-event me-1 text-primary"></i> T.A 2026/2027
            </span>

            <button class="btn btn-light rounded-circle p-2 border position-relative" style="width: 36px; height: 36px;" title="Notifikasi">
                <i class="bi bi-bell text-secondary"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </button>

            <div class="d-flex align-items-center gap-2 ps-2 border-start">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=100"
                    alt="Admin" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                <span class="fw-semibold text-dark d-none d-md-inline" style="font-size: 0.84rem;">Admin</span>
                <i class="bi bi-chevron-down text-muted" style="font-size: 0.75rem;"></i>
            </div>
        </div>
    </header>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Flash Message Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 px-3 mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div style="font-size: 0.88rem;">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 px-3 mb-4" role="alert">
                <div class="fw-bold mb-1" style="font-size: 0.88rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terdapat kesalahan input:</div>
                <ul class="mb-0 ps-3" style="font-size: 0.84rem;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Title & Subtitle -->
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.55rem; letter-spacing: -0.3px;">Akun Keluarga & Siswa</h3>
            <p class="text-muted mb-0" style="font-size: 0.88rem;">
                Manajemen data wali murid, relasi anak/siswa terdaftar, dan pemantauan kuota paket belajar.
            </p>
        </div>

        <!-- 4 Statistics Cards -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Akun Keluarga -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-title">Total Akun Keluarga</div>
                        <div class="d-flex align-items-baseline">
                            <span class="stat-value">{{ $totalKeluargaCount }}</span>
                            <span class="stat-unit">Keluarga</span>
                        </div>
                    </div>
                    <div class="stat-icon" style="background-color: #f1f5f9; color: #475569;">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>

            <!-- 2. Total Siswa Terdaftar -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-title">Total Siswa Terdaftar</div>
                        <div class="d-flex align-items-baseline">
                            <span class="stat-value">{{ $totalSiswaCount }}</span>
                            <span class="stat-unit">Siswa</span>
                        </div>
                    </div>
                    <div class="stat-icon" style="background-color: #eff6ff; color: var(--primary-blue);">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                </div>
            </div>

            <!-- 3. Paket Hampir Habis -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-title">Paket Hampir Habis</div>
                        <div class="d-flex align-items-baseline">
                            <span class="stat-value" style="color: #d97706 !important;">{{ $paketHampirHabisCount }}</span>
                            <span class="stat-unit">Siswa</span>
                        </div>
                    </div>
                    <div class="stat-icon" style="background-color: #fef3c7; color: #d97706;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>

            <!-- 4. Aktivasi WhatsApp -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-title">Aktivasi WhatsApp</div>
                        <div class="d-flex align-items-baseline">
                            <span class="stat-value" style="color: #10b981 !important;">{{ $aktivasiWhatsappPercent }}%</span>
                            <span class="stat-unit">Terhubung</span>
                        </div>
                    </div>
                    <div class="stat-icon" style="background-color: #ecfdf5; color: #10b981;">
                        <i class="bi bi-whatsapp"></i>
                    </div>
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
                <button type="button" class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalAktivasiPaket">
                    <i class="bi bi-plus-lg text-primary"></i> Aktivasi Paket
                </button>

                <!-- Tombol 2: Edit Keluarga (Solid Primary Button Sesuai Permintaan) -->
                <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAkunKeluarga" onclick="openTambahKeluargaModal()">
                    <i class="bi bi-pencil-square"></i> Edit Keluarga
                </button>
            </div>
        </div>

        <!-- Table Card: Daftar Keluarga & Siswa -->
        <div class="card-custom">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Daftar keluarga & siswa</h5>
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
                                    <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ $s->nama }}</div>
                                    <small class="text-muted" style="font-size: 0.78rem;">{{ $s->nama_wali }}</small>
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

        <!-- Footer Info -->
        <footer class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span>&copy; 2026 RACER ROBOTIC. Bertumbuh bersama teknologi.</span>
            <span>Semua jadwal dalam Waktu Indonesia Barat (WIB)</span>
        </footer>
    </main>

    <!-- ========================================================================= -->
    <!-- MODAL 1: FORM AKTIVASI PAKET BELAJAR SISWA (Front-End - Fahriel Aziz Satrio) -->
    <!-- ========================================================================= -->
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
                            </div>
                        </div>

                        <!-- 1. Pilih Siswa Aktif -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.83rem;">
                                Pilih Siswa Aktif <span class="text-danger">*</span>
                            </label>
                            <select name="id_siswa" id="aktivasiIdSiswa" class="form-select rounded-3 @error('id_siswa') is-invalid @enderror" required style="font-size: 0.88rem; padding: 0.65rem 0.9rem;" onchange="checkConflictActivePackage()">
                                @foreach ($allSiswaOptions as $opt)
                                    <option value="{{ $opt->id_siswa ?? $opt->id }}"
                                        data-nama="{{ $opt->nama }}"
                                        data-wali="{{ $opt->nama_wali }}"
                                        data-program="{{ $opt->program }}"
                                        data-status-paket="{{ $opt->status_paket ?? 'Aktif' }}"
                                        data-paket="{{ $opt->jenis_paket }}"
                                        data-pembayaran="{{ $opt->status_pembayaran }}"
                                        {{ (old('id_siswa', 1) == ($opt->id_siswa ?? $opt->id)) ? 'selected' : '' }}>
                                        {{ $opt->nama }} (Wali: {{ $opt->nama_wali }})
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

                        <!-- 3. Jenis Paket (2 Cards Sesuai Wireframe Image 1) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-2" style="font-size: 0.83rem;">
                                Jenis Paket <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="card-jenis-paket active-paket w-100" id="cardSemester" onclick="selectJenisPaket('Semester', 12)">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <input type="radio" name="jenis_paket" id="radioSemester" value="Semester" checked class="form-check-input mt-0" style="cursor: pointer;">
                                            <span class="fw-bold text-dark" style="font-size: 0.88rem;">Paket Semester</span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.76rem; padding-left: 1.45rem;">
                                            Kuota otomatis: 12 Sesi
                                        </div>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="card-jenis-paket w-100" id="cardBulanan" onclick="selectJenisPaket('Bulanan', 4)">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <input type="radio" name="jenis_paket" id="radioBulanan" value="Bulanan" class="form-check-input mt-0" style="cursor: pointer;">
                                            <span class="fw-bold text-dark" style="font-size: 0.88rem;">Paket Bulanan</span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.76rem; padding-left: 1.45rem;">
                                            Kuota otomatis: Maks. 4 Sesi
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Info Kuota Otomatis Sesuai Wireframe Image 1 -->
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
                        </div>

                        <!-- 2. PILIHAN PAKET BELAJAR (Sesuai Wireframe Image 2) -->
                        <div class="modal-section-title mt-4">PILIHAN PAKET BELAJAR</div>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="form-label text-dark fw-medium mb-1" style="font-size: 0.82rem;">Paket Belajar</label>
                                <select name="paket_belajar" id="inputPaketBelajar" class="form-select rounded-2" style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                    <optgroup label="Paket Regular — Per Bulan (4× Pertemuan)">
                                        <option value="Regular - Gold (1 Orang) · Per Bulan (4x Pertemuan)" selected>Regular - Gold (1 Orang) · Per Bulan (4× Pertemuan)</option>
                                        <option value="Regular - Silver (2 Orang) · Per Bulan (4x Pertemuan)">Regular - Silver (2 Orang) · Per Bulan (4× Pertemuan)</option>
                                        <option value="Regular - Bronze (3 Orang) · Per Bulan (4x Pertemuan)">Regular - Bronze (3 Orang) · Per Bulan (4× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Regular — 1 Semester (12× Pertemuan)">
                                        <option value="Regular - Gold (1 Orang) · 1 Semester (12x Pertemuan)">Regular - Gold (1 Orang) · 1 Semester (12× Pertemuan)</option>
                                        <option value="Regular - Silver (2 Orang) · 1 Semester (12x Pertemuan)">Regular - Silver (2 Orang) · 1 Semester (12× Pertemuan)</option>
                                        <option value="Regular - Bronze (3 Orang) · 1 Semester (12x Pertemuan)">Regular - Bronze (3 Orang) · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Private">
                                        <option value="Paket Private · Per Bulan (4x Pertemuan)">Paket Private · Per Bulan (4× Pertemuan)</option>
                                        <option value="Paket Private · 1 Semester (12x Pertemuan)">Paket Private · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                    <optgroup label="Paket Online">
                                        <option value="Paket Online · Per Bulan (4x Pertemuan)">Paket Online · Per Bulan (4× Pertemuan)</option>
                                        <option value="Paket Online · 1 Semester (12x Pertemuan)">Paket Online · 1 Semester (12× Pertemuan)</option>
                                    </optgroup>
                                </select>
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

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts for Modal Interaction & Client-side Validation -->
    <script>
        // Inisialisasi Tooltip Bootstrap
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

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

        // Pemilihan Jenis Paket (Paket Semester 12 sesi vs Paket Bulanan 4 sesi) sesuai Wireframe Image 1
        function selectJenisPaket(jenis, sesi) {
            const radioSemester = document.getElementById('radioSemester');
            const radioBulanan = document.getElementById('radioBulanan');
            const cardSemester = document.getElementById('cardSemester');
            const cardBulanan = document.getElementById('cardBulanan');
            const inputTotalSesi = document.getElementById('aktivasiTotalSesi');
            const infoText = document.getElementById('infoKuotaText');

            if (jenis === 'Semester') {
                if (radioSemester) radioSemester.checked = true;
                if (cardSemester) cardSemester.classList.add('active-paket');
                if (cardBulanan) cardBulanan.classList.remove('active-paket');
                if (inputTotalSesi) inputTotalSesi.value = sesi || 12;
                if (infoText) {
                    infoText.textContent = 'Kuota awal otomatis terisi 12 sesi saat paket diaktifkan (berlaku walaupun status Belum Bayar).';
                }
            } else {
                if (radioBulanan) radioBulanan.checked = true;
                if (cardBulanan) cardBulanan.classList.add('active-paket');
                if (cardSemester) cardSemester.classList.remove('active-paket');
                if (inputTotalSesi) inputTotalSesi.value = sesi || 4;
                if (infoText) {
                    infoText.textContent = 'Kuota awal otomatis terisi 4 sesi saat paket diaktifkan (berlaku walaupun status Belum Bayar).';
                }
            }
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

            selectJenisPaket(jenisPaket === 'Bulanan' ? 'Bulanan' : 'Semester', totalSesi || (jenisPaket === 'Bulanan' ? 4 : 12));

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

        // Buka otomatis modal jika ada flash pesan error konflik aktivasi
        @if (session('open_modal_aktivasi') || session('error_aktivasi') || $errors->has('paket_aktif'))
        document.addEventListener('DOMContentLoaded', function () {
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
            if (title) title.textContent = 'Edit Akun Keluarga & Siswa';
            if (hiddenId) hiddenId.value = '';
        }

        function openEditKeluargaModal(idSiswa, nama, panggilan, jenjang, kelas, nisn, paketBelajar, namaWali, wa, hubungan) {
            const title = document.getElementById('modalAkunKeluargaTitle');
            const hiddenId = document.getElementById('inputEditIdSiswa');
            if (title) title.textContent = 'Edit Akun Keluarga & Siswa (' + (nama || 'Siswa') + ')';
            if (hiddenId) hiddenId.value = idSiswa || '';

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

            const modalEl = document.getElementById('modalAkunKeluarga');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        }
    </script>
</body>

</html>


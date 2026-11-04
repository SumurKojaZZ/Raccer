<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tutor - RACER ROBOTIC Administrasi Akademik</title>
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

        /* Badges for Module Levels */
        .badge-modul-level1 {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
        }

        .badge-modul-level2 {
            background-color: #faf5ff;
            color: #7e22ce;
            border: 1px solid #e9d5ff;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
        }

        .badge-modul-level3 {
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
        }

        .badge-soft-primary {
            background-color: #dbeafe;
            color: #1d4ed8;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.35rem 0.65rem;
        }

        /* Table styling */
        .table-tutor th {
            color: #64748b;
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1rem;
            background-color: #ffffff;
        }

        .table-tutor td {
            vertical-align: middle;
            font-size: 0.88rem;
            padding: 1.1rem 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-tutor tr:hover td {
            background-color: #f8fafc;
        }

        .pill-panggilan {
            background-color: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            display: inline-block;
        }

        .form-section-title {
            color: #1d68f0;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .btn-primary-blue {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: #ffffff;
        }

        .btn-primary-blue:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #ffffff;
        }

        .filter-select {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.55rem 1rem;
            font-size: 0.88rem;
            color: #334155;
            font-weight: 500;
        }

        .filter-select:focus {
            border-color: #93c5fd;
            outline: none;
            box-shadow: 0 0 0 3px rgba(29, 104, 240, 0.1);
        }

        /* Modal Overlay & Fallback Display */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1055;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
            display: none;
        }

        .modal.show {
            display: block !important;
            background-color: rgba(15, 23, 42, 0.55);
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: 1.75rem auto;
            pointer-events: none;
            z-index: 1060;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 3.5rem);
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 1rem;
            outline: 0;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .modal-backdrop.show {
            opacity: 0.5;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            width: 100vw;
            height: 100vh;
            background-color: #000;
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
                        <a href="{{ route('admin') }}#modalAkunKeluarga" class="nav-link-custom">
                            <i class="bi bi-people"></i>
                            <span>Akun Keluarga & Siswa</span>
                        </a>
                        <!-- Menu Data Tutor Aktif -->
                        <a href="{{ route('admin.tutor') }}" class="nav-link-custom active">
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
                            <a href="#" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.76rem;">
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
                    <button type="button" class="btn btn-link text-muted p-1 text-decoration-none" title="Logout" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiLogout">
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
                    <form action="{{ route('admin.tutor') }}" method="GET" class="search-container flex-grow-1" style="max-width: 480px;">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="q" class="search-input w-100"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari nama siswa, nama wali, atau tutor...">
                        @if(!empty($fokus)) <input type="hidden" name="fokus" value="{{ $fokus }}"> @endif
                        @if(!empty($status)) <input type="hidden" name="status" value="{{ $status }}"> @endif
                    </form>
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

                <!-- Page Header: Judul & Subtitle Sesuai Wireframe -->
                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">Data & Manajemen Akun Tutor</h3>
                    <p class="text-muted mb-0" style="font-size: 0.88rem;">Buat akun instruktur baru, tentukan spesialisasi kurikulum, dan tautan kelas.</p>
                </div>

                <!-- 4 Top Metric Cards Sesuai Wireframe -->
                <div class="row g-3 mb-4">
                    <!-- Stat 1: Total Tutor Terdaftar -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Total Tutor Terdaftar</span>
                            <div class="d-flex align-items-baseline">
                                <span class="stat-value">{{ $totalTutorTerdaftar }}</span>
                                <span class="stat-unit">Tutor</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 2: Tutor Aktif Mengajar -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Tutor Aktif Mengajar</span>
                            <div class="d-flex align-items-baseline">
                                <span class="stat-value">{{ $tutorAktifMengajar }}</span>
                                <span class="stat-unit">Tutor</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 3: Sesi Mengajar Hari ini (Angka Kuning Sesuai Wireframe) -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Sesi Mengajar Hari ini</span>
                            <div class="d-flex align-items-baseline">
                                <span class="stat-value" style="color: #f59e0b !important;">{{ $sesiMengajarHariIni }}</span>
                                <span class="stat-unit">Siswa</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 4: Kapasitas Sesi Mengajar (82% Terisi Optimal) -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dash-card">
                            <span class="text-muted fw-medium d-block mb-2" style="font-size: 0.85rem;">Kapasitas Sesi Mengajar</span>
                            <div class="d-flex align-items-baseline">
                                <span class="stat-value">{{ $kapasitasSesiMengajar }}%</span>
                                <span class="stat-unit text-dark fw-semibold ms-2">Terisi Optimal</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls & Tombol Tambah Tutor Sesuai Wireframe -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <form action="{{ route('admin.tutor') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap" id="filterForm">
                        @if(!empty($search)) <input type="hidden" name="q" value="{{ $search }}"> @endif
                        <!-- Filter Fokus Modul -->
                        <select name="fokus" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="Semua Modul" {{ empty($fokus) || $fokus == 'Semua Modul' ? 'selected' : '' }}>Fokus: Semua Modul</option>
                            <option value="Level 1" {{ $fokus == 'Level 1' ? 'selected' : '' }}>Level 1: Building (TK/SD)</option>
                            <option value="Level 2" {{ $fokus == 'Level 2' ? 'selected' : '' }}>Level 2: Sensor (SD/SMP)</option>
                            <option value="Level 3" {{ $fokus == 'Level 3' ? 'selected' : '' }}>Level 3: Controller (SMP/SMA)</option>
                        </select>

                        <!-- Filter Status Tutor -->
                        <select name="status" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="Tutor Aktif" {{ empty($status) || $status == 'Tutor Aktif' ? 'selected' : '' }}>Status: Tutor Aktif</option>
                            <option value="Tutor Nonaktif" {{ $status == 'Tutor Nonaktif' ? 'selected' : '' }}>Status: Tutor Nonaktif</option>
                            <option value="Semua" {{ $status == 'Semua' ? 'selected' : '' }}>Status: Semua Tutor</option>
                        </select>
                    </form>

                    <!-- Tombol + Tambah Tutor (Membuka Modal) -->
                    <button type="button" id="btnBukaModalTambahTutor"
                        class="btn btn-primary-blue d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3 shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#modalTambahTutor"
                        onclick="bukaModalTambahTutor()">
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Tutor</span>
                    </button>
                </div>

                <!-- Card Tabel Daftar Tutor Berstatus Aktif -->
                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table table-tutor mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 110px;">ID TUTOR</th>
                                    <th>NAMA & GELAR</th>
                                    <th>PANGGILAN</th>
                                    <th>WHATSAPP & USERNAME</th>
                                    <th>JENJANG MODUL</th>
                                    <th>STATUS</th>
                                    <th class="text-center" style="width: 90px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tutorList as $tutor)
                                    <tr>
                                        <!-- ID TUTOR -->
                                        <td>
                                            <a href="#modalDetailTutor{{ $tutor->id_tutor ?? $tutor->id }}" data-bs-toggle="modal"
                                                onclick="bukaModalDetail('{{ $tutor->id_tutor ?? $tutor->id }}')"
                                                class="fw-bold text-primary text-decoration-none" style="font-size: 0.86rem;">
                                                {{ $tutor->kode_tutor ?: ($tutor->id_tutor ? 'TTR-' . str_pad($tutor->id_tutor, 3, '0', STR_PAD_LEFT) : ('TTR-' . str_pad($tutor->id ?? 1, 3, '0', STR_PAD_LEFT))) }}
                                            </a>
                                        </td>

                                        <!-- NAMA & GELAR + PENEMPATAN -->
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $tutor->nama_gelar ?: ($tutor->nama_lengkap ?: ($tutor->nama ?? 'Instruktur Tutor')) }}</div>
                                            <small class="text-muted" style="font-size: 0.78rem;">{{ $tutor->penempatan ?: 'Lab A • Jakarta Pusat' }}</small>
                                        </td>

                                        <!-- PANGGILAN -->
                                        <td>
                                            <span class="pill-panggilan">{{ $tutor->nama_panggilan ?: 'Kak Tutor' }}</span>
                                        </td>

                                        <!-- WHATSAPP & USERNAME -->
                                        <td>
                                            <div class="fw-medium text-dark" style="font-size: 0.88rem;">{{ $tutor->whatsapp ?: ($tutor->nomor_whatsapp ?: '-') }}</div>
                                            <small class="text-muted" style="font-size: 0.78rem;">{{ $tutor->username ?: ('@' . strtolower(str_replace(' ', '.', str_replace(['Kak ', 'Kak', 'kak '], '', $tutor->nama_panggilan ?? 'tutor')))) }} (Username)</small>
                                        </td>

                                        <!-- JENJANG MODUL -->
                                        <td>
                                            @php
                                                $modulText = $tutor->jenjang_modul ?: 'Level 1: Building (TK/SD)';
                                            @endphp
                                            @if ($tutor->level_color == 'primary' || str_contains($modulText, 'Level 1') || str_contains($modulText, 'Building'))
                                                <span class="badge-modul-level1">{{ $modulText }}</span>
                                            @elseif ($tutor->level_color == 'purple' || str_contains($modulText, 'Level 2') || str_contains($modulText, 'Sensor'))
                                                <span class="badge-modul-level2">{{ $modulText }}</span>
                                            @else
                                                <span class="badge-modul-level3">{{ $modulText }}</span>
                                            @endif
                                        </td>

                                        <!-- STATUS (Titik Hijau Aktif Sesuai Wireframe) -->
                                        <td>
                                            @if (($tutor->status ?? 'Aktif') == 'Aktif')
                                                <span class="text-success fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.84rem;">
                                                    <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="text-muted fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.84rem;">
                                                    <i class="bi bi-circle-fill" style="font-size: 0.45rem;"></i>
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>

                                        <!-- AKSI (Tombol Detail Sesuai Wireframe) -->
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light border bg-white text-secondary fw-semibold rounded-2 px-3 py-1 shadow-2xs"
                                                data-bs-toggle="modal" data-bs-target="#modalDetailTutor{{ $tutor->id_tutor ?? $tutor->id }}"
                                                onclick="bukaModalDetail('{{ $tutor->id_tutor ?? $tutor->id }}')" style="font-size: 0.82rem;">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                                            Belum ada data tutor yang sesuai dengan filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Pagination Sesuai Wireframe -->
                    <div class="d-flex justify-content-between align-items-center p-3 px-4 border-top flex-wrap gap-2">
                        <small class="text-muted" style="font-size: 0.82rem;">
                            Menampilkan 1-{{ $tutorList->count() }} dari {{ max(10, $tutorList->count()) }} Tutor Berstatus Aktif
                        </small>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-light border bg-white px-3 py-1 text-muted fw-medium rounded-2" disabled style="font-size: 0.82rem;">
                                Sebelumnya
                            </button>
                            <button class="btn btn-sm btn-light border bg-white px-3 py-1 text-dark fw-medium rounded-2" style="font-size: 0.82rem;">
                                Berikutnya
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bottom Footer Sesuai Wireframe -->
                <div class="d-flex justify-content-between align-items-center text-muted flex-wrap gap-2 pt-2 pb-4" style="font-size: 0.78rem;">
                    <div>© 2026 RACER ROBOTIC. Bertumbuh bersama teknologi.</div>
                    <div>Semua jadwal dalam Waktu Indonesia Barat (WIB)</div>
                </div>

            </div>
        </main>
    </div>

    <!-- ================= MODAL TAMBAH AKUN TUTOR BARU (Sesuai Wireframe Image 3) ================= -->
    <div class="modal fade" id="modalTambahTutor" tabindex="-1" aria-labelledby="modalTambahTutorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <form id="formTambahTutor" action="{{ route('admin.tutor.store') }}" method="POST" novalidate>
                    @csrf
                    <!-- Header Modal -->
                    <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="modal-title fw-bold text-dark" id="modalTambahTutorLabel" style="letter-spacing: -0.3px;">
                                Tambah Akun Tutor Baru
                            </h4>
                            <p class="text-muted mb-0" style="font-size: 0.86rem;">
                                Daftarkan instruktur baru, tetapkan modul ajar, dan atur tautan sesi daring.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.8rem;">
                                ID: {{ $nextIdTutor ?? 'TTR-005' }} (Auto)
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutupModalTambahTutor()"></button>
                        </div>
                    </div>

                    <!-- Body Modal -->
                    <div class="modal-body px-4 py-4">

                        <!-- Alert Pesan Validasi Jika Ada Kesalahan -->
                        <div id="modalAlertError" class="alert alert-danger @if(!$errors->any()) d-none @else d-flex @endif align-items-center gap-2 mb-4 py-2 px-3 rounded-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 fs-5"></i>
                            <div>
                                <strong class="d-block" style="font-size: 0.85rem;">Validasi Formulir Gagal</strong>
                                <span id="modalAlertErrorText" style="font-size: 0.78rem;">
                                    @if($errors->any())
                                        Terdapat data yang belum sesuai. Silakan periksa kolom bertanda merah di bawah.
                                    @else
                                        Silakan lengkapi atau perbaiki kolom yang bertanda merah di bawah.
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- SECTION 1: IDENTITAS PRIBADI & KONTAK TUTOR -->
                        <div class="mb-4">
                            <div class="form-section-title">1. IDENTITAS PRIBADI & KONTAK TUTOR</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                        Nama Lengkap & Gelar <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nama_gelar" id="inputNamaGelar"
                                        class="form-control rounded-3 @error('nama_gelar') is-invalid @enderror"
                                        placeholder="Ahmad Fauzi, S.T." value="{{ old('nama_gelar') }}"
                                        style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                    <div class="invalid-feedback" id="feedbackNamaGelar" style="font-size: 0.78rem;">
                                        @error('nama_gelar') {{ $message }} @else Nama lengkap dan gelar wajib diisi (minimal 3 karakter). @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                        Nomor WhatsApp Aktif <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="whatsapp" id="inputWhatsapp"
                                        class="form-control rounded-3 @error('whatsapp') is-invalid @enderror"
                                        placeholder="+62 813-4567-8901" value="{{ old('whatsapp') }}"
                                        style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                    <div class="invalid-feedback" id="feedbackWhatsapp" style="font-size: 0.78rem;">
                                        @error('whatsapp') {{ $message }} @else Nomor WhatsApp aktif wajib diisi (min. 10 digit, contoh: +62 813-4567-8901). @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                        Email Akun (Username Login) <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" id="inputEmail"
                                        class="form-control rounded-3 @error('email') is-invalid @enderror"
                                        placeholder="ahmad.fauzi@racer.id" value="{{ old('email') }}"
                                        style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                    <div class="invalid-feedback" id="feedbackEmail" style="font-size: 0.78rem;">
                                        @error('email') {{ $message }} @else Email akun wajib diisi dengan format email valid (contoh: ahmad.fauzi@racer.id). @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                        Tautan Meeting (Google Meet/Zoom) <span class="text-danger">*</span>
                                    </label>
                                    <input type="url" name="tautan_meeting" id="inputMeeting"
                                        class="form-control rounded-3 @error('tautan_meeting') is-invalid @enderror"
                                        placeholder="https://meet.google.com/rcr-fauzi" value="{{ old('tautan_meeting') }}"
                                        style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                    <div class="invalid-feedback" id="feedbackMeeting" style="font-size: 0.78rem;">
                                        @error('tautan_meeting') {{ $message }} @else Tautan meeting wajib diisi dengan format tautan valid (Google Meet / Zoom). @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: SPESIALISASI MODUL & KAPASITAS -->
                        <div class="mb-4">
                            <div class="form-section-title">2. SPESIALISASI MODUL & KAPASITAS</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                        Fokus Program Kursus <span class="text-danger">*</span>
                                    </label>
                                    <select name="jenjang_modul" id="selectJenjangModul"
                                        class="form-select rounded-3 @error('jenjang_modul') is-invalid @enderror"
                                        style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                        <option value="">-- Pilih Fokus Program --</option>
                                        <option value="Level 3: Controller (SMP/SMA)" {{ old('jenjang_modul', 'Level 3: Controller (SMP/SMA)') == 'Level 3: Controller (SMP/SMA)' ? 'selected' : '' }}>Controller & AI (Level 3)</option>
                                        <option value="Level 2: Sensor (SD/SMP)" {{ old('jenjang_modul') == 'Level 2: Sensor (SD/SMP)' ? 'selected' : '' }}>Sensor (Level 2)</option>
                                        <option value="Level 1: Building (TK/SD)" {{ old('jenjang_modul') == 'Level 1: Building (TK/SD)' ? 'selected' : '' }}>Building (Level 1)</option>
                                    </select>
                                    <div class="invalid-feedback" id="feedbackJenjangModul" style="font-size: 0.78rem;">
                                        @error('jenjang_modul') {{ $message }} @else Silakan pilih salah satu fokus program kursus. @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                        Penempatan Cabang / Lab Utama <span class="text-danger">*</span>
                                    </label>
                                    <select name="penempatan" id="selectPenempatan"
                                        class="form-select rounded-3 @error('penempatan') is-invalid @enderror"
                                        style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                        <option value="">-- Pilih Penempatan Cabang / Lab --</option>
                                        <option value="Lab B - Robotics Hub" {{ old('penempatan', 'Lab B - Robotics Hub') == 'Lab B - Robotics Hub' ? 'selected' : '' }}>Lab B - Robotics Hub</option>
                                        <option value="Lab A • Jakarta Pusat" {{ old('penempatan') == 'Lab A • Jakarta Pusat' ? 'selected' : '' }}>Lab A • Jakarta Pusat</option>
                                        <option value="Daring • Google Meet" {{ old('penempatan') == 'Daring • Google Meet' ? 'selected' : '' }}>Daring • Google Meet</option>
                                        <option value="Lab STEM 1" {{ old('penempatan') == 'Lab STEM 1' ? 'selected' : '' }}>Lab STEM 1</option>
                                    </select>
                                    <div class="invalid-feedback" id="feedbackPenempatan" style="font-size: 0.78rem;">
                                        @error('penempatan') {{ $message }} @else Silakan pilih penempatan cabang / lab utama. @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                        Batas Maksimum Kelas per Minggu
                                    </label>
                                    <input type="text" name="max_kelas_minggu" id="inputMaxKelas" class="form-control rounded-3"
                                        value="{{ old('max_kelas_minggu', '12 Sesi Kelas / Minggu') }}" style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                        Preferensi Mengajar
                                    </label>
                                    <input type="text" name="preferensi_mengajar" id="inputPreferensi" class="form-control rounded-3"
                                        value="{{ old('preferensi_mengajar', 'Akhir Pekan + Sore Hari') }}" style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: KREDENSIAL LOGIN AWAL -->
                        <div class="mb-2">
                            <div class="form-section-title">3. KREDENSIAL LOGIN AWAL</div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold" style="font-size: 0.82rem;">
                                    Kata Sandi Sementara (Default) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="kata_sandi_sementara" id="inputKataSandi"
                                    class="form-control rounded-3 @error('kata_sandi_sementara') is-invalid @enderror"
                                    value="{{ old('kata_sandi_sementara', 'RacerTutor2026!') }}"
                                    style="font-size: 0.88rem; padding: 0.6rem 0.85rem;">
                                <div class="invalid-feedback" id="feedbackKataSandi" style="font-size: 0.78rem;">
                                    @error('kata_sandi_sementara') {{ $message }} @else Kata sandi sementara wajib diisi minimal 8 karakter. @enderror
                                </div>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="kirim_wa" id="checkWa" checked>
                                <label class="form-check-label text-secondary" for="checkWa" style="font-size: 0.82rem;">
                                    Kirim tautan login dan kredensial sementara via WhatsApp otomatis
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wajib_ubah_password" id="checkUbahPass" checked>
                                <label class="form-check-label text-secondary" for="checkUbahPass" style="font-size: 0.82rem;">
                                    Wajibkan tutor mengubah kata sandi saat pertama kali login
                                </label>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Modal Action Buttons -->
                    <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border bg-white px-4 py-2 rounded-3 text-secondary fw-semibold" data-bs-dismiss="modal" onclick="tutupModalTambahTutor()" style="font-size: 0.88rem;">
                            Batal
                        </button>
                        <button type="submit" id="btnSimpanTutor" class="btn btn-primary-blue px-4 py-2 rounded-3 fw-semibold shadow-sm" style="font-size: 0.88rem;">
                            Simpan & Buat Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL DETAIL TUTOR UNTUK SETIAP TUTOR ================= -->
    @foreach ($tutorList as $tutor)
        <div class="modal fade" id="modalDetailTutor{{ $tutor->id_tutor ?? $tutor->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fw-bold text-dark">Detail Akun Tutor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutupModalDetail('{{ $tutor->id_tutor ?? $tutor->id }}')"></button>
                    </div>
                    <div class="modal-body px-4 py-3">
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                            <div class="brand-logo" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $tutor->nama_gelar ?: ($tutor->nama_lengkap ?: ($tutor->nama ?? 'Instruktur Tutor')) }}</h6>
                                <small class="text-muted">{{ $tutor->penempatan ?: 'Lab A • Jakarta Pusat' }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-primary-subtle text-primary">{{ $tutor->kode_tutor ?: ($tutor->id_tutor ? 'TTR-' . str_pad($tutor->id_tutor, 3, '0', STR_PAD_LEFT) : ('TTR-' . str_pad($tutor->id ?? 1, 3, '0', STR_PAD_LEFT))) }}</span>
                                    <span class="badge bg-success-subtle text-success">{{ $tutor->status ?: 'Aktif' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3" style="font-size: 0.85rem;">
                            <div class="col-5 text-muted">Nama Panggilan:</div>
                            <div class="col-7 fw-semibold text-dark">{{ $tutor->nama_panggilan ?: 'Kak Tutor' }}</div>

                            <div class="col-5 text-muted">Nomor WhatsApp:</div>
                            <div class="col-7 fw-semibold text-dark">{{ $tutor->whatsapp ?: ($tutor->nomor_whatsapp ?: '-') }}</div>

                            <div class="col-5 text-muted">Email / Username:</div>
                            <div class="col-7 fw-semibold text-dark">{{ $tutor->email ?: 'tutor@racer.id' }} ({{ $tutor->username ?: '@tutor' }})</div>

                            <div class="col-5 text-muted">Jenjang Modul:</div>
                            <div class="col-7 fw-semibold text-dark">{{ $tutor->jenjang_modul ?: 'Level 1: Building (TK/SD)' }}</div>

                            <div class="col-5 text-muted">Maks. Kelas / Minggu:</div>
                            <div class="col-7 fw-semibold text-dark">{{ $tutor->max_kelas_minggu ?? '12 Sesi Kelas / Minggu' }}</div>

                            <div class="col-5 text-muted">Preferensi Mengajar:</div>
                            <div class="col-7 fw-semibold text-dark">{{ $tutor->preferensi_mengajar ?? 'Akhir Pekan + Sore Hari' }}</div>

                            <div class="col-5 text-muted">Tautan Meeting:</div>
                            <div class="col-7">
                                <a href="{{ $tutor->tautan_meeting ?: 'https://meet.google.com/rcr-class' }}" target="_blank" class="text-primary text-truncate d-block">
                                    {{ $tutor->tautan_meeting ?: 'https://meet.google.com/rcr-class' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-light border bg-white px-4 py-2 rounded-3 text-secondary fw-semibold w-100" data-bs-dismiss="modal" onclick="tutupModalDetail('{{ $tutor->id_tutor ?? $tutor->id }}')">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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

    <!-- Bootstrap 5 Bundle JS -->
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

        // ================= KONTROL PEMBUKA/PENUTUP MODAL (FAILSAFE) =================
        window.bukaModalTambahTutor = function () {
            const modalEl = document.getElementById('modalTambahTutor');
            if (!modalEl) return;

            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                try {
                    const inst = bootstrap.Modal.getOrCreateInstance(modalEl);
                    inst.show();
                    return;
                } catch (e) {}
            }

            // Fallback Vanilla JS jika Bootstrap CDN belum termuat
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.removeAttribute('aria-hidden');
            modalEl.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');

            let backdrop = document.getElementById('customBackdropTutor');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.id = 'customBackdropTutor';
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
                backdrop.addEventListener('click', window.tutupModalTambahTutor);
            }
        };

        window.tutupModalTambahTutor = function () {
            const modalEl = document.getElementById('modalTambahTutor');
            if (!modalEl) return;

            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                try {
                    const inst = bootstrap.Modal.getInstance(modalEl);
                    if (inst) inst.hide();
                } catch (e) {}
            }

            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            modalEl.setAttribute('aria-hidden', 'true');
            modalEl.removeAttribute('aria-modal');
            document.body.classList.remove('modal-open');

            const backdrop = document.getElementById('customBackdropTutor');
            if (backdrop) backdrop.remove();
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        };

        window.bukaModalDetail = function (id) {
            const modalEl = document.getElementById('modalDetailTutor' + id);
            if (!modalEl) return;

            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                try {
                    const inst = bootstrap.Modal.getOrCreateInstance(modalEl);
                    inst.show();
                    return;
                } catch (e) {}
            }

            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.removeAttribute('aria-hidden');
            modalEl.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');

            let backdrop = document.getElementById('customBackdropDetail');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.id = 'customBackdropDetail';
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
                backdrop.addEventListener('click', () => window.tutupModalDetail(id));
            }
        };

        window.tutupModalDetail = function (id) {
            const modalEl = document.getElementById('modalDetailTutor' + id);
            if (modalEl) {
                if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                    try {
                        const inst = bootstrap.Modal.getInstance(modalEl);
                        if (inst) inst.hide();
                    } catch (e) {}
                }
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                modalEl.removeAttribute('aria-modal');
            }

            document.body.classList.remove('modal-open');
            const backdrop = document.getElementById('customBackdropDetail');
            if (backdrop) backdrop.remove();
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        };

        // ESC Key listener untuk menutup modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                window.tutupModalTambahTutor();
                document.querySelectorAll('.modal.show').forEach(m => {
                    m.classList.remove('show');
                    m.style.display = 'none';
                });
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            }
        });

        // ================= VALIDASI FORMULIR PENDAFTARAN AKUN TUTOR =================
        const formTambahTutor = document.getElementById('formTambahTutor');
        const modalAlertError = document.getElementById('modalAlertError');
        const modalAlertErrorText = document.getElementById('modalAlertErrorText');

        const inputNamaGelar = document.getElementById('inputNamaGelar');
        const inputWhatsapp = document.getElementById('inputWhatsapp');
        const inputEmail = document.getElementById('inputEmail');
        const inputMeeting = document.getElementById('inputMeeting');
        const selectJenjangModul = document.getElementById('selectJenjangModul');
        const selectPenempatan = document.getElementById('selectPenempatan');
        const inputKataSandi = document.getElementById('inputKataSandi');

        function setFieldStatus(inputEl, feedbackEl, isValid, errorMsg) {
            if (!isValid) {
                inputEl.classList.add('is-invalid');
                inputEl.classList.remove('is-valid');
                if (feedbackEl && errorMsg) {
                    feedbackEl.textContent = errorMsg;
                }
            } else {
                inputEl.classList.remove('is-invalid');
                inputEl.classList.add('is-valid');
            }
            return isValid;
        }

        function validateNamaGelar() {
            if (!inputNamaGelar) return true;
            const val = inputNamaGelar.value.trim();
            const feedback = document.getElementById('feedbackNamaGelar');
            if (val === '') {
                return setFieldStatus(inputNamaGelar, feedback, false, 'Nama lengkap dan gelar wajib diisi.');
            }
            if (val.length < 3) {
                return setFieldStatus(inputNamaGelar, feedback, false, 'Nama lengkap dan gelar minimal 3 karakter.');
            }
            return setFieldStatus(inputNamaGelar, feedback, true);
        }

        function validateWhatsapp() {
            if (!inputWhatsapp) return true;
            const val = inputWhatsapp.value.trim();
            const feedback = document.getElementById('feedbackWhatsapp');
            if (val === '') {
                return setFieldStatus(inputWhatsapp, feedback, false, 'Nomor WhatsApp aktif wajib diisi.');
            }
            const cleanDigits = val.replace(/[^0-9]/g, '');
            if (cleanDigits.length < 10) {
                return setFieldStatus(inputWhatsapp, feedback, false, 'Nomor WhatsApp minimal 10 digit angka (contoh: +62 813-4567-8901).');
            }
            return setFieldStatus(inputWhatsapp, feedback, true);
        }

        function validateEmail() {
            if (!inputEmail) return true;
            const val = inputEmail.value.trim();
            const feedback = document.getElementById('feedbackEmail');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (val === '') {
                return setFieldStatus(inputEmail, feedback, false, 'Email akun (username login) wajib diisi.');
            }
            if (!emailRegex.test(val)) {
                return setFieldStatus(inputEmail, feedback, false, 'Format email akun tidak valid (contoh: ahmad.fauzi@racer.id).');
            }
            return setFieldStatus(inputEmail, feedback, true);
        }

        function validateMeeting() {
            if (!inputMeeting) return true;
            const val = inputMeeting.value.trim();
            const feedback = document.getElementById('feedbackMeeting');
            if (val === '') {
                return setFieldStatus(inputMeeting, feedback, false, 'Tautan meeting (Google Meet/Zoom) wajib diisi.');
            }
            try {
                const url = new URL(val);
                if (url.protocol !== 'http:' && url.protocol !== 'https:') {
                    return setFieldStatus(inputMeeting, feedback, false, 'Tautan meeting harus diawali http:// atau https://');
                }
            } catch (_) {
                return setFieldStatus(inputMeeting, feedback, false, 'Format tautan meeting tidak valid (contoh: https://meet.google.com/rcr-fauzi).');
            }
            return setFieldStatus(inputMeeting, feedback, true);
        }

        function validateJenjang() {
            if (!selectJenjangModul) return true;
            const val = selectJenjangModul.value.trim();
            const feedback = document.getElementById('feedbackJenjangModul');
            if (val === '') {
                return setFieldStatus(selectJenjangModul, feedback, false, 'Silakan pilih fokus program kursus.');
            }
            return setFieldStatus(selectJenjangModul, feedback, true);
        }

        function validatePenempatan() {
            if (!selectPenempatan) return true;
            const val = selectPenempatan.value.trim();
            const feedback = document.getElementById('feedbackPenempatan');
            if (val === '') {
                return setFieldStatus(selectPenempatan, feedback, false, 'Silakan pilih penempatan cabang / lab utama.');
            }
            return setFieldStatus(selectPenempatan, feedback, true);
        }

        function validateKataSandi() {
            if (!inputKataSandi) return true;
            const val = inputKataSandi.value;
            const feedback = document.getElementById('feedbackKataSandi');
            if (val === '') {
                return setFieldStatus(inputKataSandi, feedback, false, 'Kata sandi sementara wajib diisi.');
            }
            if (val.length < 8) {
                return setFieldStatus(inputKataSandi, feedback, false, 'Kata sandi sementara wajib diisi minimal 8 karakter.');
            }
            return setFieldStatus(inputKataSandi, feedback, true);
        }

        // Live validation saat user mengetik / mengubah pilihan
        if (inputNamaGelar) inputNamaGelar.addEventListener('input', validateNamaGelar);
        if (inputWhatsapp) inputWhatsapp.addEventListener('input', validateWhatsapp);
        if (inputEmail) inputEmail.addEventListener('input', validateEmail);
        if (inputMeeting) inputMeeting.addEventListener('input', validateMeeting);
        if (selectJenjangModul) selectJenjangModul.addEventListener('change', validateJenjang);
        if (selectPenempatan) selectPenempatan.addEventListener('change', validatePenempatan);
        if (inputKataSandi) inputKataSandi.addEventListener('input', validateKataSandi);

        // Validasi saat form disubmit
        if (formTambahTutor) {
            formTambahTutor.addEventListener('submit', function (e) {
                const isNamaValid = validateNamaGelar();
                const isWaValid = validateWhatsapp();
                const isEmailValid = validateEmail();
                const isMeetingValid = validateMeeting();
                const isJenjangValid = validateJenjang();
                const isPenempatanValid = validatePenempatan();
                const isKataSandiValid = validateKataSandi();

                const allValid = isNamaValid && isWaValid && isEmailValid && isMeetingValid && isJenjangValid && isPenempatanValid && isKataSandiValid;

                if (!allValid) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (modalAlertError) {
                        modalAlertError.classList.remove('d-none');
                        modalAlertError.classList.add('d-flex');
                        if (modalAlertErrorText) {
                            modalAlertErrorText.textContent = 'Silakan lengkapi atau perbaiki kolom yang bertanda merah di bawah.';
                        }
                    }

                    const firstInvalid = formTambahTutor.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                } else {
                    if (modalAlertError) {
                        modalAlertError.classList.add('d-none');
                        modalAlertError.classList.remove('d-flex');
                    }
                }
            });
        }

        // Reset class validasi saat modal ditutup
        const modalTambahTutorEl = document.getElementById('modalTambahTutor');
        if (modalTambahTutorEl) {
            modalTambahTutorEl.addEventListener('hidden.bs.modal', function () {
                const inputs = formTambahTutor.querySelectorAll('.is-invalid, .is-valid');
                inputs.forEach(el => el.classList.remove('is-invalid', 'is-valid'));
                if (modalAlertError) {
                    modalAlertError.classList.add('d-none');
                    modalAlertError.classList.remove('d-flex');
                }
            });
        }

        // Buka otomatis modal jika terdapat error validasi dari server (Laravel)
        @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('modalTambahTutor');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
        @endif
    </script>
</body>

</html>


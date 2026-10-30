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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
            color: #94a3b8;
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
                        <a href="#modalAkunKeluarga" data-bs-toggle="modal" class="nav-link-custom">
                            <i class="bi bi-people"></i>
                            <span>Akun Keluarga & Siswa</span>
                        </a>
                        <a href="#modalBuatJadwal" data-bs-toggle="modal" class="nav-link-custom">
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
                                style="font-size: 0.76rem;">Hubungi tim support <i
                                    class="bi bi-arrow-up-right"></i></a>
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
                    <form method="POST" action="{{ route('logout') }}" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted p-1 text-decoration-none" title="Logout">
                            <i class="bi bi-box-arrow-right fs-5"></i>
                        </button>
                    </form>
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
                    <form action="{{ route('admin') }}" method="GET" class="search-container">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="q" class="search-input w-100"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari nama siswa, nama wali, atau tutor...">
                        <span class="search-shortcut">⌘ K</span>
                    </form>
                </div>

                <!-- Right items: Semester, Notification, Profile -->
                <div class="d-flex align-items-center gap-2">
                    <div class="badge-soft-primary px-3 py-2 d-flex align-items-center gap-2"
                        style="font-size: 0.82rem;">
                        <i class="bi bi-calendar3"></i>
                        <span>T.A 2026/2027</span>
                    </div>

                    <button class="btn btn-white border bg-white rounded-3 px-2 py-2 position-relative text-muted">
                        <i class="bi bi-bell"></i>
                        <span
                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>

                    <div class="d-flex align-items-center gap-2 bg-white border rounded-3 px-2 py-1 ms-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=fee2e2&color=dc2626&bold=true"
                            alt="Avatar" class="rounded-circle" width="28" height="28">
                        <i class="bi bi-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                    </div>
                </div>
            </header>

            <!-- Dashboard Body -->
            <div class="p-4 pt-2">

                <!-- Flash Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
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
                            {{ Auth::user()->name ?? 'Administrator' }}!</h3>
                        <p class="text-muted mb-0" style="font-size: 0.88rem;">Pantau operasional kelas dan paket siswa
                            hari ini.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Tombol Buka Modal Akun Keluarga -->
                        <button class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3"
                            data-bs-toggle="modal" data-bs-target="#modalAkunKeluarga"
                            style="background-color: var(--primary-blue); font-size: 0.88rem;">
                            <i class="bi bi-plus-lg"></i> Akun Keluarga
                        </button>
                        <!-- Tombol Buka Modal Buat Jadwal -->
                        <button
                            class="btn btn-white bg-white border d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3 text-dark"
                            data-bs-toggle="modal" data-bs-target="#modalBuatJadwal"
                            style="font-size: 0.88rem;">
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
                            <small class="text-muted" style="font-size: 0.76rem;">{{ $kelasSelesai }} selesai · {{ $kelasBerlangsung }} berlangsung · {{ $kelasBerikutnya }}
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
                            <small class="text-muted" style="font-size: 0.76rem;">{{ $tutorMengajarCount }} tutor mengajar hari ini</small>
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
                            <small class="text-warning fw-semibold" style="font-size: 0.76rem; color: #b45309 !important;">
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
                                    <span class="badge-soft-primary px-2 py-1" style="font-size: 0.75rem;">{{ $kelasHariIniCount }} kelas</span>
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
                                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $j->waktu_selesai }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark">{{ $j->kelas }}</div>
                                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $j->lokasi }}</small>
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

                                                    <!-- Dropdown Ubah Status -->
                                                    <div class="dropdown d-inline">
                                                        <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                            <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 0.8rem;">
                                                            <li><h6 class="dropdown-header">Ubah Status</h6></li>
                                                            <li>
                                                                <form action="{{ route('admin.jadwal.status', $j->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Berlangsung">
                                                                    <button type="submit" class="dropdown-item">Mulai (Berlangsung)</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.jadwal.status', $j->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Selesai">
                                                                    <button type="submit" class="dropdown-item">Tandai Selesai</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.jadwal.status', $j->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Terjadwal">
                                                                    <button type="submit" class="dropdown-item">Reset ke Terjadwal</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Belum ada jadwal kelas hari ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center pt-3 border-top mt-2">
                                <a href="#modalBuatJadwal" data-bs-toggle="modal" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.85rem;">
                                    + Tambah Jadwal Kelas Baru <i class="bi bi-arrow-right"></i>
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
                                <a href="#modalAkunKeluarga" data-bs-toggle="modal" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.85rem;">
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
                                                    <div class="fw-semibold text-dark">{{ $p->nama }}</div>
                                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $p->nama_wali }}</small>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $p->paket_belajar }}</span>
                                                </td>
                                                <td style="min-width: 140px;">
                                                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.75rem;">
                                                        <span class="text-muted">{{ $p->sesi_terpakai }} dari {{ $p->total_sesi }} sesi</span>
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
                                                        <span class="badge-soft-warning me-2">Sisa {{ $p->sisa_sesi }} sesi</span>
                                                    @else
                                                        <span class="badge-soft-success me-2">Aktif</span>
                                                    @endif
                                                    <i class="bi bi-three-dots text-muted"></i>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Tidak ada data paket siswa.</td>
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
                                <div class="stat-value" style="font-size: 1.85rem;">{{ $presensi['hadir'] }} / {{ $presensi['total'] }} <span class="stat-unit">siswa</span></div>
                                <span class="badge-soft-success">{{ $presensi['persentase'] }}% hadir</span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="progress progress-slim mb-3" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: {{ $presensi['persentase'] }}%;"></div>
                            </div>

                            <div class="d-flex justify-content-between text-muted mb-3" style="font-size: 0.74rem;">
                                <span><i class="bi bi-circle-fill text-primary" style="font-size: 0.5rem;"></i> {{ $presensi['hadir'] }} hadir</span>
                                <span><i class="bi bi-circle-fill text-danger" style="font-size: 0.5rem;"></i> {{ $presensi['absen'] }} absen</span>
                                <span><i class="bi bi-circle-fill text-secondary" style="font-size: 0.5rem;"></i> {{ $presensi['menunggu'] }} menunggu</span>
                            </div>

                            <!-- Alert Box -->
                            <div class="alert alert-primary py-2 px-3 d-flex align-items-center gap-2 mb-4" style="background-color: #ebf3ff; border: none; border-radius: 10px; font-size: 0.8rem; color: #1d4ed8;">
                                <i class="bi bi-info-circle"></i>
                                <span>Notifikasi: Presensi kelas otomatis terupdate</span>
                            </div>

                            <!-- Sub-section: Menunggu Persetujuan -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-dark" style="font-size: 0.86rem;">Menunggu persetujuan</span>
                                <span class="badge-soft-warning px-2 py-1" style="font-size: 0.72rem;">{{ $rescheduleList->count() }} reschedule</span>
                            </div>

                            <!-- List Item Reschedule -->
                            @forelse ($rescheduleList as $res)
                                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">{{ $res->inisial ?? 'RS' }}</div>
                                        <div style="line-height: 1.2;">
                                            <div class="fw-semibold text-dark" style="font-size: 0.84rem;">{{ $res->nama_siswa }}</div>
                                            <small class="text-muted" style="font-size: 0.72rem;">{{ $res->jadwal_lama }} &rarr; {{ $res->jadwal_baru }} · {{ $res->kelas }}</small>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.reschedule.approve', $res->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.72rem;" title="Setujui">
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
                                <a href="#" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.82rem;">
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
                                            <span class="badge bg-secondary px-2 py-1" style="font-size: 0.68rem;">{{ $item->kategori }}</span>
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
    <div class="modal fade" id="modalBuatJadwal" tabindex="-1" aria-labelledby="modalBuatJadwalLabel" aria-hidden="true">
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
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Kelas</label>
                            <input type="text" name="kelas" class="form-control rounded-3" placeholder="Contoh: Junior Robotics" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Lokasi / Ruangan</label>
                            <input type="text" name="lokasi" class="form-control rounded-3" placeholder="Contoh: Lab Robotika 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Tutor</label>
                            <input type="text" name="tutor" class="form-control rounded-3" placeholder="Contoh: Kak Dimas" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Waktu Mulai</label>
                                <input type="text" name="waktu_mulai" class="form-control rounded-3" placeholder="09.00" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Waktu Selesai</label>
                                <input type="text" name="waktu_selesai" class="form-control rounded-3" placeholder="10.00 WIB" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Status</label>
                            <select name="status" class="form-select rounded-3">
                                <option value="Terjadwal">Terjadwal</option>
                                <option value="Berlangsung">Berlangsung</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4" style="background-color: var(--primary-blue);">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL: AKUN KELUARGA & SISWA ================= -->
    <div class="modal fade" id="modalAkunKeluarga" tabindex="-1" aria-labelledby="modalAkunKeluargaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="modalAkunKeluargaLabel">Tambah Akun Keluarga & Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.keluarga.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Siswa</label>
                            <input type="text" name="nama" class="form-control rounded-3" placeholder="Nama lengkap siswa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Wali</label>
                            <input type="text" name="nama_wali" class="form-control rounded-3" placeholder="Contoh: Wali: Ibu Ratna" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Paket Belajar</label>
                            <input type="text" name="paket_belajar" class="form-control rounded-3" placeholder="Contoh: Gold · 8 sesi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Total Sesi</label>
                            <input type="number" name="total_sesi" class="form-control rounded-3" value="8" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4" style="background-color: var(--primary-blue);">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        // Toggle mobile sidebar
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    </script>
</body>

</html>

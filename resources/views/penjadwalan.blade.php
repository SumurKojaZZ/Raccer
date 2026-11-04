<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjadwalan Terpusat - RACER ROBOTIC</title>
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
            background: var(--primary-blue);
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
            background: #eff6ff;
        }

        .nav-link-custom.active {
            color: var(--primary-blue);
            background: #eff6ff;
            font-weight: 600;
        }

        .nav-link-custom i {
            font-size: 1.15rem;
        }

        /* Navbar & Search */
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1010;
        }

        .search-container {
            position: relative;
            max-width: 380px;
        }

        .search-input {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.55rem 2.4rem 0.55rem 2.4rem;
            font-size: 0.88rem;
            color: #334155;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            background: #ffffff;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(29, 104, 240, 0.12);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 1.25rem 1.4rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            height: 100%;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
        }

        .badge-soft-primary {
            background: #eff6ff;
            color: #1d68f0;
            border-radius: 8px;
            font-weight: 600;
        }

        .badge-soft-success {
            background: #ecfdf5;
            color: #10b981;
            border-radius: 8px;
            font-weight: 600;
        }

        .badge-soft-warning {
            background: #fffbeb;
            color: #f59e0b;
            border-radius: 8px;
            font-weight: 600;
        }

        .badge-soft-info {
            background: #f0fdfa;
            color: #0d9488;
            border-radius: 8px;
            font-weight: 600;
        }

        .table-custom {
            margin-bottom: 0;
        }

        .table-custom th {
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748b;
            font-weight: 700;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1rem;
            background: #f8fafc;
        }

        .table-custom td {
            font-size: 0.88rem;
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .support-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar">
            <div class="d-flex flex-column h-100 justify-content-between">
                <div>
                    <!-- Brand -->
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
                            <a href="{{ route('admin.tutor') }}" class="nav-link-custom">
                                <i class="bi bi-person-badge"></i>
                                <span>Data Tutor</span>
                            </a>
                            <a href="{{ route('admin.penjadwalan') }}" class="nav-link-custom active">
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

                <!-- Bottom Profile -->
                <div>
                    <div class="support-card mb-3">
                        <div class="d-flex gap-2">
                            <div class="text-primary mt-1"><i class="bi bi-question-circle-fill fs-5"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.82rem;">Butuh bantuan operasional?</h6>
                                <small class="text-muted" style="font-size: 0.72rem;">Tim support siap membantu Anda.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=1d68f0&color=ffffff&bold=true"
                                class="rounded-circle" width="34" height="34" alt="Admin">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.82rem;">{{ Auth::user()->name ?? 'Admin' }}</h6>
                                <small class="text-muted" style="font-size: 0.72rem;">{{ Auth::user()->email ?? 'admin@raccer.com' }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-light border text-danger p-1 px-2" title="Keluar" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiLogout">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT ================= -->
        <main class="main-wrapper flex-grow-1">
            <!-- Navbar -->
            <header class="top-navbar d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2 flex-grow-1">
                    <button class="btn btn-light d-lg-none border" id="toggleSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <form action="{{ route('admin.penjadwalan') }}" method="GET" class="search-container w-100">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="q" class="search-input w-100"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari program, tutor, atau lokasi kelas...">
                        @if(!empty($status)) <input type="hidden" name="status" value="{{ $status }}"> @endif
                        @if(!empty($moda)) <input type="hidden" name="moda" value="{{ $moda }}"> @endif
                        @if(!empty($tanggal)) <input type="hidden" name="tanggal" value="{{ $tanggal }}"> @endif
                    </form>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3 shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#modalBuatJadwal"
                        style="background-color: var(--primary-blue); font-size: 0.88rem;">
                        <i class="bi bi-plus-lg"></i> Buat Jadwal
                    </button>
                </div>
            </header>

            <!-- Body -->
            <div class="p-4 pt-3">
                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center gap-2" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3" role="alert">
                        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan input:</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Page Header Title -->
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1" style="font-size: 0.82rem;">
                                <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none">Beranda</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Penjadwalan Terpusat</li>
                            </ol>
                        </nav>
                        <h4 class="fw-bold mb-1 text-dark">Penjadwalan Terpusat</h4>
                        <p class="text-muted mb-0" style="font-size: 0.88rem;">
                            Kelola seluruh jadwal program robotika, tutor pengampu aktif, jam sesi, dan moda belajar dalam satu sistem terpadu.
                        </p>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <span class="text-muted fw-medium d-block mb-1" style="font-size: 0.85rem;">Total Jadwal Terdaftar</span>
                            <div class="stat-value">{{ $totalJadwal }} <span class="fs-6 fw-normal text-muted">sesi</span></div>
                            <small class="text-muted" style="font-size: 0.76rem;">Keseluruhan program</small>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <span class="text-muted fw-medium d-block mb-1" style="font-size: 0.85rem;">Jadwal Mendatang</span>
                            <div class="stat-value text-primary">{{ $totalTerjadwal }} <span class="fs-6 fw-normal text-muted">sesi</span></div>
                            <small class="text-muted" style="font-size: 0.76rem;">Status siap berlangsung</small>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <span class="text-muted fw-medium d-block mb-1" style="font-size: 0.85rem;">Sedang Berlangsung</span>
                            <div class="stat-value text-warning">{{ $totalBerlangsung }} <span class="fs-6 fw-normal text-muted">sesi</span></div>
                            <small class="text-muted" style="font-size: 0.76rem;">Di ruang lab / daring</small>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <span class="text-muted fw-medium d-block mb-1" style="font-size: 0.85rem;">Selesai Terlaksana</span>
                            <div class="stat-value text-success">{{ $totalSelesai }} <span class="fs-6 fw-normal text-muted">sesi</span></div>
                            <small class="text-muted" style="font-size: 0.76rem;">Telah dirampungkan</small>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-3">
                        <form action="{{ route('admin.penjadwalan') }}" method="GET" class="row g-2 align-items-center">
                            @if(!empty($search)) <input type="hidden" name="q" value="{{ $search }}"> @endif

                            <div class="col-12 col-md-3">
                                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.75rem;">FILTER STATUS</label>
                                <select name="status" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                                    <option value="Semua Status" {{ ($status ?? '') === 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="Terjadwal" {{ ($status ?? '') === 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                                    <option value="Berlangsung" {{ ($status ?? '') === 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                    <option value="Selesai" {{ ($status ?? '') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.75rem;">MODA KELAS</label>
                                <select name="moda" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                                    <option value="Semua Moda" {{ ($moda ?? '') === 'Semua Moda' ? 'selected' : '' }}>Semua Moda</option>
                                    <option value="Offline" {{ ($moda ?? '') === 'Offline' ? 'selected' : '' }}>Offline (Tatap Muka)</option>
                                    <option value="Online" {{ ($moda ?? '') === 'Online' ? 'selected' : '' }}>Online (Daring)</option>
                                    <option value="Hybrid" {{ ($moda ?? '') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.75rem;">TANGGAL</label>
                                <input type="date" name="tanggal" class="form-control form-control-sm rounded-3"
                                    value="{{ $tanggal ?? '' }}" onchange="this.form.submit()">
                            </div>

                            <div class="col-12 col-md-2 d-flex align-items-end pt-3 pt-md-0">
                                <a href="{{ route('admin.penjadwalan') }}" class="btn btn-sm btn-light border w-100 rounded-3 text-muted">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Schedule Table Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold mb-0 text-dark">Daftar Jadwal Kelas Terpusat</h5>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.78rem;">
                                {{ count($jadwalList) }} jadwal
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Program / Kelas</th>
                                    <th>Tutor Pengampu</th>
                                    <th>Tanggal</th>
                                    <th>Jam Sesi</th>
                                    <th>Moda & Lokasi</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalList as $jdw)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $jdw->program ?? $jdw->kelas }}</div>
                                            <small class="text-muted" style="font-size: 0.76rem;">ID: #{{ $jdw->id }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark" style="font-size: 0.85rem;">
                                                        {{ $jdw->tutor_pengampu ?? $jdw->tutor }}
                                                    </div>
                                                    <span class="badge bg-success-subtle text-success" style="font-size: 0.68rem;">Tutor Aktif</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">
                                                {{ $jdw->formatted_tanggal }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">
                                                {{ $jdw->jam_mulai ?? $jdw->waktu_mulai }} - {{ $jdw->jam_selesai ?? $jdw->waktu_selesai }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $modaLower = strtolower($jdw->moda ?? 'offline');
                                            @endphp
                                            @if($modaLower === 'online')
                                                <span class="badge-soft-primary px-2 py-1 d-inline-flex align-items-center gap-1 mb-1" style="font-size: 0.76rem;">
                                                    <i class="bi bi-camera-video"></i> Online
                                                </span>
                                            @elseif($modaLower === 'hybrid')
                                                <span class="badge-soft-warning px-2 py-1 d-inline-flex align-items-center gap-1 mb-1" style="font-size: 0.76rem;">
                                                    <i class="bi bi-layers"></i> Hybrid
                                                </span>
                                            @else
                                                <span class="badge-soft-success px-2 py-1 d-inline-flex align-items-center gap-1 mb-1" style="font-size: 0.76rem;">
                                                    <i class="bi bi-geo-alt"></i> Offline
                                                </span>
                                            @endif
                                            <div class="text-muted" style="font-size: 0.78rem;">{{ $jdw->lokasi ?? 'Lab Robotika' }}</div>
                                        </td>
                                        <td>
                                            @if($jdw->status === 'Selesai')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                                </span>
                                            @elseif($jdw->status === 'Berlangsung')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                                    <i class="bi bi-clock-history me-1"></i> Berlangsung
                                                </span>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                                    <i class="bi bi-calendar-event me-1"></i> Terjadwal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($jdw->status !== 'Selesai')
                                                <form action="{{ route('admin.jadwal.status', $jdw->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Selesai">
                                                    <button type="submit" class="btn btn-sm btn-light border text-success rounded-3 px-2 py-1" title="Tandai Selesai" style="font-size: 0.78rem;">
                                                        <i class="bi bi-check-lg"></i> Selesai
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted" style="font-size: 0.78rem;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                                            <div class="fw-semibold">Tidak ada jadwal kelas yang ditemukan.</div>
                                            <small>Silakan ubah filter pencarian atau klik tombol "Buat Jadwal" untuk menambahkan jadwal baru.</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Program Kelas</label>
                            <input type="text" name="program" class="form-control rounded-3" placeholder="Contoh: Junior Robotics" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Lokasi / Ruangan</label>
                            <input type="text" name="lokasi" class="form-control rounded-3" placeholder="Contoh: Lab Robotika 1, Lab Maker, atau Tautan Google Meet" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">Tutor Pengampu <span class="badge bg-success-subtle text-success fw-normal ms-1" style="font-size: 0.72rem;">Aktif</span></label>
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
                            <input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control rounded-3" value="09:00" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control rounded-3" value="10:30" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Moda Pembelajaran</label>
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
                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4" style="background-color: var(--primary-blue);">Simpan Jadwal</button>
                    </div>
                </form>
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

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    </script>
</body>

</html>


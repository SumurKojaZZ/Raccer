<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Siswa - Racer Robotic Kids</title>

    <!-- Google Font: Poppins & Fredoka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-kid: #4f46e5;
            --primary-kid-hover: #4338ca;
            --secondary-kid: #06b6d4;
            --accent-kid: #f59e0b;
            --success-kid: #10b981;
            --danger-kid: #ef4444;
            --card-border-radius: 24px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #eef2ff 50%, #fdf4ff 100%);
            min-height: 100vh;
            color: #1e293b;
            padding-bottom: 3rem;
        }

        .heading-kid {
            font-family: 'Fredoka', 'Poppins', sans-serif;
        }

        /* Top Navbar */
        .kid-navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 2px solid #e0e7ff;
            padding: 0.85rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand-logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.4rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .brand-title {
            font-family: 'Fredoka', cursive, sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e1b4b;
            letter-spacing: -0.3px;
            margin: 0;
            line-height: 1.1;
        }

        .brand-badge {
            font-size: 0.72rem;
            background: #fef3c7;
            color: #b45309;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 50rem;
        }

        /* Tombol Keluar (Logout) Ramah Anak & Tegas */
        .btn-keluar-siswa {
            background: #fff1f2;
            color: #e11d48;
            border: 2px solid #fecdd3;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.55rem 1.25rem;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(225, 29, 72, 0.08);
            cursor: pointer;
        }

        .btn-keluar-siswa:hover {
            background: #e11d48;
            color: #ffffff;
            border-color: #e11d48;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(225, 29, 72, 0.25);
        }

        /* Hero Welcome Card */
        .hero-student-card {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #8b5cf6 100%);
            border-radius: var(--card-border-radius);
            padding: 2.25rem 2rem;
            color: #ffffff;
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.22);
            position: relative;
            overflow: hidden;
            margin-top: 1.5rem;
        }

        .hero-student-card::after {
            content: '';
            position: absolute;
            right: -20px;
            bottom: -30px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .avatar-robot-box {
            width: 90px;
            height: 90px;
            border-radius: 28px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border: 4px solid #ede9fe;
            animation: bounceSoft 3s ease-in-out infinite;
        }

        @keyframes bounceSoft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* Feature Cards */
        .kid-feature-card {
            background: #ffffff;
            border-radius: var(--card-border-radius);
            padding: 1.6rem;
            border: 2px solid #f1f5f9;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .kid-feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(79, 70, 229, 0.08);
        }

        .icon-circle-badge {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }

        /* Progress Bar Sesi Belajar */
        .sesi-progress-track {
            height: 16px;
            border-radius: 50rem;
            background: #e2e8f0;
            overflow: hidden;
            position: relative;
        }

        .sesi-progress-bar {
            height: 100%;
            border-radius: 50rem;
            background: linear-gradient(90deg, #10b981, #06b6d4);
            transition: width 0.6s ease;
        }

        /* Jadwal Card */
        .jadwal-item-card {
            background: #f8fafc;
            border-left: 5px solid #4f46e5;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
        }

        /* Mission Badge */
        .mission-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1rem;
            border-radius: 16px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            margin-bottom: 0.6rem;
        }

        /* Mobile Adjustments (390px) */
        @media (max-width: 576px) {
            .hero-student-card {
                padding: 1.6rem 1.25rem;
            }

            .avatar-robot-box {
                width: 72px;
                height: 72px;
                font-size: 2.3rem;
            }

            .brand-title {
                font-size: 1.15rem;
            }

            .btn-keluar-siswa {
                padding: 0.45rem 0.95rem;
                font-size: 0.88rem;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navbar Siswa -->
    <nav class="kid-navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a href="{{ route('siswa.beranda') }}" class="brand-logo-area">
                <div class="brand-icon-box">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    <h1 class="brand-title">RACER KIDS</h1>
                    <span class="brand-badge"><i class="bi bi-stars me-1"></i>Portal Siswa</span>
                </div>
            </a>

            <!-- Tombol Keluar (Logout) Ramah Anak -->
            <form method="POST" action="{{ route('logout') }}" id="formLogoutSiswa" class="m-0">
                @csrf
                <button type="submit" class="btn-keluar-siswa" title="Keluar dari akun siswa">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container py-3">

        <!-- Flash Alert Sukses Login -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 py-3 px-4 shadow-sm border-0 d-flex align-items-center gap-3 mt-2"
                style="background: #ecfdf5; color: #065f46; border-left: 6px solid #10b981 !important;">
                <span style="font-size: 1.8rem;">🎉</span>
                <div class="flex-grow-1">
                    <strong class="d-block heading-kid" style="font-size: 1.05rem;">Berhasil Masuk!</strong>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Hero Card Siswa -->
        <section class="hero-student-card">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="avatar-robot-box" title="Avatar Kamu">
                        {{ session('avatar') ?? '🤖' }}
                    </div>
                </div>
                <div class="col mt-3 mt-sm-0">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill bg-white text-dark fw-bold px-3 py-2 shadow-sm" style="font-size: 0.82rem;">
                            <i class="bi bi-patch-check-fill text-success me-1"></i>Siswa Aktif
                        </span>
                        <span class="badge rounded-pill bg-warning text-dark fw-bold px-3 py-2 shadow-sm" style="font-size: 0.82rem;">
                            <i class="bi bi-mortarboard me-1"></i>{{ $jenjang }} · Kelas {{ $kelas }}
                        </span>
                    </div>
                    <h2 class="heading-kid fw-bold mb-1" style="font-size: clamp(1.4rem, 4vw, 2.1rem); letter-spacing: -0.5px;">
                        Halo, {{ $namaPanggilan }}! 👋
                    </h2>
                    <p class="mb-0 text-white-50" style="font-size: clamp(0.9rem, 2.5vw, 1.05rem);">
                        Siap merakit robot dan belajar coding hari ini? Tetap semangat dan capai impianmu ya! 🚀
                    </p>
                </div>
            </div>
        </section>

        <!-- Informasi Paket & Sesi Belajar -->
        <div class="row g-4 mt-2">
            <!-- Kartu Program Kursus & Sesi -->
            <div class="col-lg-7">
                <div class="kid-feature-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-circle-badge" style="background: #eef2ff; color: #4f46e5;">
                                <i class="bi bi-cpu-fill"></i>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Program Belajar</small>
                                <h3 class="heading-kid fw-bold mb-0 text-dark" style="font-size: 1.35rem;">{{ $program }}</h3>
                            </div>
                        </div>
                        <span class="badge rounded-pill px-3 py-2" style="background: #dbeafe; color: #1e40af; font-size: 0.8rem;">
                            <i class="bi bi-stars me-1"></i>Level Praktikum
                        </span>
                    </div>

                    <!-- Progress Sesi -->
                    <div class="p-3 rounded-4 mb-3" style="background: #f8fafc; border: 1.5px dashed #cbd5e1;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-muted" style="font-size: 0.88rem;">
                                <i class="bi bi-calendar-check me-1 text-primary"></i>Kemajuan Sesi Belajar:
                            </span>
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                                <strong class="text-primary">{{ $sesiTerpakai }}</strong> dari {{ $totalSesi }} Sesi
                            </span>
                        </div>
                        <div class="sesi-progress-track mb-2">
                            <div class="sesi-progress-bar" style="width: {{ $persentase }}%;"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.78rem;">
                            <span>Sisa sesi belajar: <strong class="text-success">{{ $sisaSesi }} sesi lagi</strong></span>
                            <span>{{ $persentase }}% Selesai</span>
                        </div>
                    </div>

                    <!-- Detail Data Siswa -->
                    <div class="row g-2 text-muted" style="font-size: 0.85rem;">
                        <div class="col-sm-6">
                            <div class="p-2 rounded-3 bg-light d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge text-primary" style="font-size: 1.1rem;"></i>
                                <div>
                                    <div style="font-size: 0.72rem;">Nama Lengkap:</div>
                                    <strong class="text-dark">{{ $namaLengkap }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2 rounded-3 bg-light d-flex align-items-center gap-2">
                                <i class="bi bi-at text-info" style="font-size: 1.1rem;"></i>
                                <div>
                                    <div style="font-size: 0.72rem;">Username Akun:</div>
                                    <strong class="text-dark">{{ $user->username }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Misi Robotika Hari Ini -->
            <div class="col-lg-5">
                <div class="kid-feature-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-circle-badge" style="background: #fef3c7; color: #d97706;">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Misi Belajar</small>
                            <h3 class="heading-kid fw-bold mb-0 text-dark" style="font-size: 1.35rem;">Tantangan Robotik</h3>
                        </div>
                    </div>

                    <div class="mission-item">
                        <div style="font-size: 1.5rem;">🧩</div>
                        <div class="flex-grow-1">
                            <strong class="d-block text-dark" style="font-size: 0.88rem;">Rakit Modul Sensor Cahaya</strong>
                            <small class="text-muted" style="font-size: 0.76rem;">Gunakan resistor dan sensor LDR pada breadboard.</small>
                        </div>
                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">Siap</span>
                    </div>

                    <div class="mission-item" style="background: #fdf4ff; border-color: #f5d0fe;">
                        <div style="font-size: 1.5rem;">💻</div>
                        <div class="flex-grow-1">
                            <strong class="d-block text-dark" style="font-size: 0.88rem;">Logika Blok Perulangan (Loop)</strong>
                            <small class="text-muted" style="font-size: 0.76rem;">Gerakkan roda motor maju 5 detik lalu berbelok.</small>
                        </div>
                        <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">Praktik</span>
                    </div>

                    <div class="mission-item" style="background: #eff6ff; border-color: #bfdbfe;">
                        <div style="font-size: 1.5rem;">🛡️</div>
                        <div class="flex-grow-1">
                            <strong class="d-block text-dark" style="font-size: 0.88rem;">Tips Keamanan PIN Rahasia</strong>
                            <small class="text-muted" style="font-size: 0.76rem;">Jangan bagikan 6 angka PIN kamu ke orang lain ya!</small>
                        </div>
                        <i class="bi bi-check-circle-fill text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Praktikum Kelas Belajar -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="kid-feature-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-circle-badge" style="background: #ecfdf5; color: #059669;">
                                <i class="bi bi-calendar-event-fill"></i>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Jadwal Kelas</small>
                                <h3 class="heading-kid fw-bold mb-0 text-dark" style="font-size: 1.35rem;">Jadwal Belajar Kamu</h3>
                            </div>
                        </div>
                        <span class="badge bg-light text-muted border px-3 py-2" style="font-size: 0.8rem;">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>Robotics Hub
                        </span>
                    </div>

                    @if ($jadwalList->isNotEmpty())
                        <div class="row g-3">
                            @foreach ($jadwalList as $j)
                                <div class="col-md-6 col-lg-4">
                                    <div class="jadwal-item-card">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-primary text-white fw-bold">{{ $j->hari ?? 'Sabtu' }}</span>
                                            <small class="text-muted">{{ $j->jam_mulai ?? '09:00' }} - {{ $j->jam_selesai ?? '10:30' }}</small>
                                        </div>
                                        <h4 class="fw-bold text-dark mb-1" style="font-size: 1rem;">{{ $j->nama_kelas ?? $program }}</h4>
                                        <div class="text-muted" style="font-size: 0.8rem;">
                                            <i class="bi bi-person-video3 me-1 text-primary"></i>Tutor: {{ $j->nama_tutor ?? 'Tutor Pembina' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Fallback Jadwal Statis Edukatif -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="jadwal-item-card">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-primary text-white fw-bold">Sabtu</span>
                                        <small class="text-muted fw-bold">09:00 - 10:30 WIB</small>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Lab Robotika A · Sesi Praktik Perangkat</h4>
                                    <div class="text-muted" style="font-size: 0.8rem;">
                                        <i class="bi bi-person-badge-fill me-1 text-primary"></i>Tutor Pengampu: Kak Tutor Robotika
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="jadwal-item-card" style="border-left-color: #06b6d4;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-info text-white fw-bold">Minggu</span>
                                        <small class="text-muted fw-bold">13:30 - 15:00 WIB</small>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Lab Komputer B · Coding & Simulasi Logika</h4>
                                    <div class="text-muted" style="font-size: 0.8rem;">
                                        <i class="bi bi-person-badge-fill me-1 text-info"></i>Tutor Pengampu: Kak Tutor Coding
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tombol Keluar di Bawah untuk Akses Mudah di Mobile -->
        <div class="text-center mt-5 pt-2">
            <button type="button" class="btn btn-outline-danger btn-lg rounded-pill px-4 py-2 fw-bold"
                onclick="document.getElementById('formLogoutSiswa').submit();" style="font-size: 0.95rem;">
                <i class="bi bi-box-arrow-right me-2"></i>Keluar dari Akun Siswa
            </button>
            <div class="text-muted mt-2" style="font-size: 0.78rem;">
                Pastikan kamu sudah selesai belajar sebelum menekan tombol keluar ya! 👋
            </div>
        </div>

    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mencegah cache browser (bfcache) saat tombol Back ditekan
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>

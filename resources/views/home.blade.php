<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RACER ROBOTIC - Platform Pembelajaran STEM & Robotika</title>

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
            --primary-blue: #1e5ee6;
            --navy-dark: #0f1c3f;
            --navy-card: #142042;
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: #0b1329;
            color: #ffffff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Hero Background with Dark Overlay */
        .hero-wrapper {
            position: relative;
            min-height: 100vh;
            background-image: 
                linear-gradient(rgba(10, 18, 38, 0.78), rgba(8, 14, 30, 0.88)),
                url("{{ file_exists(public_path('assets/background.png')) ? asset('assets/background.png') : asset('assets/background-login.jpg') }}");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .landing-navbar {
            padding: 1.25rem 2.5rem;
            background: rgba(10, 18, 38, 0.45);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 100;
        }

        .brand-logo-box {
            width: 40px;
            height: 40px;
            background: var(--primary-blue);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.3rem;
            box-shadow: 0 4px 12px rgba(30, 94, 230, 0.35);
        }

        /* Headline styling matching wireframe */
        .hero-headline {
            font-size: 2.75rem;
            font-weight: 800;
            line-height: 1.25;
            color: #ffffff;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        @media (max-width: 991.98px) {
            .hero-headline {
                font-size: 2rem;
            }
            .landing-navbar {
                padding: 1rem 1.5rem;
            }
        }

        /* Accent bar below headline */
        .accent-bar {
            width: 60px;
            height: 4px;
            background-color: #3b82f6;
            border-radius: 2px;
            margin: 1.5rem 0;
            box-shadow: 0 0 12px rgba(59, 130, 246, 0.6);
        }

        /* Phone / WhatsApp Display */
        .phone-display {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 0.55rem 1.25rem;
            border-radius: 50rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .phone-display:hover {
            color: #ffffff;
            background: rgba(37, 211, 102, 0.25);
            border-color: #25d366;
            transform: translateY(-2px);
        }

        /* Instruction prompt */
        .instruction-text {
            font-size: 0.92rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        /* Dark Navy Pill Login Button */
        .btn-login-navy {
            background-color: #121e3a;
            color: #ffffff;
            border: 1px solid #233562;
            border-radius: 50rem;
            padding: 0.65rem 1.85rem;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-login-navy:hover {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30, 94, 230, 0.45);
        }

        /* Right Column: Card Pengumuman */
        .card-pengumuman {
            background: #ffffff;
            color: #0f172a;
            border-radius: 20px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
            padding: 2.25rem;
            min-height: 520px;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .pengumuman-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }

        .pengumuman-list {
            flex-grow: 1;
            overflow-y: auto;
            max-height: 420px;
            padding-right: 0.35rem;
        }

        /* Custom Scrollbar for announcements */
        .pengumuman-list::-webkit-scrollbar {
            width: 5px;
        }
        .pengumuman-list::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        .pengumuman-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .pengumuman-item {
            padding: 1.15rem;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            margin-bottom: 0.85rem;
            transition: all 0.2s ease;
        }

        .pengumuman-item:hover {
            background: #ffffff;
            border-color: #bfdbfe;
            box-shadow: 0 4px 14px rgba(30, 94, 230, 0.08);
            transform: translateY(-1px);
        }

        .badge-penting {
            background-color: #fee2e2;
            color: #dc2626;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
        }

        .badge-kategori {
            background-color: #e0edff;
            color: var(--primary-blue);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
        }

        /* Footer */
        .landing-footer {
            margin-top: auto;
            padding: 1.25rem 2.5rem;
            background: rgba(8, 14, 30, 0.85);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
        }
    </style>
</head>

<body>
    <div class="hero-wrapper">
        <!-- Top Navbar -->
        <header class="landing-navbar d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-logo-box">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-white mb-0" style="letter-spacing: -0.2px;">RACER ROBOTIC</h5>
                    <small class="text-white-50" style="font-size: 0.72rem;">Administrasi Akademik & STEM Center</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('tutor.register') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.82rem; border-color: rgba(255,255,255,0.25);">
                    <i class="bi bi-person-plus me-1"></i> Daftar Tutor
                </a>
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-semibold" style="background-color: var(--primary-blue); border-color: var(--primary-blue); font-size: 0.82rem;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </a>
            </div>
        </header>

        <!-- Main Hero Section (2 Kolom sesuai Wireframe) -->
        <main class="flex-grow-1 d-flex align-items-center py-5">
            <div class="container-fluid px-4 px-md-5">
                <div class="row align-items-center g-4 g-lg-5">
                    
                    <!-- Kolom Kiri: Informasi Pendaftaran & Login -->
                    <div class="col-12 col-lg-7 text-start">
                        <!-- Tagline Edukasi Kursus -->
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(30, 94, 230, 0.25); border: 1px solid rgba(59, 130, 246, 0.4); font-size: 0.78rem; font-weight: 600; color: #93c5fd;">
                            <i class="bi bi-stars text-warning"></i> Kursus Robotika, Coding & AI Anak (Jenjang TK • SD • SMP)
                        </div>

                        <!-- Headline yang disesuaikan untuk Lembaga Les Robotika TK, SD, SMP -->
                        <h1 class="hero-headline">
                            Mau mendaftarkan ananda (TK, SD, atau SMP) les robotika atau belum memiliki akun? Hubungi admin akademik kami di bawah ini.
                        </h1>

                        <!-- Accent bar -->
                        <div class="accent-bar"></div>

                        <!-- Kontak Admin WhatsApp & Konsultasi Kelas -->
                        <div class="mb-4">
                            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer%20Robotic,%20saya%20ingin%20mendaftarkan%20ananda%20(TK/SD/SMP)%20untuk%20les%20robotika.%20Boleh%20minta%20info%20program%20dan%20jadwalnya?"
                                target="_blank" class="phone-display" title="Klik untuk konsultasi & daftar les via WhatsApp">
                                <i class="bi bi-whatsapp text-success fs-5"></i>
                                <span>+62 812-3456-7890</span>
                                <small class="text-white-50 ms-1 fw-normal">(Konsultasi & Pendaftaran)</small>
                            </a>
                            <div class="text-white-50 mt-2 d-flex flex-wrap align-items-center gap-3" style="font-size: 0.8rem;">
                                <span><i class="bi bi-check-circle-fill text-success me-1"></i> Program khusus usia TK, SD, & SMP</span>
                                <span><i class="bi bi-check-circle-fill text-success me-1"></i> Tersedia kelas coba gratis (free trial)</span>
                            </div>
                        </div>

                        <!-- Petunjuk Login Siswa, Orang Tua, & Tutor -->
                        <div class="mb-3">
                            <div class="instruction-text mb-2">
                                Sudah terdaftar? Masuk ke akun Anda di sini:
                            </div>

                            <!-- Tombol Login Berbentuk Pill -->
                            <a href="{{ route('login') }}" class="btn-login-navy">
                                <i class="bi bi-box-arrow-in-right fs-5"></i>
                                <span>Masuk ke Akun</span>
                            </a>
                        </div>

                        <!-- Link Pendaftaran Mandiri Tutor -->
                        <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.12); max-width: 540px;">
                            <span class="text-white-50" style="font-size: 0.84rem;">Tertarik bergabung sebagai pengajar les?</span>
                            <a href="{{ route('tutor.register') }}" class="text-info fw-semibold text-decoration-none ms-1" style="font-size: 0.84rem;">
                                Daftar Akun Tutor di sini <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Card Pengumuman -->
                    <div class="col-12 col-lg-5">
                        <div class="card-pengumuman">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <h2 class="pengumuman-title">
                                    Pengumuman
                                </h2>
                                <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i class="bi bi-broadcast me-1"></i> Terkini
                                </span>
                            </div>

                            <!-- List Pengumuman -->
                            <div class="pengumuman-list">
                                @forelse ($pengumumanList as $item)
                                    <div class="pengumuman-item">
                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                            @if ($item->is_penting)
                                                <span class="badge-penting">
                                                    <i class="bi bi-pin-angle-fill me-1"></i>{{ $item->kategori ?: 'PENTING' }}
                                                </span>
                                            @else
                                                <span class="badge-kategori">
                                                    {{ $item->kategori ?: 'Akademik' }}
                                                </span>
                                            @endif
                                            <small class="text-muted" style="font-size: 0.74rem;">
                                                <i class="bi bi-calendar-event me-1"></i>{{ $item->tanggal ?: now()->format('d M Y') }}
                                            </small>
                                        </div>

                                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.9rem; line-height: 1.4;">
                                            {{ $item->judul }}
                                        </h6>
                                        <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.45;">
                                            {{ $item->konten }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-megaphone fs-1 d-block mb-2 text-secondary"></i>
                                        Belum ada pengumuman terbaru.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Card Footer -->
                            <div class="pt-3 mt-auto border-top text-center" style="font-size: 0.8rem;">
                                <span class="text-muted">Butuh info jadwal & kelas?</span>
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer%20Robotic,%20saya%20ingin%20bertanya%20seputar%20informasi%20kelas"
                                    target="_blank" class="text-primary fw-semibold text-decoration-none ms-1">
                                    Hubungi Admin WhatsApp <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="landing-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>© 2026 RACER ROBOTIC. Bertumbuh bersama teknologi.</div>
            <div>Semua jadwal dalam Waktu Indonesia Barat (WIB)</div>
        </footer>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


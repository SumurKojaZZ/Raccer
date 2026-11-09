<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Racer Robotic - Robotic & Drone Coding Academy</title>

    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
            /* Palet Warna Sesuai Referensi Gambar */
            --racer-sky-blue: #00A3FF;
            /* Biru cerah Hero */
            --racer-sky-dark: #0077c8;
            /* Biru gelap */
            --racer-orange: #FF9900;
            /* Oranye tombol */
            --racer-orange-hover: #e68500;
            /* Oranye hover */
            --racer-yellow-accent: #FFC000;
            /* Kuning teks highlight */
            --racer-dark: #1e293b;
            /* Teks utama */
            --racer-gray-bg: #f8fafc;
            /* Background abu terang */
            --racer-whatsapp: #25D366;
            /* Hijau WhatsApp */
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--racer-gray-bg);
            color: var(--racer-dark);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Top Navbar ── */
        .racer-navbar {
            background-color: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 0.85rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.08);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .racer-logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .racer-logo-title {
            font-size: 1.45rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #008be3;
            line-height: 1;
            margin: 0;
        }

        .racer-logo-title span {
            color: #ef4444;
            /* Sentuhan merah di logo RACER */
        }

        .racer-logo-subtitle {
            font-size: 0.65rem;
            font-weight: 800;
            color: #475569;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        .nav-link-racer {
            color: #334155;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 0.5rem 0.9rem !important;
            transition: all 0.2s ease;
        }

        .nav-link-racer:hover {
            color: var(--racer-sky-blue);
        }

        /* ── Hero Carousel Container ── */
        .hero-carousel-container {
            max-width: 1360px;
            margin: 1.5rem auto 2.5rem auto;
            padding: 0 1.25rem;
            width: 100%;
        }

        /* FIX ANTI MENCIUT: Tetapkan tinggi mutlak pada container, carousel, inner, dan item */
        .hero-slider-card,
        .hero-slider-card .carousel,
        .hero-slider-card .carousel-inner,
        .hero-slider-card .carousel-item {
            height: 540px !important;
            min-height: 540px !important;
            max-height: 540px !important;
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            background-color: #0284c7;
            /* Warna dasar jika foto sedang dimuat */
        }

        /* Transisi Fade Lembut & Halus */
        .carousel-fade .carousel-item {
            opacity: 0;
            transition: opacity 0.8s ease-in-out !important;
        }

        .carousel-fade .carousel-item.active {
            opacity: 1;
        }

        .hero-slider-card {
            box-shadow: 0 20px 45px rgba(0, 139, 227, 0.22);
        }

        /* Foto background slide diposisikan absolute agar tidak menciut saat transisi */
        .hero-slider-card .hero-slide-img {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            min-width: 100% !important;
            min-height: 100% !important;
            object-fit: cover !important;
            object-position: center right !important;
            z-index: 1;
            display: block;
        }

        /* OVERLAY GRADIENT BIRU: Menempatkan teks langsung di dalam foto dengan kontras tajam */
        .hero-slide-overlay {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg,
                    rgba(0, 120, 215, 0.97) 0%,
                    rgba(0, 145, 235, 0.90) 42%,
                    rgba(0, 163, 255, 0.60) 68%,
                    rgba(0, 0, 0, 0.15) 100%);
            display: flex;
            align-items: center;
            padding: 3rem 4rem;
            z-index: 2;
        }

        /* Tipografi Teks di Dalam Gambar */
        .hero-headline {
            font-size: 2.75rem;
            font-weight: 900;
            line-height: 1.22;
            letter-spacing: -0.5px;
            color: #ffffff;
            margin-bottom: 1.25rem;
        }

        .hero-headline .text-accent-orange {
            color: var(--racer-yellow-accent);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .hero-subtext {
            color: rgba(255, 255, 255, 0.92);
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            max-width: 580px;
        }

        /* Tag Label Kegiatan */
        .badge-kegiatan-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 0.4rem 1rem;
            border-radius: 50rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1.2rem;
            letter-spacing: 0.5px;
        }

        /* ── Tombol Oranye Khas Referensi (Di Dalam Banner) ── */
        .btn-racer-orange {
            background-color: var(--racer-orange);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.98rem;
            padding: 0.75rem 1.65rem;
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(255, 153, 0, 0.45);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-racer-orange:hover {
            background-color: var(--racer-orange-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(230, 133, 0, 0.55);
        }

        .btn-racer-white {
            background-color: #ffffff;
            color: var(--racer-sky-dark);
            font-weight: 700;
            font-size: 0.98rem;
            padding: 0.75rem 1.65rem;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-racer-white:hover {
            background-color: #f8fafc;
            color: var(--racer-sky-blue);
            transform: translateY(-2px);
        }

        /* Indikator Carousel Bulat */
        .hero-slider-card .carousel-indicators {
            margin-bottom: 1.5rem;
            z-index: 5;
        }

        .hero-slider-card .carousel-indicators [data-bs-target] {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
            background-color: #ffffff;
            opacity: 0.55;
            border: 2px solid rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .hero-slider-card .carousel-indicators .active {
            opacity: 1;
            background-color: var(--racer-orange);
            transform: scale(1.3);
            border-color: #ffffff;
        }

        /* Tombol Navigasi Panah Kiri/Kanan */
        .hero-slider-card .carousel-control-prev,
        .hero-slider-card .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(6px);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            margin: 0 1.25rem;
            z-index: 5;
            transition: all 0.2s;
            opacity: 0.85;
        }

        .hero-slider-card .carousel-control-prev:hover,
        .hero-slider-card .carousel-control-next:hover {
            background: var(--racer-orange);
            opacity: 1;
        }

        /* ── Section Company Profile / Program ── */
        .section-header-title {
            font-size: 2.2rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-align: center;
        }

        .section-header-title .title-orange {
            color: var(--racer-orange);
        }

        .section-header-title .title-blue {
            color: var(--racer-sky-blue);
        }

        /* ── Cards Program ── */
        .card-feature {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 2.2rem 1.75rem;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 32px rgba(0, 163, 255, 0.14);
            border-color: #bae6fd;
        }

        .feature-icon-box {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: #e0f2fe;
            color: var(--racer-sky-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            margin-bottom: 1.25rem;
        }

        /* ── Floating WhatsApp Button (Sesuai Referensi) ── */
        .floating-wa-btn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 60px;
            height: 60px;
            background-color: var(--racer-whatsapp);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.45);
            z-index: 1050;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .floating-wa-btn:hover {
            background-color: #20ba5a;
            color: #ffffff;
            transform: scale(1.1) translateY(-3px);
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.55);
        }

        /* ── Modern Light Footer Sesuai Palet Warna ── */
        .racer-footer {
            background-color: #f8fafc;
            border-top: 3px solid var(--racer-sky-blue);
            color: #475569;
            margin-top: auto;
        }

        .footer-link {
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .footer-link:hover {
            color: var(--racer-sky-blue);
            padding-left: 4px;
        }

        .footer-social-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }

        .footer-social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 163, 255, 0.15);
            border-color: #bae6fd;
        }

        .footer-bottom-bar {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
        }

        /* Responsif untuk Layar Tablet & HP */
        @media (max-width: 991.98px) {

            .hero-slider-card,
            .hero-slider-card .carousel,
            .hero-slider-card .carousel-inner,
            .hero-slider-card .carousel-item {
                height: 480px !important;
                min-height: 480px !important;
                max-height: 480px !important;
            }

            .hero-slide-overlay {
                padding: 2.5rem 2rem;
                background: linear-gradient(180deg,
                        rgba(0, 115, 205, 0.94) 0%,
                        rgba(0, 140, 230, 0.90) 65%,
                        rgba(0, 0, 0, 0.35) 100%);
            }

            .hero-headline {
                font-size: 2.1rem;
            }

            .hero-subtext {
                font-size: 0.95rem;
            }

            .btn-group-hero {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn-racer-orange,
            .btn-racer-white {
                width: 100%;
            }
        }

        @media (max-width: 576px) {

            .hero-slider-card,
            .hero-slider-card .carousel,
            .hero-slider-card .carousel-inner,
            .hero-slider-card .carousel-item {
                height: 460px !important;
                min-height: 460px !important;
                max-height: 460px !important;
            }

            .hero-headline {
                font-size: 1.7rem;
            }

            .hero-slide-overlay {
                padding: 2rem 1.25rem;
            }
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <header class="racer-navbar">
        <div class="container-fluid px-lg-5 d-flex align-items-center justify-content-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="racer-logo-badge">
                <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center"
                    style="width: 42px; height: 42px; background: linear-gradient(135deg, #00A3FF, #0077cc) !important;">
                    <i class="bi bi-robot fs-4"></i>
                </div>
                <div>
                    <h1 class="racer-logo-title"><span>RACER</span> ROBOTIC</h1>
                    <span class="racer-logo-subtitle">ROBOTIC & DRONE CODING ACADEMY</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="d-none d-lg-flex align-items-center gap-2">
                <a href="#kelas" class="nav-link nav-link-racer">Our Class</a>
                <a href="#tentang" class="nav-link nav-link-racer">About Us</a>
                <a href="{{ route('tutor.register') }}" class="nav-link nav-link-racer text-primary">
                    <i class="bi bi-person-plus me-1"></i> Gabung Tutor
                </a>
            </nav>

            <!-- Tombol Masuk / Login -->
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-3 py-1 fw-bold"
                    style="font-size: 0.85rem; border-color: var(--racer-sky-blue); color: var(--racer-sky-blue);">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </a>
            </div>
        </div>
    </header>

    <!-- ================= HERO CAROUSEL (ANTI-MENCIUT) ================= -->
    <section class="hero-carousel-container">
        <div class="hero-slider-card">
            <!-- Carousel otomatis fade halus setiap 3.5 detik -->
            <div id="carouselHeroKegiatan" class="carousel slide carousel-fade" data-bs-ride="carousel"
                data-bs-interval="3500">

                <!-- Indikator Slide -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselHeroKegiatan" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselHeroKegiatan" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselHeroKegiatan" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselHeroKegiatan" data-bs-slide-to="3"
                        aria-label="Slide 4"></button>
                </div>

                <div class="carousel-inner">

                    <!-- ================= SLIDE 1 ================= -->
                    <div class="carousel-item active">
                        <img src="{{ asset('assets/kegitatan-1.png') }}" class="hero-slide-img"
                            alt="Kegiatan Praktikum Robotika Siswa">
                        <div class="hero-slide-overlay">
                            <div class="col-12 col-lg-8 col-xl-7">
                                <div class="badge-kegiatan-tag">
                                    <i class="bi bi-stars text-warning"></i> Sesi Praktikum & Eksplorasi Sains
                                </div>
                                <h2 class="hero-headline">
                                    Menciptakan <span class="text-accent-orange">Generasi Unggul</span> Melalui
                                    <span class="text-accent-orange">Edukasi AI STEM</span> Robotik Dan
                                    <span class="text-accent-orange">Inovasi Digital!</span>
                                </h2>
                                <p class="hero-subtext">
                                    Membimbing ananda (TK, SD, SMP) menjadi kreator teknologi dengan kurikulum berbasis
                                    proyek praktikum yang seru dan aplikatif.
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-3 btn-group-hero">
                                    <a href="#tentang" class="btn-racer-orange">
                                        <i class="bi bi-info-circle-fill"></i> Tentang Kami
                                    </a>
                                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer%20Robotic,%20saya%20ingin%20konsultasi%20pendaftaran%20kelas%20robotika"
                                        target="_blank" class="btn-racer-orange">
                                        <i class="bi bi-whatsapp"></i> Hubungi Kami
                                    </a>
                                    <a href="{{ route('login') }}" class="btn-racer-white">
                                        <i class="bi bi-box-arrow-in-right"></i> Masuk Akun
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SLIDE 2 ================= -->
                    <div class="carousel-item">
                        <img src="{{ asset('assets/kegitatan-2.png') }}" class="hero-slide-img"
                            alt="Kelas Coding & Logika Mikrokontroler">
                        <div class="hero-slide-overlay">
                            <div class="col-12 col-lg-8 col-xl-7">
                                <div class="badge-kegiatan-tag">
                                    <i class="bi bi-cpu-fill text-warning"></i> Coding, Logika Algoritma & Drone
                                </div>
                                <h2 class="hero-headline">
                                    Belajar <span class="text-accent-orange">Coding & Drone</span> Dengan Cara
                                    <span class="text-accent-orange">Menyenangkan</span> Dan
                                    <span class="text-accent-orange">Interaktif!</span>
                                </h2>
                                <p class="hero-subtext">
                                    Mengasah computational thinking anak sejak dini melalui simulasi blok coding hingga
                                    perakitan drone terbang cerdas.
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-3 btn-group-hero">
                                    <a href="#tentang" class="btn-racer-orange">
                                        <i class="bi bi-info-circle-fill"></i> Tentang Kami
                                    </a>
                                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer%20Robotic,%20saya%20ingin%20konsultasi%20kelas%20coding%20dan%20drone"
                                        target="_blank" class="btn-racer-orange">
                                        <i class="bi bi-whatsapp"></i> Hubungi Kami
                                    </a>
                                    <a href="{{ route('login') }}" class="btn-racer-white">
                                        <i class="bi bi-box-arrow-in-right"></i> Masuk Akun
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SLIDE 3 ================= -->
                    <div class="carousel-item">
                        <img src="{{ asset('assets/kegitatan-3.png') }}" class="hero-slide-img"
                            alt="Kolaborasi Tim & Pemecahan Masalah">
                        <div class="hero-slide-overlay">
                            <div class="col-12 col-lg-8 col-xl-7">
                                <div class="badge-kegiatan-tag">
                                    <i class="bi bi-people-fill text-warning"></i> Kerja Sama Tim & Kepemimpinan
                                </div>
                                <h2 class="hero-headline">
                                    Mengasah <span class="text-accent-orange">Kreativitas</span> Dan Kemampuan
                                    <span class="text-accent-orange">Problem Solving</span> Di Setiap Sesi!
                                </h2>
                                <p class="hero-subtext">
                                    Didampingi instruktur dan tutor bersertifikat yang siap mendampingi perkembangan
                                    ananda secara personal dan intensif.
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-3 btn-group-hero">
                                    <a href="#tentang" class="btn-racer-orange">
                                        <i class="bi bi-info-circle-fill"></i> Tentang Kami
                                    </a>
                                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer%20Robotic,%20saya%20ingin%20tanya%20program%20bimbingan%20robotik"
                                        target="_blank" class="btn-racer-orange">
                                        <i class="bi bi-whatsapp"></i> Hubungi Kami
                                    </a>
                                    <a href="{{ route('login') }}" class="btn-racer-white">
                                        <i class="bi bi-box-arrow-in-right"></i> Masuk Akun
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= SLIDE 4 ================= -->
                    <div class="carousel-item">
                        <img src="{{ asset('assets/kegitatan-4.png') }}" class="hero-slide-img"
                            alt="Showcase Karya & Kompetisi Robotika">
                        <div class="hero-slide-overlay">
                            <div class="col-12 col-lg-8 col-xl-7">
                                <div class="badge-kegiatan-tag">
                                    <i class="bi bi-trophy-fill text-warning"></i> Prestasi & Turnamen Robotika
                                </div>
                                <h2 class="hero-headline">
                                    Persiapan <span class="text-accent-orange">Kompetisi Robotika</span> Nasional
                                    Hingga <span class="text-accent-orange">Tingkat Dunia!</span>
                                </h2>
                                <p class="hero-subtext">
                                    Fasilitas lab lengkap, modul terstandarisasi, dan portofolio prestasi teknologi yang
                                    membanggakan untuk masa depan anak.
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-3 btn-group-hero">
                                    <a href="#tentang" class="btn-racer-orange">
                                        <i class="bi bi-info-circle-fill"></i> Tentang Kami
                                    </a>
                                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer%20Robotic,%20saya%20ingin%20konsultasi%20pembinaan%20kompetisi"
                                        target="_blank" class="btn-racer-orange">
                                        <i class="bi bi-whatsapp"></i> Hubungi Kami
                                    </a>
                                    <a href="{{ route('login') }}" class="btn-racer-white">
                                        <i class="bi bi-box-arrow-in-right"></i> Masuk Akun
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Navigasi Kiri / Kanan -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselHeroKegiatan"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Sebelumnya</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselHeroKegiatan"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Selanjutnya</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ================= SECTION COMPANY PROFILE ================= -->
    <section id="tentang" class="py-5" style="background-color: #ffffff;">
        <div class="container py-3">
            <!-- Header Section Khas Referensi: Oranye + Biru -->
            <div class="text-center mb-5">
                <h3 class="section-header-title">
                    <span class="title-orange">Company Profile</span> <span class="title-blue">RACER ROBOTIC</span>
                </h3>
                <p class="text-muted mx-auto" style="max-width: 650px;">
                    Membimbing anak-anak usia dini hingga remaja dalam memahami teknologi masa depan dengan pendekatan
                    praktikum yang menyenangkan dan berbobot.
                </p>
            </div>

            <!-- Grid 3 Pilar Keunggulan -->
            <div class="row g-4" id="kelas">
                <div class="col-12 col-md-4">
                    <div class="card-feature text-center">
                        <div class="feature-icon-box mx-auto">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Kurikulum Robotika & IoT</h5>
                        <p class="text-muted small mb-0">
                            Mulai dari kit mekanik dasar hingga pemrograman Arduino dan sensor cerdas terkini.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card-feature text-center">
                        <div class="feature-icon-box mx-auto"
                            style="background-color: #fef3c7; color: var(--racer-orange);">
                            <i class="bi bi-airplane"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Drone & Coding AI</h5>
                        <p class="text-muted small mb-0">
                            Pemahaman logika komputasi anak, logika drone terbang otomatis, dan dasar kecerdasan buatan.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card-feature text-center">
                        <div class="feature-icon-box mx-auto" style="background-color: #dcfce7; color: #16a34a;">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Persiapan Kompetisi</h5>
                        <p class="text-muted small mb-0">
                            Pembinaan intensif untuk olimpiade robotika nasional maupun turnamen inovasi internasional.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FLOATING WHATSAPP BUTTON (SESUAI REFERENSI) ================= -->
    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer%20Robotic,%20saya%20ingin%20tanya%20informasi%20kelas%20robotika"
        target="_blank" class="floating-wa-btn" title="Chat Admin via WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- ================= MODERN LIGHT FOOTER ================= -->
    <footer class="racer-footer">
        <div class="container py-5">
            <div class="row g-4 justify-content-between">
                <!-- Kolom 1: Profil Brand & Visi -->
                <div class="col-12 col-lg-5">
                    <a href="{{ route('home') }}" class="racer-logo-badge mb-3">
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px; background: linear-gradient(135deg, #00A3FF, #0077cc) !important; color: white;">
                            <i class="bi bi-robot fs-4"></i>
                        </div>
                        <div>
                            <h5 class="racer-logo-title mb-0"><span>RACER</span> ROBOTIC</h5>
                            <span class="racer-logo-subtitle">ROBOTIC & DRONE CODING ACADEMY</span>
                        </div>
                    </a>
                    <p class="text-muted mt-3" style="font-size: 0.92rem; line-height: 1.6; max-width: 420px;">
                        Lembaga edukasi teknologi dan STEM terdepan untuk ananda jenjang TK, SD, hingga SMP. Mendidik
                        generasi penerus bangsa yang kreatif, mandiri, dan berdaya saing global melalui robotika dan
                        kecerdasan buatan.
                    </p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <a href="https://wa.me/6281234567890" target="_blank" class="footer-social-btn text-success"
                            title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="#" class="footer-social-btn text-primary" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="footer-social-btn text-danger" title="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Navigasi Cepat -->
                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.95rem; letter-spacing: 0.3px;">MENU CEPAT
                    </h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 0.9rem;">
                        <li><a href="#kelas" class="footer-link"><i class="bi bi-chevron-right text-primary me-1"></i>
                                Our Class</a></li>
                        <li><a href="#tentang" class="footer-link"><i class="bi bi-chevron-right text-primary me-1"></i>
                                About Us</a></li>
                        <li><a href="{{ route('tutor.register') }}" class="footer-link"><i
                                    class="bi bi-chevron-right text-primary me-1"></i> Gabung Tutor</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link"><i
                                    class="bi bi-chevron-right text-primary me-1"></i> Masuk Akun Portal</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak & Jam Operasional -->
                <div class="col-6 col-lg-4">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.95rem; letter-spacing: 0.3px;">HUBUNGI KAMI
                    </h6>
                    <div class="d-flex flex-column gap-2 text-muted" style="font-size: 0.9rem;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                            <span>Robotics Hub Lab & STEM Training Center Jakarta</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-whatsapp text-success"></i>
                            <a href="https://wa.me/6281234567890" target="_blank" class="footer-link fw-semibold">+62
                                812-3456-7890</a>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock-fill text-primary"></i>
                            <span>Senin - Sabtu (08.00 - 17.00 WIB)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Bawah Copyright -->
        <div class="footer-bottom-bar py-3">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center text-md-start"
                style="font-size: 0.84rem;">
                <div class="text-muted">
                    © 2026 <b class="text-primary">RACER ROBOTIC</b>. All rights reserved.
                </div>
                <div class="text-muted">
                    Menciptakan Generasi Unggul Melalui Edukasi AI & STEM
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
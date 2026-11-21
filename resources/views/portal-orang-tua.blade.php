<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Orang Tua - RACER ROBOTIC</title>

    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
            --primary-blue: #1570EF;
            --primary-hover: #0d5bbd;
            --indigo-accent: #6366f1;
            --bg-canvas: #f8fafc;
            --card-border: #eef2f6;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--bg-canvas);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .portal-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1.75rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        }

        .portal-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .portal-card:hover {
            box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.07);
        }

        .badge-role-primary {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .badge-role-secondary {
            background-color: #f5f3ff;
            color: #6d28d9;
            border: 1px solid #ddd6fe;
        }

        .progress-bar-racer {
            background: linear-gradient(90deg, #1570EF, #00A3FF);
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <!-- Topbar Header -->
    <header class="portal-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <div class="rounded-3 p-2 d-flex align-items-center justify-content-center text-white"
                    style="width: 38px; height: 38px; background: linear-gradient(135deg, #1570EF, #00A3FF);">
                    <i class="bi bi-robot fs-5"></i>
                </div>
                <div>
                    <span class="fw-bold fs-5 mb-0 d-block" style="color: #1570EF; line-height: 1.1;">RACER <span class="text-danger">ROBOTIC</span></span>
                    <small class="text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.8px;">PORTAL ORANG TUA</small>
                </div>
            </a>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Badge Role: Primary vs Secondary Parent -->
            @if(($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent')
                <span class="badge badge-role-secondary rounded-pill px-3 py-2 fw-semibold d-none d-md-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                    <i class="bi bi-people-fill text-indigo"></i>
                    <span>Secondary Parent (Wali Pendamping)</span>
                </span>
            @else
                <span class="badge badge-role-primary rounded-pill px-3 py-2 fw-semibold d-none d-md-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                    <i class="bi bi-shield-check text-primary"></i>
                    <span>Primary Parent (Wali Utama)</span>
                </span>
            @endif

            <!-- User Menu & Logout -->
            <div class="dropdown">
                <button class="btn btn-light bg-white border rounded-pill px-3 py-1 d-flex align-items-center gap-2 dropdown-toggle"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.85rem;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                        style="width: 28px; height: 28px; font-size: 0.75rem; background-color: {{ ($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent' ? '#6366f1' : '#1570EF' }};">
                        {{ strtoupper(substr($user->name ?? 'W', 0, 1)) }}
                    </div>
                    <span class="fw-semibold text-dark">{{ $user->name ?? 'Orang Tua' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-2 mt-2" style="font-size: 0.85rem; min-width: 220px;">
                    <li class="px-3 py-2 border-bottom">
                        <small class="text-muted d-block" style="font-size: 0.72rem;">Masuk sebagai:</small>
                        <span class="fw-bold text-dark d-block">{{ $user->name ?? '-' }}</span>
                        <span class="badge {{ ($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent' ? 'badge-role-secondary' : 'badge-role-primary' }} rounded-pill px-2 py-0 mt-1" style="font-size: 0.68rem;">
                            {{ ($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent' ? 'Secondary Parent' : 'Primary Parent' }}
                        </span>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('home') }}">
                            <i class="bi bi-house-door text-secondary"></i> Beranda Utama
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('orang-tua.edit-kontak') }}">
                            <i class="bi bi-pencil-square text-primary"></i> Edit Kontak & Secondary Parent
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <button type="button" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalLogout">
                            <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tombol Keluar Langsung -->
            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 fw-semibold d-none d-sm-inline-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#modalLogout" style="font-size: 0.82rem;">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="container py-4 flex-grow-1" style="max-width: 1100px;">

        <!-- Alert Notifikasi Flash -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div class="flex-grow-1" style="font-size: 0.92rem;">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-info-circle-fill text-info fs-5"></i>
                <div class="flex-grow-1" style="font-size: 0.92rem;">
                    {{ session('info') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Banner Selamat Datang -->
        <div class="portal-card p-4 mb-4 text-white position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #1570EF 0%, #008be3 60%, #00A3FF 100%);">
            <div class="position-relative z-1">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm" style="font-size: 0.75rem;">
                        <i class="bi bi-house-door-fill me-1"></i> {{ $keluarga->nama_keluarga ?? 'Akun Keluarga' }}
                    </span>
                    @if(($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent')
                        <span class="badge rounded-pill px-3 py-1 fw-bold text-white border border-light" style="font-size: 0.75rem; background-color: rgba(99, 102, 241, 0.85);">
                            <i class="bi bi-people-fill me-1"></i> Secondary Parent
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-1 fw-bold text-white border border-light" style="font-size: 0.75rem; background-color: rgba(255, 255, 255, 0.25);">
                            <i class="bi bi-shield-check me-1"></i> Primary Parent
                        </span>
                    @endif
                </div>

                <h3 class="fw-bold mb-1" style="letter-spacing: -0.3px;">
                    Selamat Datang, {{ $user->name ?? 'Bpk/Ibu Wali' }}!
                </h3>
                <p class="mb-0 text-white-50" style="font-size: 0.9rem; max-width: 650px;">
                    Pantau kehadiran, kuota sesi belajar robotika, dan perkembangan kurikulum anak Anda secara transparan dan terpadu.
                </p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Kolom 1: Profil Wali yang Sedang Masuk -->
            <div class="col-lg-6">
                <div class="portal-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                style="width: 36px; height: 36px; background-color: {{ ($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent' ? '#6366f1' : '#1570EF' }};">
                                <i class="bi {{ ($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent' ? 'bi-person-badge' : 'bi-person-check-fill' }}"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Profil Akun Anda</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Sesi aktif saat ini</small>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i> Aktif
                        </span>
                    </div>

                    <div class="row g-3" style="font-size: 0.85rem;">
                        <div class="col-6">
                            <span class="text-muted d-block" style="font-size: 0.75rem;">Nama Lengkap:</span>
                            <span class="fw-semibold text-dark">{{ $orangTua->nama_lengkap ?? $user->name }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block" style="font-size: 0.75rem;">Peran Wali:</span>
                            @if(($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent')
                                <span class="fw-bold" style="color: #6366f1;">Secondary Parent (Wali Pendamping)</span>
                            @else
                                <span class="fw-bold text-primary">Primary Parent (Wali Utama)</span>
                            @endif
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block" style="font-size: 0.75rem;">Hubungan dengan Anak:</span>
                            <span class="fw-semibold text-dark">{{ $orangTua->hubungan ?? $hubungan ?? 'Orang Tua' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block" style="font-size: 0.75rem;">No. WhatsApp:</span>
                            <span class="fw-semibold text-dark">{{ $orangTua->nomor_whatsapp ?? session('whatsapp_wali') ?? '-' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block" style="font-size: 0.75rem;">Alamat Domisili Keluarga:</span>
                            <span class="fw-semibold text-dark">{{ $keluarga->alamat_domisili ?? 'Belum dilengkapi' }}</span>
                            @if(!empty($keluarga->kota_kabupaten))
                                <span class="text-muted">({{ $keluarga->kota_kabupaten }})</span>
                            @endif
                        </div>
                        <div class="col-12 d-flex justify-content-between align-items-center pt-1 flex-wrap gap-2">
                            <div>
                                <span class="text-muted d-block" style="font-size: 0.75rem;">Username Akun Login:</span>
                                <span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 0.82rem;">{{ $user->username ?? '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#modalEditKontakDomisili">
                                    <i class="bi bi-pencil-square"></i> Ubah Kontak & Domisili
                                </button>
                                <a href="{{ route('orang-tua.edit-kontak') }}" class="btn btn-sm btn-light border rounded-pill px-2 py-1 text-muted" style="font-size: 0.78rem;" title="Buka Halaman Penuh">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Informasi Mitra Wali dalam Keluarga -->
            <div class="col-lg-6">
                <div class="portal-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary"
                                style="width: 36px; height: 36px;">
                                <i class="bi bi-people fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">
                                    {{ ($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent' ? 'Wali Utama (Primary Parent)' : 'Wali Pendamping (Secondary Parent)' }}
                                </h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Pasangan / Wali Lain dalam Keluarga</small>
                            </div>
                        </div>
                    </div>

                    @if(($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent')
                        <!-- Tampilkan Data Primary Parent -->
                        @if($primaryParent)
                            <div class="row g-3" style="font-size: 0.85rem;">
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">Nama Wali Utama:</span>
                                    <span class="fw-semibold text-dark">{{ $primaryParent->nama_lengkap }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">Hubungan:</span>
                                    <span class="fw-semibold text-dark">{{ $primaryParent->hubungan }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">No. WhatsApp:</span>
                                    <span class="fw-semibold text-dark">{{ $primaryParent->nomor_whatsapp }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">Peran:</span>
                                    <span class="badge badge-role-primary rounded-pill px-2 py-1">Primary Parent</span>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0 small">Data Primary Parent tidak ditemukan.</p>
                        @endif
                    @else
                        <!-- Tampilkan Data Secondary Parent -->
                        @if($secondaryParent)
                            <div class="row g-3" style="font-size: 0.85rem;">
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">Nama Wali Pendamping:</span>
                                    <span class="fw-semibold text-dark">{{ $secondaryParent->nama_lengkap }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">Hubungan:</span>
                                    <span class="fw-semibold text-dark">{{ $secondaryParent->hubungan }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">No. WhatsApp:</span>
                                    <span class="fw-semibold text-dark">{{ $secondaryParent->nomor_whatsapp }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">Peran:</span>
                                    <span class="badge badge-role-secondary rounded-pill px-2 py-1">Secondary Parent</span>
                                </div>
                                <div class="col-12 pt-1 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#modalEditSecondaryParent">
                                        <i class="bi bi-person-gear"></i> Kelola Secondary Parent
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-person-dash fs-3 d-block mb-1 opacity-50"></i>
                                <span style="font-size: 0.85rem;">Belum ada Secondary Parent (Wali Pendamping) terdaftar.</span>
                                <small class="d-block text-muted mt-1 mb-2" style="font-size: 0.76rem;">Anda dapat menambahkan wali pendamping opsional (maksimal 1 per keluarga) untuk bersama-sama memantau anak.</small>
                                <button type="button" class="btn btn-sm text-white rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-sm" style="font-size: 0.8rem; background-color: #6366f1;" data-bs-toggle="modal" data-bs-target="#modalEditSecondaryParent">
                                    <i class="bi bi-person-plus-fill"></i> Tambah Wali Pendamping
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= SECTION 3: CHILD SWITCHER & KARTU PROFIL ANAK (FAHRIEL AZIZ SATRIO) ================= -->
        @if($siswaList->isNotEmpty())
            <!-- Komponen Child Switcher: Pergantian Antar-Anak Tanpa Login Ulang -->
            <div class="portal-card p-3 p-md-4 mb-4 bg-white">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.76rem;">
                                <i class="bi bi-arrow-left-right me-1"></i> Child Switcher
                            </span>
                            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Pilih Profil Anak yang Dipantau</h5>
                        </div>
                        <small class="text-muted" style="font-size: 0.8rem;">
                            Beralih antar-anak secara instan tanpa perlu keluar (logout) dari akun keluarga Anda.
                        </small>
                    </div>
                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.78rem;">
                        <i class="bi bi-people-fill me-1 text-primary"></i> {{ $siswaList->count() }} Anak Terdaftar
                    </span>
                </div>

                <!-- Barisan Kartu Switcher Anak -->
                <div class="d-flex align-items-center gap-2 overflow-x-auto pb-2 flex-nowrap" id="childSwitcherContainer" style="scrollbar-width: thin;">
                    @foreach($siswaList as $idx => $s)
                        @php
                            $sId = $s->id_siswa ?? $s->id;
                            $isActive = ($activeChild && ($activeChild->id_siswa ?? $activeChild->id) == $sId) || (!$activeChild && $idx === 0);
                        @endphp
                        <button type="button" 
                                class="btn child-switcher-btn d-flex align-items-center gap-2 px-3 py-2 rounded-4 text-start {{ $isActive ? 'active btn-primary shadow-sm' : 'btn-light border bg-white text-dark' }}" 
                                data-child-id="{{ $sId }}"
                                onclick="switchActiveChild('{{ $sId }}', this)"
                                style="min-width: 200px; flex-shrink: 0; cursor: pointer; transition: all 0.2s ease;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm {{ $isActive ? 'bg-white text-primary' : 'bg-primary-subtle text-primary' }}"
                                style="width: 38px; height: 38px; font-size: 0.9rem; flex-shrink: 0;">
                                {{ strtoupper(substr($s->nama_lengkap ?? $s->nama ?? 'A', 0, 1)) }}
                            </div>
                            <div class="text-truncate" style="max-width: 140px;">
                                <div class="fw-bold text-truncate" style="font-size: 0.86rem;">
                                    {{ $s->nama_panggilan ?? $s->nama_lengkap ?? $s->nama }}
                                </div>
                                <div class="small opacity-75 text-truncate" style="font-size: 0.72rem;">
                                    {{ $s->jenjang ?? 'SD' }} Kelas {{ $s->kelas ?? '1' }}
                                </div>
                            </div>
                            @if($isActive)
                                <i class="bi bi-check-circle-fill ms-auto fs-5 text-white"></i>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Kartu Profil Utama Anak Aktif (Detail Lengkap & Kredensial Siswa) -->
            @foreach($siswaList as $idx => $s)
                @php
                    $sId = $s->id_siswa ?? $s->id;
                    $isActive = ($activeChild && ($activeChild->id_siswa ?? $activeChild->id) == $sId) || (!$activeChild && $idx === 0);
                    $totalSesi = (int) ($s->total_sesi ?? 12);
                    $terpakai = (int) ($s->sesi_terpakai ?? 0);
                    $sisa = max(0, $totalSesi - $terpakai);
                    $persen = ($totalSesi > 0) ? min(100, round(($terpakai / $totalSesi) * 100, 1)) : 0;
                    $isLunas = ($s->status_pembayaran ?? 'Lunas') === 'Lunas';
                    
                    $sUsername = null;
                    if (!empty($s->id_pengguna) && Schema::hasTable('pengguna')) {
                        $sUsername = DB::table('pengguna')->where('id_pengguna', $s->id_pengguna)->value('username');
                    }
                    
                    $usia = null;
                    if (!empty($s->tanggal_lahir)) {
                        try {
                            $usia = \Carbon\Carbon::parse($s->tanggal_lahir)->age;
                        } catch (\Throwable $e) {}
                    }
                @endphp

                <div class="child-profile-panel {{ $isActive ? '' : 'd-none' }}" id="panelProfilAnak{{ $sId }}">
                    <div class="portal-card p-4 mb-4 border-2 shadow-sm" style="border-color: #cbd5e1;">
                        <!-- Header Kartu Profil Anak -->
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow"
                                        style="width: 58px; height: 58px; font-size: 1.4rem; background: linear-gradient(135deg, #1570EF, #00A3FF);">
                                        {{ strtoupper(substr($s->nama_lengkap ?? $s->nama ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" title="Siswa Aktif" style="width: 14px; height: 14px;"></span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.2px;">
                                            {{ $s->nama_lengkap ?? $s->nama }}
                                        </h4>
                                        @if(!empty($s->nama_panggilan))
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.76rem;">
                                                Panggilan: {{ $s->nama_panggilan }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-2 text-muted mt-1 flex-wrap" style="font-size: 0.8rem;">
                                        <span><i class="bi bi-mortarboard-fill text-primary me-1"></i>{{ $s->jenjang ?? 'SD' }} Kelas {{ $s->kelas ?? '1' }}</span>
                                        <span>&bull;</span>
                                        <span><i class="bi bi-shield-check text-success me-1"></i>NISN: <strong>{{ $s->nisn ?? '-' }}</strong></span>
                                        @if($usia !== null)
                                            <span>&bull;</span>
                                            <span><i class="bi bi-calendar2-heart text-danger me-1"></i>Usia: {{ $usia }} Tahun</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Status Akun & Pembayaran Badge -->
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge {{ $isLunas ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }} rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.78rem;">
                                    <i class="bi {{ $isLunas ? 'bi-check-circle-fill' : 'bi-clock-history' }} me-1"></i>
                                    Pembayaran: {{ $s->status_pembayaran ?? 'Lunas' }}
                                </span>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.78rem;">
                                    Status: {{ $s->status_siswa ?? 'Aktif' }}
                                </span>
                            </div>
                        </div>

                        <!-- 3 Grid Metrik / Kartu Ringkasan Anak -->
                        <div class="row g-3 mb-4">
                            <!-- Box 1: Paket & Program Kursus -->
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light h-100 border">
                                    <span class="text-muted d-block mb-1" style="font-size: 0.74rem;">PROGRAM & PAKET KURSUS</span>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">
                                        {{ $s->program ?? $s->paket_belajar ?? 'Junior Robotics' }}
                                    </h6>
                                    <small class="text-secondary d-block" style="font-size: 0.78rem;">
                                        {{ $s->paket_belajar ?? 'Reguler Bulanan (Robotika Dasar)' }}
                                    </small>
                                </div>
                            </div>

                            <!-- Box 2: Kuota & Progres Sesi Belajar -->
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light h-100 border">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted" style="font-size: 0.74rem;">KUOTA SESI PERTEMUAN</span>
                                        <span class="fw-bold text-primary" style="font-size: 0.8rem;">Sisa: {{ $sisa }} Sesi</span>
                                    </div>
                                    <div class="d-flex align-items-baseline gap-1 mb-2">
                                        <h4 class="fw-bold text-dark mb-0">{{ $terpakai }}</h4>
                                        <span class="text-muted" style="font-size: 0.82rem;">/ {{ $totalSesi }} Sesi Selesai</span>
                                    </div>
                                    <div class="progress" style="height: 7px; background-color: #e2e8f0; border-radius: 10px;">
                                        <div class="progress-bar progress-bar-racer" role="progressbar" style="width: {{ $persen }}%;" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Box 3: Kredensial Akun Siswa Mandiri -->
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light h-100 border">
                                    <span class="text-muted d-block mb-1" style="font-size: 0.74rem;">AKUN LOGIN SISWA MANDIRI</span>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-person-fill-lock text-primary fs-5"></i>
                                        <div>
                                            <span class="badge bg-white text-dark border px-2 py-1 font-monospace" style="font-size: 0.82rem;">
                                                {{ $sUsername ?? 'akun.' . strtolower(str_replace(' ', '', $s->nama_panggilan ?? 'siswa')) }}
                                            </span>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.73rem;">
                                        <i class="bi bi-shield-lock-fill text-success me-1"></i>PIN 6-digit terenkripsi (keamanan aktif)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Rinci Profil Anak (Tabel Grid Informasi Lengkap) -->
                        <div class="border rounded-4 p-3 bg-white mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">
                                    <i class="bi bi-info-circle-fill text-primary me-2"></i>Rincian Identitas & Data Belajar Siswa
                                </h6>
                                <span class="badge bg-light text-muted border" style="font-size: 0.72rem;">ID Siswa: #{{ $sId }}</span>
                            </div>
                            <div class="row g-3" style="font-size: 0.84rem;">
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block" style="font-size: 0.74rem;">Nama Lengkap:</span>
                                    <strong class="text-dark">{{ $s->nama_lengkap ?: $s->nama }}</strong>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block" style="font-size: 0.74rem;">Nama Panggilan:</span>
                                    <span class="text-dark">{{ $s->nama_panggilan ?? '-' }}</span>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block" style="font-size: 0.74rem;">Nomor Induk Siswa Nasional (NISN):</span>
                                    <span class="fw-semibold text-dark">{{ $s->nisn ?? 'Belum tercatat' }}</span>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block" style="font-size: 0.74rem;">Jenjang Pendidikan & Kelas:</span>
                                    <span class="text-dark">{{ $s->jenjang ?? 'SD' }} - Kelas {{ $s->kelas ?? '-' }}</span>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block" style="font-size: 0.74rem;">Tanggal Lahir:</span>
                                    <span class="text-dark">
                                        {{ $s->tanggal_lahir ? \Carbon\Carbon::parse($s->tanggal_lahir)->isoFormat('dddd, D MMMM Y') : '-' }}
                                    </span>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block" style="font-size: 0.74rem;">Terdaftar di Keluarga:</span>
                                    <span class="text-dark">{{ $keluarga->nama_keluarga ?? 'Keluarga Siswa' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Kartu Profil & Tombol Aksi Cepat -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2">
                            <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.78rem;">
                                <i class="bi bi-shield-check text-success fs-6"></i>
                                <span>Data tersinkronisasi otomatis dengan server Raccer Robotic.</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer,%20saya%20ingin%20konsultasi%20mengenai%20jadwal%20dan%20paket%20anak%20saya%20({{ urlencode($s->nama_lengkap ?? $s->nama) }})" 
                                   target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-whatsapp me-1"></i> Konsultasi Paket Anak
                                </a>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1" style="background-color: var(--primary-blue); font-size: 0.8rem;" 
                                        data-bs-toggle="modal" data-bs-target="#modalDetailAnak{{ $sId }}">
                                    <i class="bi bi-eye me-1"></i> Modal Ringkas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Daftar Ringkas Seluruh Anak dalam Keluarga -->
        <div class="portal-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 36px; height: 36px; background-color: var(--primary-blue);">
                        <i class="bi bi-backpack4 fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Daftar Siswa Terhubung</h5>
                        <small class="text-muted" style="font-size: 0.78rem;">Semua anak yang berada di bawah naungan akun keluarga Anda</small>
                    </div>
                </div>
                <span class="badge bg-light text-secondary border rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.75rem;">
                    {{ $siswaList->count() }} Anak Terdaftar
                </span>
            </div>

            @if($siswaList->isNotEmpty())
                <div class="row g-3">
                    @foreach($siswaList as $s)
                        @php
                            $totalSesi = (int) ($s->total_sesi ?? 12);
                            $terpakai = (int) ($s->sesi_terpakai ?? 0);
                            $sisa = max(0, $totalSesi - $terpakai);
                            $persen = ($totalSesi > 0) ? min(100, round(($terpakai / $totalSesi) * 100, 1)) : 0;
                            $isLunas = ($s->status_pembayaran ?? 'Lunas') === 'Lunas';
                        @endphp
                        <div class="col-md-6">
                            <div class="card border rounded-3 p-3 bg-light h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-white text-primary border shadow-sm d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 38px; height: 38px; font-size: 0.85rem;">
                                            {{ strtoupper(substr($s->nama_lengkap ?? $s->nama ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">
                                                {{ $s->nama_lengkap ?? $s->nama }}
                                                @if(!empty($s->nama_panggilan))
                                                    <span class="text-muted fw-normal">({{ $s->nama_panggilan }})</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                {{ $s->jenjang ?? 'SD' }} &bull; Kelas {{ $s->kelas ?? '3' }}
                                                @if(!empty($s->nisn))
                                                    &bull; NISN: {{ $s->nisn }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge {{ $isLunas ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }} rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        <i class="bi {{ $isLunas ? 'bi-check-circle' : 'bi-clock-history' }} me-1"></i>
                                        {{ $s->status_pembayaran ?? 'Lunas' }}
                                    </span>
                                </div>

                                <hr class="my-2 border-secondary-subtle">

                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.78rem;">
                                        <span class="text-muted">Paket: <strong>{{ $s->paket_belajar ?? 'Junior Robotics' }}</strong></span>
                                        <span class="fw-semibold text-dark">{{ $terpakai }} / {{ $totalSesi }} Sesi (Sisa: {{ $sisa }})</span>
                                    </div>
                                    <div class="progress" style="height: 6px; background-color: #e2e8f0; border-radius: 10px;">
                                        <div class="progress-bar progress-bar-racer" role="progressbar" style="width: {{ $persen }}%;" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2 pt-1" style="font-size: 0.76rem;">
                                    <span class="badge bg-white text-secondary border rounded-pill px-2 py-1">
                                        Status: {{ $s->status_paket ?? 'Aktif' }}
                                    </span>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-2 py-0" style="font-size: 0.72rem; background-color: var(--primary-blue);" onclick="switchActiveChild('{{ $s->id_siswa ?? $s->id }}', document.querySelector('.child-switcher-btn[data-child-id=\'{{ $s->id_siswa ?? $s->id }}\']'))">
                                            <i class="bi bi-person-check-fill me-1"></i>Pilih Anak Ini
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#modalDetailAnak{{ $s->id_siswa ?? $s->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Modals Detail Profil Anak -->
                @foreach($siswaList as $s)
                    <div class="modal fade" id="modalDetailAnak{{ $s->id_siswa ?? $s->id }}" tabindex="-1" aria-labelledby="modalDetailAnakLabel{{ $s->id_siswa ?? $s->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 p-3">
                                <div class="modal-header border-0 pb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-badge fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $s->nama_lengkap ?? $s->nama }}</h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">NISN: {{ $s->nisn ?? '-' }}</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-2" style="font-size: 0.84rem;">
                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <span class="text-muted d-block" style="font-size: 0.72rem;">Jenjang & Kelas:</span>
                                                <strong>{{ $s->jenjang ?? 'SD' }} - Kelas {{ $s->kelas ?? '-' }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <span class="text-muted d-block" style="font-size: 0.72rem;">Tanggal Lahir:</span>
                                                <strong>{{ $s->tanggal_lahir ? \Carbon\Carbon::parse($s->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <span class="text-muted d-block" style="font-size: 0.72rem;">Status Siswa:</span>
                                                <span class="badge bg-success-subtle text-success">{{ $s->status_siswa ?? 'Aktif' }}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="text-muted d-block" style="font-size: 0.72rem;">Status Pembayaran:</span>
                                                <span class="badge bg-primary-subtle text-primary">{{ $s->status_pembayaran ?? 'Lunas' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border rounded-3 p-3 bg-white">
                                        <div class="fw-bold text-dark mb-1" style="font-size: 0.82rem;">Informasi Paket Belajar</div>
                                        <div class="text-muted mb-2" style="font-size: 0.78rem;">{{ $s->paket_belajar ?? 'Junior Robotics' }}</div>
                                        <div class="d-flex justify-content-between align-items-center" style="font-size: 0.76rem;">
                                            <span>Total Sesi: <strong>{{ $s->total_sesi ?? 12 }} Pertemuan</strong></span>
                                            <span>Sesi Terpakai: <strong>{{ $s->sesi_terpakai ?? 0 }} Sesi</strong></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                    Belum ada data siswa terdaftar di bawah keluarga ini.
                </div>
            @endif
        </div>

        <!-- Section 4: Tombol Bantuan & Logout -->
        <div class="portal-card p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Butuh Bantuan Administrasi atau Jadwal?</h6>
                <small class="text-muted" style="font-size: 0.8rem;">Hubungi Customer Support Racer Robotic jika ada kendala paket belajar atau presensi anak.</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer,%20saya%20wali%20dari%20{{ urlencode($keluarga->nama_keluarga ?? 'Siswa') }}"
                    target="_blank" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1" style="font-size: 0.84rem;">
                    <i class="bi bi-whatsapp"></i> Chat Admin WA
                </a>
                <button type="button" class="btn btn-danger rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#modalLogout" style="font-size: 0.84rem;">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </div>
        </div>

    </main>

    <!-- Modal Edit Kontak & Domisili Keluarga -->
    <div class="modal fade" id="modalEditKontakDomisili" tabindex="-1" aria-labelledby="modalEditKontakDomisiliLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 p-3 shadow-lg">
                <div class="modal-header border-0 pb-1">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" id="modalEditKontakDomisiliLabel">Ubah Kontak & Domisili Keluarga</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Validasi kepemilikan sesi di server aktif</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('orang-tua.profil.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body pt-2" style="font-size: 0.85rem;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">
                                Nomor WhatsApp Anda (Aktif) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-whatsapp text-success"></i></span>
                                <input type="text" name="nomor_whatsapp_sendiri" class="form-control"
                                    value="{{ old('nomor_whatsapp_sendiri', $orangTua->nomor_whatsapp ?? session('whatsapp_wali') ?? '') }}"
                                    placeholder="Contoh: 081298765432" required>
                            </div>
                            <small class="text-muted" style="font-size: 0.72rem;">Format sah: diawali 08, 62, atau +62 (9-16 digit).</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">
                                Alamat Domisili Keluarga <span class="text-danger">*</span>
                            </label>
                            <textarea name="alamat_domisili" class="form-control" rows="2"
                                placeholder="Jalan, nomor rumah, RT/RW..." required>{{ old('alamat_domisili', $keluarga->alamat_domisili ?? '') }}</textarea>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">Kota / Kabupaten</label>
                                <input type="text" name="kota_kabupaten" class="form-control form-control-sm"
                                    value="{{ old('kota_kabupaten', $keluarga->kota_kabupaten ?? '') }}" placeholder="Contoh: Jakarta Selatan">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control form-control-sm"
                                    value="{{ old('kecamatan', $keluarga->kecamatan ?? '') }}" placeholder="Contoh: Tebet">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">Kelurahan</label>
                                <input type="text" name="kelurahan" class="form-control form-control-sm"
                                    value="{{ old('kelurahan', $keluarga->kelurahan ?? '') }}" placeholder="Contoh: Tebet Barat">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-3 py-1 text-secondary" data-bs-dismiss="modal" style="font-size: 0.84rem;">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-1 fw-semibold shadow-sm" style="background-color: var(--primary-blue); font-size: 0.84rem;">
                            <i class="bi bi-save me-1"></i> Simpan Kontak & Domisili
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Kelola / Tambah Secondary Parent -->
    <div class="modal fade" id="modalEditSecondaryParent" tabindex="-1" aria-labelledby="modalEditSecondaryParentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 p-3 shadow-lg">
                <div class="modal-header border-0 pb-1">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white p-2" style="width: 40px; height: 40px; background-color: #6366f1;">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" id="modalEditSecondaryParentLabel">
                                {{ $secondaryParent ? 'Kelola Wali Pendamping (Secondary Parent)' : 'Tambah Wali Pendamping (Secondary Parent)' }}
                            </h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Maksimal 1 orang tua pendamping per keluarga</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('orang-tua.secondary-parent.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body pt-2" style="font-size: 0.85rem;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">
                                Nama Lengkap Wali Pendamping <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_secondary_parent" class="form-control"
                                value="{{ old('nama_secondary_parent', $secondaryParent->nama_lengkap ?? '') }}"
                                placeholder="Contoh: Bpk. Bambang Pratama" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">
                                    Hubungan <span class="text-danger">*</span>
                                </label>
                                <select name="hubungan_secondary_parent" class="form-select form-select-sm" required>
                                    @php $currentHub = old('hubungan_secondary_parent', $secondaryParent->hubungan ?? 'Ayah'); @endphp
                                    <option value="Ayah" {{ $currentHub === 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="Ibu" {{ $currentHub === 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="Wali" {{ $currentHub === 'Wali' ? 'selected' : '' }}>Wali</option>
                                    <option value="Kakek" {{ $currentHub === 'Kakek' ? 'selected' : '' }}>Kakek</option>
                                    <option value="Nenek" {{ $currentHub === 'Nenek' ? 'selected' : '' }}>Nenek</option>
                                    <option value="Paman" {{ $currentHub === 'Paman' ? 'selected' : '' }}>Paman</option>
                                    <option value="Bibi" {{ $currentHub === 'Bibi' ? 'selected' : '' }}>Bibi</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">
                                    No. WhatsApp <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nomor_whatsapp_secondary_parent" class="form-control form-control-sm"
                                    value="{{ old('nomor_whatsapp_secondary_parent', $secondaryParent->nomor_whatsapp ?? '') }}"
                                    placeholder="0812xxxxxxx" required>
                            </div>
                        </div>

                        @if(!$secondaryParent)
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <span class="fw-bold text-dark d-block mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-shield-lock-fill text-primary me-1"></i>Kredensial Akun Login Wali Pendamping
                                </span>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Username (Opsional)</label>
                                        <input type="text" name="username_secondary_parent" class="form-control form-control-sm font-monospace" placeholder="ayah.kenzo">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Password (Opsional)</label>
                                        <input type="password" name="password_secondary_parent" class="form-control form-control-sm" placeholder="Minimal 6 karakter">
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.8rem;">
                                    Ganti Password Akun (Opsional)
                                </label>
                                <input type="password" name="password_secondary_parent" class="form-control form-control-sm" placeholder="Kosongkan jika tidak ingin mengubah password">
                            </div>

                            <div class="form-check p-3 bg-danger-subtle rounded-3 border border-danger-subtle">
                                <input class="form-check-input text-danger" type="checkbox" name="hapus_secondary_parent" value="1" id="checkHapusSecondary">
                                <label class="form-check-label text-danger fw-semibold" for="checkHapusSecondary" style="font-size: 0.8rem;">
                                    Hapus / Lepaskan Wali Pendamping ini dari Akun Keluarga
                                </label>
                                <small class="d-block text-muted" style="font-size: 0.72rem;">Tindakan ini akan menghapus akses login wali pendamping.</small>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-3 py-1 text-secondary" data-bs-dismiss="modal" style="font-size: 0.84rem;">Batal</button>
                        <button type="submit" class="btn text-white rounded-pill px-4 py-1 fw-semibold shadow-sm" style="background-color: #6366f1; font-size: 0.84rem;">
                            <i class="bi bi-save me-1"></i> Simpan Data Secondary Parent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content rounded-4 border-0 shadow-lg text-center p-4">
                <div class="modal-body p-2">
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px; background-color: #fee2e2; color: #dc2626;">
                            <i class="bi bi-box-arrow-right fs-2"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2" id="modalLogoutLabel">Konfirmasi Keluar</h5>
                    <p class="text-muted mb-4" style="font-size: 0.85rem;">
                        Apakah Anda yakin ingin keluar dari Portal Orang Tua? Sesi login Anda akan ditutup secara aman.
                    </p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4 py-2 text-secondary fw-semibold"
                                data-bs-dismiss="modal" style="font-size: 0.85rem;">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm"
                                style="font-size: 0.85rem;">
                                Ya, Keluar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mencegah cache browser (bfcache) saat tombol Back ditekan
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Child Switcher: Pergantian Antar-Anak Tanpa Login Ulang (Fahriel Aziz Satrio)
        function switchActiveChild(childId, btnElement) {
            if (!childId) return;

            // 1. Update status aktif tombol di container switcher
            document.querySelectorAll('.child-switcher-btn').forEach(btn => {
                btn.classList.remove('active', 'btn-primary', 'shadow-sm');
                btn.classList.add('btn-light', 'border', 'bg-white', 'text-dark');
                const avatar = btn.querySelector('.rounded-circle');
                if (avatar) {
                    avatar.classList.remove('bg-white', 'text-primary');
                    avatar.classList.add('bg-primary-subtle', 'text-primary');
                }
                const checkIcon = btn.querySelector('.bi-check-circle-fill');
                if (checkIcon) checkIcon.remove();
            });

            // Target tombol switcher yang sesuai
            const activeBtn = btnElement || document.querySelector(`.child-switcher-btn[data-child-id="${childId}"]`);
            if (activeBtn) {
                activeBtn.classList.add('active', 'btn-primary', 'shadow-sm');
                activeBtn.classList.remove('btn-light', 'border', 'bg-white', 'text-dark');
                const avatar = activeBtn.querySelector('.rounded-circle');
                if (avatar) {
                    avatar.classList.add('bg-white', 'text-primary');
                    avatar.classList.remove('bg-primary-subtle', 'text-primary');
                }
                if (!activeBtn.querySelector('.bi-check-circle-fill')) {
                    const icon = document.createElement('i');
                    icon.className = 'bi bi-check-circle-fill ms-auto fs-5 text-white';
                    activeBtn.appendChild(icon);
                }
            }

            // 2. Tampilkan panel profil anak terpilih, sembunyikan yang lain
            document.querySelectorAll('.child-profile-panel').forEach(panel => {
                panel.classList.add('d-none');
            });
            const targetPanel = document.getElementById('panelProfilAnak' + childId);
            if (targetPanel) {
                targetPanel.classList.remove('d-none');
                targetPanel.style.opacity = '0';
                setTimeout(() => {
                    targetPanel.style.transition = 'opacity 0.2s ease-in-out';
                    targetPanel.style.opacity = '1';
                }, 10);
            }

            // 3. Simpan state anak terpilih di URL query parameter & localStorage
            try {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('anak', childId);
                window.history.replaceState({ childId: childId }, '', currentUrl.toString());
                localStorage.setItem('raccer_selected_child_id', childId);
            } catch (e) {}
        }

        // Otomatis aktifkan anak sesuai query URL atau localStorage saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const savedChildId = urlParams.get('anak') || urlParams.get('siswa') || localStorage.getItem('raccer_selected_child_id');
            if (savedChildId) {
                const targetBtn = document.querySelector(`.child-switcher-btn[data-child-id="${savedChildId}"]`);
                if (targetBtn) {
                    switchActiveChild(savedChildId, targetBtn);
                }
            }
        });
    </script>
</body>

</html>

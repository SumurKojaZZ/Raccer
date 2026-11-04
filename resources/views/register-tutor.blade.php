<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Tutor - RACER ROBOTIC</title>
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
            --primary-hover: #164ec4;
            --bg-canvas: #f4f6fa;
            --card-border: #e6ebf3;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-canvas);
            font-family: var(--bs-body-font-family);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 0.9rem;
        }

        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 2rem;
        }

        .logo-box {
            width: 38px;
            height: 38px;
            background: var(--primary-blue);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(30, 94, 230, 0.25);
        }

        .card-custom {
            background-color: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            max-width: 480px;
            width: 100%;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.82rem;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        .form-control,
        .form-select {
            font-size: 0.88rem;
            padding: 0.6rem 0.85rem;
            border-color: #cbd5e1;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(30, 94, 230, 0.15);
        }

        .btn-primary-custom {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: #ffffff;
            font-weight: 600;
            padding: 0.65rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #ffffff;
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .toggle-password-btn:hover {
            color: #475569;
        }

        footer {
            margin-top: auto;
            border-top: 1px solid #e2e8f0;
            background-color: #ffffff;
            padding: 1rem 2rem;
            font-size: 0.78rem;
            color: var(--text-muted);
        }
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <header class="navbar-custom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="logo-box">
                <i class="bi bi-robot"></i>
            </div>
            <div>
                <span class="fw-bold d-block text-dark" style="font-size: 0.95rem; letter-spacing: -0.2px;">RACER ROBOTIC</span>
                <small class="text-muted" style="font-size: 0.72rem;">Registrasi Tutor</small>
            </div>
        </div>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.82rem;">
            Masuk ke Akun
        </a>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center p-3 py-5">
        @if ($isSuccess)
            <!-- PANEL 2: Registrasi Berhasil (Menunggu Persetujuan Admin) -->
            <div class="card-custom p-4 p-md-5 text-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 70px; height: 70px; background-color: #fef3c7; color: #d97706;">
                    <i class="bi bi-clock-history fs-1"></i>
                </div>

                <h4 class="fw-bold text-dark mb-3">Pendaftaran Berhasil!</h4>

                <div class="py-2 px-3 mb-3 border-0 rounded-3 text-center"
                    style="background-color: #fef9c3; color: #854d0e; font-size: 0.86rem; font-weight: 600;">
                    Status: Nonaktif (Menunggu Persetujuan Admin)
                </div>

                <p class="text-muted mb-4" style="font-size: 0.88rem; line-height: 1.6;">
                    Akun Anda dengan username <strong class="text-dark">{{ $registeredUsername ? '@' . ltrim($registeredUsername, '@') : '@budi.santoso' }}</strong> telah tersimpan di sistem. Tim Admin Akademik akan meninjau data Anda dan menghubungi via WhatsApp sebelum mengaktifkan akun mengajar.
                </p>

                <a href="{{ route('login') }}" class="btn btn-light border bg-white text-secondary fw-semibold w-100 py-2 rounded-3 mb-4 shadow-sm" style="font-size: 0.88rem;">
                    Kembali ke Halaman Masuk
                </a>

                <div class="text-center text-muted" style="font-size: 0.82rem;">
                    <span class="d-block mb-1">Butuh konfirmasi cepat?</span>
                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Racer%20Robotic,%20saya%20sudah%20mendaftar%20akun%20tutor%20dengan%20username%20{{ $registeredUsername }}"
                        target="_blank" class="text-decoration-none fw-semibold" style="color: #10b981;">
                        <i class="bi bi-whatsapp me-1"></i> Hubungi Admin WhatsApp
                    </a>
                </div>
            </div>
        @else
            <!-- PANEL 1: Form Registrasi Akun Tutor -->
            <div class="card-custom p-4 p-md-5">
                <h4 class="fw-bold text-dark mb-1">Daftar Akun Tutor</h4>
                <p class="text-muted mb-4" style="font-size: 0.86rem;">
                    Lengkapi data di bawah untuk mengajukan akun pengajar di Racer Robotic.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 rounded-3 mb-3" style="font-size: 0.84rem;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('tutor.register.store') }}">
                    @csrf

                    <!-- Nama Lengkap & Gelar -->
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                            placeholder="Budi Santoso, S.T." value="{{ old('nama_lengkap') }}" required autofocus>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            placeholder="budi.santoso" value="{{ old('username') }}" required>
                        <div class="text-muted mt-1" style="font-size: 0.74rem;">Gunakan huruf kecil tanpa spasi</div>
                    </div>

                    <!-- Nomor WhatsApp Aktif -->
                    <div class="mb-3">
                        <label class="form-label">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                        <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                            placeholder="0812-3456-7890" value="{{ old('whatsapp') }}" required>
                    </div>

                    <!-- Pilihan Spesialisasi Modul -->
                    <div class="mb-3">
                        <label class="form-label">Pilihan Spesialisasi Modul <span class="text-danger">*</span></label>
                        <select name="spesialisasi" class="form-select @error('spesialisasi') is-invalid @enderror" required>
                            <option value="" disabled {{ old('spesialisasi') ? '' : 'selected' }}>Pilih spesialisasi modul</option>
                            <option value="Level 1: Building (TK/SD)" {{ old('spesialisasi') == 'Level 1: Building (TK/SD)' ? 'selected' : '' }}>Level 1: Building (TK/SD)</option>
                            <option value="Level 2: Sensor (SD/SMP)" {{ old('spesialisasi') == 'Level 2: Sensor (SD/SMP)' ? 'selected' : '' }}>Level 2: Sensor (SD/SMP)</option>
                            <option value="Level 3: Controller (SMP)" {{ old('spesialisasi') == 'Level 3: Controller (SMP)' ? 'selected' : '' }}>Level 3: Controller (SMP)</option>
                            <option value="Scratch & AI Robotics" {{ old('spesialisasi') == 'Scratch & AI Robotics' ? 'selected' : '' }}>Scratch & AI Robotics</option>
                        </select>
                    </div>

                    <!-- Kata Sandi -->
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" id="inputPassword" name="password"
                                class="form-control pe-5 @error('password') is-invalid @enderror"
                                placeholder="Masukkan kata sandi" required>
                            <button type="button" class="toggle-password-btn" onclick="togglePass('inputPassword', 'iconPass')">
                                <i class="bi bi-eye" id="iconPass"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Kata Sandi -->
                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" id="inputConfirmPassword" name="password_confirmation"
                                class="form-control pe-5" placeholder="Ulangi kata sandi" required>
                            <button type="button" class="toggle-password-btn" onclick="togglePass('inputConfirmPassword', 'iconConfirmPass')">
                                <i class="bi bi-eye" id="iconConfirmPass"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Daftar -->
                    <button type="submit" class="btn btn-primary-custom w-100 shadow-sm py-2">
                        Daftar Akun Tutor
                    </button>

                    <!-- Link Masuk -->
                    <div class="text-center mt-3 text-muted" style="font-size: 0.84rem;">
                        Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Masuk di sini</a>
                    </div>
                </form>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div>© 2026 RACER ROBOTIC. Bertumbuh bersama teknologi.</div>
        <div>Semua jadwal dalam Waktu Indonesia Barat (WIB)</div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input && icon) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        }
    </script>
</body>

</html>


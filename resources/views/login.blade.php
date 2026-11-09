<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Racer Robotic</title>
    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
            position: relative;
        }

        /* Tombol Kembali ke Beranda di Pojok Kiri Atas */
        .btn-back-home {
            position: absolute;
            top: 24px;
            left: 28px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 0.6rem 1.25rem;
            border-radius: 50rem;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            z-index: 10;
        }

        .btn-back-home:hover {
            color: #ffffff;
            background: rgba(13, 110, 253, 0.6);
            border-color: #0d6efd;
            transform: translateX(-3px);
        }

        /* Overlay lembut agar background tetap terlihat dan fokus ke form */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 20, 40, 0.45);
            z-index: 0;
        }

        /* Wrapper Form Dibuat Jauh Lebih Lebar & Tegas */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 540px;
            /* Form diperlebar agar terlihat gagah dan nyaman */
        }

        .login-brand-title {
            font-size: 2.4rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .login-brand-sub {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.5;
        }

        /* Card Login Luas & Elegan */
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.7);
            padding: 3rem 2.75rem;
        }

        .login-title {
            font-size: 1.85rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
            margin-bottom: 0.25rem;
        }

        .login-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 400;
            margin-bottom: 1.75rem;
        }

        /* Label Teks Besar & Jelas */
        .form-label-custom {
            font-size: 1.05rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.55rem;
            display: block;
        }

        /* Input Field Lebih Tinggi & Teks Jelas */
        .form-control-custom {
            font-size: 1.08rem;
            padding: 0.85rem 1.25rem;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
        }

        /* Tombol Masuk Besar & Mantap */
        .btn-login-submit {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 0.85rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(13, 110, 253, 0.35);
            transition: all 0.2s ease;
            color: #fff;
        }

        .btn-login-submit:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.45);
        }

        .login-link {
            font-size: 1rem;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 2.25rem 1.75rem;
            }

            .login-brand-title {
                font-size: 1.95rem;
            }

            .login-title {
                font-size: 1.85rem;
            }

            .form-control-custom {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Tombol Kembali ke Beranda di Pojok Kiri Atas -->
    <a href="{{ route('home') }}" class="btn-back-home" title="Kembali ke Beranda">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Beranda</span>
    </a>

    <div class="login-wrapper">

        <!-- Header Besar & Jelas di Atas Card -->
        <div class="text-center text-white mb-4">
            <h1 class="login-brand-title mb-2">RACER ROBOTIC</h1>
            <p class="login-brand-sub mb-0">Membangun Generasi AI & Robotika Masa Depan</p>
        </div>

        <!-- Card Form Login yang Lebih Gede & Lapang -->
        <div class="login-card">
            <div class="text-center">
                <h2 class="login-title">Login</h2>
                <p class="login-subtitle">Silakan masukkan akun anda untuk melanjutkan</p>
            </div>

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="alert alert-success py-3 px-3 rounded-3 mb-4" style="font-size: 0.95rem;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="alert alert-danger py-3 px-3 rounded-3 mb-4" style="font-size: 0.95rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Input Username -->
                <div class="mb-4">
                    <label for="username" class="form-label-custom">Username</label>
                    <input type="text" class="form-control form-control-custom @error('username') is-invalid @enderror"
                        id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username"
                        required autofocus>
                </div>

                <!-- Input Password -->
                <div class="mb-4">
                    <label for="password" class="form-label-custom">Password</label>
                    <input type="password" class="form-control form-control-custom" id="password" name="password"
                        placeholder="Masukkan password" required>
                </div>

                <!-- Tombol Masuk -->
                <button type="submit" class="btn btn-login-submit w-100 mt-2">
                    Masuk
                </button>

                <!-- Lupa Password -->
                <div class="text-center mt-3 pt-1">
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-muted login-link">
                        Lupa Password?
                    </a>
                </div>

                <!-- Daftar Tutor -->
                <div class="text-center mt-4 pt-3 border-top">
                    <span class="text-muted login-link">Ingin mengajar? </span>
                    <a href="{{ route('tutor.register') }}"
                        class="text-decoration-none fw-bold login-link text-primary ms-1">
                        Daftar Akun Tutor
                    </a>
                </div>

                <!-- Kembali ke Beranda -->
                <div class="text-center mt-3 pt-1">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted login-link d-inline-flex align-items-center gap-1" style="font-size: 0.92rem;">
                        <i class="bi bi-house-door"></i> Kembali ke Beranda
                    </a>
                </div>
            </form>
        </div>

    </div>

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
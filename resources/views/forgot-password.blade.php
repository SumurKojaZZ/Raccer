<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100 m-0">
            <!-- Kolom Kiri -->
            <div class="col-12 col-md-7 d-flex flex-column justify-content-center align-items-start text-start text-white p-4 p-md-5 left">
                <h1 class="display-5 fw-bold mb-3">Membangun Generasi AI & Robotika Masa Depan</h1>
                <p class="fs-6 fw-light w-75">Platform manajemen pembelajaran kelas STEM,
                    inventaris robotik, dan pendampingan sekolah mitra.</p>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-12 col-md-5 d-flex flex-column justify-content-center align-items-center p-4 p-md-5 right">
                <div class="w-100" style="max-width: 500px;">
                    <h2 class="fw-bold mb-2 text-center">Lupa Password</h2>
                    <p class="text-muted text-center mb-4">Masukkan email Anda, kami akan kirim link reset password.</p>

                    {{-- Pesan sukses --}}
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@email.com" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                            Kirim Link Reset Password
                        </button>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                            &larr; Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


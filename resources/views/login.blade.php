<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <div
                class="col-12 col-md-7 d-flex flex-column justify-content-center align-items-start text-start text-white p-4 p-md-5 left">
                <h1 class="display-5 fw-bold mb-3">Membangun Generasi AI & Robotika Masa Depan</h1>
                <p class="fs-6 fw-light w-75">Platform manajemen pembelajaran kelas STEM,
                    inventaris
                    robotik,
                    dan pendampingan
                    sekolah mitra.</p>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-12 col-md-5 d-flex flex-column justify-content-center align-items-center p-4 p-md-5 right">
                <div class="w-100" style="max-width: 500px;">
                    <h2 class="fw-bold mb-4 text-center">Login</h2>

                    {{-- Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="text" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username') }}" placeholder="Username"
                                required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>

                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-muted small">
                                Lupa Password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
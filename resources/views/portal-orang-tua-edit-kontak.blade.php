<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kontak & Secondary Parent - Portal Orang Tua | RACER ROBOTIC</title>

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
    </style>
</head>

<body>
    <!-- Topbar Header -->
    <header class="portal-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('portal.orang-tua') }}" class="d-flex align-items-center gap-2 text-decoration-none">
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

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('portal.orang-tua') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.82rem;">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="container py-4 flex-grow-1" style="max-width: 1050px;">

        <!-- Alert Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div class="flex-grow-1" style="font-size: 0.92rem;">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <div class="flex-grow-1" style="font-size: 0.92rem;">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm p-3 mb-4" role="alert">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                    <strong>Terdapat beberapa kesalahan input:</strong>
                </div>
                <ul class="mb-0 ps-3" style="font-size: 0.88rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Banner Header Halaman -->
        <div class="portal-card p-4 mb-4 text-white position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #1570EF 0%, #1e40af 100%);">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 fw-bold shadow-sm" style="font-size: 0.75rem;">
                    <i class="bi bi-shield-check me-1"></i> Validasi Kepemilikan Sesi Aktif
                </span>
                <span class="badge rounded-pill px-3 py-1 fw-semibold text-white border border-light" style="font-size: 0.75rem; background-color: rgba(255, 255, 255, 0.2);">
                    {{ $keluarga->nama_keluarga ?? 'Akun Keluarga' }}
                </span>
            </div>
            <h3 class="fw-bold mb-1" style="letter-spacing: -0.3px;">
                Pengaturan Kontak & Secondary Parent
            </h3>
            <p class="mb-0 text-white-50" style="font-size: 0.88rem; max-width: 700px;">
                Perbarui nomor WhatsApp aktif Anda, alamat domisili keluarga, serta kelola wali pendamping (Secondary Parent) untuk kenyamanan koordinasi belajar robotika anak.
            </p>
        </div>

        <div class="row g-4">
            <!-- Form 1: Kontak WhatsApp Sendiri & Alamat Domisili Keluarga -->
            <div class="col-lg-6">
                <div class="portal-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                style="width: 36px; height: 36px; background-color: var(--primary-blue);">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Kontak & Domisili Keluarga</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Nomor WhatsApp Anda dan tempat tinggal</small>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('orang-tua.profil.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.84rem;">
                                Nama Lengkap Wali (Akun Anda)
                            </label>
                            <input type="text" class="form-control bg-light" value="{{ $orangTua->nama_lengkap ?? $user->name }}" readonly disabled>
                            <small class="text-muted" style="font-size: 0.72rem;">Peran: <strong>{{ ($jenisWali ?? 'Primary_Parent') === 'Secondary_Parent' ? 'Secondary Parent' : 'Primary Parent' }}</strong></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.84rem;">
                                Nomor WhatsApp Anda (Aktif) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-success border-end-0"><i class="bi bi-whatsapp"></i></span>
                                <input type="text" name="nomor_whatsapp_sendiri" class="form-control"
                                    value="{{ old('nomor_whatsapp_sendiri', $orangTua->nomor_whatsapp ?? session('whatsapp_wali') ?? '') }}"
                                    placeholder="Contoh: 081298765432" required>
                            </div>
                            <small class="text-muted" style="font-size: 0.72rem;">Wajib diawali 08, 62, atau +62 dengan 9-16 digit angka.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.84rem;">
                                Alamat Domisili Keluarga <span class="text-danger">*</span>
                            </label>
                            <textarea name="alamat_domisili" class="form-control" rows="3"
                                placeholder="Jalan, nomor rumah, RT/RW..." required>{{ old('alamat_domisili', $keluarga->alamat_domisili ?? '') }}</textarea>
                            <small class="text-muted" style="font-size: 0.72rem;">Digunakan untuk pengiriman kit modul / sertifikat robotika.</small>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.82rem;">Kota / Kabupaten</label>
                                <input type="text" name="kota_kabupaten" class="form-control form-control-sm"
                                    value="{{ old('kota_kabupaten', $keluarga->kota_kabupaten ?? '') }}" placeholder="Contoh: Jakarta Selatan">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.82rem;">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control form-control-sm"
                                    value="{{ old('kecamatan', $keluarga->kecamatan ?? '') }}" placeholder="Contoh: Kebayoran Baru">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.82rem;">Kelurahan</label>
                                <input type="text" name="kelurahan" class="form-control form-control-sm"
                                    value="{{ old('kelurahan', $keluarga->kelurahan ?? '') }}" placeholder="Contoh: Selong">
                            </div>
                        </div>

                        <div class="pt-2 text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm" style="background-color: var(--primary-blue); font-size: 0.85rem;">
                                <i class="bi bi-save me-1"></i> Simpan Kontak & Domisili
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form 2: Pengelolaan Secondary Parent (Wali Pendamping) -->
            <div class="col-lg-6">
                <div class="portal-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                style="width: 36px; height: 36px; background-color: var(--indigo-accent);">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 1rem;">Wali Pendamping (Secondary Parent)</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Pasangan atau wali kedua dalam keluarga</small>
                            </div>
                        </div>
                        @if($secondaryParent)
                            <span class="badge badge-role-secondary rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                Terdaftar
                            </span>
                        @else
                            <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                Belum Ada
                            </span>
                        @endif
                    </div>

                    @if($secondaryParent)
                        <div class="alert alert-light border rounded-3 p-3 mb-3" style="font-size: 0.84rem;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-info-circle-fill text-indigo"></i>
                                <strong>Secondary Parent Terdaftar:</strong>
                            </div>
                            <div class="row g-2 mt-1">
                                <div class="col-6"><span class="text-muted d-block" style="font-size: 0.72rem;">Nama:</span><strong>{{ $secondaryParent->nama_lengkap }}</strong></div>
                                <div class="col-6"><span class="text-muted d-block" style="font-size: 0.72rem;">Hubungan:</span><strong>{{ $secondaryParent->hubungan }}</strong></div>
                                <div class="col-6"><span class="text-muted d-block" style="font-size: 0.72rem;">WhatsApp:</span><strong>{{ $secondaryParent->nomor_whatsapp }}</strong></div>
                                <div class="col-6"><span class="text-muted d-block" style="font-size: 0.72rem;">Status:</span><span class="badge bg-success-subtle text-success">Aktif</span></div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orang-tua.secondary-parent.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.84rem;">
                                Nama Lengkap Wali Pendamping <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_secondary_parent" class="form-control"
                                value="{{ old('nama_secondary_parent', $secondaryParent->nama_lengkap ?? '') }}"
                                placeholder="Contoh: Bpk. Bambang Pratama" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.82rem;">
                                    Hubungan <span class="text-danger">*</span>
                                </label>
                                <select name="hubungan_secondary_parent" class="form-select form-select-sm" required>
                                    @php $selectedHub = old('hubungan_secondary_parent', $secondaryParent->hubungan ?? 'Ayah'); @endphp
                                    <option value="Ayah" {{ $selectedHub === 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="Ibu" {{ $selectedHub === 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="Wali" {{ $selectedHub === 'Wali' ? 'selected' : '' }}>Wali</option>
                                    <option value="Kakek" {{ $selectedHub === 'Kakek' ? 'selected' : '' }}>Kakek</option>
                                    <option value="Nenek" {{ $selectedHub === 'Nenek' ? 'selected' : '' }}>Nenek</option>
                                    <option value="Paman" {{ $selectedHub === 'Paman' ? 'selected' : '' }}>Paman</option>
                                    <option value="Bibi" {{ $selectedHub === 'Bibi' ? 'selected' : '' }}>Bibi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.82rem;">
                                    No. WhatsApp <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nomor_whatsapp_secondary_parent" class="form-control form-control-sm"
                                    value="{{ old('nomor_whatsapp_secondary_parent', $secondaryParent->nomor_whatsapp ?? '') }}"
                                    placeholder="0812xxxxxxxx" required>
                            </div>
                        </div>

                        @if(!$secondaryParent)
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <span class="fw-bold text-dark d-block mb-1" style="font-size: 0.82rem;">
                                    <i class="bi bi-shield-lock-fill text-indigo me-1"></i>Akun Login Wali Pendamping
                                </span>
                                <small class="text-muted d-block mb-2" style="font-size: 0.74rem;">Opsional: Secondary Parent dapat login mandiri untuk memantau anak.</small>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Username</label>
                                        <input type="text" name="username_secondary_parent" class="form-control form-control-sm font-monospace"
                                            value="{{ old('username_secondary_parent') }}" placeholder="ayah.kenzo">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Password</label>
                                        <input type="password" name="password_secondary_parent" class="form-control form-control-sm" placeholder="Minimal 6 karakter">
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.82rem;">
                                    Ganti Password Akun Wali Pendamping (Opsional)
                                </label>
                                <input type="password" name="password_secondary_parent" class="form-control form-control-sm" placeholder="Kosongkan jika tidak ingin mengubah password">
                            </div>

                            <div class="p-3 bg-danger-subtle rounded-3 border border-danger-subtle mb-3">
                                <div class="form-check">
                                    <input class="form-check-input text-danger" type="checkbox" name="hapus_secondary_parent" value="1" id="pageCheckHapusSecondary">
                                    <label class="form-check-label text-danger fw-semibold" for="pageCheckHapusSecondary" style="font-size: 0.82rem;">
                                        Hapus / Lepaskan Wali Pendamping ini dari Akun Keluarga
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1 ps-4" style="font-size: 0.73rem;">
                                    Akses login wali pendamping akan dicabut secara permanen.
                                </small>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <a href="{{ route('portal.orang-tua') }}" class="btn btn-light rounded-pill px-3 py-2 text-secondary" style="font-size: 0.84rem;">
                                Batal
                            </a>
                            <button type="submit" class="btn text-white rounded-pill px-4 py-2 fw-semibold shadow-sm" style="background-color: var(--indigo-accent); font-size: 0.85rem;">
                                <i class="bi bi-save me-1"></i> Simpan Data Secondary Parent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

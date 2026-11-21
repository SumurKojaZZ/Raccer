<?php

namespace App\Http\Controllers;

use App\Models\AkunKeluarga;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Controller untuk Portal dan Layanan API Orang Tua / Wali Siswa.
 * Menangani akses daftar anak per keluarga dan detail profil anak
 * dengan proteksi validasi kepemilikan sesi di server (BOLA / IDOR Defense).
 *
 * Penanggung Jawab: Muhammad Fadhil Swarigading
 */
class OrangTuaController extends Controller
{
    /**
     * Resolusi ID Keluarga dari sesi pengguna yang sedang terautentikasi.
     * Mengambil secara berurutan dari:
     * 1. Session aktif ('id_keluarga')
     * 2. Model User jika memiliki atribut id_keluarga
     * 3. Tabel orang_tua berdasarkan id_pengguna atau nama_lengkap
     * 4. Tabel siswa jika pengguna yang masuk adalah akun siswa itu sendiri
     */
    public static function resolveKeluargaIdFromUser(?User $user): ?int
    {
        if (!$user) {
            return null;
        }

        // 1. Cek dari Session aktif
        if (session()->has('id_keluarga') && is_numeric(session('id_keluarga'))) {
            return (int) session('id_keluarga');
        }

        // 2. Cek atribut langsung pada model User
        if (!empty($user->id_keluarga) && is_numeric($user->id_keluarga)) {
            return (int) $user->id_keluarga;
        }

        $userId = $user->id_pengguna ?? $user->id;

        // 3. Cek dari tabel orang_tua berdasarkan id_pengguna
        if (Schema::hasTable('orang_tua') && $userId) {
            $idKeluarga = DB::table('orang_tua')
                ->where('id_pengguna', $userId)
                ->value('id_keluarga');

            if ($idKeluarga) {
                return (int) $idKeluarga;
            }
        }

        // 4. Cek dari tabel orang_tua berdasarkan nama_lengkap
        if (Schema::hasTable('orang_tua') && !empty($user->name)) {
            $idKeluarga = DB::table('orang_tua')
                ->where('nama_lengkap', $user->name)
                ->value('id_keluarga');

            if ($idKeluarga) {
                return (int) $idKeluarga;
            }
        }

        // 5. Cek dari tabel siswa jika yang login adalah Siswa
        if (Schema::hasTable('siswa') && $userId) {
            $idKeluarga = DB::table('siswa')
                ->where('id_pengguna', $userId)
                ->value('id_keluarga');

            if ($idKeluarga) {
                return (int) $idKeluarga;
            }
        }

        return null;
    }

    /**
     * Memeriksa apakah pengguna memiliki hak akses terhadap Akun Keluarga tertentu.
     * Aturan:
     * - Admin memiliki akses penuh (true).
     * - Orang_Tua hanya boleh mengakses keluarga miliknya sendiri (sesi cocok).
     * - Siswa hanya boleh mengakses keluarga miliknya sendiri.
     * - Role lain ditolak (false).
     */
    public static function authorizeKeluargaAccess(?User $user, int $targetKeluargaId): bool
    {
        if (!$user) {
            return false;
        }

        $role = session('role') ?? $user->role ?? null;
        $roleNormalized = strtolower((string) $role);

        // 1. Admin memiliki hak akses manajemen ke semua keluarga
        if (in_array($roleNormalized, ['admin', 'superadmin', 'administrator'])) {
            return true;
        }

        // 2. Orang Tua / Wali wajib memiliki kesesuaian ID Keluarga sesi
        if (in_array($roleNormalized, ['orang_tua', 'orang tua', 'wali', 'parent'])) {
            $userKeluargaId = self::resolveKeluargaIdFromUser($user);
            return $userKeluargaId !== null && $userKeluargaId === $targetKeluargaId;
        }

        // 3. Siswa hanya boleh jika keluarga sesuai
        if (in_array($roleNormalized, ['siswa', 'student'])) {
            $userKeluargaId = self::resolveKeluargaIdFromUser($user);
            return $userKeluargaId !== null && $userKeluargaId === $targetKeluargaId;
        }

        return false;
    }

    /**
     * Memeriksa apakah pengguna memiliki hak akses terhadap profil Siswa tertentu.
     */
    public static function authorizeSiswaAccess(?User $user, Siswa $siswa): bool
    {
        if (!$user) {
            return false;
        }

        $role = session('role') ?? $user->role ?? null;
        $roleNormalized = strtolower((string) $role);

        // 1. Admin selalu diizinkan
        if (in_array($roleNormalized, ['admin', 'superadmin', 'administrator'])) {
            return true;
        }

        // 2. Orang Tua: Anak wajib terdaftar di bawah ID Keluarga yang sama dengan sesi orang tua
        if (in_array($roleNormalized, ['orang_tua', 'orang tua', 'wali', 'parent'])) {
            $userKeluargaId = self::resolveKeluargaIdFromUser($user);
            return $userKeluargaId !== null && (int) $siswa->id_keluarga === (int) $userKeluargaId;
        }

        // 3. Siswa: Hanya boleh melihat profil miliknya sendiri
        if (in_array($roleNormalized, ['siswa', 'student'])) {
            $userId = $user->id_pengguna ?? $user->id;
            return $siswa->id_pengguna && (int) $siswa->id_pengguna === (int) $userId;
        }

        return false;
    }

    /**
     * Endpoint: Daftar Anak per ID Keluarga
     * GET /api/keluarga/{id}/anak
     * GET /api/keluarga/{id}/siswa
     * GET /api/admin/keluarga/{id}/siswa
     */
    public function getAnakPerKeluarga(Request $request, int $id): JsonResponse
    {
        // 1. Verifikasi Autentikasi Pengguna
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
            ], 401);
        }

        // 2. Cek Keberadaan Akun Keluarga di Database
        $keluarga = null;
        if (Schema::hasTable('akun_keluarga')) {
            $keluarga = AkunKeluarga::find($id);
        }

        // Fallback jika akun_keluarga tidak ada tapi ada record siswa
        $hasSiswa = Schema::hasTable('siswa') && Siswa::where('id_keluarga', $id)->exists();
        if (!$keluarga && !$hasSiswa) {
            return response()->json([
                'success' => false,
                'message' => "Akun Keluarga dengan ID {$id} tidak ditemukan.",
            ], 404);
        }

        // 3. Validasi Kepemilikan Sesi di Server (Session Ownership Verification)
        if (!self::authorizeKeluargaAccess($user, $id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya dapat mengakses daftar anak pada keluarga Anda sendiri.',
            ], 403);
        }

        // 4. Query Data Anak (Siswa) Terhubung
        $siswaQuery = Siswa::where('id_keluarga', $id);
        $siswaList = $siswaQuery->get();

        $namaKeluarga = $keluarga?->nama_keluarga;
        if (!$namaKeluarga && $siswaList->isNotEmpty()) {
            $firstSiswa = $siswaList->first();
            $namaKeluarga = $firstSiswa->nama_wali ? "Keluarga {$firstSiswa->nama_wali}" : "Keluarga #{$id}";
        }

        return response()->json([
            'success' => true,
            'id_keluarga' => (int) $id,
            'nama_keluarga' => $namaKeluarga,
            'total' => $siswaList->count(),
            'data' => $siswaList->map(function ($s) {
                $username = null;
                if ($s->id_pengguna && Schema::hasTable('pengguna')) {
                    $u = DB::table('pengguna')->where('id_pengguna', $s->id_pengguna)->first();
                    $username = $u->username ?? null;
                }

                $totalSesi = (int) ($s->total_sesi ?? 12);
                $sesiTerpakai = (int) ($s->sesi_terpakai ?? 0);
                $sisaSesi = max(0, $totalSesi - $sesiTerpakai);

                return [
                    'id_siswa' => $s->id_siswa ?? $s->id,
                    'id_keluarga' => (int) $s->id_keluarga,
                    'id_pengguna' => $s->id_pengguna ? (int) $s->id_pengguna : null,
                    'nisn' => $s->nisn,
                    'nama_lengkap' => $s->nama_lengkap ?: $s->nama,
                    'nama_panggilan' => $s->nama_panggilan,
                    'jenjang' => $s->jenjang,
                    'kelas' => $s->kelas,
                    'tanggal_lahir' => $s->tanggal_lahir,
                    'status_siswa' => $s->status_siswa ?? 'Aktif',
                    'username' => $username,
                    'paket_belajar' => $s->paket_belajar ?? 'Regular',
                    'program' => $s->program,
                    'total_sesi' => $totalSesi,
                    'sesi_terpakai' => $sesiTerpakai,
                    'sisa_sesi' => $sisaSesi,
                    'status_paket' => $s->status_paket ?? 'Aktif',
                    'status_pembayaran' => $s->status_pembayaran ?? 'Lunas',
                    'dibuat_pada' => $s->dibuat_pada ?? $s->created_at,
                ];
            }),
        ], 200);
    }

    /**
     * Endpoint: Detail Profil Anak Berdasarkan ID Siswa
     * GET /api/siswa/{id}
     * GET /api/anak/{id}
     * GET /api/keluarga/{id_keluarga}/anak/{id_siswa}
     * GET /api/keluarga/{id_keluarga}/siswa/{id_siswa}
     */
    public function getDetailAnak(Request $request, int $id, ?int $id_siswa = null): JsonResponse
    {
        // 1. Verifikasi Autentikasi Pengguna
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
            ], 401);
        }

        // Handle routing jika format URL adalah /keluarga/{id_keluarga}/anak/{id_siswa}
        $targetSiswaId = $id_siswa !== null ? $id_siswa : $id;
        $expectedKeluargaId = $id_siswa !== null ? $id : null;

        // 2. Cari Data Siswa di Basis Data
        $siswa = Siswa::where('id_siswa', $targetSiswaId)
            ->orWhere('id', $targetSiswaId)
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => "Data anak/siswa dengan ID {$targetSiswaId} tidak ditemukan.",
            ], 404);
        }

        // Jika URL menyertakan id_keluarga, pastikan anak memang anggota keluarga tersebut
        if ($expectedKeluargaId !== null && (int) $siswa->id_keluarga !== (int) $expectedKeluargaId) {
            return response()->json([
                'success' => false,
                'message' => "Siswa dengan ID {$targetSiswaId} bukan merupakan anggota dari Keluarga ID {$expectedKeluargaId}.",
            ], 404);
        }

        // 3. Validasi Kepemilikan Sesi di Server (Session Ownership Verification)
        if (!self::authorizeSiswaAccess($user, $siswa)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya dapat melihat detail profil anak dari keluarga Anda sendiri.',
            ], 403);
        }

        // 4. Ambil Username Akun Anak
        $username = null;
        if ($siswa->id_pengguna && Schema::hasTable('pengguna')) {
            $u = DB::table('pengguna')->where('id_pengguna', $siswa->id_pengguna)->first();
            $username = $u->username ?? null;
        }

        // 5. Ambil Informasi Keluarga & Orang Tua (Wali) Terkait
        $keluarga = null;
        if (Schema::hasTable('akun_keluarga')) {
            $keluarga = AkunKeluarga::find($siswa->id_keluarga);
        }

        $waliList = collect();
        if (Schema::hasTable('orang_tua')) {
            $waliList = OrangTua::where('id_keluarga', $siswa->id_keluarga)->get()->map(function ($w) {
                return [
                    'id_orang_tua' => (int) $w->id_orang_tua,
                    'nama_lengkap' => $w->nama_lengkap,
                    'hubungan' => $w->hubungan,
                    'jenis_wali' => $w->jenis_wali,
                    'nomor_whatsapp' => $w->nomor_whatsapp,
                ];
            });
        }

        $totalSesi = (int) ($siswa->total_sesi ?? 12);
        $sesiTerpakai = (int) ($siswa->sesi_terpakai ?? 0);
        $sisaSesi = max(0, $totalSesi - $sesiTerpakai);

        return response()->json([
            'success' => true,
            'message' => 'Detail profil anak berhasil dimuat.',
            'data' => [
                'id_siswa' => $siswa->id_siswa ?? $siswa->id,
                'id_keluarga' => (int) $siswa->id_keluarga,
                'id_pengguna' => $siswa->id_pengguna ? (int) $siswa->id_pengguna : null,
                'nisn' => $siswa->nisn,
                'nama_lengkap' => $siswa->nama_lengkap ?: $siswa->nama,
                'nama_panggilan' => $siswa->nama_panggilan,
                'jenjang' => $siswa->jenjang,
                'kelas' => $siswa->kelas,
                'tanggal_lahir' => $siswa->tanggal_lahir,
                'status_siswa' => $siswa->status_siswa ?? 'Aktif',
                'username' => $username,
                'paket_belajar' => $siswa->paket_belajar ?? 'Regular',
                'program' => $siswa->program,
                'total_sesi' => $totalSesi,
                'sesi_terpakai' => $sesiTerpakai,
                'sisa_sesi' => $sisaSesi,
                'status_paket' => $siswa->status_paket ?? 'Aktif',
                'status_pembayaran' => $siswa->status_pembayaran ?? 'Lunas',
                'dibuat_pada' => $siswa->dibuat_pada ?? $siswa->created_at,
                'keluarga' => [
                    'id_keluarga' => (int) $siswa->id_keluarga,
                    'nama_keluarga' => $keluarga?->nama_keluarga ?? ($siswa->nama_wali ? "Keluarga {$siswa->nama_wali}" : "Keluarga #{$siswa->id_keluarga}"),
                    'alamat_domisili' => $keluarga?->alamat_domisili,
                    'kelurahan' => $keluarga?->kelurahan,
                    'kecamatan' => $keluarga?->kecamatan,
                    'kota_kabupaten' => $keluarga?->kota_kabupaten,
                    'wali' => $waliList,
                ],
            ],
        ], 200);
    }

    /**
     * Endpoint PUT / PATCH: Pembaruan WhatsApp Sendiri, Alamat Domisili, dan Data Secondary Parent.
     * Backlog Pembaruan Data Kontak & Profil Keluarga oleh Orang Tua.
     *
     * Validasi Kepemilikan Sesi di Server:
     * - Tamu (unauthenticated) -> 401 Unauthenticated
     * - Non-Orang Tua / Non-Admin -> 403 Unauthorized
     * - Orang Tua mencoba update keluarga lain -> 403 Forbidden (BOLA / IDOR Defense)
     * - Nomor WhatsApp wajib diawali 08, 62, atau +62 dengan 9-16 digit angka
     * - Maksimal 1 Secondary Parent per keluarga
     *
     * Route:
     * PUT/PATCH /api/orang-tua/profil
     * PUT/PATCH /api/orang-tua/kontak-domisili
     * PUT/PATCH /api/keluarga/{id}/orang-tua
     * PUT/PATCH /api/keluarga/{id}/profil
     * PUT/PATCH /orang-tua/profil
     */
    public function updateProfilDanKeluarga(Request $request, ?int $id = null): JsonResponse|RedirectResponse
    {
        // 1. Verifikasi Autentikasi Pengguna
        $user = Auth::user();
        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
                ], 401);
            }
            return redirect()->route('login');
        }

        // 2. Verifikasi Peran Pengguna (Khusus Orang Tua atau Admin)
        $role = session('role') ?? $user->role ?? null;
        $roleNorm = strtolower((string) $role);
        $isOrangTua = in_array($roleNorm, ['orang_tua', 'orang tua', 'wali', 'parent']);
        $isAdmin = in_array($roleNorm, ['admin', 'superadmin', 'administrator']);

        if (!$isOrangTua && !$isAdmin) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Akses ini khusus untuk Orang Tua / Wali Siswa.',
                ], 403);
            }
            abort(403, 'Unauthorized. Akses ini khusus untuk Orang Tua / Wali Siswa.');
        }

        // 3. Validasi Kepemilikan Sesi di Server (Session Ownership Verification - BOLA / IDOR Defense)
        $sessionKeluargaId = self::resolveKeluargaIdFromUser($user);
        $targetKeluargaId = $id !== null ? (int) $id : $sessionKeluargaId;

        if (!$isAdmin) {
            if ($sessionKeluargaId === null || $targetKeluargaId !== $sessionKeluargaId) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya berhak memperbarui data profil keluarga Anda sendiri.',
                    ], 403);
                }
                abort(403, 'Unauthorized. Akses ditolak (HTTP 403). Anda hanya berhak memperbarui data profil keluarga Anda sendiri.');
            }
        }

        // 4. Validasi Input Server-Side (Allowlist Validation)
        $validated = $request->validate([
            // 4a. Nomor WhatsApp Sendiri
            'nomor_whatsapp' => [
                'nullable',
                'string',
                'max:25',
                'regex:/^(\+62|62|08)[0-9\-\s]{7,15}$/',
            ],
            'nomor_whatsapp_sendiri' => [
                'nullable',
                'string',
                'max:25',
                'regex:/^(\+62|62|08)[0-9\-\s]{7,15}$/',
            ],

            // 4b. Alamat Domisili Keluarga
            'alamat_domisili' => ['nullable', 'string', 'max:500'],
            'kelurahan' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kota_kabupaten' => ['nullable', 'string', 'max:100'],
            'nama_keluarga' => ['nullable', 'string', 'max:150'],

            // 4c. Secondary Parent (Objek bersarang)
            'secondary_parent' => ['nullable', 'array'],
            'secondary_parent.nama_lengkap' => ['nullable', 'string', 'max:150'],
            'secondary_parent.hubungan' => ['nullable', 'string', 'max:50'],
            'secondary_parent.nomor_whatsapp' => [
                'nullable',
                'string',
                'max:25',
                'regex:/^(\+62|62|08)[0-9\-\s]{7,15}$/',
            ],
            'secondary_parent.username' => ['nullable', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-Z0-9_\.\-]+$/'],
            'secondary_parent.password' => ['nullable', 'string', 'min:6'],

            // 4d. Secondary Parent (Flat Fields)
            'nama_secondary_parent' => ['nullable', 'string', 'max:150'],
            'hubungan_secondary_parent' => ['nullable', 'string', 'max:50'],
            'nomor_whatsapp_secondary_parent' => [
                'nullable',
                'string',
                'max:25',
                'regex:/^(\+62|62|08)[0-9\-\s]{7,15}$/',
            ],
            'nomor_whatsapp_secondary' => [
                'nullable',
                'string',
                'max:25',
                'regex:/^(\+62|62|08)[0-9\-\s]{7,15}$/',
            ],
            'username_secondary_parent' => ['nullable', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-Z0-9_\.\-]+$/'],
            'password_secondary_parent' => ['nullable', 'string', 'min:6'],
            'hapus_secondary_parent' => ['nullable', 'boolean'],
            'remove_secondary_parent' => ['nullable', 'boolean'],
        ], [
            'nomor_whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format diawali 08, 62, atau +62 dengan 9-16 digit angka.',
            'nomor_whatsapp_sendiri.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format diawali 08, 62, atau +62 dengan 9-16 digit angka.',
            'secondary_parent.nomor_whatsapp.regex' => 'Format nomor WhatsApp Secondary Parent tidak valid. Gunakan format diawali 08, 62, atau +62 dengan 9-16 digit angka.',
            'nomor_whatsapp_secondary_parent.regex' => 'Format nomor WhatsApp Secondary Parent tidak valid. Gunakan format diawali 08, 62, atau +62 dengan 9-16 digit angka.',
            'nomor_whatsapp_secondary.regex' => 'Format nomor WhatsApp Secondary Parent tidak valid. Gunakan format diawali 08, 62, atau +62 dengan 9-16 digit angka.',
            'secondary_parent.username.regex' => 'Username Secondary Parent hanya boleh berisi huruf, angka, titik, strip, dan underscore.',
            'username_secondary_parent.regex' => 'Username Secondary Parent hanya boleh berisi huruf, angka, titik, strip, dan underscore.',
        ]);

        // 5. Eksekusi Pembaruan dalam Database Transaction
        $result = DB::transaction(function () use ($request, $user, $targetKeluargaId) {
            $keluarga = null;
            if (Schema::hasTable('akun_keluarga')) {
                $keluarga = AkunKeluarga::find($targetKeluargaId);
            }

            // 5a. Update Alamat Domisili Keluarga
            $keluargaUpdates = [];
            if ($request->has('alamat_domisili')) {
                $keluargaUpdates['alamat_domisili'] = trim((string) $request->input('alamat_domisili'));
            }
            if ($request->has('kelurahan')) {
                $keluargaUpdates['kelurahan'] = trim((string) $request->input('kelurahan'));
            }
            if ($request->has('kecamatan')) {
                $keluargaUpdates['kecamatan'] = trim((string) $request->input('kecamatan'));
            }
            if ($request->has('kota_kabupaten')) {
                $keluargaUpdates['kota_kabupaten'] = trim((string) $request->input('kota_kabupaten'));
            }
            if ($request->has('nama_keluarga')) {
                $keluargaUpdates['nama_keluarga'] = trim((string) $request->input('nama_keluarga'));
            }

            if (!empty($keluargaUpdates) && $keluarga) {
                $keluarga->update($keluargaUpdates);
            }

            // Sinkronkan ke tabel siswa jika memiliki kolom alamat
            if (Schema::hasTable('siswa') && !empty($keluargaUpdates['alamat_domisili']) && Schema::hasColumn('siswa', 'alamat')) {
                DB::table('siswa')->where('id_keluarga', $targetKeluargaId)->update([
                    'alamat' => $keluargaUpdates['alamat_domisili'],
                ]);
            }

            // 5b. Update WhatsApp Sendiri
            $newMyWa = $request->input('nomor_whatsapp_sendiri') ?? $request->input('nomor_whatsapp');
            $myOrtu = null;
            if ($newMyWa !== null && $newMyWa !== '') {
                $cleanMyWa = trim((string) $newMyWa);
                $userId = $user->id_pengguna ?? $user->id;

                if (Schema::hasTable('orang_tua')) {
                    if ($userId) {
                        $myOrtu = OrangTua::where('id_keluarga', $targetKeluargaId)
                            ->where('id_pengguna', $userId)
                            ->first();
                    }
                    if (!$myOrtu && !empty($user->name)) {
                        $myOrtu = OrangTua::where('id_keluarga', $targetKeluargaId)
                            ->where('nama_lengkap', $user->name)
                            ->first();
                    }
                    if (!$myOrtu) {
                        $myOrtu = OrangTua::where('id_keluarga', $targetKeluargaId)
                            ->where('jenis_wali', 'Primary_Parent')
                            ->first();
                    }

                    if ($myOrtu) {
                        $myOrtu->update(['nomor_whatsapp' => $cleanMyWa]);
                    } else {
                        $myOrtu = OrangTua::create([
                            'id_keluarga' => $targetKeluargaId,
                            'id_pengguna' => $userId ?: 1,
                            'jenis_wali' => 'Primary_Parent',
                            'nama_lengkap' => $user->name ?? 'Orang Tua',
                            'hubungan' => 'Wali',
                            'nomor_whatsapp' => $cleanMyWa,
                        ]);
                    }
                }
                session(['whatsapp_wali' => $cleanMyWa]);
            }

            // 5c. Kelola Secondary Parent (Wali Pendamping)
            $existingSecondary = null;
            if (Schema::hasTable('orang_tua')) {
                $existingSecondary = OrangTua::where('id_keluarga', $targetKeluargaId)
                    ->where('jenis_wali', 'Secondary_Parent')
                    ->first();
            }

            $shouldRemoveSec = $request->boolean('hapus_secondary_parent') || $request->boolean('remove_secondary_parent');

            if ($shouldRemoveSec) {
                if ($existingSecondary) {
                    $secPenggunaId = $existingSecondary->id_pengguna;
                    $existingSecondary->delete();
                    if ($secPenggunaId && Schema::hasTable('pengguna')) {
                        DB::table('pengguna')->where('id_pengguna', $secPenggunaId)->delete();
                    }
                    $existingSecondary = null;
                }
            } else {
                $secNama = $request->input('secondary_parent.nama_lengkap') ?? $request->input('nama_secondary_parent');
                $secHubungan = $request->input('secondary_parent.hubungan') ?? $request->input('hubungan_secondary_parent');
                $secWa = $request->input('secondary_parent.nomor_whatsapp') ?? $request->input('nomor_whatsapp_secondary_parent') ?? $request->input('nomor_whatsapp_secondary');
                $secUsername = $request->input('secondary_parent.username') ?? $request->input('username_secondary_parent');
                $secPassword = $request->input('secondary_parent.password') ?? $request->input('password_secondary_parent');

                if ($secNama !== null || $secHubungan !== null || $secWa !== null || $secUsername !== null || $secPassword !== null) {
                    if ($existingSecondary) {
                        $secUpdates = [];
                        if ($secNama !== null && $secNama !== '') $secUpdates['nama_lengkap'] = trim((string) $secNama);
                        if ($secHubungan !== null && $secHubungan !== '') $secUpdates['hubungan'] = trim((string) $secHubungan);
                        if ($secWa !== null && $secWa !== '') $secUpdates['nomor_whatsapp'] = trim((string) $secWa);

                        if (!empty($secUpdates)) {
                            $existingSecondary->update($secUpdates);
                        }

                        if ($secPassword !== null && $secPassword !== '' && $existingSecondary->id_pengguna && Schema::hasTable('pengguna')) {
                            DB::table('pengguna')->where('id_pengguna', $existingSecondary->id_pengguna)->update([
                                'password_hash' => Hash::make($secPassword),
                            ]);
                        }
                    } else {
                        // Daftarkan Secondary Parent baru
                        if ($secUsername && Schema::hasTable('pengguna')) {
                            $isDuplicate = DB::table('pengguna')->where('username', $secUsername)->exists();
                            if ($isDuplicate) {
                                throw \Illuminate\Validation\ValidationException::withMessages([
                                    'username' => ['Username sudah dipakai oleh pengguna lain.'],
                                    'username_secondary_parent' => ['Username sudah dipakai oleh pengguna lain.'],
                                ]);
                            }
                        }

                        $cleanSecNama = !empty($secNama) ? trim((string) $secNama) : 'Secondary Parent';
                        $cleanSecHubungan = !empty($secHubungan) ? trim((string) $secHubungan) : 'Wali Pendamping';
                        $cleanSecWa = !empty($secWa) ? trim((string) $secWa) : ($cleanMyWa ?? '081234567890');
                        $cleanSecUsername = !empty($secUsername) ? trim((string) $secUsername) : ('wali.' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $cleanSecNama)) . rand(100, 999));

                        $secPenggunaId = null;
                        if (Schema::hasTable('pengguna')) {
                            $secPenggunaId = DB::table('pengguna')->insertGetId([
                                'username' => $cleanSecUsername,
                                'password_hash' => Hash::make($secPassword ?: 'RacerWali2026!'),
                                'role' => 'Orang_Tua',
                                'status_aktif' => 1,
                                'dibuat_pada' => now(),
                            ]);
                        }

                        if (Schema::hasTable('orang_tua')) {
                            $existingSecondary = OrangTua::create([
                                'id_keluarga' => $targetKeluargaId,
                                'id_pengguna' => $secPenggunaId ?: rand(5000, 9999),
                                'jenis_wali' => 'Secondary_Parent',
                                'nama_lengkap' => $cleanSecNama,
                                'hubungan' => $cleanSecHubungan,
                                'nomor_whatsapp' => $cleanSecWa,
                            ]);
                        }
                    }
                }
            }

            return [
                'keluarga' => $keluarga ? $keluarga->fresh() : null,
                'myOrtu' => $myOrtu ? $myOrtu->fresh() : null,
                'secondary' => $existingSecondary ? $existingSecondary->fresh() : null,
            ];
        });

        // 6. Siapkan Payload Respons JSON
        $freshKeluarga = $result['keluarga'] ?? (Schema::hasTable('akun_keluarga') ? AkunKeluarga::find($targetKeluargaId) : null);
        $freshMyOrtu = $result['myOrtu'] ?? null;
        $freshSecondary = $result['secondary'] ?? (Schema::hasTable('orang_tua') ? OrangTua::where('id_keluarga', $targetKeluargaId)->where('jenis_wali', 'Secondary_Parent')->first() : null);

        $responseData = [
            'success' => true,
            'message' => 'Pembaruan nomor WhatsApp, domisili, dan data Secondary Parent berhasil disimpan.',
            'data' => [
                'id_keluarga' => $targetKeluargaId,
                'nama_keluarga' => $freshKeluarga?->nama_keluarga,
                'alamat_domisili' => $freshKeluarga?->alamat_domisili,
                'kelurahan' => $freshKeluarga?->kelurahan,
                'kecamatan' => $freshKeluarga?->kecamatan,
                'kota_kabupaten' => $freshKeluarga?->kota_kabupaten,
                'whatsapp_sendiri' => $freshMyOrtu?->nomor_whatsapp ?? $request->input('nomor_whatsapp') ?? $request->input('nomor_whatsapp_sendiri'),
                'secondary_parent' => $freshSecondary ? [
                    'id_orang_tua' => $freshSecondary->id_orang_tua,
                    'nama_lengkap' => $freshSecondary->nama_lengkap,
                    'hubungan' => $freshSecondary->hubungan,
                    'nomor_whatsapp' => $freshSecondary->nomor_whatsapp,
                    'jenis_wali' => $freshSecondary->jenis_wali,
                ] : null,
            ],
        ];

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($responseData, 200);
        }

        return back()->with('success', 'Data profil kontak dan domisili keluarga berhasil diperbarui!');
    }

    /**
     * Endpoint khusus pembaruan Secondary Parent untuk Orang Tua.
     */
    public function updateSecondaryParentOrangTua(Request $request, ?int $id = null): JsonResponse|RedirectResponse
    {
        return $this->updateProfilDanKeluarga($request, $id);
    }

    /**
     * Endpoint khusus penghapusan Secondary Parent untuk Orang Tua.
     */
    public function destroySecondaryParentOrangTua(Request $request, ?int $id = null): JsonResponse|RedirectResponse
    {
        $request->merge(['hapus_secondary_parent' => true]);
        return $this->updateProfilDanKeluarga($request, $id);
    }

    /**
     * Menampilkan Halaman Khusus Ubah Kontak WhatsApp & Secondary Parent di Portal Orang Tua.
     * Front-end: Fahriel Aziz Satrio.
     */
    public function editKontakKeluarga(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $idPengguna = session('id_pengguna');
        if (!$idPengguna && Schema::hasTable('pengguna')) {
            $idPengguna = DB::table('pengguna')->where('username', $user->username)->value('id_pengguna');
        }

        $orangTua = null;
        if (Schema::hasTable('orang_tua')) {
            if ($idPengguna) {
                $orangTua = DB::table('orang_tua')->where('id_pengguna', $idPengguna)->first();
            }
            if (!$orangTua) {
                $orangTua = DB::table('orang_tua')->where('nama_lengkap', $user->name)->first();
            }
        }

        $idKeluarga = self::resolveKeluargaIdFromUser($user) ?? $orangTua->id_keluarga ?? session('id_keluarga') ?? 1;
        $jenisWali = $orangTua->jenis_wali ?? session('jenis_wali') ?? 'Primary_Parent';
        $hubungan = $orangTua->hubungan ?? session('hubungan_wali') ?? 'Orang Tua';

        $keluarga = null;
        $primaryParent = null;
        $secondaryParent = null;

        if (Schema::hasTable('akun_keluarga')) {
            $keluarga = DB::table('akun_keluarga')->where('id_keluarga', $idKeluarga)->first();
        }

        if (Schema::hasTable('orang_tua')) {
            $allOrtu = DB::table('orang_tua')->where('id_keluarga', $idKeluarga)->get();
            $primaryParent = $allOrtu->firstWhere('jenis_wali', 'Primary_Parent');
            $secondaryParent = $allOrtu->firstWhere('jenis_wali', 'Secondary_Parent');
        }

        return view('portal-orang-tua-edit-kontak', compact(
            'user',
            'orangTua',
            'jenisWali',
            'hubungan',
            'keluarga',
            'primaryParent',
            'secondaryParent'
        ));
    }
}


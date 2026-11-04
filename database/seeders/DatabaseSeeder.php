<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial data from SQL dump.
     */
    public function run(): void
    {
        // 1. Users bawaan Laravel
        DB::table('users')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@raccer.com',
                'email_verified_at' => null,
                'password' => '$2y$12$hYPR4aLjBuyXD7stA2qhQ.I.oJiba56MRL0MzkLPq4WeennMDYP2q',
                'remember_token' => null,
                'created_at' => '2026-11-01 22:15:48',
                'updated_at' => '2026-11-01 22:15:48',
            ],
        ]);

        // 2. Pengguna
        DB::table('pengguna')->insertOrIgnore([
            ['id_pengguna' => 1, 'username' => 'admin', 'password_hash' => '$2y$10$U6580BdNQNHp2FO9pJQFu.FKo73we0kre5esH.P32AJfsuAV2k6I2', 'role' => 'Admin', 'status_aktif' => 1, 'jumlah_gagal_login' => 0, 'terkunci_sampai' => null, 'dibuat_pada' => '2026-10-29 14:11:20'],
            ['id_pengguna' => 5, 'username' => 'wali.budi', 'password_hash' => '$2y$10$b5nWw/9LunGx8.ZH9B0TaOxVBBpZsEE9g4MKXusNLhz8KWTxSk8Tq', 'role' => 'Orang_Tua', 'status_aktif' => 1, 'jumlah_gagal_login' => 0, 'terkunci_sampai' => null, 'dibuat_pada' => '2026-10-31 20:13:48'],
            ['id_pengguna' => 7, 'username' => 'tutor.rina', 'password_hash' => '$2y$10$zf4gDz.DNX7Ngn/SzFD5MuBO0P6YxnHyqsaAjUyMiQs.CHoRSj.Si', 'role' => 'Tutor', 'status_aktif' => 1, 'jumlah_gagal_login' => 0, 'terkunci_sampai' => null, 'dibuat_pada' => '2026-11-01 13:54:09'],
            ['id_pengguna' => 8, 'username' => 'siswa.andi', 'password_hash' => '$2y$10$/Dp4loNV.ZFiPnHeieZ.i.yDrkf8B0V9iizjAvjVaiM6bVnB4I8D6', 'role' => 'Siswa', 'status_aktif' => 1, 'jumlah_gagal_login' => 0, 'terkunci_sampai' => null, 'dibuat_pada' => '2026-11-01 14:17:23'],
            ['id_pengguna' => 9, 'username' => 'wali.siti', 'password_hash' => '$2y$10$deVhWz6Den5WWFgfBG9f7uJfZuUYLwERxcO1uhTPomlE.D2m.4unu', 'role' => 'Orang_Tua', 'status_aktif' => 1, 'jumlah_gagal_login' => 0, 'terkunci_sampai' => null, 'dibuat_pada' => '2026-11-01 14:22:59'],
        ]);

        // 3. Akun Keluarga
        DB::table('akun_keluarga')->insertOrIgnore([
            ['id_keluarga' => 1, 'nama_keluarga' => 'Keluarga Budi', 'alamat_domisili' => 'Jl. Contoh No. 1, RT 02/RW 05', 'kelurahan' => 'Menteng', 'kecamatan' => 'Menteng', 'kota_kabupaten' => 'Jakarta Pusat', 'dibuat_pada' => '2026-10-31 20:13:48'],
        ]);

        // 4. Orang Tua
        DB::table('orang_tua')->insertOrIgnore([
            ['id_orang_tua' => 1, 'id_keluarga' => 1, 'id_pengguna' => 5, 'jenis_wali' => 'Primary_Parent', 'nama_lengkap' => 'Budi Santoso', 'hubungan' => 'Ayah', 'nomor_whatsapp' => '081234567890'],
            ['id_orang_tua' => 2, 'id_keluarga' => 1, 'id_pengguna' => 9, 'jenis_wali' => 'Secondary_Parent', 'nama_lengkap' => 'Siti Aminah', 'hubungan' => 'Ibu', 'nomor_whatsapp' => '081234567892'],
        ]);

        // 5. Siswa
        DB::table('siswa')->insertOrIgnore([
            [
                'id_siswa' => 1,
                'id_keluarga' => 1,
                'id_pengguna' => 8,
                'nisn' => '0012345678',
                'nama_lengkap' => 'Andi Santoso',
                'nama_panggilan' => 'Andi',
                'nama' => 'Andi Santoso',
                'nama_wali' => 'Wali: Budi Santoso',
                'jenjang' => 'SD',
                'kelas' => '3',
                'tanggal_lahir' => '2017-05-12',
                'status_siswa' => 'Aktif',
                'paket_belajar' => 'Regular Gold (1 Orang) · Bulanan (4x Pertemuan)',
                'total_sesi' => 4,
                'sesi_terpakai' => 0,
                'status_paket' => 'Aktif',
                'dibuat_pada' => '2026-11-01 14:17:24',
            ],
        ]);

        // 6. Tutor
        DB::table('tutor')->insertOrIgnore([
            ['id_tutor' => 1, 'id_pengguna' => 7, 'nama_lengkap' => 'Rina Maharani', 'nama_panggilan' => 'Kak Rina', 'nomor_whatsapp' => '081234567891', 'spesialisasi' => 'SD', 'status' => 'Aktif', 'dibuat_pada' => '2026-11-01 13:54:09'],
        ]);

        // 7. Jadwal
        DB::table('jadwal')->insertOrIgnore([
            ['id' => 1, 'kelas' => 'Junior Robotics', 'lokasi' => 'Lab STEM 1', 'tutor' => 'Kak Dimas', 'waktu_mulai' => '09.00', 'waktu_selesai' => '10.00 WIB', 'status' => 'Selesai', 'tanggal' => null, 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
            ['id' => 2, 'kelas' => 'Python for Kids', 'lokasi' => 'Lab Komputer A', 'tutor' => 'Kak Sarah', 'waktu_mulai' => '10.30', 'waktu_selesai' => '11.30 WIB', 'status' => 'Selesai', 'tanggal' => null, 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
            ['id' => 3, 'kelas' => 'Arduino Explorer', 'lokasi' => 'Lab Maker', 'tutor' => 'Kak Reza', 'waktu_mulai' => '13.00', 'waktu_selesai' => '14.00 WIB', 'status' => 'Berlangsung', 'tanggal' => null, 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
            ['id' => 4, 'kelas' => 'Robotic Scratch', 'lokasi' => 'Ruang Teori 2', 'tutor' => 'Kak Budi', 'waktu_mulai' => '14.30', 'waktu_selesai' => '15.30 WIB', 'status' => 'Terjadwal', 'tanggal' => null, 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
            ['id' => 5, 'kelas' => 'AI Builder', 'lokasi' => 'Lab Robotika', 'tutor' => 'Kak Nita', 'waktu_mulai' => '16.00', 'waktu_selesai' => '17.00 WIB', 'status' => 'Terjadwal', 'tanggal' => null, 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
            ['id' => 6, 'kelas' => 'IoT Smart Home', 'lokasi' => 'Lab Inovasi', 'tutor' => 'Kak Fajar', 'waktu_mulai' => '17.00', 'waktu_selesai' => '18.00 WIB', 'status' => 'Terjadwal', 'tanggal' => null, 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
        ]);

        // 8. Pengumuman
        DB::table('pengumuman')->insertOrIgnore([
            ['id' => 1, 'judul' => 'Pengumuman Penting', 'konten' => 'Jadwal ujian sertifikasi robotika semester ganjil dimulai pekan depan.', 'kategori' => 'PENTING', 'is_penting' => 1, 'tanggal' => '26 Okt 2026', 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
            ['id' => 2, 'judul' => 'Pengumuman Kelas', 'konten' => 'Penambahan modul baru AI Builder di lab robotika.', 'kategori' => 'Akademik', 'is_penting' => 0, 'tanggal' => '24 Okt 2026', 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
        ]);

        // 9. Reschedule
        DB::table('reschedule')->insertOrIgnore([
            ['id' => 1, 'nama_siswa' => 'Alya Putri', 'inisial' => 'AP', 'kelas' => 'Junior Robotics', 'jadwal_lama' => '28 Okt', 'jadwal_baru' => '30 Okt', 'status' => 'Menunggu', 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
            ['id' => 2, 'nama_siswa' => 'Raka Kurniawan', 'inisial' => 'RK', 'kelas' => 'Python for Kids', 'jadwal_lama' => '29 Okt', 'jadwal_baru' => '31 Okt', 'status' => 'Menunggu', 'created_at' => '2026-11-01 22:16:03', 'updated_at' => '2026-11-01 22:16:03'],
        ]);
    }
}

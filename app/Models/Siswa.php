<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_keluarga',
        'id_pengguna',
        'nisn',
        'nama_lengkap',
        'nama_panggilan',
        'jenjang',
        'kelas',
        'tanggal_lahir',
        'status_siswa',
        'pin_hash',
        'dibuat_pada',
        'nama',
        'nama_wali',
        'paket_belajar',
        'program',
        'jenis_paket',
        'total_sesi',
        'sesi_terpakai',
        'status_paket',
        'status_pembayaran',
    ];

    protected $hidden = [
        'pin_hash',
    ];

    /**
     * Booted model event hooks.
     */
    protected static function booted(): void
    {
        static::creating(function ($siswa): void {
            if (Schema::hasTable('akun_keluarga')) {
                $hasFam = !empty($siswa->id_keluarga) && DB::table('akun_keluarga')->where('id_keluarga', $siswa->id_keluarga)->exists();
                if (!$hasFam) {
                    try {
                        $famId = $siswa->id_keluarga ?: 1;
                        DB::table('akun_keluarga')->insert([
                            'id_keluarga' => $famId,
                            'nama_keluarga' => 'Keluarga Siswa',
                            'alamat_domisili' => 'Jl. Merdeka No. 1',
                            'kelurahan' => 'Kramat',
                            'kecamatan' => 'Senen',
                            'kota_kabupaten' => 'Jakarta Pusat',
                            'dibuat_pada' => now(),
                        ]);
                        $siswa->id_keluarga = $famId;
                    } catch (\Throwable $e) {}
                }
            }

            if (empty($siswa->id_pengguna) && Schema::hasTable('pengguna')) {
                $randomUser = 'siswa.' . Str::lower(Str::random(8));
                try {
                    $idPengguna = DB::table('pengguna')->insertGetId([
                        'username' => $randomUser,
                        'password_hash' => Hash::make('123456'),
                        'role' => 'Siswa',
                        'status_aktif' => 1,
                        'jumlah_gagal_login' => 0,
                        'dibuat_pada' => now(),
                    ]);
                    $siswa->id_pengguna = $idPengguna;
                } catch (\Throwable $e) {}
            }

            if (empty($siswa->nama_panggilan)) {
                $name = $siswa->nama_lengkap ?? $siswa->nama ?? 'Siswa';
                $siswa->nama_panggilan = explode(' ', trim($name))[0] ?: $name;
            }
            if (empty($siswa->jenjang)) {
                $siswa->jenjang = 'SD';
            }
            if (empty($siswa->kelas)) {
                $siswa->kelas = '1';
            }
            if (empty($siswa->tanggal_lahir)) {
                $siswa->tanggal_lahir = '2016-01-01';
            }
        });
    }

    /**
     * Fallback accessor untuk status pembayaran (Belum Bayar / Lunas).
     */
    public function getStatusPembayaranAttribute(?string $value): string
    {
        return !empty($value) ? $value : 'Lunas';
    }

    /**
     * Fallback accessor untuk program kursus robotika.
     */
    public function getProgramAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        $pb = (string) ($this->attributes['paket_belajar'] ?? '');
        if (stripos($pb, 'Junior Robotics') !== false) {
            return 'Junior Robotics';
        }
        if (stripos($pb, 'Python') !== false) {
            return 'Python for Kids';
        }
        if (stripos($pb, 'Scratch') !== false) {
            return 'Robotic Scratch';
        }
        if (stripos($pb, 'Arduino') !== false) {
            return 'Arduino Explorer';
        }
        if (stripos($pb, 'AI') !== false) {
            return 'AI Builder';
        }
        if (stripos($pb, 'IoT') !== false) {
            return 'IoT Smart Home';
        }

        return 'Junior Robotics';
    }

    /**
     * Fallback accessor untuk jenis paket (Semester / Bulanan / Private).
     */
    public function getJenisPaketAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        $pb = (string) ($this->attributes['paket_belajar'] ?? '');
        if (stripos($pb, 'Semester') !== false) {
            return 'Semester';
        }
        if (stripos($pb, 'Bulanan') !== false) {
            return 'Bulanan';
        }
        if (stripos($pb, 'Private') !== false) {
            return 'Private';
        }
        if ($this->total_sesi >= 12) {
            return 'Semester';
        }
        if ($this->total_sesi <= 4) {
            return 'Bulanan';
        }

        return 'Semester';
    }

    /**
     * Fallback accessor untuk nama siswa.
     */
    public function getNamaAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->attributes['nama_lengkap'] ?? $this->attributes['nama_panggilan'] ?? '-';
    }

    /**
     * Fallback accessor untuk nama wali dari relasi orang_tua / akun_keluarga.
     */
    public function getNamaWaliAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        $idKeluarga = $this->attributes['id_keluarga'] ?? null;
        if ($idKeluarga) {
            try {
                if (Schema::hasTable('orang_tua')) {
                    $ortu = DB::table('orang_tua')
                        ->where('id_keluarga', $idKeluarga)
                        ->orderBy('jenis_wali', 'asc')
                        ->first();
                    if ($ortu && !empty($ortu->nama_lengkap)) {
                        return 'Wali: ' . $ortu->nama_lengkap;
                    }
                }
                if (Schema::hasTable('akun_keluarga')) {
                    $keluarga = DB::table('akun_keluarga')
                        ->where('id_keluarga', $idKeluarga)
                        ->first();
                    if ($keluarga && !empty($keluarga->nama_keluarga)) {
                        return 'Wali: ' . $keluarga->nama_keluarga;
                    }
                }
            } catch (\Throwable $e) {
                // Abaikan jika tabel belum siap
            }
        }

        return 'Wali Siswa';
    }

    /**
     * Fallback accessor untuk paket belajar.
     */
    public function getPaketBelajarAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return 'Gold · ' . $this->total_sesi . ' sesi';
    }

    /**
     * Fallback accessor untuk total sesi.
     */
    public function getTotalSesiAttribute($value): int
    {
        return $value !== null ? (int) $value : 8;
    }

    /**
     * Fallback accessor untuk sesi terpakai.
     */
    public function getSesiTerpakaiAttribute($value): int
    {
        return $value !== null ? (int) $value : 0;
    }

    /**
     * Sisa sesi yang belum dipakai.
     */
    public function getSisaSesiAttribute(): int
    {
        return max(0, $this->total_sesi - $this->sesi_terpakai);
    }

    /**
     * Persentase pemakaian sesi.
     */
    public function getPersentaseAttribute(): float
    {
        if ($this->total_sesi <= 0) {
            return 0;
        }

        return min(100, round(($this->sesi_terpakai / $this->total_sesi) * 100, 1));
    }

    /**
     * Relasi ke riwayat paket siswa.
     */
    public function paketSiswa()
    {
        return $this->hasMany(PaketSiswa::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Paket siswa yang sedang aktif.
     */
    public function activePaket()
    {
        return $this->hasOne(PaketSiswa::class, 'id_siswa', 'id_siswa')
            ->where('status_paket', 'Aktif')
            ->latest('id_paket_siswa');
    }

    /**
     * Relasi ke entitas PesertaJadwal.
     */
    public function pesertaJadwal()
    {
        return $this->hasMany(PesertaJadwal::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Relasi many-to-many ke Jadwal melalui peserta_jadwal.
     */
    public function jadwalKelas()
    {
        return $this->belongsToMany(Jadwal::class, 'peserta_jadwal', 'id_siswa', 'id_jadwal')
            ->withPivot(['id_peserta_jadwal', 'id_paket_siswa', 'status_kehadiran', 'catatan'])
            ->withTimestamps();
    }

    /**
     * Relasi ke Akun Keluarga.
     */
    public function keluarga()
    {
        return $this->belongsTo(AkunKeluarga::class, 'id_keluarga', 'id_keluarga');
    }

    /**
     * Relasi ke Orang Tua di bawah keluarga yang sama.
     */
    public function orangTua()
    {
        return $this->hasMany(OrangTua::class, 'id_keluarga', 'id_keluarga');
    }
}

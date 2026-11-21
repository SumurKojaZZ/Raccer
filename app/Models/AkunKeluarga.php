<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AkunKeluarga extends Model
{
    use HasFactory;

    protected $table = 'akun_keluarga';
    protected $primaryKey = 'id_keluarga';
    public $timestamps = false;

    protected $fillable = [
        'id_keluarga',
        'nama_keluarga',
        'alamat_domisili',
        'kelurahan',
        'kecamatan',
        'kota_kabupaten',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_keluarga' => 'integer',
        'dibuat_pada' => 'datetime',
    ];

    /**
     * Seluruh Orang Tua (Primary & Secondary) yang terhubung ke keluarga ini.
     */
    public function orangTua(): HasMany
    {
        return $this->hasMany(OrangTua::class, 'id_keluarga', 'id_keluarga');
    }

    /**
     * Primary Parent (Wali Utama) - Maksimal 1 per keluarga.
     */
    public function primaryParent(): HasOne
    {
        return $this->hasOne(OrangTua::class, 'id_keluarga', 'id_keluarga')
            ->where('jenis_wali', 'Primary_Parent');
    }

    /**
     * Secondary Parent (Wali Pendamping Opsional) - Maksimal 1 per keluarga.
     */
    public function secondaryParent(): HasOne
    {
        return $this->hasOne(OrangTua::class, 'id_keluarga', 'id_keluarga')
            ->where('jenis_wali', 'Secondary_Parent');
    }

    /**
     * Seluruh Siswa (Anak) yang terdaftar di dalam keluarga ini.
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'id_keluarga', 'id_keluarga');
    }
}

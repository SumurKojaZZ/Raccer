<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tua';
    protected $primaryKey = 'id_orang_tua';
    public $timestamps = false;

    protected $fillable = [
        'id_keluarga',
        'id_pengguna',
        'jenis_wali',
        'nama_lengkap',
        'hubungan',
        'nomor_whatsapp',
    ];

    protected $casts = [
        'id_orang_tua' => 'integer',
        'id_keluarga' => 'integer',
        'id_pengguna' => 'integer',
    ];

    /**
     * Akun Keluarga yang menaungi orang tua ini.
     */
    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(AkunKeluarga::class, 'id_keluarga', 'id_keluarga');
    }

    /**
     * Siswa yang terhubung di bawah keluarga yang sama.
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'id_keluarga', 'id_keluarga');
    }
}

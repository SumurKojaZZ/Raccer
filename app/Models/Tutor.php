<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $table = 'tutor';
    protected $primaryKey = 'id_tutor';
    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'kode_tutor',
        'nama_lengkap',
        'nama_gelar',
        'nama_panggilan',
        'nomor_whatsapp',
        'whatsapp',
        'email',
        'username',
        'spesialisasi',
        'jenjang_modul',
        'penempatan',
        'tautan_meeting',
        'max_kelas_minggu',
        'preferensi_mengajar',
        'status',
        'dibuat_pada',
    ];

    /**
     * Accessor ID Tutor TTR-XXX
     */
    public function getFormattedIdAttribute(): string
    {
        if (!empty($this->kode_tutor)) {
            return $this->kode_tutor;
        }

        return 'TTR-' . str_pad((string) ($this->id_tutor ?? $this->id ?? 1), 3, '0', STR_PAD_LEFT);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AturanPaketBelajar extends Model
{
    use HasFactory;

    protected $table = 'aturan_paket_belajar';

    protected $primaryKey = 'id_aturan_paket';

    protected $fillable = [
        'jenis_paket',
        'nama_aturan',
        'min_sesi',
        'maks_sesi',
        'sesi_default',
        'deskripsi',
        'status_aktif',
    ];

    protected $casts = [
        'min_sesi' => 'integer',
        'maks_sesi' => 'integer',
        'sesi_default' => 'integer',
        'status_aktif' => 'boolean',
    ];

    /**
     * Dapatkan aturan berdasarkan nama jenis paket (Semester / Bulanan).
     */
    public static function getAturan(string $jenisPaket): ?self
    {
        $clean = trim($jenisPaket);
        $kategori = (stripos($clean, 'semester') !== false || stripos($clean, '12') !== false)
            ? 'Semester'
            : ((stripos($clean, 'bulanan') !== false || stripos($clean, '4') !== false) ? 'Bulanan' : $clean);

        return static::where('status_aktif', true)
            ->where(function ($q) use ($clean, $kategori) {
                $q->where('jenis_paket', $kategori)
                  ->orWhere('jenis_paket', $clean);
            })
            ->first();
    }
}

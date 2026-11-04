<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $appends = [
        'formatted_tanggal',
    ];

    protected $fillable = [
        'program',
        'kelas',
        'lokasi',
        'tutor',
        'tutor_pengampu',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'moda',
        'status',
    ];

    /**
     * Format tampilan tanggal relatif sesuai kalender:
     * - Tanggal hari ini -> 'Hari ini'
     * - Tanggal besok -> 'Besok'
     * - Tanggal lainnya -> 'dd/mm/yyyy' (contoh: 30/11/2026)
     */
    public function getFormattedTanggalAttribute(): string
    {
        if (empty($this->tanggal)) {
            return 'Hari ini';
        }

        try {
            $date = \Carbon\Carbon::parse($this->tanggal)->startOfDay();
            $today = \Carbon\Carbon::today();

            if ($date->isSameDay($today)) {
                return 'Hari ini';
            }

            if ($date->isSameDay($today->copy()->addDay())) {
                return 'Besok';
            }

            return $date->format('d/m/Y');
        } catch (\Throwable $e) {
            return (string) $this->tanggal;
        }
    }
}


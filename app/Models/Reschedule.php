<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reschedule extends Model
{
    use HasFactory;

    protected $table = 'reschedule';

    protected $fillable = [
        'nama_siswa',
        'inisial',
        'kelas',
        'jadwal_lama',
        'jadwal_baru',
        'status',
    ];
}


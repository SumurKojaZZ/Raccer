<?php

namespace App\Rules;

use App\Models\AturanPaketBelajar;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Schema;

class ValidasiSesiPaket implements ValidationRule, DataAwareRule
{
    /**
     * Seluruh data request yang sedang divalidasi.
     */
    protected array $data = [];

    /**
     * Set data request.
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Jalankan aturan validasi server untuk total sesi berdasarkan jenis paket.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $totalSesi = (int) $value;
        $jenisPaket = (string) ($this->data['jenis_paket'] ?? $this->data['paket_belajar'] ?? '');

        // 1. Cek dari database tabel aturan_paket_belajar jika tabel tersedia
        if (Schema::hasTable('aturan_paket_belajar')) {
            $aturan = AturanPaketBelajar::getAturan($jenisPaket);
            if ($aturan) {
                if ($aturan->jenis_paket === 'Semester') {
                    if ($totalSesi !== 12) {
                        $fail("Paket Semester wajib memiliki tepat 12 sesi pembelajaran (input saat ini: {$totalSesi} sesi).");
                        return;
                    }
                } elseif ($aturan->jenis_paket === 'Bulanan') {
                    if ($totalSesi > 4) {
                        $fail("Paket Bulanan maksimal 4 sesi pembelajaran per paket (input saat ini: {$totalSesi} sesi).");
                        return;
                    }
                    if ($totalSesi < 1) {
                        $fail("Paket Bulanan minimal 1 sesi pembelajaran.");
                        return;
                    }
                } elseif ($totalSesi < $aturan->min_sesi || $totalSesi > $aturan->maks_sesi) {
                    $fail("Jumlah sesi untuk {$aturan->nama_aturan} harus antara {$aturan->min_sesi} sampai {$aturan->maks_sesi} sesi.");
                    return;
                }
                return;
            }
        }

        // 2. Fallback validasi server jika database belum memiliki baris aturan
        $isSemester = stripos($jenisPaket, 'semester') !== false || stripos($jenisPaket, '12') !== false;
        $isBulanan = stripos($jenisPaket, 'bulanan') !== false || stripos($jenisPaket, '4') !== false;

        if ($isSemester && $totalSesi !== 12) {
            $fail("Paket Semester wajib memiliki tepat 12 sesi pembelajaran (input saat ini: {$totalSesi} sesi).");
        } elseif ($isBulanan && $totalSesi > 4) {
            $fail("Paket Bulanan maksimal 4 sesi pembelajaran per paket (input saat ini: {$totalSesi} sesi).");
        }
    }
}

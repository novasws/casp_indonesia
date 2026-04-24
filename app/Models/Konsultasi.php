<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasis';

    protected $fillable = [
        'klien_nama',
        'klien_email',
        'klien_hp',
        'bidang_hukum',
        'deskripsi_keluhan',
        'konsultan_id',
        'paket',          // 1 | 2 | 3
        'status',         // 'aktif' | 'selesai'
        'mulai_at',
        'pembayaran_id',
        'jadwal_at',
        'token_sesi',
    ];

    protected $casts = [
        'mulai_at'  => 'datetime',
        'jadwal_at' => 'datetime',
    ];

    /*
    |------------------------------------------------------------------
    | Relasi
    |------------------------------------------------------------------
    */

    public function konsultan()
    {
        return $this->belongsTo(Konsultan::class);
    }

    public function pesans()
    {
        return $this->hasMany(Pesan::class);
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    /*
    |------------------------------------------------------------------
    | Accessor / Helper
    |------------------------------------------------------------------
    */

    /**
     * Durasi konsultasi dalam detik berdasarkan paket yang dipilih.
     */
    public function getDurasiDetikAttribute(): int
    {
        return match ((int) $this->paket) {
            1 => 3600,
            3 => 10800,
            default => 7200,
        };
    }

    /**
     * Sisa waktu dalam detik (0 jika sudah habis).
     */
    public function getSisaDetikAttribute(): int
    {
        if (!$this->mulai_at) {
            return $this->durasi_detik;
        }

        $elapsed = now()->timestamp - $this->mulai_at->timestamp;
        return max(0, $this->durasi_detik - $elapsed);
    }

    /**
     * Apakah sesi konsultasi sudah berakhir.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->sisa_detik <= 0;
    }

    /*
    |------------------------------------------------------------------
    | Scopes
    |------------------------------------------------------------------
    */

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
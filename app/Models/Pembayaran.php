<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'order_id',
        'nama_klien',
        'email_klien',
        'hp_klien',
        'bidang_hukum',
        'deskripsi_keluhan',
        'konsultan_id',
        'paket',
        'metode',        // 'qris' | 'bca' | 'gopay' | 'ovo'
        'harga',
        'biaya_layanan',
        'total',
        'status',        // 'menunggu' | 'lunas' | 'gagal' | 'refund'
        'jadwal_at',
    ];

    protected $casts = [
        'harga'         => 'integer',
        'biaya_layanan' => 'integer',
        'total'         => 'integer',
        'jadwal_at'     => 'datetime',
    ];

    /*
    |------------------------------------------------------------------
    | Boot: generate order_id otomatis
    |------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (Pembayaran $pembayaran) {
            if (empty($pembayaran->order_id)) {
                $pembayaran->order_id = 'CASP-' . strtoupper(uniqid());
            }
        });
    }

    /*
    |------------------------------------------------------------------
    | Relasi
    |------------------------------------------------------------------
    */

    public function konsultan()
    {
        return $this->belongsTo(Konsultan::class);
    }

    public function konsultasi()
    {
        return $this->hasOne(Konsultasi::class);
    }

    /*
    |------------------------------------------------------------------
    | Accessor
    |------------------------------------------------------------------
    */

    /**
     * Format total dalam rupiah: "Rp 95.000"
     */
    public function getTotalRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getHargaRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /*
    |------------------------------------------------------------------
    | Scopes
    |------------------------------------------------------------------
    */

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $table = 'pesans';

    protected $fillable = [
        'konsultasi_id',
        'pengirim',  // 'klien' | 'konsultan'
        'isi',
    ];

    /*
    |------------------------------------------------------------------
    | Relasi
    |------------------------------------------------------------------
    */

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class);
    }

    /*
    |------------------------------------------------------------------
    | Accessor
    |------------------------------------------------------------------
    */

    /**
     * Waktu kirim dalam format jam.menit WIB.
     */
    public function getWaktuAttribute(): string
    {
        return $this->created_at->format('H.i');
    }

    public function getIsKlienAttribute(): bool
    {
        return $this->pengirim === 'klien';
    }

    public function getIsKonsultanAttribute(): bool
    {
        return $this->pengirim === 'konsultan';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    use HasFactory;

    protected $table = 'keluhans';

    protected $fillable = [
        'nama',
        'hp',
        'email',
        'kategori',
        'urgensi',
        'isi',
        'status',
        'token_sesi',
    ];

    public function pesans()
    {
        return $this->hasMany(KeluhanPesan::class);
    }

    /**
     * Status yang valid:
     * - menunggu  : belum ditangani
     * - diproses  : sedang ditangani tim
     * - selesai   : sudah diselesaikan
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Konsultan extends Authenticatable
{
    use HasFactory;

    protected $table = 'konsultans';

    protected $fillable = [
        'nama',
        'gelar',
        'spesialisasi',
        'pengalaman_tahun',
        'inisial',
        'warna_avatar',
        'foto',
        'bio',
        'quote',
        'status', // 'online' | 'sibuk' | 'offline'
        'username',
        'password',
        'is_superadmin',
        'jadwal_shift',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Nama lengkap beserta gelar.
     */
    public function getNamaLengkapAttribute(): string
    {
        return $this->nama . ' ' . $this->gelar;
    }

    /**
     * Scope: hanya konsultan yang aktif/online.
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['online', 'sibuk']);
    }

    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    /**
     * Relasi ke sesi konsultasi yang dimiliki konsultan ini.
     */
    public function konsultasis()
    {
        return $this->hasMany(Konsultasi::class);
    }
}
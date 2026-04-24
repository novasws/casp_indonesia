<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeluhanPesan extends Model
{
    use HasFactory;

    protected $table = 'keluhan_pesans';

    protected $fillable = [
        'keluhan_id',
        'pengirim',
        'isi',
    ];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }

    public function getWaktuAttribute(): string
    {
        return $this->created_at->format('H.i');
    }
}

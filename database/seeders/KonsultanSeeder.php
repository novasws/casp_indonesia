<?php

namespace Database\Seeders;

use App\Models\Konsultan;
use Illuminate\Database\Seeder;

class KonsultanSeeder extends Seeder
{
    public function run(): void
    {
        $konsultans = [
            [
                'nama'              => 'Dr. Agus Santoso',
                'gelar'             => 'S.H.',
                'spesialisasi'      => 'Hukum Perdata',
                'pengalaman_tahun'  => 10,
                'inisial'           => 'AS',
                'warna_avatar'      => 'blue',
                'status'            => 'online',
            ],
            [
                'nama'              => 'Siti Rahayu',
                'gelar'             => 'S.H., M.Kn',
                'spesialisasi'      => 'Hukum Keluarga',
                'pengalaman_tahun'  => 8,
                'inisial'           => 'SR',
                'warna_avatar'      => 'indigo',
                'status'            => 'online',
            ],
            [
                'nama'              => 'Budi Prakoso',
                'gelar'             => 'S.H.',
                'spesialisasi'      => 'Hukum Bisnis',
                'pengalaman_tahun'  => 12,
                'inisial'           => 'BP',
                'warna_avatar'      => 'green',
                'status'            => 'online',
            ],
            [
                'nama'              => 'Rina Wulandari',
                'gelar'             => 'S.H.',
                'spesialisasi'      => 'Hukum Properti',
                'pengalaman_tahun'  => 7,
                'inisial'           => 'RW',
                'warna_avatar'      => 'orange',
                'status'            => 'online',
            ],
            [
                'nama'              => 'Hendra Adi',
                'gelar'             => 'S.H., M.H.',
                'spesialisasi'      => 'Hukum Ketenagakerjaan',
                'pengalaman_tahun'  => 9,
                'inisial'           => 'HA',
                'warna_avatar'      => 'purple',
                'status'            => 'online',
            ],
            [
                'nama'              => 'Lisa Maharani',
                'gelar'             => 'S.H.',
                'spesialisasi'      => 'Hukum Pidana',
                'pengalaman_tahun'  => 15,
                'inisial'           => 'LM',
                'warna_avatar'      => 'red',
                'status'            => 'sibuk',
            ],
        ];

        foreach ($konsultans as $data) {
            Konsultan::updateOrCreate(
                ['nama' => $data['nama'], 'gelar' => $data['gelar']],
                $data
            );
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestoreDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Restore KONSULTANS (Satu per satu biar aman)
        $konsultans = [
            [
                'id' => 1,
                'nama' => 'Dr. Agus Santoso',
                'gelar' => 'S.H.',
                'spesialisasi' => 'Hukum Perdata',
                'pengalaman_tahun' => 10,
                'inisial' => 'AS',
                'warna_avatar' => 'blue',
                'foto' => 'profil/UYcVACC9q46CI5eZtRIVaXqnBwajX85kRcyft5sX.png',
                'bio' => 'Saya adalah agus',
                'quote' => 'agus adalah saya',
                'status' => 'online',
                'jadwal_shift' => '07:00 - 11:00',
                'username' => 'agus',
                'password' => \Illuminate\Support\Facades\Hash::make('agus123'),
                'is_superadmin' => 0,
                'created_at' => '2026-04-09 01:19:40',
                'updated_at' => '2026-04-24 14:53:01',
            ],
            [
                'id' => 3,
                'nama' => 'Budi Prakoso',
                'gelar' => 'S.H.',
                'spesialisasi' => 'Hukum Bisnis',
                'pengalaman_tahun' => 12,
                'inisial' => 'BP',
                'warna_avatar' => 'green',
                'foto' => 'profil/HLq2DWSCo0CSGlcKziush5Ig03w1jXZwtbuSoZCh.png',
                'bio' => 'Budi adalah konsep mendalam...',
                'quote' => 'Budi adalah konsep mendalam dalam budaya Indonesia',
                'status' => 'offline',
                'jadwal_shift' => '15:00 - 19:00',
                'username' => 'budi',
                'password' => \Illuminate\Support\Facades\Hash::make('budi123'),
                'is_superadmin' => 0,
                'created_at' => '2026-04-09 01:19:40',
                'updated_at' => '2026-04-24 14:00:45',
            ],
            [
                'id' => 7,
                'nama' => 'Pusat CASP',
                'gelar' => '',
                'spesialisasi' => 'Super Administrator',
                'pengalaman_tahun' => 0,
                'inisial' => 'AD',
                'warna_avatar' => 'blue',
                'foto' => null,
                'bio' => null,
                'quote' => null,
                'status' => 'offline',
                'jadwal_shift' => null,
                'username' => 'superadmin',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'is_superadmin' => 1,
                'created_at' => '2026-04-11 01:24:44',
                'updated_at' => '2026-04-24 14:52:49',
                'remember_token' => 'eqDRwXFL85yZWeHjNvzkpOtSA5GfWc6NksUii3CqNMqg4t5S65jeWddhIjpC',
            ],
            [
                'id' => 8,
                'nama' => 'ucup',
                'gelar' => 'S.H., M.Kn',
                'spesialisasi' => 'Hukum Perdata',
                'pengalaman_tahun' => 6,
                'inisial' => 'UC',
                'warna_avatar' => 'bg-purple-50',
                'foto' => 'profil/r3kgzzPwkHpYVAgT0hmwTbQpVrZJy0HtVvNDjtX5.png',
                'bio' => 'Ucup umumnya merupakan nama panggilan...',
                'quote' => 'saya ucup gg',
                'status' => 'offline',
                'jadwal_shift' => null,
                'username' => 'ucup',
                'password' => \Illuminate\Support\Facades\Hash::make('ucup123'),
                'is_superadmin' => 0,
                'created_at' => '2026-04-24 14:20:36',
                'updated_at' => '2026-04-24 14:41:59',
            ],
        ];

        foreach($konsultans as $k) { DB::table('konsultans')->updateOrInsert(['id' => $k['id']], $k); }

        // 2. Restore PEMBAYARANS
        DB::table('pembayarans')->updateOrInsert(['id' => 51], [
            'id' => 51,
            'order_id' => 'CASP-69EB83B15CDC6',
            'nama_klien' => 'Novasws',
            'email_klien' => 'novasatriaws@gmail.com',
            'hp_klien' => '082141186468',
            'bidang_hukum' => 'Hukum Perdata',
            'deskripsi_keluhan' => 'Hukum adalah sistem...',
            'konsultan_id' => 1,
            'paket' => 1,
            'metode' => 'bca',
            'harga' => 50000,
            'biaya_layanan' => 5000,
            'total' => 55000,
            'status' => 'lunas',
            'created_at' => '2026-04-24 14:52:33',
            'updated_at' => '2026-04-24 14:52:36',
        ]);

        // 3. Restore KONSULTASIS
        DB::table('konsultasis')->updateOrInsert(['id' => 49], [
            'id' => 49,
            'token_sesi' => 'CASP-OYJEKY',
            'klien_nama' => 'Novasws',
            'klien_email' => 'novasatriaws@gmail.com',
            'klien_hp' => '082141186468',
            'bidang_hukum' => 'Hukum Perdata',
            'deskripsi_keluhan' => 'Hukum adalah sistem...',
            'konsultan_id' => 1,
            'pembayaran_id' => 51,
            'paket' => 1,
            'status' => 'selesai',
            'mulai_at' => '2026-04-24 14:53:35',
            'created_at' => '2026-04-24 14:52:36',
            'updated_at' => '2026-04-24 14:54:35',
        ]);

        // 4. Restore PESANS
        DB::table('pesans')->insert([
            ['konsultasi_id' => 49, 'pengirim' => 'klien', 'isi' => 'hallo bapak', 'created_at' => '2026-04-24 14:53:48'],
            ['konsultasi_id' => 49, 'pengirim' => 'klien', 'isi' => 'bisakah membantu saya', 'created_at' => '2026-04-24 14:53:52'],
            ['konsultasi_id' => 49, 'pengirim' => 'konsultan', 'isi' => 'silahkan ceritakan apa masalah anda nak?', 'created_at' => '2026-04-24 14:54:07'],
        ]);
    }
}

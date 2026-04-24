<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Konsultan;
use App\Models\Konsultasi;
use App\Models\Pesan;
use App\Models\Pembayaran;

// Delete all payments and consultations and messages to avoid FK constraints when deleting consultants
Pesan::truncate();
Konsultasi::query()->delete();
Pembayaran::query()->delete();

// Delete consultants ID 4,5,6
Konsultan::whereIn('id', [4, 5, 6])->delete();

// Update existing 3
$kons1 = Konsultan::find(1);
if($kons1) {
    $kons1->update([
        'nama_lengkap' => 'Dr. Agus Santoso S.H.',
        'inisial' => 'AS',
        'username' => 'agus',
        'jadwal_shift' => '07:00 - 11:00',
        'status' => 'offline',
        'password' => bcrypt('password')
    ]);
}

$kons2 = Konsultan::find(2);
if($kons2) {
    $kons2->update([
        'nama_lengkap' => 'Siti Rahayu S.H., M.Kn',
        'inisial' => 'SR',
        'username' => 'siti',
        'jadwal_shift' => '11:00 - 15:00',
        'status' => 'offline',
        'password' => bcrypt('password')
    ]);
}

$kons3 = Konsultan::find(3);
if($kons3) {
    $kons3->update([
        'nama_lengkap' => 'Budi Prakoso S.H.',
        'inisial' => 'BP',
        'username' => 'budi',
        'jadwal_shift' => '15:00 - 19:00',
        'status' => 'offline',
        'password' => bcrypt('password')
    ]);
}

echo "Berhasil update 3 konsultan (Agus, Siti, Budi) dan membersihkan data lama.\n";

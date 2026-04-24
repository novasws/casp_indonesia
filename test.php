<?php
$k = App\Models\Keluhan::create([
    'nama'=>'T',
    'hp'=>'08123456789',
    'email'=>'t@t.com',
    'kategori'=>'Bantuan Umum / Pusat Bantuan',
    'urgensi'=>'Normal (1-3 hari)',
    'isi'=>'jadi gini kan beli ikan,tapi semoga bener bener josjis',
    'status'=>'menunggu'
]);
echo "ID: " . $k->id . "\n";

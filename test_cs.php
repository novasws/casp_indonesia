<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/keluhan', 'POST', [
    'nama'=>'T',
    'hp'=>'08828282828',
    'email'=>'novasws851@gmail.com',
    'kategori'=>'Bantuan Umum / Pusat Bantuan',
    'urgensi'=>'Normal (1-3 hari)',
    'isi'=>'jadi gini kan beli ikan,tapi semoga bener bener josjis dan mentalnya cepat jadi aamiin'
]);
$request->headers->set('Accept', 'application/json');

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";

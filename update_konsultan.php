<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (App\Models\Konsultan::all() as $k) {
    $u = strtolower(explode(' ', $k->nama)[0]);
    $k->update([
        'username' => $u,
        'password' => bcrypt('password'),
        'is_superadmin' => ($u === 'agus')
    ]);
    echo "Updated $u\n";
}

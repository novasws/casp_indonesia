<?php
require_once 'vendor/autoload.php';

$key = 'SB-Mid-server-EoFxzqyskywotRTYinLAUCs'; // Paksa pake SB-

\Midtrans\Config::$serverKey = $key;
\Midtrans\Config::$isProduction = false;

try {
    $token = \Midtrans\Snap::getSnapToken([
        'transaction_details' => [
            'order_id' => 'TEST-' . time(),
            'gross_amount' => 10000
        ]
    ]);
    echo "BERHASIL! Token: " . $token;
} catch (\Exception $e) {
    echo "GAGAL: " . $e->getMessage();
}

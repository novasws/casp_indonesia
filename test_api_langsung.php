<?php
$serverKey = 'SB-Mid-server-EoFxzqyskfywotRTYinLaUCs';
$auth = base64_encode($serverKey . ':');

$url = "https://app.sandbox.midtrans.com/snap/v1/transactions";
$body = json_encode([
    'transaction_details' => [
        'order_id' => 'ORDER-' . rand(),
        'gross_amount' => 10000,
    ]
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . $auth
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

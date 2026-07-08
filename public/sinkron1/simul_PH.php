<?php

//atur timezone supaya sesuai dengan waktu lokal
date_default_timezone_set('Asia/Jakarta');

define('INTERVAL', 27);          // detik
define('NODE_ID', 'XN10823598');

define('PH_BASELINE', 5.88);     // nilai rata-rata
define('PH_AMPLITUDE', 0.11);    // amplitudo harian
define('PH_NOISE', 0.015);       // noise ±0.015
// define('API_URL', 'http://127.0.0.1/xn_app/public/node/sl');
define('API_URL', 'http://xn.online-farm.com/node/sl');


function generatePH(DateTime $now): float
{
    // Posisi waktu dalam satu hari (0 - 86399 detik)
    $detik = ((int)$now->format('H') * 3600)
           + ((int)$now->format('i') * 60)
           + ((int)$now->format('s'));

    // Sudut 0 - 2π
    $angle = 2 * pi() * ($detik / 86400);

    // Baseline
    $baseline = PH_BASELINE;

    // Naik siang, turun malam
    $trend = PH_AMPLITUDE * sin($angle - pi()/2);

    static $lastNoise = 0;

// perubahan kecil setiap pembacaan
    $delta = ((mt_rand() / mt_getrandmax()) - 0.5) * 0.003;

    // noise baru mengikuti noise lama
    $lastNoise += $delta;

    // dibatasi supaya tidak kebablasan
    $lastNoise = max(-PH_NOISE, min(PH_NOISE, $lastNoise));

    $noise = $lastNoise;

    return $baseline + $trend + $noise;
}

$now = new DateTime();

$ph = generatePH($now);


$payload = [
    "c"  => NODE_ID,
    "n"  => 1,
    "r0" => $ph,
    "v1" => $ph
];


$ch = curl_init(API_URL);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS,
    json_encode($payload)
);


$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);



echo json_encode([
    "time" => $now->format('Y-m-d H:i:s'),
    "ph" => $ph,
    "http" => $httpCode,
    "response" => $response
]);
<?php

//atur timezone supaya sesuai dengan waktu lokal
date_default_timezone_set('Asia/Jakarta');


 
// define('API_URL', 'http://127.0.0.1/xn_app/public/node/sl');
define('API_URL', 'http://xn.online-farm.com/node/sl');



$ch = curl_init(API_URL);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);


/**  
 * $data = json_decode($sDataDataPost); 
 {"c":"XN0123456", "n":1, "t":"2023-08-15 18:36:54", "r0":510, "v1":51 }
 {"c":"XN0123456", "t":"2023-08-15 18:36:54","D":[ 
     { "n":1, "r0":510, "v1":51 }, { "n":2, "r0":540, "v1":54 }, 
     { "n":3, "r0":550, "v1":55 }, { "n":4, "r0":560, "v1":56 }   
   ]}
 {"c":"XN0123456", "t":"2023-08-15 18:36:54", "st":90, "ic":3, "D":[   
     { "n":1, "r0_0":509, "r0_1":510, "r0_2":509, "v1_0":50, "v1_1":51,"v1_2":50 }, 
 */

$now = new DateTime();
$ph = generatePH($now);
$payload = [
    "c"  => "XN10823598",
    "n"  => 1,
    "r0" => $ph,
    "v1" => $ph
];

$randomDetik = rand(9, 27);
$t = date("Y-m-d H:i:s", time() - $randomDetik);
$now = new DateTime($t);
$ec = generateEC($now);
$payload2 = [
    "c"  => "XN10823598",
    "n"  => 3,
    "t"  => $t,
    "r0" => $ec,
    "v1" => $ec
];
//request 1
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($payload));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

//request 2
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($payload2)); 
$response2 = curl_exec($ch);
$httpCode2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);   



curl_close($ch);
 

echo json_encode([ 
    "ph" => $ph,
    "http" => $httpCode,
    "response" => $response,
    "t_rand" => $t,
    "ec" => $ec,
    "http2" => $httpCode2,
    "response2" => $response2
]);


//fungsi-fungsi=====================================================
//==================================================================
 
function generatePH(DateTime $now): float
{
    // Posisi waktu dalam satu hari (0 - 86399 detik)
    $detik = ((int)$now->format('H') * 3600)
           + ((int)$now->format('i') * 60)
           + ((int)$now->format('s'));

    // Sudut 0 - 2π
    $angle = 2 * pi() * ($detik / 86400);

    // Baseline
    $baseline = 5.88;
    $phAmplitude = 0.11;
    $phNoise = 0.027;

    // Naik siang, turun malam
    $trend = $phAmplitude * sin($angle - pi()/2);

    static $lastNoise = 0;

// perubahan kecil setiap pembacaan
    $delta = ((mt_rand() / mt_getrandmax()) - 0.5) * 0.003;

    // noise baru mengikuti noise lama
    $lastNoise += $delta;

    // dibatasi supaya tidak kebablasan
    $lastNoise = max(-$phNoise, min($phNoise, $lastNoise));

    $noise = $lastNoise;

    return $baseline + $trend + $noise;
}
 
function generateEC(DateTime $now): float
{
    $jam = (int)$now->format('H');
    $menit = (int)$now->format('i');

    // waktu dalam menit sehari
    $menitHari = ($jam * 60) + $menit;


    // titik awal 07:30
    $start = 7 * 60 + 30;


    // random kecil seperti sensor lapangan
    $noise = rand(-15, 15);


    // ===== 07:30 - 11:00 naik =====
    if ($menitHari >= $start && $menitHari < 11*60) {

        $selisih = $menitHari - $start;

        // durasi 210 menit
        $trend = ($selisih / 210) * 90;

        // gergaji kecil
        $gergaji = rand(-20,20);

        $ec = 2250 + $trend + $gergaji;
    }


    // ===== 11:00 - 13:00 turun perlahan =====
    else if ($menitHari >= 11*60 && $menitHari < 13*60) {

        $selisih = $menitHari - (11*60);

        // turun dari 2340 ke 2280
        $trend = ($selisih / 120) * (-60);

        $ec = 2340 + $trend + rand(-15,15);
    }


    // ===== 13:00 - 15:00 koreksi drastis =====
    else if ($menitHari >= 13*60 && $menitHari < 15*60) {

        $selisih = $menitHari - (13*60);

        // turun cepat 2280 -> 2220
        $trend = ($selisih / 120) * (-60);

        $ec = 2280 + $trend + rand(-20,20);
    }


    // ===== malam sampai pagi stabil =====
    else {

        // baseline malam
        $ec = 2220 + rand(-10,10);
    }


    return number_format($ec, 0, '.', '');
}
<?php
header('Content-Type: application/json');
$token = 'TOKEN-RAHASIA-TERIMPAN-DI-SERVER';

require_once __DIR__ . "/../../app/fsambungan.php";   

$conn = new mysqli(HOST, USER, PASS, DB);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Koneksi database gagal']);
    exit;
}

// Ambil input dari client
$input = json_decode(file_get_contents('php://input'), true);


// Validasi token
if ($input['token'] !== $token) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Daftar tabel dan timestamp
$tables = $input['tables'] ?? [];
$limit_per_table = 9999; // 💡 Batas default per tabel

$response = ['status' => 'success', 'tables' => []];

foreach ($tables as $table => $info) {
    $safe_table = $conn->real_escape_string($table);
    $timestamp = $conn->real_escape_string($info['timestamp'] ?? '');
    $column = $conn->real_escape_string($info['column'] ?? '');

    if (!$timestamp || !$column) {
        $response['tables'][$table] = ['error' => 'Parameter timestamp atau column tidak lengkap'];
        continue;
    }

    $query = "SELECT * FROM `$safe_table` WHERE `$column` > '$timestamp' ORDER BY `$column` ASC LIMIT $limit_per_table";
    $result = $conn->query($query);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $response['tables'][$table] = $data;
}

echo json_encode($response);
?> 

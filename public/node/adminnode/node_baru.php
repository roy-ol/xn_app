<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login atau belum
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  require_once __DIR__ . '../../../../app/init_class.php';
  $cUmum = new cUmum();
}else{  
  header("Location: login.php");  
  exit;
}

// Query SELECT dengan JOIN
$query = "SELECT p.nama AS perusahaan, k.nama AS kebun, c.id, c.chip, t.nama AS tipe, t.kelompok, c.keterangan, c.versi, c.build, c.updated, c.created
          FROM chip c
          JOIN kebun k ON k.id = c.id_kebun
          JOIN perusahaan p ON p.id = k.id_perusahaan
          JOIN tipe t ON t.id = c.id_tipe
          ORDER BY id DESC limit 10" ;

$result = $cUmum->ambilData($query);
// Memeriksa apakah query berhasil dijalankan
if ($result) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Perusahaan</th>';
    echo '<th>Kebun</th>';
    echo '<th>ID</th>';
    echo '<th>Chip</th>';
    echo '<th>Tipe</th>';
    echo '<th>Kel</th>';
    echo '<th>Keterangan</th>';
    echo '<th>Ver</th>';
    echo '<th>Build</th>';
    echo '<th>Updated</th>';
    echo '<th>Created</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Menampilkan data dalam tabel
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $row['perusahaan'] . '</td>';
        echo '<td>' . $row['kebun'] . '</td>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['chip'] . '</td>';
        echo '<td>' . $row['tipe'] . '</td>';
        echo '<td>' . $row['kelompok'] . '</td>';
        echo '<td>' . $row['keterangan'] . '</td>';
        echo '<td>' . $row['versi'] . '</td>';
        echo '<td>' . $row['build'] . '</td>';
        echo '<td>' . $row['updated'] . '</td>';
        echo '<td>' . $row['created'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    $conn = $cUmum->getPDO();
    echo "Error: " . $query . "<br>" . $conn->errorInfo()[2];
}

?>
<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body onload="onLoadFunctions()">
  <?php echo $_SESSION['username']; ?>   <label id="clock"></label>
  <label id="date"></label> + <label id="elapsed-time"></label>
  <a href="logout.php">Logout</a>
  <br><br>
  <a href="node_baru.php">+ Node / Chip Baru</a>
  <br><br><br> 

<?php

require_once __DIR__ . '../../../../app/init_class.php';
$cUmum = new cUmum();
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


</body>
</html>




<script>
  function onLoadFunctions() {
      displayDateTime();
      displayElapsedTime();
  }
  function displayDateTime() {
    var date = new Date();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var day = date.getDate();
    var month = date.getMonth() + 1; // Nilai bulan dimulai dari 0, sehingga perlu ditambah 1
    var year = date.getFullYear();

    // Menambahkan nol pada angka satu digit
    hours = (hours < 10) ? "0" + hours : hours;
    minutes = (minutes < 10) ? "0" + minutes : minutes;
    seconds = (seconds < 10) ? "0" + seconds : seconds;
    day = (day < 10) ? "0" + day : day;
    month = (month < 10) ? "0" + month : month;

    var time = hours + ":" + minutes + ":" + seconds;
    var fullDate = day + "/" + month + "/" + year;

    document.getElementById("clock").textContent = time;
    document.getElementById("date").textContent = fullDate;

    // setTimeout(displayDateTime, 1000); // Memperbarui waktu dan tanggal setiap 1 detik
  }

  function displayElapsedTime() {
    var startTime = new Date(); // Waktu mulai
    var interval = setInterval(updateElapsedTime, 1000); // Memperbarui waktu yang telah berlalu setiap 1 detik

    function updateElapsedTime() {
        var currentTime = new Date(); // Waktu saat ini
        var elapsedTime = Math.floor((currentTime - startTime) / 1000); // Waktu yang telah berlalu dalam detik

        var hours = Math.floor(elapsedTime / 3600);
        var minutes = Math.floor((elapsedTime % 3600) / 60);
        var seconds = elapsedTime % 60;

        // Menambahkan nol pada angka satu digit
        hours = (hours < 10) ? "0" + hours : hours;
        minutes = (minutes < 10) ? "0" + minutes : minutes;
        seconds = (seconds < 10) ? "0" + seconds : seconds;

        var elapsedTimeString = hours + ":" + minutes + ":" + seconds;

        document.getElementById("elapsed-time").textContent = elapsedTimeString;
    }
  }
</script>
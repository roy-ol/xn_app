<body onload="onLoadFunctions()">
<?php
require_once __DIR__ . '../../../../app/init_class.php';
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit;
}

echo $_SESSION['username'] . " lvl:" . $_SESSION['id_level']; 
?>  
<label id="clock"></label>
<label id="date"></label> + <label id="elapsed-time"></label>
<a href="logout.php">Logout</a>
<br><br>
<a href="dashboard.php">Dashboard</a> &nbsp &nbsp &nbsp
<a href="node_baru.php">+Node / Chip Baru</a> &nbsp &nbsp &nbsp
<a href="binfirupd.php">binfirup</a>&nbsp &nbsp &nbsp
<br><br><br> 

<?php //================fungsi fungsi umum web php koneksi dll   
$cUmum = new cUmum();
/**
 * membuat tampilan tabel dari query sql
 */
function bikinTabelSQL($sqlQuery) {
    // Menggunakan kelas umum untuk eksekusi query
    global $cUmum ;
 
    // Query SQL
    $hasil = $cUmum->ambilData($sqlQuery);
    $result = $hasil->fetchAll(PDO::FETCH_ASSOC); 

    if (empty($result)) {
        return '<p>Tidak ada data yang ditemukan.</p>';
    }

    // Buat tampilan tabel
    $tableHTML = '<table>
                    <tr>';
    
    // Membuat header tabel dari nama kolom hasil query
    foreach(array_keys($result[0]) as $columnName) {
        $tableHTML .= '<th>'.$columnName.'</th>';
    }
    
    $tableHTML .= '</tr>'; 
    // Membuat baris tabel dari hasil query
    foreach($result as $row) {
        $tableHTML .= '<tr>'; 
        foreach($row as $value) {
            $tableHTML .= '<td>'.$value.'</td>';
        } 
        $tableHTML .= '</tr>';
    }

    // Menutup tabel
    $tableHTML .= '</table>';

    return $tableHTML;
}

/**
 * bikin isian option dari sql berisi id (ex: <Select .. . )
 * @param sTampil wajib ada setelah id text tampil di dalam opsi
 * @param sp1 = pemisah 1 2 3
 * @param tampil1 = field dari query untuk menjadi teks ditampilkan 1 2 3 
 */
function bikinOption($sqlQuery,$sTampil,$sp1="",$sTampil1="",$sp2="",$sTampil2="",$sp3="",$sTampil3=""){
  global $cUmum ;
  $result = $cUmum->ambilData($sqlQuery); 
  // Memeriksa apakah query berhasil dijalankan
  if ($result) {
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
        echo '<option value="' . $row['id'] . '">' . $row[$sTampil] .$sp1 . $row[$sTampil1] 
        . $sp2 . $row[$sTampil2] . $sp3 . $row[$sTampil3]  . '</option>';
      }
  } else { echo "Error: " . $sqlQuery . "<br>" . $cUmum->getPDO()->errorInfo()[2];}
}

?>
  
<style>
  table {
      border-collapse: collapse;
      width: 100%;
      border: 2px solid black; /* Ketebalan garis tepi tabel */
  }

  th, td {
      text-align: left;
      padding: 8px;
      border-bottom: 1px solid black; /* Garis antara baris */
  }

  th {
      background-color: #f2f2f2;
      border-right: 1px solid black; /* Garis antara kolom header */
  }

  td {
      border-right: 1px solid black; /* Garis antara kolom isi tabel */
  }

  tr:nth-child(even) {
      background-color: #f2f2f2;
  }    
</style>

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
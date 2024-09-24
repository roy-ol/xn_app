<body onload="onLoadFunctions()">
  <?php
require_once __DIR__ . '../../fungsi/koneksi_umum.php';
// session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit;
}

$id_level = intval($_SESSION['id_level']);
if($id_level > 1 ){
  $error_message = "maaf hak akses anda tidak diperbolehkan menggunakan fitur ini";
  die($error_message);
} 

echo $_SESSION['username'] . " lvl:" . $id_level; 
?>

  <label id="clock"></label>
  <label id="date"></label> + <label id="elapsed-time"></label>
  <a href="logout.php">Logout</a>
  <br><br>
  <a href="dashboard.php">Dashboard</a> &nbsp &nbsp &nbsp
  <a href="chip_log.php" title="flag chip / status chip / node">flag Chip</a> &nbsp &nbsp &nbsp
  <a href="binfirupd.php" title="binary firmware chip and flag update">binfirupd</a>&nbsp &nbsp &nbsp
  <a href="xt_aktuator.php">XT ExeTest Aktuator</a>&nbsp &nbsp &nbsp
  <a href="node_baru.php">+Node+ </a> &nbsp &nbsp &nbsp
  <a href="chip_baru.php">+Chip+</a> &nbsp &nbsp &nbsp
  <a href="kebun.php">+Kebun</a> &nbsp &nbsp &nbsp
  <a href="user.php">+User</a> &nbsp &nbsp &nbsp
  <a href="perusahaan.php">+Perusahaan</a> &nbsp &nbsp &nbsp
  <br><br><br>

  <?php //================fungsi fungsi umum web php koneksi dll   
  if(1==0) $cUmum = new cUmum(); 
?>

  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      border: 2px solid black;
      /* Ketebalan garis tepi tabel */
    }

    th,
    td {
      text-align: left;
      padding: 8px;
      border-bottom: 1px solid black;
      /* Garis antara baris */
    }

    th {
      background-color: #f2f2f2;
      border-right: 1px solid black;
      /* Garis antara kolom header */
    }

    td {
      border-right: 1px solid black;
      /* Garis antara kolom isi tabel */
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
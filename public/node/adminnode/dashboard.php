<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
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
  <a href="node_baru.php">+Node Baru</a>
  <br> 




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
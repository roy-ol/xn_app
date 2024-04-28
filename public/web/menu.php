<!DOCTYPE html>
<html lang="en"> 
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body onload="onLoadFunctions()">
<?php
require_once __DIR__ . '/fungsi/koneksi_umum.php';  
?>
 <button  id="menuButton" onclick="toggleMenu()">&#9782;</button> 
<?php
echo '<div style="text-align: right;">'.  $_SESSION['username'] . " (L" . $_SESSION['id_level'] . ') <label id="elapsed-time"></label> </div>';  
// echo ' &nbsp <label id="date"></label> <label id="clock"></label>  ';
// echo '<br> &nbsp &nbsp '. $sNamaPerus . ' + <label id="elapsed-time"></label> ';  
?>  
 

<div id="popupMenu" class="popup-menu">
    <!-- <button onclick="toggleMenu()">&#9665;</button><br><br> -->
    <ul>
        <li><a href="#"> </a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="node_role.php">NodeRole</a></li>
        <li><a href="xt_aktuator.php">XT ExeTest Katuator</a></li>
        <li><a href="test.php">..</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<br><br>
  
<style>
  table {
      border-collapse: collapse;
      width: 100%;
      border: 2px solid black; /* Ketebalan garis tepi tabel */
  } 
  td  {
      text-align: left;
      padding: 8px;
      border-bottom: 1px solid black; /* Garis antara baris */
  } 
  th {
      text-align: left;
      padding: 8px;
      border-bottom:2px solid black; /* Garis antara baris */
  } 
  th,td { 
      border-right: 1px solid black; /* Garis antara kolom header */
  }  
  tr:nth-child(even) {
      background-color: #f2f2f2;
  }   

/* CSS untuk mode gelap */
@media (prefers-color-scheme: dark) {
    body {
        background-color: #333; /* Warna latar belakang gelap */
        color: #fff; /* Warna teks putih untuk kontras yang baik */
    }
}

  
/* Gaya untuk menu popup */
#menuButton {
    position: fixed;
    top: 9px;
    left: 9px;
    z-index: 999; /* Pastikan tombol di atas konten lainnya */
}

.popup-menu {
    position: fixed;
    top: 0; 
    left: -300px; /* Mulai dari luar layar */
    width: 250px;
    height: 100%;
    background-color: #333;
    padding: 20px;
    transition: left 0.7s ease; /* Transisi untuk efek keluar masuk */
}
.popup-menu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
.popup-menu ul li {
    padding: 10px 0;
    border-bottom: 1px solid #555;
}
.popup-menu ul li:last-child {
    border-bottom: none; /* Menghilangkan garis bawah pada item terakhir */
}
.popup-menu ul li a {
    color: #fff;
    text-decoration: none;
}
</style>

<script>
function onLoadFunctions() {
//   displayDateTime();
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

// Fungsi untuk menampilkan atau menyembunyikan menu popup
function toggleMenu() {
    var menu = document.getElementById('popupMenu'); 
    if (menu.style.left !== '0px') {
        menu.style.left = '0px';
    } else {
        menu.style.left = '-300px';
    }
}   

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    
    document.getElementById("menuButton").style.display = "block";
    // if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    //     document.getElementById("menuButton").style.display = "block";
    // } else {
    //     document.getElementById("menuButton").style.display = "none";
    // }
}

</script>
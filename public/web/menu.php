<!DOCTYPE html>
<html lang="en"> 
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="favicon.png">
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
        <li id="subMenu1" style="color : greenyellow">NodeRole▶<ul> 
            <li class="submenu"><a href="node_role.php">NodeRole List</a></li>
            <li class="submenu"><a href="node_role_form.php">NodeRole Baru</a></li>
        </ul></li> 
        <li id="subMenuJadwal" style="color : greenyellow">Jadwal Aktuator▶<ul> 
            <li class="submenu"><a href="nr_jadwal_list.php">List Jadwal</a></li>
            <li class="submenu"><a href="nr_week.php">NodeRole Mingguan</a></li>
            <li class="submenu"><a href="nr_date.php">NodeRole Tanggal</a></li>
        </ul></li> 
        <li><a href="xt_aktuator.php">XT ExeTest Katuator</a></li>
        <li><a href="test.php">..</a></li> 
        <li><a href="pwd_update.php">Password Update</a></li> 
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<br>
<style>
  table {
      border-collapse: collapse;
      width: 100%;
      border: 2px solid #090945; /* Ketebalan garis tepi tabel */
  } 
  td  {
      text-align: left;
      padding: 8px;
      border-bottom: 1px solid #090945; /* Garis antara baris */
  } 
  th {
      text-align: left;
      padding: 8px;
      border-bottom:2px solid #090945; /* Garis antara baris */
  } 
  th,td { 
      border-right: 1px solid #090945; /* Garis antara kolom + header */
  }  
  tr:nth-child(even) {
      background-color: #f2f2f9;
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
    /* background-color: #333; */
    padding: 20px;
    transition: left 0.7s ease; /* Transisi untuk efek keluar masuk */
    background-color: rgba(27,27,45,0.9); /* Latar belakang semi transparan */ 
    z-index: 9999; /* Menempatkan popup di atas elemen lain */
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

/* set posisi sub menuitem */
ul {
    list-style-type: none;
    padding: 0;
}

ul li {
    margin: 0;
    padding: 0;
}

ul li.submenu {
    padding-left: 20px;
    position: relative;
}

ul li.submenu::before {
    content: '\203A'; /* kode unicode untuk simbol panah ke kanan */
    position: absolute;
    left: 0;
}

ul ul {
    display: none;
}

ul li:hover ul {
    display: block;
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

window.onclick = function(event) {  
    var modal = document.getElementById("menuButton");
    var modal1 = document.getElementById("subMenu1");
    var modal2 = document.getElementById("subMenuJadwal");
    if (event.target != modal && event.target != modal1 && event.target != modal2 ) { 
        var menu = document.getElementById('popupMenu');  
        menu.style.left = '-300px'; 
    } 
}

window.onscroll = function() {scrollFunction()};

function scrollFunction() { 
    document.getElementById("menuButton").style.display = "block"; 
}

</script>
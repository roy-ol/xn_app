<?php  
require_once __DIR__ . '/menu.php';
    
?> 
<head>
<title>Jadwal Pertanggal</title>
</head>
</head>

<link rel="stylesheet" href="css/cssumum1.css">
<style>
    /* Style untuk popup */
  .popup {
    display: none; /* Awalnya disembunyikan */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7); /* Latar belakang semi transparan */ 
    z-index: 9999; /* Menempatkan popup di atas elemen lain */
  }
  .popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 300px;
  }

  .center-text {
    text-align: center;
    vertical-align: middle;
  }
</style> 
<?php 
$id_role = 0 ;
$id_node = 0 ;
splashTengah("on Developing",1);
?>
<h2>Jadwal Mingguan</h2>

</body></html>
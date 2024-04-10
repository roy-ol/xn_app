<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>

<?php  
require_once __DIR__ . '/menu.php';
$id_Kebun=0;
if(isset($_POST['idKebun'])) $id_Kebun = $_POST['idKebun']; 
$kebunTerpilih="";
?>
 
<form action="?" method="post" enctype="multipart/form-data" id="formKebun">
  <div class="form-group"> 
      <select id="idKebun" name="idKebun"  onchange="submitFormKebun()">
        <option value=0> - - - pilih kebun - - - - </option>;
        <?php 
          $query = "SELECT k.id,p.nama as prs,k.nama,substr(k.keterangan,1,27) as keterangan
            FROM kebun k, perusahaan p where p.id = k.id_perusahaan and p.id = " . $id_perusahaan ; 
          $result = $cUmum->ambilData($query); 
          // Memeriksa apakah query berhasil dijalankan
          if ($result) {
              while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if($row['id'] === $id_Kebun){
                  $kebunTerpilih = $row['prs'] . ' : ' . $row['nama'] .' - ' . $row['keterangan'] ;
                }
                echo '<option value="' . $row['id'] . '">' . $row['prs'] . ':' . $row['nama'] .'-' . $row['keterangan'] . '</option>';
              }
          } else { echo "Error: " . $query . "<br>" . $conn->errorInfo()[2];}
        ?>
      </select>
  </div>
  <!-- <input type="submit" value="refresh" name="refresh"> -->
</form>

<script>
function submitFormKebun() {
    document.getElementById("formKebun").submit(); // Mengirimkan formulir saat opsi dipilih
}
</script>

<?php

  echo $kebunTerpilih; 
  echo "<br><br>";
  echo "Chip Last Hit :";
  $sql = "SELECT c.chip,c.keterangan,hc.waktu,hc.hit FROM `hit_chip` hc, chip c where hc.id_chip=c.id and c.id_kebun=$id_Kebun";
  $sHitTabel=bikinTabelSQL($sql);
  echo $sHitTabel;


  
  echo "<br><br>";
  echo "Eksekutor Last Hit :";
  $sql = "SELECT n.nama Node,DATE(l.created) Tanggal , l.relay Rly, 
    TIME(l.created) Strt,TIME(l.waktu)Fin , l.exeval Val, l.exe_v1 V1, l.exe_v2 V2,  
     TIMEDIFF(l.waktu, l.created) Durasi FROM log_eksekutor l 
    JOIN node n ON l.id_node = n.id
    JOIN chip c ON n.id_chip = c.id
    JOIN kebun k ON c.id_kebun = k.id
    WHERE k.id_perusahaan = $id_perusahaan
    ORDER BY l.id DESC 
    LIMIT 10;";
  // $sql = "SELECT n.nama Node,DATE(l.created) Tanggal , l.relay Rly, TIME(l.created) Strt, l.exeval Val, l.exe_v1 V1, l.exe_v2 V2, TIME(l.waktu)Fin ,  IF(TIME_FORMAT(TIMEDIFF(l.waktu, l.created), '%i') + 0 = 0, 
  //   CONCAT(TIME_FORMAT(TIMEDIFF(l.waktu, l.created), '%s'), '``'),
  //   CONCAT(TIME_FORMAT(TIMEDIFF(l.waktu, l.created), '%i') + 0, '`', TIME_FORMAT(TIMEDIFF(l.waktu, l.created), '%s'), '``')) as Durasi FROM log_eksekutor l 
  //   JOIN node n ON l.id_node = n.id
  //   JOIN chip c ON n.id_chip = c.id
  //   JOIN kebun k ON c.id_kebun = k.id
  //   WHERE k.id_perusahaan = $id_perusahaan
  //   ORDER BY l.id DESC 
  //   LIMIT 10;";
  $sHitTabel=bikinTabelSQL($sql);
  echo $sHitTabel;
  
?>



</body>
</html>
 
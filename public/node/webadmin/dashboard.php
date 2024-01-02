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
            FROM kebun k, perusahaan p where p.id = k.id_perusahaan"; 
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
  $query = "call getStatusKebun($id_Kebun) " ;
  $sTabel = bikinTabelSQL($query);
  echo $sTabel;  

  echo "<br><br>";
  echo "Chip Last Hit :";
  $sql = "SELECT c.chip,c.keterangan,hc.waktu,hc.hit FROM `hit_chip` hc, chip c where hc.id_chip=c.id and c.id_kebun=$id_Kebun";
  $sHitTabel=bikinTabelSQL($sql);
  echo $sHitTabel;


?>



</body>
</html>
 
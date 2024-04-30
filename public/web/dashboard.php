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
          // bikinOption($query,1,'prs',' : ','nama', ' - ' ,'keterangan');        
          $result = $cUmum->ambilData($query); 
          // Memeriksa apakah query berhasil dijalankan
          if ($result) {
            $isAwal=true;
              while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if($isAwal && $id_Kebun == 0 ){
                  $id_Kebun = $row['id'];
                  $isAwal = false;
                }
                if($row['id'] === $id_Kebun){
                  $kebunTerpilih = $row['prs'] . ' : ' . $row['nama'] .' - ' . $row['keterangan'] ;
                  echo '<option value="' . $row['id'] . '" SELECTED >' . $row['prs'] . ':' . $row['nama'] .'-' . $row['keterangan'] . '</option>';
                } else{ 
                  echo '<option value="' . $row['id'] . '">' . $row['prs'] . ':' . $row['nama'] .'-' . $row['keterangan'] . '</option>';
                }
              }
          } else { echo "Error: " . $query . "<br>" . $conn->errorInfo()[2];}  
        ?>
      </select>
  </div>
  <!-- <input type="submit" value="refresh" name="refresh"> -->
</form>
<br><?=$kebunTerpilih;?><br><br> Chip Last Hit :

<script>  
function submitFormKebun() {
    document.getElementById("formKebun").submit(); // Mengirimkan formulir saat opsi dipilih
}
</script>

<?php

  $sql = "SELECT c.chip,c.keterangan,hc.waktu,hc.hit FROM `hit_chip` hc, chip c where hc.id_chip=c.id and c.id_kebun=$id_Kebun";
  $sHitTabel=bikinTabelSQL($sql);
  echo $sHitTabel;


  
  echo "<br><br>";
  echo "Eksekutor Last Hit :";
  $sql = "SELECT CONCAT(n.nama,'\n', DATE(l.created)) Node , CONCAT('R:',l.relay ,'\n', l.exeval) 'Relay Val', 
  CONCAT(TIME(l.created),'\n',  COALESCE(TIME(l.waktu),'-- : -- : --')) 'Start Fin' , CONCAT(l.exe_v1,'\n', l.exe_v2) 'V1 V2',  
  TIMEDIFF(l.waktu, l.created) Durasi FROM log_eksekutor l 
    JOIN node n ON l.id_node = n.id
    JOIN chip c ON n.id_chip = c.id
    JOIN kebun k ON c.id_kebun = k.id
    WHERE k.id_perusahaan = $id_perusahaan
    ORDER BY l.id DESC 
    LIMIT 16;"; 
  $sHitTabel=bikinTabelSQL($sql);
  echo $sHitTabel;
  
?>



</body>
</html>
 
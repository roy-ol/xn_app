<!DOCTYPE html>
<html>
<head>
    <title>XT_Aktuator</title>
</head>

<style>
  .two-column-form {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px; /* Jarak antar elemen */
}

/* Atau menggunakan flexbox untuk tata letak dua kolom */

/* .two-column-form {
  display: flex;
  flex-wrap: wrap;
}

.form-group {
  flex-basis: calc(50% - 15px); /* 50% lebar elemen - jarak antar elemen 
} */

.form-group {
  margin-bottom: 15px;
}

/* label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
} */

input,
select {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
}

input[type="submit"] {
  background-color: #4caf50;
  color: white;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #45a049;
}

</style>

<?php require_once __DIR__ . '/menu.php'; // berisi juga tag <body> 
echo "<h4>Execution Test Aktuator</h4>";
if(1 == 0 ) $cUmum = new cUmum();
$sPesan ="";
 
//======untuk eksekusi =============
//==============================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eksekusi"])) {
  // Ambil data dari formulir
  $idNode = $_POST["id_node"];
  $relay = $_POST["relay"];
  $exeval = $_POST["exeval"];
  $exe_v1 = $_POST["exe_v1"];
  $exe_v2 = $_POST["exe_v2"]; 
  if($idNode > 0){
    $paramInsert["id_node"] = $idNode;
    $paramInsert["relay"]= $relay;
    $paramInsert["exeval"]= $exeval;
    $paramInsert["exe_v1"]= $exe_v1;
    $paramInsert["exe_v2"]= $exe_v2;
  
    $userID = $_SESSION['userID'] ;
    // Cek apakah sudah ada record dengan id_node yang sama dan flag = 0
    $checkSql = "SELECT COUNT(*) FROM `node_xt` WHERE `id_node` = :id_node AND `flag` = 0";
    $checkParams = array('id_node' => $idNode);

    $result = $cUmum->ambil1Data($checkSql, $checkParams);

    if ($result > 0) {
        // Record dengan id_node yang sama dan flag = 0 sudah ada, maka tidak perlu melakukan INSERT
        $sPesan = "Record sudah ada dengan flag = 0 untuk id_node yang sama.";
    } else {
        // Jika tidak ada record dengan id_node yang sama dan flag = 0, lakukan INSERT
        $sql = "INSERT INTO `node_xt` (`id_node`, `relay`, `exeval`, `exe_v1`, `exe_v2`, `updater`, `flag`)
            VALUES (:id_node, :relay, :exeval, :exe_v1, :exe_v2, $userID, 0)";
        $cUmum->eksekusi($sql, $paramInsert);
    } 
  } else{
    $sPesan = "Belum ada Node Aktuator dipilih";
  }
}
 


?>


<br>Node to test : <?= $sPesan ?> 
<form action="?" method="post" enctype="multipart/form-data" class="two-column-form">
  <div class="form-group"> 
    <select id="node_id" name="id_node">
      <option value="0">- - - Pilih Node aktuator - - -</option>
      <?php 
        $query = "SELECT n.id, c.chip, n.nama, n.keterangan FROM `node` n 
        INNER JOIN chip c ON c.id = n.id_chip 
        INNER JOIN tipe t ON c.id_tipe = t.id 
        INNER JOIN kebun k ON c.id_kebun = k.id
        WHERE t.kelompok IN (2, 3) and k.id_perusahaan=$id_perusahaan ORDER BY n.id DESC";       
        bikinOption($query, "chip", " - ", "nama"," :=> ","keterangan"); 
      ?>
    </select>
  </div>

  <div class="form-group">
    <label for="relay">Relay</label>
    <input type="number" id="relay" name="relay" value=1  title="Masukkan hanya angka"  oninput="this.value = this.value.replace(/[^0-9]/g, '');">
  </div>

  <div class="form-group">
    <label for="exeval">Exeval</label>
    <input type="number" id="exeval" name="exeval" placeholder="0" oninput="this.value = this.value.replace(/[^0-9]/g, '');" title="Masukkan hanya angka">
  </div>

  <div class="form-group">
    <label for="exe_v1">Exe_v1</label>
    <input type="number" id="exe_v1" name="exe_v1" placeholder="0" oninput="this.value = this.value.replace(/[^0-9]/g, '');" title="Masukkan hanya angka">
  </div>

  <div class="form-group">
    <label for="exe_v2">Exe_v2</label>
    <input type="number" id="exe_v2" name="exe_v2" placeholder="0" oninput="this.value = this.value.replace(/[^0-9]/g, '');" title="Masukkan hanya angka">
  </div>

  <input type="submit" value="Eksekusi" name="eksekusi">
</form>


<?php 
$sql = "SELECT  n.nama Node,  nx.created,nx.updated ,nx.relay Rel, nx.exeval Val,
  nx.exe_v1 V1, nx.exe_v2 V2, nx.flag F
  FROM node n
  INNER JOIN node_xt nx on n.id = nx.id_node
  INNER JOIN chip c on n.id_chip = c.id
  INNER JOIN kebun k on c.id_kebun = k.id
  where k.id_perusahaan = 5
  order by nx.created desc limit  9;";

$tabel = bikinTabelSQL($sql);
echo "<br>Chip Repo<br>";
echo $tabel; 
?>


</body>
</html>
 
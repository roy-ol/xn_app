<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Node Role ++</title>
<style>
  /* CSS untuk tampilan modal */
  .modal {
      display: none; /* Sembunyikan modal secara default */
      position: fixed; /* Tetapkan posisi elemen */
      z-index: 1; /* Set z-index agar modal muncul di atas elemen lain */
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto; /* Aktifkan overflow untuk mengizinkan scroll jika konten terlalu panjang */
      background-color: rgba(0,0,0,0.4); /* Background dengan transparansi */
  }
  .modal-content {
      background-color: #fefefe;
      margin: 15% auto; /* Atur margin agar modal berada di tengah layar */
      padding: 20px;
      border: 1px solid #888;
      width: 80%; /* Atur lebar modal */
      border-radius: 8px;
  }
  .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
  }
  .close:hover,
  .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
  }
  /* CSS untuk mengatur tata letak tombol di tengah */
  .center-button {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 9vh;
  }

  /* Styling untuk tombol */
  .btn-submit {
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
  }
</style>
 
</head>
<body>
<?php  
  require_once __DIR__ . '/menu.php';
  if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cUmum sebagai class sebelumnya  
    // $cUser = new cUser();
    $cUmum = new cUmum();
  }  
?>
   
<h2>Node Role Form</h2>
<form action="insert_node_role.php" method="post">
  <table>
    <tr>
      <td><label for="keterangan">NodeRole :</label></td>
      <td><input type="text" id="keterangan" name="keterangan" required></td>
    </tr> 
    
    <tr>
      <td><label for="pola">Pola:</label></td>
      <td><input type="text" id="pola" name="pola" required></td>
    </tr> 
     
    <tr>
      <td><label for="exeval">Exe Val:</label></td>
      <td><input type="text" id="exeval" name="exeval" required></td>
    </tr> 
    
    <tr>
      <td><label for="val1">Value 1:</label></td>
      <td><input type="text" id="val1" name="val1" required></td>
    </tr> 
    <tr>
      <td><label for="val2">Value 2:</label></td>
      <td><input type="text" id="val2" name="val2" required></td>
    </tr> 
    <tr><td><label for="satuan">satuan</label></td><td><input type="text" id="satuan" name="satuan" required></td></tr> 
    <tr><td><label for="reff_node">reff_node</label></td><td><input type="text" id="reff_node" name="reff_node" required></td></tr> 
    <tr><td><label for="ref_n1">ref_n1</label></td><td><input type="text" id="ref_n1" name="ref_n1" required></td></tr> 
    <tr><td><label for="ref_n2">ref_n2</label></td><td><input type="text" id="ref_n2" name="ref_n2" required></td></tr> 
    <tr><td><label for="ref_n4">ref_n4</label></td><td><input type="text" id="ref_n4" name="ref_n4" required></td></tr> 
    <tr><td><label for="ref_n3">ref_n3</label></td><td><input type="text" id="ref_n3" name="ref_n3" required></td></tr> 
    <tr><td><label for="ref_n5">ref_n5</label></td><td><input type="text" id="ref_n5" name="ref_n5" required></td></tr> 
    <tr><td><label for="relay">relay</label></td><td><input type="text" id="relay" name="relay" required></td></tr> 
    <tr><td><label for="repeater">repeater</label></td><td><input type="text" id="repeater" name="repeater" required></td></tr> 
    <tr><td><label for="nilai_1">nilai_1</label></td><td><input type="text" id="nilai_1" name="nilai_1" required></td></tr> 
    <tr><td><label for="nilai_2">nilai_2</label></td><td><input type="text" id="nilai_2" name="nilai_2" required></td></tr> 
    <tr><td><label for="nilai_3">nilai_3</label></td><td><input type="text" id="nilai_3" name="nilai_3" required></td></tr> 
    <tr><td><label for="nilai_4">nilai_4</label></td><td><input type="text" id="nilai_4" name="nilai_4" required></td></tr> 
    <tr><td><label for="nilai_5">nilai_5</label></td><td><input type="text" id="nilai_5" name="nilai_5" required></td></tr> 
    <tr><td><label for="nilai_6">nilai_6</label></td><td><input type="text" id="nilai_6" name="nilai_6" required></td></tr> 
    <tr><td><label for="nilai_7">nilai_7</label></td><td><input type="text" id="nilai_7" name="nilai_7" required></td></tr> 
    <tr><td><label for="keterangan">keterangan</label></td><td><input type="text" id="keterangan" name="keterangan" required></td></tr> 
    <tr><td><label for="id_memo">id_memo</label></td><td><input type="text" id="id_memo" name="id_memo" required></td></tr> 

  </table>  
  <div class="center-button">
    <input type="submit" value="Simpan" class="btn-submit">
  </div>
</form> 
  
<br><br><br>

<?php

$sql = "SELECT nr.keterangan NodeRole, nr.relay rl,nr.exeval xVal, CONCAT('V1:',nr.exe_v1 , ' V2:', 
nr.exe_v2) Val, CONCAT('Ref:',nr.reff_node, ' Nil:' , nr.nilai_1) Ref,nr.updated
  FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan ";


$sHitTabel=bikinTabelSQL($sql);
echo $sHitTabel;

?>

<script>
// Fungsi untuk membuka modal
function openModal() {
    document.getElementById("myModal").style.display = "block";
}

// Fungsi untuk menutup modal
function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

// Fungsi untuk buka link
function bukaLink() {
  window.location.href = "dashboard.php";
}

// Menutup modal ketika pengguna mengklik di luar modal
window.onclick = function(event) {
    var modal = document.getElementById("myModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>


</body>
</html>
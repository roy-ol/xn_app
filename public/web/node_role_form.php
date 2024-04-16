<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Node Role</title>
 
</head>
<body>
<?php  
  require_once __DIR__ . '/menu.php';
  if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cUmum sebagai class sebelumnya  
    // $cUser = new cUser();
    $cUmum = new cUmum();
  }  
?>
 
<div class="modal-content">
  <span class="close" onclick="closeModal()">&times;</span>
  <h2>Form Insert Data Node Role</h2>
  <form action="insert_node_role.php" method="post">
    <table>
      <tr>
        <td><label for="id_perusahaan">ID Perusahaan:</label></td>
        <td><input type="text" id="id_perusahaan" name="id_perusahaan" required></td>
      </tr> 
      <tr>
        <td><label for="pola">Pola:</label></td>
        <td><input type="text" id="pola" name="pola" required></td>
      </tr>
      <!-- Tambahkan baris untuk setiap kolom pada tabel node_role sesuai strukturnya -->
    </table>
      <!-- Tambahkan form group untuk setiap kolom pada tabel node_role sesuai struktur -->
      <div class="form-group">
          <input type="submit" value="Submit" class="btn-submit">
      </div>
  </form>
</div>

</div>
<span class="close" onclick="bukaLink()">&#9660;</span>

<?php

$sql = "SELECT nr.keterangan, nr.relay,nr.exeval, nr.exe_v1, 
  nr.exe_v2, nr.reff_node, nr.nilai_1,nr.updated 
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
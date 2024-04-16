<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Insert Data Node Role</title>
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
</style>
</head>
<body>
<?php require_once __DIR__ . '/menu.php'; ?>

<!-- Tombol untuk membuka modal -->
<button onclick="openModal()">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal">

  <!-- Konten modal -->
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

require_once __DIR__ . '/menu.php';
if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cUmum sebagai class sebelumnya  
  // $cUser = new cUser();
  $cUmum = new cUmum();
}  

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
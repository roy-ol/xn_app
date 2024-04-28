<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Node Role</title>
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
<form action="../web/fungsi/addNodeRole1" method="post">
  <input type="hidden" name="id_memo" value=0>
  <input type="hidden" name="id_node_role" value=0>
  <table>
    <tr><td><label for="keterangan">NodeRole :</label></td><td>
      <input type="text" id="keterangan" name="keterangan"  title="Keterangan Role" required></td></tr> 
    <tr><td><label for="pola">Pola:</label></td><td>
    <select id="pola" name="pola" onchange="showKetDetail(this.value)">
    <option value="0"> - - - pilih pola - - - - </option>
    <?php  
        $sSqlOp1 = "select id, pola from nrpola";
        bikinOption($sSqlOp1,"pola");
    ?>
    </select> 
    <label id="lblMemPola" for="pola" onclick="tampilMemoPola()"> </label>
     
    </td></tr>      
    <tr><td><label for="relay">relay</label></td><td><input type="number" id="relay" name="relay" required></td></tr> 
    <tr><td><label for="exeval">Exe Val:</label></td><td><input type="number" id="exeval" name="exeval" required></td></tr>   
    <tr><td><label for="val1">Value 1:</label></td><td><input type="number" id="val1" name="val1"  ></td></tr> 
    <tr><td><label for="val2">Value 2:</label></td><td><input type="number" id="val2" name="val2"  ></td></tr> 
    
    <tr><td><label for="reff_node">reff_node</label></td><td><input type="number" id="reff_node" name="reff_node"  ></td></tr> 
    <tr><td><label for="nilai_1">nilai_1</label></td><td><input type="number" id="nilai_1" name="nilai_1"  ></td></tr> 
    <tr><td><label for="repeater">repeater</label></td><td>
      <label><input type="radio" name="repeater" value=0 checked> Off</label>
      <label><input type="radio" name="repeater" value=1> On</label></td></tr> 
    <tr><td><label for="memo">Memo : </label></td><td><textarea id="memo" name="memo" maxlength="2000" height = "auto"></textarea></td></tr> 
  </table>  
  <div class="center-button">
    <input type="submit" value="Simpan" class="btn-submit">
  </div>
</form> 
<!-- Popup  Form -->
<div class="popup" id="popup">
    <div class="popup-content">   
      <label id="popup_memo"></label><br><br> 
      <button onclick="hidePopup()" >Tutup</button>
    </div>
</div>
<br><br><br>

<?php

$sql = "SELECT nr.keterangan NodeRole, nr.relay rl,nr.exeval xVal, CONCAT('V1:',nr.exe_v1 , ' V2:', 
nr.exe_v2) Val, CONCAT('Ref:',nr.reff_node, ' Nil:' , nr.nilai_1) Ref,nr.updated
  FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan ";


$sHitTabel=bikinTabelSQL($sql);
echo $sHitTabel;

?>

<script>
var sMemoPola = "Ket";

function tampilMemoPola() {
  // if(sMemoPola.length() < 2){
    if(sMemoPola === null || sMemoPola.length < 2){
      sMemoPola = "Tidak ada Keterangan khusus untuk pola ini "; 
    } 
    showPopup(); 
    // alert(sMemoPola); //webview standar tidak bisa menampilkan alert 
}

// Fungsi untuk menampilkan popup
function showPopup() {
  document.getElementById('popup_memo').textContent = sMemoPola;
  document.getElementById('popup').style.display = 'block';
}

// Fungsi untuk menyembunyikan popup
function hidePopup() {
  document.getElementById('popup').style.display = 'none';
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

function showKetDetail(id_pola) {
    // Buat objek data dengan ID pola
    const data = {
        idPola: id_pola
    };

    // Kirim permintaan AJAX dengan metode POST
    fetch('../web/fungsi/ketPola', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log("Status respons:", response.status);
        return response.json();
    })
    .then(data => {
        // Tampilkan balon info dengan keterangan pola 
        const ketPolaLabel = document.getElementById('lblMemPola'); 
        ketPolaLabel.textContent = '\u25A5'; 
        // ketPolaLabel.textContent = '\u25BA'; 
        ketPolaLabel.style.cursor = 'pointer';

        document.getElementById('pola').title = data.keterangan;
        // alert(data.memo); 
        sMemoPola = data.memo;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

</script>


</body>
</html>
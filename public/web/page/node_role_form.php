<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 
 
$cTemp->setHeaderCap("Node Role Form"); 
$cTemp->loadHeader();
?>

<style>
  /* Style untuk popup */
  .popup {
    display: none;
    /* Awalnya disembunyikan */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    /* Latar belakang semi transparan */
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



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <?php   
  $sURL_Action = "../fungsi/addNodeRole1"; 
  $btnCap = "Tambah";
  $id_role=0;
  $repeater = 0;
  $id_memo = 0;
  
  $pola  = 0;     
  $exeval  = null;      
  $exe_v1 = null;    
  $exe_v2 = null;    
  $reff_node = null;    
  $relay = null;    
  $repeater = null;    
  $nilai_1 = null;    
  $keterangan = null;     
  $memo = null;    
  $updater  = null;    


  if($val1 == "nrid") {
    $id_role = $val2;
    $sURL_Action = "../fungsi/updateNodeRole1"; 
    $btnCap = "Update";
    $sql="SELECT pola, exeval, exe_v1, exe_v2, reff_node,
      relay, repeater, nilai_1, keterangan, id_memo, memo, updater 
      FROM node_role nr 
      LEFT JOIN memo m ON m.id = nr.id_memo
      where id_perusahaan = $id_perusahaan and nr.id= :nrid " ;
    $barisData = $cUmum->ambil1Row($sql, ["nrid"=>$id_role] );
    if($barisData){
      $pola  = $barisData['pola'];     
      $exeval  = $barisData['exeval'];      
      $exe_v1 = $barisData['exe_v1'];
      $exe_v2 = $barisData['exe_v2'];
      $reff_node = $barisData['reff_node'];
      $relay = $barisData['relay'];
      $repeater = $barisData['repeater'];
      $nilai_1 = $barisData['nilai_1'];
      $keterangan = $barisData['keterangan'];
      $id_memo = $barisData['id_memo'];
      $memo = $barisData['memo'];
      $updater  = $barisData['updater'];
    }
  }
  if($pola > 0){
    echo '<script>';
    echo 'window.onload = function() { showKetDetail(' . $pola . '); }';
    echo '</script>';
  }
?>
  <form action="<?=$sURL_Action?>" method="post" id="nr_form">
    <input type="hidden" name="id_memo" value=<?=$id_memo;?>>
    <input type="hidden" name="id_role" value=<?=$id_role;?>>
    <table>
      <tr>
        <td><label for="keterangan">NodeRole :</label></td>
        <td>
          <input type="text" id="keterangan" value="<?=$keterangan;?>" name="keterangan" title="Keterangan Role"
            required></td>
      </tr>
      <tr>
        <td><label for="pola">Pola:</label></td>
        <td>
          <select id="pola" name="pola" value=<?=$pola;?> onchange="showKetDetail(this.value)">
            <option value=0> - - - pilih pola - - - - </option>
            <?php  
              $sSqlOp1 = "select id, pola from nrpola";
              bikinOption($sSqlOp1, $pola,"pola");
            ?>
          </select>
          <label id="lblMemPola" for="pola" onclick="tampilMemoPola()"> </label>

        </td>
      </tr>
      <tr>
        <td><label for="relay">relay</label></td>
        <td><input type="number" value=<?=$relay;?> id="relay" name="relay" required></td>
      </tr>
      <tr>
        <td><label for="exeval">Exe Val:</label></td>
        <td><input type="number" value=<?=$exeval;?> id="exeval" name="exeval" required></td>
      </tr>
      <tr>
        <td><label for="val1">Value 1:</label></td>
        <td><input type="number" value=<?=$exe_v1;?> id="val1" name="val1"></td>
      </tr>
      <tr>
        <td><label for="val2">Value 2:</label></td>
        <td><input type="number" value=<?=$exe_v2;?> id="val2" name="val2"></td>
      </tr>

      <tr>
        <td><label for="reff_node">reff_node</label></td>
        <td><input type="number" value=<?=$reff_node;?> id="reff_node" name="reff_node"></td>
      </tr>
      <tr>
        <td><label for="nilai_1">nilai_1</label></td>
        <td><input type="number" value=<?=$nilai_1;?> id="nilai_1" name="nilai_1"></td>
      </tr>
      <tr>
        <td><label for="repeater">repeater</label></td>
        <td>
          <?php
        if($repeater == 1){ 
          echo '<label><input type="radio" name="repeater" value=0 > Off</label>';
          echo '<label><input type="radio" name="repeater" value=1 checked> On</label></td></tr>';  
        }else{
          echo '<label><input type="radio" name="repeater" value=0 checked> Off</label>';
          echo '<label><input type="radio" name="repeater" value=1> On</label></td></tr>';
        }
      ?>

      <tr>
        <td><label for="memo">Memo : </label></td>
        <td><textarea id="memo" name="memo" maxlength="2000" style="height:auto;"><?=$memo;?></textarea></td>
      </tr>
    </table>
    <div class="center-button">
      <a class="btn btn-app" onclick="submitForm()">
        <i class="fas fa-save"></i> <?=$btnCap?>
      </a>

    </div>
  </form>
  <!-- Popup  Form -->
  <div class="popup" id="popup">
    <div class="popup-content">
      <label id="popup_memo"></label><br><br>
      <button onclick="hidePopup()">Tutup</button>
    </div>
  </div>
  <br><br><br>

  <?php

$sql = "SELECT nr.keterangan NodeRole, nr.relay rl,nr.exeval xVal, CONCAT('V1:',nr.exe_v1 , ' V2:', 
nr.exe_v2) Val, CONCAT('Ref:',nr.reff_node, ' Nil:' , nr.nilai_1) Ref,nr.updated
  FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan ";


$sHitTabel=bikinTabelSQL3($sql);
echo $sHitTabel;

?>

  <script>
    var sMemoPola = "Ket";

    function tampilMemoPola() {
      // if(sMemoPola.length() < 2){
      if (sMemoPola === null || sMemoPola.length < 2) {
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
    window.onclick = function (event) {
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
      fetch('../fungsi/ketPola', {
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

    function submitForm() {
      document.getElementById('nr_form').submit();
    }
  </script>

</div>
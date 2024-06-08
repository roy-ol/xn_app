<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 
 
$cTemp->setTitle("XT Aktuator"); 
$cTemp->loadHeader(); 

$sPesan ="Node to Test:";

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
  } else {
      $sPesan = "Belum ada Node Aktuator dipilih";
  }
}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="col-12">
        <h3>Execute Test</h3>
        <span>Direct Order to Node Aktuator</span>
      </div>
    </div>
  </section>

  <div class="col-md-12">
    <div class="card card-primary" id="card_node">
      <div class="card-header">
        <h3 class="card-title"><?=$sPesan;?></h3>
      </div>
      <div class="card-body">
        <form action="?" method="post" enctype="multipart/form-data" class="two-column-form" id="form_xt">
          <div class="row">
            <label for="node_id" class="col-3 col-form-label">
              <span class="float-right"> Node </span>
            </label>
            <select id="node_id" name="id_node" class="form-control col-8" onchange="show_modal_memo(this.value)">
              <option value="0">- - - Pilih Node aktuator - - -</option>
              <?php 
          $query = "SELECT n.id, c.chip, n.nama, n.keterangan FROM `node` n 
          INNER JOIN chip c ON c.id = n.id_chip 
          INNER JOIN tipe t ON c.id_tipe = t.id 
          INNER JOIN kebun k ON c.id_kebun = k.id
          WHERE t.kelompok IN (2, 3) and k.id_perusahaan=$id_perusahaan ORDER BY n.id DESC";       
          bikinOption($query,0, "chip", " - ", "nama"," :=> ","keterangan"); 
          ?>
            </select>
          </div>
          <div class="form-group row">
            <label for="relay" class="col-3 col-form-label">
              <span class="float-right"> Relay </span>
            </label>
            <input type="number" id="relay" class="col-8" name="relay" value=1 title="Masukkan hanya angka"
              oninput="this.value = this.value.replace(/[^0-9]/g, '');">
          </div>

          <div class="form-group row">
            <label for="exeval" class="col-3 col-form-label">
              <span class="float-right"> Exeval </span>
            </label>
            <input type="number" id="exeval" class="col-8" name="exeval" placeholder="0"
              oninput="this.value = this.value.replace(/[^0-9]/g, '');" title="Masukkan hanya angka">
          </div>

          <div class="form-group row">
            <label for="exe_v1" class="col-3 col-form-label">
              <span class="float-right"> Exe_v1 </span>
            </label>
            <input type="number" id="exe_v1" class="col-8" name="exe_v1" placeholder="0"
              oninput="this.value = this.value.replace(/[^0-9]/g, '');" title="Masukkan hanya angka">
          </div>

          <div class="form-group row">
            <label for="exe_v2" class="col-3 col-form-label">
              <span class="float-right">exe_v2</span>
            </label>
            <input type="number" id="exe_v2" class="col-8" name="exe_v2" placeholder="0"
              oninput="this.value = this.value.replace(/[^0-9]/g, '');" title="Masukkan hanya angka">
          </div>
          <input type="hidden" value="Eksekusi" name="eksekusi">
        </form>
        <div style="justify-content: center; display: flex;">
          <a class="btn btn-app bg-warning center" onclick="submitForm()">
            <i class="fas fa-play"></i> Eksekusi
          </a>
        </div>
      </div>
    </div>
  </div>

  <?php 
  $sql = "SELECT  CONCAT(n.nama,' ', COALESCE( TIME(nx.updated),' --:--:--') ) 'Node Updated', 
    CONCAT('Relay:', nx.relay, ' Val:', nx.exeval) 'Relay Val', 
    CONCAT(DATE(nx.created),' ', TIME(nx.created)) Created, c.chip,
    nx.exe_v1 val1,  nx.exe_v2 val2, nx.flag flag, n.keterangan,nx.id
    FROM node n
    INNER JOIN node_xt nx on n.id = nx.id_node
    INNER JOIN chip c on n.id_chip = c.id
    INNER JOIN kebun k on c.id_kebun = k.id
    where k.id_perusahaan = $id_perusahaan
    order by nx.id desc limit  100;"; 
  $tabel = isiTabelSQL($sql); 
  ?>
  <div class="col-12">
    <div class='card card-primary'>
      <div class="card-header">
        <h3 class="card-title">Log Aktuator</h3>
      </div>
      <table id="log_xt" class="table table-bordered">
        <?=$tabel;?>
      </table>
    </div>
  </div>
</div>

<div class="modal" id="modal_memo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Aktuator</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">OK</button> -->
      </div>
    </div>
  </div>
</div>


<script>
  //fungsi show modal
  //ambil keterang node berdasarkan id/kirim ajax ke server
  function show_modal_memo(id) {
    $.ajax({
      type: 'POST',
      url: '../fungsi/ketNode',
      dataType: 'json',
      data: {
        'idKey': id
      },
      success: function (data) {
        $('#card_node .card-title').html(data.nama + ' (' + data.chip + ')');
        $('#modal_memo .modal-title').html(data.nama);
        $('#modal_memo .modal-body').html(
          data.ketNode + '<br>' + data.kebun + '<br>' + data.ketKebun +
          '<br>' + data.ketChip + '<br>' + data.memo
        );
        $('#modal_memo').modal('show');
      }
    });
  }

  $(document).ready(function () {
    $('#log_xt').DataTable({
      "responsive": true,
      "scrollX": true,
      "order": [7, "desc"]
    });
  });


  // fungsi untuk submit form
  function submitForm() {
    document.getElementById("form_xt").submit();
  }
</script>
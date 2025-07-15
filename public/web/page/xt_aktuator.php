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
      $rHasil = $cUmum->eksekusi($sql, $paramInsert); 
      if ($rHasil) {
        $sPesan = "Insert Data "  . $rHasil;
      }
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
    <div class="card card-success" id="card_node"> <!-- Ubah warna card -->
      <div class="card-header bg-gradient-success"> <!-- Tambahkan gradient -->
        <h3 class="card-title mb-0">
          <i class="fas fa-microchip mr-2"></i> <!-- Tambahkan ikon -->
          <?= !empty($sPesan) ? $sPesan : "Pilih Node Aktuator" ?>
        </h3>
      </div>
      <div class="card-body">
        <form action="?" method="post" class="two-column-form" id="form_xt">
          <input type="hidden" name="eksekusi" value="1">
          <!-- Input Group dengan Ikon -->
          <div class="form-group row align-items-center">
            <label class="col-3 col-form-label text-right">
              <i class="fas fa-sitemap text-success"></i> Node
            </label>
            <div class="col-8">
              <select id="node_id" name="id_node" class="form-control select2bs4" onchange="show_modal_memo(this.value)">
                <option value="0">-- Pilih Node Aktuator --</option>
                <?php  
            $query = "SELECT n.id, c.chip, n.nama, n.keterangan FROM `node` n 
            INNER JOIN chip c ON c.id = n.id_chip 
            INNER JOIN tipe t ON c.id_tipe = t.id 
            INNER JOIN kebun k ON c.id_kebun = k.id
            WHERE t.kelompok IN (2, 3) and k.id_perusahaan=$id_perusahaan ORDER BY n.id DESC";       
            bikinOption($query,0, "chip", " - ", "nama"," :=> ","keterangan"); ?>
              </select>
            </div>
          </div>

          <!-- Input Field dengan Deskripsi -->
          <div class="form-group row align-items-center">
            <label class="col-3 col-form-label text-right">
              <i class="fas fa-toggle-on text-success"></i> Relay
            </label>
            <div class="col-8">
              <input type="number" class="form-control" name="relay" 
                    min="1" max="4" value="1" 
                    placeholder="Nomor Relay (1-4)">
            </div>
          </div>

          <!-- Grup Parameter -->
          <div class="border-top mt-3 pt-3">
            <h6 class="text-muted mb-3">
              <i class="fas fa-cogs text-success"></i> Parameter Eksekusi
            </h6>
            
            <div class="form-group row align-items-center">
              <label class="col-3 col-form-label text-right">Nilai Utama</label>
              <div class="col-8">
                <input type="number" class="form-control" name="exeval" 
                      placeholder="Nilai eksekusi utama" required>
              </div>
            </div>

            <div class="form-group row align-items-center">
              <label class="col-3 col-form-label text-right">Parameter 1</label>
              <div class="col-8">
                <input type="number" class="form-control" name="exe_v1" 
                      placeholder="Parameter tambahan 1">
              </div>
            </div>

            <div class="form-group row align-items-center">
              <label class="col-3 col-form-label text-right">Parameter 2</label>
              <div class="col-8">
                <input type="number" class="form-control" name="exe_v2" 
                      placeholder="Parameter tambahan 2">
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="button" class="btn btn-lg btn-success px-5" onclick="submitForm()">
              <i class="fas fa-play-circle mr-2"></i> Jalankan Eksekusi
            </button>
          </div>
        </form>
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

<!-- <div class="modal" id="modal_memo">
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
      </div>
    </div>
  </div>
</div> -->

<div class="modal fade" id="modal_memo" tabindex="-1" role="dialog" aria-labelledby="modal_memo_label">
  <div class="modal-dialog modal-lg" role="document"> <!-- Tambah opsi modal-lg -->
    <div class="modal-content bg-gradient-light"> <!-- Tambahkan gradient -->
      <div class="modal-header bg-success">
        <h4 class="modal-title">
          <i class="fas fa-robot mr-2"></i> <!-- Tambahkan ikon -->
          <span id="modal_memo_label">Detail Aktuator</span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span> <!-- Warna teks putih -->
        </button>
      </div>
      
      <div class="modal-body p-4">
        <div class="row">
          <div class="col-md-6">
            <div class="info-box bg-light mb-4"> <!-- Info Box -->
              <span class="info-box-icon bg-info"><i class="fas fa-microchip"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Identifikasi Node</span>
                <span id="node-identity" class="info-box-number">-</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-box bg-light mb-4">
              <span class="info-box-icon bg-purple"><i class="fas fa-map-marker-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Lokasi</span>
                <span id="node-location" class="info-box-number">-</span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="callout callout-info"> <!-- Callout untuk keterangan -->
          <h5><i class="fas fa-info-circle"></i> Spesifikasi</h5>
          <div id="node-specs" class="py-2">-</div>
        </div>
        
        <div class="callout callout-warning">
          <h5><i class="fas fa-exclamation-triangle"></i> Catatan Operasional</h5>
          <div id="node-memo" class="py-2">-</div>
        </div>
      </div>
      
      <div class="modal-footer justify-content-end">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Update fungsi AJAX untuk mengisi struktur baru
function show_modal_memo(id) {
  $.ajax({
    type: 'POST',
    url: '../fungsi/ketNode',
    dataType: 'json',
    data: {'idKey': id},
    success: function(data) {
      // Validasi response server
      if (!data || data.error) {
        console.error("Data tidak ditemukan");
        return;
      }
      
      // Update header
      $('#modal_memo_label').html(data.nama);
      
      // Update konten terstruktur
      $('#node-identity').html(data.chip);
      $('#node-location').html(data.kebun + ' - ' + data.ketKebun);
      $('#node-specs').html(data.ketNode + '<br>' + data.ketChip);
      $('#node-memo').html(data.memo || 'Tidak ada catatan khusus');
      
      // Tampilkan modal
      $('#modal_memo').modal('show');
    }
  });
} 
  //fungsi show modal
  //ambil keterang node berdasarkan id/kirim ajax ke server
  // function show_modal_memo(id) {
  //   $.ajax({
  //     type: 'POST',
  //     url: '../fungsi/ketNode',
  //     dataType: 'json',
  //     data: {
  //       'idKey': id
  //     },
  //     success: function (data) {
  //       $('#card_node .card-title').html(data.nama + ' (' + data.chip + ')');
  //       $('#modal_memo .modal-title').html(data.nama);
  //       $('#modal_memo .modal-body').html(
  //         data.ketNode + '<br>' + data.kebun + '<br>' + data.ketKebun +
  //         '<br>' + data.ketChip + '<br>' + data.memo
  //       );
  //       $('#modal_memo').modal('show');
  //     }
  //   });
  // }

  $(document).ready(function () {
    $('#log_xt').DataTable({
      "responsive": true,
      "scrollX": true,
      "order": [8, "desc"]
    });
  });


  // fungsi untuk submit form
  function submitForm() {
    document.getElementById("form_xt").submit();
  }
</script>
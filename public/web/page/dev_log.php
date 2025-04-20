<?php  
//device_logging / dashboard lama
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}


$id_Kebun=(isset($_SESSION['idKebun'])) ? $_SESSION['idKebun'] :  0;
$id_Kebun_awal = $id_Kebun; 
if($val1 > 0){
  $id_Kebun =  intval($val1);
  $_SESSION['idKebun'] = $id_Kebun;
  // echo $id_Kebun;
  // exit;
}  

$sSqlKebun="SELECT k.id, k.nama FROM kebun k
JOIN perusahaan p ON k.id_perusahaan = p.id where p.id = $id_perusahaan 
AND k.id = $id_Kebun";
if( $id_Kebun < 1 ){
  $sSqlKebun="SELECT k.id, k.nama FROM kebun k
  JOIN perusahaan p ON k.id_perusahaan = p.id where p.id = $id_perusahaan 
  ORDER BY k.id DESC LIMIT 1";
}

$arrKebun = $cUmum->ambil1Row($sSqlKebun);
if($arrKebun && is_array($arrKebun)){
  $id_Kebun = $arrKebun['id'];
  $kebunTerpilih = $arrKebun['nama']; 
}else{
  $id_Kebun = $id_Kebun_awal;
  $_SESSION['idKebun'] = $id_Kebun;
  $kebunTerpilih = "Pilih Kebun";
} 

//==================== Template load Halaman =========================
$sAddOnNavBar=' 
<li class="nav-item">
<div class="nav-link">Device Log, Kebun '.$kebunTerpilih.'</div>
</li>
';
$cTemp->setAddOnNavBarLeft($sAddOnNavBar); 

$sAddOnNavBar='
<ul class="navbar-nav">
<li class="nav-item">
<a class="nav-link" data-widget="fullscreen" href="#" role="button">
<i class="fas fa-expand-arrows-alt"></i>
</a>
</li>
</ul>'; 
if(empty($val1)){$val1 = "";} 
$cTemp->setTitle("Device Log"); 
$cTemp->setAddOnNavBarRight($sAddOnNavBar); 
$cTemp->loadHeader();
//======================================================================== 
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <p>Kebun Aktif :
        <button id="pilihKebun" type="button" class="btn btn-success"
          onclick="pilihKebun()"><?=$kebunTerpilih;?></button>
      </p>
      <div id="content_dashboard">
      </div>
    </div><!-- /.container-fluid -->
    <div class="col-md-12" id="content_dashboard">
      <!-- card primary -->
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Present State</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>

        <div class="card-body">
          <table id="tblState" class="table table-bordered table-striped">
            <?php
               $sql = "SELECT  CONCAT(c.chip,' ',hc.hit) 'Chip Hit',INSERT(c.keterangan, 11, 0, ' ') AS keterangan,hc.waktu 
               FROM `hit_chip` hc, chip c WHERE hc.id_chip=c.id AND c.id_kebun=$id_Kebun"; 
              $sHitTabel=isiTabelSQL($sql);
              echo $sHitTabel;
            ?>
          </table>
        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
    </div>

    <div class="modal" id="modKebun">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Pilih Kebun</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <select id="kebunId" name="kebunId" title="pilih kebun">
              <option value=0> - - - pilih Kebun - - - - </option>
              <?php            
                $sSQL = "SELECT k.id,p.nama as prs,k.nama,substr(k.keterangan,1,27) as keterangan
                FROM kebun k, perusahaan p where p.id = k.id_perusahaan and p.id = $id_perusahaan 
                  ORDER BY k.id DESC LIMIT 50"; 
                bikinOption($sSQL, $id_Kebun,"nama"," : ","prs", " - " ,"keterangan");
              ?>
            </select>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="setKebun()">Save changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>


    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Device Log, <?php echo $kebunTerpilih; ?></h3>
      </div>
      <div class="card-body">
        <table id="aktLog" class="table table-bordered table-striped">
          <?php
          $sql = "SELECT l.id,  CONCAT(n.nama,'\n', DATE(l.created)) Node ,  l.flag Aksi, CONCAT('R:',l.relay ,'\n', l.exeval) 'Relay Val', 
          CONCAT(TIME(l.created),'\n',  COALESCE(TIME(l.waktu),'-- : -- : --')) 'Start Fin' , CONCAT(l.exe_v1,'\n', l.exe_v2) 'V1 V2',  
          TIMEDIFF(l.waktu, l.created) Durasi FROM log_eksekutor l 
            JOIN node n ON l.id_node = n.id
            JOIN chip c ON n.id_chip = c.id
            JOIN kebun k ON c.id_kebun = k.id
            WHERE k.id_perusahaan = $id_perusahaan
            ORDER BY l.id DESC 
            LIMIT 50;" ; 
          $custom=[ 
            "isHideKunci" => true,
            "sKunci" => 'id',
            "sLink" => null, 
            "jsFlagOnClick" => 'resetFlag',
            "sKeyFlag" => 'Aksi',
            "key1"=>0, "sIcon1"=>'🟠',
            "key2"=>9, "sIcon2"=>'🔄', 
            "key3"=>11, "sIcon3"=>'🟢▶'
          ];
          $sHitTabel=isiTabelSQL2($sql, null,$custom);
          echo $sHitTabel;
          ?>
        </table>
      </div>
    </div>
  </div>
</div>

<script> 
  function resetFlag(id,element) { 
    if(confirm(`Apakah Anda yakin ingin mereset log eksekutor ID ${id}?\n\nPerhatian: `
    + `Proses ini memungkinkan eksekusi ulang meskipun repeater off.`)) {
        // Create AJAX request
        $.ajax({
            url: "../fungsi/reset_log_eksekutor",
            method: "POST", 
            dataType: 'json', 
            data: { 'id_le': id }, 
            success: function (response) {
                if (response.status === "success") {
                    // Update the UI if successful
                    element.style.color = "green";
                    element.innerHTML = "✓ resetted"; 
                    alert("Flag berhasil diupdate");
                } else {
                    alert("Gagal mengupdate flag: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                alert("Gagal mengupdate flag: " + error);
            }    
        });
    }
  }
   
  $(function () {
    $("#aktLog").DataTable({
      "order": [0, 'desc'],
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });

  function pilihKebun() {
    //menampilkan modal-tampil
    $('#modKebun').modal('show');
  }

  function setKebun() {
    var id = $('#kebunId').val();
    var url = 'dev_log$$' + id;
    window.location.replace(url);
  }
</script>
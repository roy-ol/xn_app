<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}

$sAddOnNavBar='
<ul class="navbar-nav">
  <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
      </a>
  </li>
</ul>'; 
if(empty($val1)){$val1 = "";} 
$cTemp->setTitle("Dashboard"); 
$cTemp->setAddOnNavBarRight($sAddOnNavBar); 
$cTemp->loadHeader();


$id_Kebun=0;
if(isset($_POST['idKebun'])) $id_Kebun = $_POST['idKebun']; 
$_SESSION['idKebun'] = $id_Kebun;
$kebunTerpilih=""; 
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <p>Selamat datang di
        <button id="pilihKebun" type="button" class="btn btn-default" onclick="pilihKebun()">pilih kebun</button>
      </p>
      <div id="content_dashboard">
        <?=$val1 ?>
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
               $sql = "SELECT c.chip,c.keterangan,hc.waktu,hc.hit FROM `hit_chip` hc, chip c 
               where hc.id_chip=c.id and c.id_kebun=$id_Kebun"; 
              $sHitTabel=isiTabelSQL($sql);
              echo $sHitTabel;
            ?>
          </table>
        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
    </div>

    <div class="modal" id="modal-tampil">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Default Modal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>One fine body&hellip;</p>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>


    <div class="card">
      <div class="card-header">
        <h3 class="card-title">tabel data</h3>
      </div>
      <div class="card-body">
        <table id="aktLog" class="table table-bordered table-striped">
          <?php
          $sql = "SELECT CONCAT(n.nama,'\n', DATE(l.created)) Node , CONCAT('R:',l.relay ,'\n', l.exeval) 'Relay Val', 
          CONCAT(TIME(l.created),'\n',  COALESCE(TIME(l.waktu),'-- : -- : --')) 'Start Fin' , CONCAT(l.exe_v1,'\n', l.exe_v2) 'V1 V2',  
          TIMEDIFF(l.waktu, l.created) Durasi,l.id FROM log_eksekutor l 
            JOIN node n ON l.id_node = n.id
            JOIN chip c ON n.id_chip = c.id
            JOIN kebun k ON c.id_kebun = k.id
            WHERE k.id_perusahaan = $id_perusahaan
            ORDER BY l.id DESC 
            LIMIT 100;" ;
          $sHitTabel=isiTabelSQL($sql);
          echo $sHitTabel;
          ?>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(function () {
    $("#aktLog").DataTable({
      "order": [5, 'desc'],
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });

  function tampil() {
    //menampilkan modal-tampil
    $('#modal-tampil').modal('show');
  }
</script>
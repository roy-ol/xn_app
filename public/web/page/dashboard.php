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
$sTitle2 = "tes";
$cTemp->loadHeader();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <p>Selamat datang di Dashboard!</p>
      <div id="content_dashboard">
        <?=$val1 ?>
      </div>
    </div><!-- /.container-fluid -->
    <div class="col-md-12" id="content_dashboard">
      <!-- card primary -->
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Judul Card</h3>
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
          <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-tampil">
            Launch Default Modal
          </button>
          <!-- /.card -->
          <!-- tombol klik untuk memanggil funsi js tampil() -->
          <button type="button" class="btn btn-default" onclick="tampil()">tombol klik</button>
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
        <table id="example1" class="table table-bordered table-striped">
          <?php
          $sql = "SELECT nr.keterangan NodeRole, nr.relay rl,nr.exeval xVal, CONCAT('V1:',nr.exe_v1 , ' V2:', 
          nr.exe_v2) Val, CONCAT('Ref:',nr.reff_node, ' Nil:' , nr.nilai_1) Ref,nr.updated
            FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan LIMIT 99";
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
    $("#example1").DataTable({
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
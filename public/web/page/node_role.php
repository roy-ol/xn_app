<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 
 
$cTemp->setTitle("Node Role"); 
$cTemp->loadHeader();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <h2>Node Role</h2>
      <span class="text-muted">List Node Role</span>
      <div id="content_header"><?=$val1 ?></div>
    </div><!-- /.container-fluid -->
  </div>


  <div class="card">
    <div class="card-header">
      <button class="btn btn-primary btn-sm" onclick="window.location.href = '../page/node_role_form'">
        +Role Baru
      </button>
    </div>
    <div class="card-body">
      <table id="list_role" class="table table-bordered table-striped">
        <?php  
          $sql = "SELECT nr.id nrid, nr.keterangan nRole,  nr.relay Relay,nr.exeval xVal,
          nr.exe_v1 Val1, nr.exe_v2 Val2,  nr.reff_node , nr.nilai_1, nr.updated
          FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan ORDER BY nr.id DESC limit 100";
          $sHitTabel=isiTabelSQL($sql,"../page/node_role_form");
          echo $sHitTabel;
        ?>
      </table>
    </div>
  </div>

</div>
<!-- /.content-wrapper -->

<script>
  $(function () {
    $("#list_role").DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "order": [7, "desc"],
      "buttons": ["copy", "excel", "pdf", "colvis"]
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('.col-md-6:eq(0)');
  });
</script>
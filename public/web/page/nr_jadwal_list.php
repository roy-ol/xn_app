<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 
 
$cTemp->setTitle("Jadwal Role"); 
$cTemp->loadHeader();
$id_perusahaan = $_SESSION['id_perusahaan'];
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <h2>Schedule List</h2>
      <span class="text-muted">List Jadwal Role</span>
      <div id="content_header"><?=$val1 ?></div>
    </div><!-- /.container-fluid -->
  </div>
 
  <div class="card">
    <div class="card-header">
      <button class="btn btn-primary btn-sm" onclick="window.location.href = '../page/nr_date'">
        +Node Role Date
      </button> 
    </div>
    <div class="card-body">
      <!-- Tambahkan div untuk tombol DataTables -->
      <div class="card-tools">
        <div class="btn-group-buttons-1"></div>
      </div>
      <table id="list_jadwal" class="table table-bordered table-striped">
        <?php  
          $sql = "SELECT n.id, n.nama as Aktuator, c.chip, COUNT(nrd.id) as Date, '0' as week, 
           n.keterangan
          FROM node_role_date nrd JOIN node n ON nrd.id_node = n.id 
          JOIN chip c ON n.id_chip = c.id JOIN kebun k ON k.id=c.id_kebun  
          WHERE k.id_perusahaan = $id_perusahaan GROUP BY n.nama" ; 
          $sHitTabel=isiTabelSQL($sql,"../page/nr_date");
          echo $sHitTabel;
          ?>
      </table>
    </div>
  </div>
 
  <div class="card">
    <div class="card-header"> 
      <button class="btn btn-primary btn-sm" onclick="window.location.href = '../page/nr_week'">
        +Node Role week
      </button>
    </div>
    <div class="card-body">
      <table id="list_week" class="table table-bordered table-striped">
        <?php  
          $sql = "SELECT n.id, n.nama as Aktuator, c.chip, '0' as Date, COUNT(nrw.id) as week,
           n.keterangan 
          FROM node_role_week nrw JOIN node n ON nrw.id_node = n.id 
          JOIN chip c ON n.id_chip = c.id JOIN kebun k ON k.id=c.id_kebun 
          WHERE k.id_perusahaan = $id_perusahaan GROUP BY n.nama" ; 
          $sHitTabel=isiTabelSQL($sql,"../page/nr_week");
          echo $sHitTabel;
        ?>
      </table>
      
      <!-- Tambahkan div untuk tombol DataTables -->
      <div class="card-tools float-left">
        <div class="btn-group-buttons-2"></div>
      </div>
    </div>
  </div>

</div>
<!-- /.content-wrapper -->

<script>
  $(function () {
    $("#list_jadwal").DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "order": [3, "desc"],
      "buttons": ["copy", "excel", "pdf", "colvis"]
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('.btn-group-buttons-1'); 
    
    $("#list_week").DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "order": [3, "desc"],
      "buttons": ["copy", "excel", "pdf", "colvis"]
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('.btn-group-buttons-2');  
  });
</script>
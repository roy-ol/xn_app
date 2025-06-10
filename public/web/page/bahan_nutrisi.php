<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 

$sTitleFile = "Master Bahan Nutrisi";
 
$cTemp->setTitle($sTitleFile); 
$cTemp->loadHeader();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <h2><?=$sTitleFile ?></h2>
      <span class="text-muted"><?$sTitleFile ?></span>
      <div id="content_header"><?=$val1 ?></div>
    </div><!-- /.container-fluid -->
  </div>


  <div class="card">
      <div class="card-header">
        <h3 class="card-title">Card, <?=$sTitleFile ?></h3>
      </div>
      <div class="card-body">
        <table id="tbl" class="table table-bordered table-striped">
          <?php
          $sql = "SELECT l.id,  CONCAT(n.nama,'\n', DATE(l.created)) Node ,  CONCAT('R:',l.relay ,'\n', l.exeval) 'Relay Val', 
          CONCAT(TIME(l.created),'\n',  COALESCE(TIME(l.waktu),'-- : -- : --')) 'Start Fin' , CONCAT(l.exe_v1,'\n', l.exe_v2) 'V1 V2',  
          TIMEDIFF(l.waktu, l.created) Durasi FROM log_eksekutor l 
            JOIN node n ON l.id_node = n.id
            JOIN chip c ON n.id_chip = c.id
            JOIN kebun k ON c.id_kebun = k.id
            WHERE k.id_perusahaan = $id_perusahaan
            ORDER BY l.id DESC 
            LIMIT 50;" ;  
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
    $("#tbl").DataTable({
      "order": [0, 'desc'],
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('.col-md-6:eq(0)');

  });
  
</script>
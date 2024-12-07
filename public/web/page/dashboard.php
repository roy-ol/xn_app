<?php  
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
if( $id_Kebun == 0 ){
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

//===================================== ambil data node untuk gauge ================= 
$sSqlNode="SELECT sl.id_node,COALESCE(sl.waktu_node,sl.created) waktu,sl.nilai,n.nama,n.keterangan ,
  s.display,s.min,s.max,s.yellow_from, s.yellow_to,s.red_from,s.red_to,s.minor_tick
  FROM sensor_logger sl
  INNER JOIN node n ON sl.id_node = n.id
  INNER JOIN chip c ON c.id = n.id_chip
  INNER JOIN satuan s ON s.id = n.id_satuan
  INNER JOIN (SELECT id_node, MAX(id) AS max_id 
      FROM sensor_logger GROUP BY id_node) AS max_ids 
      ON sl.id_node = max_ids.id_node AND sl.id = max_ids.max_id
  WHERE c.id_kebun = :idKebun AND n.flag > 1 ";

$sDataNode=$cUmum->ambilData($sSqlNode,["idKebun"=>$id_Kebun])->fetchAll(PDO::FETCH_ASSOC); 
$sDiv='';
$sDrawChart='';
$iKol=1;
foreach($sDataNode as $key => $value){
  if($iKol > 7){
    $iKol=1;
    $sDiv .= '</div><div class="row">';
  }
  $sDiv .= '<div class="col-lg-2 col-4" title="'.$value['keterangan'].'=>'.$value['waktu'].'"> 
  <div id="chart_div_'.$value['id_node'].'"></div>    
  <p id="ket_'.$value['id_node'].'" class="text-center" title="'.$value['waktu'].'" >'.$value['nama'].'</p>
  </div>';
  $iKol++;

  $sDrawChart .="var data".$value['id_node']." = google.visualization.arrayToDataTable([
     ['Label', 'Value'],
     ['".$value['display']."', ".$value['nilai']."],
   ]);
   var options".$value['id_node']. " = {
     min: ".$value['min'].", max: ".$value['max'].", 
     yellowFrom: ".$value['yellow_from'].", yellowTo: ".$value['yellow_to'].", 
    //  greenFrom:".$value['yellow_to'].", greenTo: ".$value['red_from'].",
     redFrom: ".$value['red_from'].", redTo: ".$value['red_to'].",
     minorTicks: ".$value['minor_tick']."
   }; 
   var chart".$value['id_node']." = new google.visualization.Gauge(document.getElementById('chart_div_".$value['id_node']."'));
   chart".$value['id_node'].".draw(data".$value['id_node'].", options".$value['id_node'].");
  ";
}
   
//================================template load halaman========================================
$sAddOnNavBar=' 
  <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
      </a>
  </li> '; 
if(empty($val1)){$val1 = "";} 
$cTemp->setTitle("Dashboard"); 
$cTemp->setAddOnNavBarRight($sAddOnNavBar); 
$cTemp->loadHeader();
//=============================================================================================
?>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <p>Kebun Aktif :
        <button id="pilihKebun" type="button" class="btn btn-success"
          onclick="pilihKebun()"><?=$kebunTerpilih;?></button>
      </p> 
      
      <div class="row" id="div_gauge">
        <?=$sDiv?>
         <!-- ========================================= di isi gauge ================== --> 
         <!-- ========================================= di isi gauge ================== -->
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
          ini area card body

        </div>

           
        <!-- test isi gaouge --> 

        <div class="card-body">
          <table id="tblState" class="table table-bordered table-striped">
            <?php
              $sql = "SELECT COALESCE(sl.waktu_node,sl.created) as waktu , n.nama,sl.nilai  FROM sensor_logger sl 
                JOIN node n ON n.id = sl.id_node 
                JOIN chip c ON c.id = n.id_chip
                WHERE c.id_kebun = $id_Kebun AND n.flag > 0
                ORDER BY sl.id DESC LIMIT 45 "; 
              //  $sTabel=isiTabelSQL($sSqlNodeAktif,"",["idKebun"=>$id_Kebun]);
              $sTabel=isiTabelSQL($sql);
              echo $sTabel;
            ?>
          </table> 
        </div>
      </div> <!-- /.card-primary -->
        <!-- /.content -->         
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- /.content --> 

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
  <!-- /.modal --> 
</div>

 
<script>
  google.charts.load('current', {'packages':['gauge']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    <?php echo $sDrawChart; ?> 

 
    // setInterval(function() {
    //   data.setValue(0, 0, 'Nama' + Math.round(15 * Math.random()));
    //   chart.draw(data, options);
    // }, 1000);
    // setInterval(function() {
    //   data.setValue(0, 1, 40 + Math.round(45 * Math.random()));
    //   chart.draw(data, options);
    // }, 3600);
    // setInterval(function() {
    //   data2.setValue(0, 1, 40 + (54 * Math.random()));
    //   chart2.draw(data2, options2);
    // }, 1260); 

  }

  
  function pilihKebun() {
    //menampilkan modal-tampil
    $('#modKebun').modal('show');
  }

  function setKebun() {
    var id = $('#kebunId').val();
    var url = 'dashboard$$' + id;
    window.location.replace(url);
  }
 
  $(function () {  
    $('#tblState').DataTable();

  });
</script>
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
        <div class="col-6 col-md-3 text-center">
          <script src="../../adminlte/plugins/jquery-knob/jquery.knob.min.js"></script>
          <div style="display:inline;width:120px;height:120px;">
            <canvas width="120" height="120"></canvas>
            <input type="text" class="knob" value="100" data-skin="tron" data-thickness="0.2" 
            data-anglearc="250" data-angleoffset="-125" data-width="120" data-height="120" 
            data-fgcolor="#00c0ef" style="width: 64px; height: 40px; position: absolute; 
            vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none; 
            font: bold 24px Arial; text-align: center; color: rgb(0, 192, 239); padding: 0px; 
            appearance: none;">
          </div>
          <div class="knob-label">data-angleArc="250"</div>
        </div>

        <!-- test isi gaouge -->
         
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Memory', 10],
          ['CPU', 40],
          ['CPU2', 60],
          ['Network', 80]
        ]);

        var options = {
          width: 400, height: 120,
          redFrom: 70, redTo: 100,
          yellowFrom:10, yellowTo: 30, 
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 3600);
        setInterval(function() {
          data.setValue(1, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 2700);
        setInterval(function() {
          data.setValue(2, 1, 60 + Math.round(20 * Math.random()));
          chart.draw(data, options);
        }, 1800);
        setInterval(function() {
          data.setValue(3, 1, 60 + Math.round(20 * Math.random()));
          chart.draw(data, options);
        }, 8100);
      }
    </script> 
    <div id="chart_div" style="width: 400px; height: 120px;"></div> 
        <!-- test isi gaouge -->

        <div class="card-body">
          <table id="tblState" class="table table-bordered table-striped">
            <?php
              $sql = "SELECT COALESCE(sl.waktu_node,sl.created) as waktu , n.nama,sl.nilai  FROM sensor_logger sl 
                JOIN node n ON n.id = sl.id_node 
                JOIN chip c ON c.id = n.id_chip
                WHERE c.id_kebun = $id_Kebun AND n.flag > 0
                ORDER BY sl.id DESC LIMIT 45;"; 
              $sTabel=isiTabelSQL($sql);
              echo $sTabel;
            ?>
          </table>
        </div>
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
</div>


<script>
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
    $('.knob').knob({ 
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a   = this.angle(this.cv)  // Angle
            ,
              sa  = this.startAngle          // Previous start angle
            ,
              sat = this.startAngle         // Start angle
            ,
              ea                            // Previous end angle
            ,
              eat = sat + a                 // End angle
            ,
              r   = true

          this.g.lineWidth = this.lineWidth

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3)

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value)
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3)
            this.g.beginPath()
            this.g.strokeStyle = this.previousColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
            this.g.stroke()
          }

          this.g.beginPath()
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
          this.g.stroke()

          this.g.lineWidth = 2
          this.g.beginPath()
          this.g.strokeStyle = this.o.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
          this.g.stroke()

          return false
        }
      }
    }
  )

  });
</script>
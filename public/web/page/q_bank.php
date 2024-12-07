<?php   
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = "";  $id_perusahaan=0;
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = 0;} 

//=========================== load identitas Query terpilih °° ==============
$id_Kebun=(isset($_SESSION['idKebun'])) ? $_SESSION['idKebun'] :  0;

$sParams="";
$sMemo="";
$sNama="Query Bank";
$sKeterangan="Pilih Query Bank";
$iParams=0;
$sParams="";
$sSqlQB="";
$iQbID=0;
$iQbID = ($val1 =="id") ? $val2:intval($val1); //id qb dari url

if(intval($iQbID) > 0) {
  $rHasil = $cUmum->ambil1Row("SELECT q.*,m.memo FROM qbank q 
    LEFT JOIN memo m on m.id = q.id_memo WHERE q.id= :id" ,["id"=>$iQbID]);  
  $sNama = $rHasil["nama"];
  $sParams = $rHasil["params"];
  $sSqlQB= $rHasil["query"];
  $sMemo = $rHasil["memo"]; 
  $sKeterangan = $rHasil["keterangan"];  
  $sParams =$rHasil["params"];
  $iParams=0;
  if(strlen($sParams) > 0){
    $sParams = explode("$$",$sParams);
    $iParams = count($sParams);
  }  
  // $sMemo = nl2br($sMemo);
  $sSqlQB = str_replace("[idkebun]",$id_Kebun,$sSqlQB);
  $sSqlQB = str_replace("[idPerusahaan]",$id_perusahaan,$sSqlQB);
}

//==================== Template load Halaman =========================
$sAddOnNavBar=' 
<li class="nav-item">
<a class="nav-link" onclick="'. "$('#mod-qbank').modal('show');". '" role="button">
      Pilih Query <i class="fas fa-arrow-circle-down"></i>
    </a>
</li>
';
$cTemp->setAddOnNavBarLeft($sAddOnNavBar);
$cTemp->setTitle($sNama); 
$cTemp->loadHeader(); 
//======================================================================== 
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->  
  <h2>Query Bank</h2>  
  <div class="content">
    <div class="container-fluid">        
      <!-- <div class="callout callout-info"> -->
      <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-info"></i><?=$sKeterangan?></h5>
        <p><?=$sMemo?></p>
      </div>  
    </div><!-- /.container-fluid -->
  </div>
  <div class="card">
    <div class="card-header">  
      <?php
      if($iParams > 0 ){
        for($i=0;$i<$iParams;$i++){
          $sInParam='
          <div class="form-group row">
            <label for="p'.$i.'" class="col-3 col-form-label">
              <span class="float-right"> '. $sParams[$i].'  </span>
            </label>
            <input type="text" id="p'.$i.'" class="col-8" name="'.$sParams[$i].'" title="'.$sParams[$i].'">
          </div> '; 
          echo $sInParam;
        }
         ?>
        <div style="justify-content: center; display: flex;">
          <a class="btn btn-app bg-warning center" onclick="submitForm()">
            <i class="fas fa-search"></i> Jalankan Query
          </a>
        </div>  
         <?php
      }else{
        $sParams=""; 
      }  
      ?> 
    </div> 
    <div class="card-body">
      <table id="t_data" class="table table-bordered table-striped">
        <?php  
          $iValPal=0;
          $paramSQL = null;
          $isAdaTabel = 0;
          if($iParams == 0 && strlen($sSqlQB) > 0){ 
            echo isiTabelSQL($sSqlQB ,null,$paramSQL); 
            $isAdaTabel = 1 ;
          } 

        ?>
      </table> 
    </div> 
  </div>
  <!-- test area bawah --> 

  <!-- /.content-wrapper -->
</div>

<!-- modal qbank -->
<div class="modal" id="mod-qbank">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Pilih Query</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <table id="list_QBank" class="table table-bordered table-striped">
          <?php  
            $sql = " select id,nama, keterangan from qbank" ;
            $sHitTabel=isiTabelSQL($sql,"../page/q_bank");
            echo $sHitTabel;
          ?>
        </table>
 
      </div> 
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>




<script>
  function fungsiOnLoad() {
    displayElapsedTime(); // start the timer yang sudah di overide fungsiOnLoad nya 
    //tampilkan modal jika tidak ada parameter url
    if (window.location.href.indexOf("$$") < 0) {
      $('#mod-qbank').modal('show'); 
    }
  }
 
  function assignKeDT(strDataSource) { //str JSON ke datatable  
    var strData = JSON.parse(strDataSource); 
    
    if ($.fn.DataTable.isDataTable("#t_data")) {
        $('#t_data').DataTable().clear().destroy();
    }   
    $('#t_data').empty(); 
    
    var adColumns = [];
    Object.keys(strData[0]).forEach(key => {
      var col = {
        data: key,
        title: key
      };      
      adColumns.push(col);
    });
    $('#t_data').DataTable({
        "data": strData,
        "columns": JSON.parse(JSON.stringify(adColumns)),
        "responsive": true, 
        "autoWidth": false, 
        // dom: 'Bfrtip',
      "buttons": ["copy", "excel", "pdf", "colvis"]
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('.col-md-6:eq(0)'); 
      // alert($.fn.dataTable.version); //1.11.4
  }


  function submitForm() { 
    var iParams = '<?=$iParams?>';
    if(iParams > 0){
      var sParams = 'iQbID=' + <?=$iQbID?> + '&';
      for (var i = 0; i < iParams; i++) {
        var sKunciParam =document.getElementById("p" + i).title;
        var sValParam = document.getElementById("p" + i).value;
        //jadikan object post
        sParams += sKunciParam + '=' + sValParam + '&';
      } 
    }  
    $.ajax({
      url: '../fungsi/qbank',
      type: 'POST', 
      data: sParams,
      success: function (data) {    
        assignKeDT(data);
      }
    });
  }
  
  function muat_qparam(qb_id){ 
    var url_qb='../q_bank$$' + qb_id;
    window.location.replace(url_qb);
  }

  $(document).ready(function(){
    $(function () {
      $("#list_QBank").DataTable();

      var isAdaTable = <?=$isAdaTabel?>; 
      if(isAdaTable > 0){         
        $("#t_data").DataTable({ 
          "responsive": true,
        // dom: 'Bfrtip',
        "buttons": ["copy", "excel", "pdf", "colvis"]
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('.col-md-6:eq(0)');  
      }
    });
  });
 
</script>
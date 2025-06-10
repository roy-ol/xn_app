<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 
 
$cTemp->setTitle("Node Role Form"); 
$cTemp->loadHeader();
?>

<style>
  /* Style untuk popup */
  .popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
  }

  .popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 300px;
  }
  
  /* New styles for form layout */
  .form-container {
    max-width: auto;
    margin: 0 auto;
    padding: 9px;
  }
  
  .form-row {
    display: flex;
    margin-bottom: 15px;
    align-items: center;
  }
  
  .form-label-left {
    width: 120px;
    font-weight: bold;
    margin-right: 10px;
  }
  
  .form-input {
    width: 200px;
    margin-right: 10px;
  }
  
  .form-label-right {
    width: 300px;
    color: #666;
    font-style: italic;
    font-size: 0.9em;
  }
  
  .radio-group {
    display: flex;
    gap: 15px;
  }
  
  textarea {
    width: 200px;
    min-height: 80px;
  }
  
  select, input, textarea {
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <?php   
  $sURL_Action = "../fungsi/addNodeRole1"; 
  $btnCap = "Tambah";
  $id_role=0;
  $repeater = 0;
  $id_memo = 0;
  $id_node = 0;
  $KetNode="Pilih Node / Aktuator";
  $is_nrid = false;
  
  $pola  = 0;     
  $exeval  = null;      
  $exe_v1 = null;    
  $exe_v2 = null;    
  $reff_node = null;    
  $relay = null;    
  $repeater = null;    
  $nilai_1 = null;    
  $keterangan = null;     
  $memo = null;    
  $updater  = null;     
  $nil_value=[];

  if($val1 == "nrid") {
    $id_role = $val2;
    $sURL_Action = "../fungsi/updateNodeRole1"; 
    $btnCap = "Update";
    $sql="SELECT reff_node, pola, nr.flag, exeval, exe_v1, exe_v2,satuan,
      ref_n1,ref_n2,ref_n3,ref_n4,ref_n5, relay, repeater, 
      nilai_1,nilai_2,nilai_3,nilai_4,nilai_5,nilai_6,nilai_7, 
      keterangan, id_memo, memo, updater 
      FROM node_role nr 
      LEFT JOIN memo m ON m.id = nr.id_memo 
      where id_perusahaan = $id_perusahaan and nr.id= :nrid " ;
    $barisData = $cUmum->ambil1Row($sql, ["nrid"=>$id_role] );
    if($barisData){ 
      $reff_node =  $barisData['reff_node'] ;
      $pola =  $barisData['pola'];
      $flag = $barisData['flag'];  
      $exeval  = $barisData['exeval'];      
      $exe_v1 = $barisData['exe_v1'];
      $exe_v2 = $barisData['exe_v2'];
      $satuan = $barisData['satuan'];
      $ref_n1 = $barisData['ref_n1'];
      $ref_n2 = $barisData['ref_n2'];
      $ref_n3 = $barisData['ref_n3'];
      $ref_n4 = $barisData['ref_n4'];
      $ref_n5 = $barisData['ref_n5'];
      $relay = $barisData['relay'];
      $repeater = $barisData['repeater'];
      $nilai_1 = $barisData['nilai_1'];
      $nilai_2 = $barisData['nilai_2'];
      $nilai_3 = $barisData['nilai_3'];
      $nilai_4 = $barisData['nilai_4'];
      $nilai_5 = $barisData['nilai_5'];
      $nilai_6 = $barisData['nilai_6'];
      $nilai_7 = $barisData['nilai_7'];
      $keterangan = $barisData['keterangan'];
      $id_memo = $barisData['id_memo'];
      $memo = $barisData['memo'];
      $updater  = $barisData['updater'];
      $val1 = "idnd";$val2 = $reff_node; 
      $is_nrid = true;
    }
  }



  if($val1 == "idnd") {
    $id_node = $val2;
    $sSQL = "SELECT CONCAT_WS(' => ', nama, keterangan) AS ketNode FROM node WHERE id = :id"; 
    $KetNode = $cUmum->ambil1Data($sSQL, ['id' => $val2]);
    $reff_node = $id_node = $val2;
  } 

  if($pola > 0){
    echo '<script>';
    echo 'window.onload = function() { showKetDetail(' . $pola . '); }';
    echo '</script>';
  }
?>
  
  <div class="form-container">
    <div class="form-group">
      <label><?=$KetNode?></label>
      <select id="node_id" name="id_node" class="form-control" onchange="node_terpilih(this.value)">
        <option value="0">- - - Pilih Node aktuator - - -</option>
        <?php  
        $sSqlOp2 = "SELECT n.id,n.nama,c.chip from node n INNER JOIN chip c ON c.id = n.id_chip 
        INNER JOIN tipe t ON t.id=c.id_tipe INNER JOIN kebun k ON k.id=c.id_kebun 
        where t.kelompok > 1 and k.id_perusahaan=" . $id_perusahaan;
        bikinOption($sSqlOp2, $id_node, "chip", "-", "nama");
        ?>
      </select> 
    </div>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Node Role Form</h3>
      </div>
      <div class="card-body">
        <form action="<?=$sURL_Action?>" method="post" id="nr_form">
          <input type="hidden" name="id_node" value=<?=$id_node;?>>
          <input type="hidden" name="id_memo" value=<?=$id_memo;?>>
          <input type="hidden" name="id_role" value=<?=$id_role;?>>
          <input type="hidden" name="reff_node" value=<?=$reff_node;?>>
          
          <div class="form-row">
            <label class="form-label-left" for="keterangan">NodeRule :</label>
            <div class="form-input">
              <input type="text" id="keterangan" value="<?=$keterangan;?>" name="keterangan" 
              title="Nama node_role / keterangan / identifikasi rule ini" placeholder="Keterangan NodeRole" required>
            </div>
            <div class="form-label-right">Keterangan Rule untuk identifikasi</div>
          </div>
          
          <div class="form-row">
            <label class="form-label-left" for="pola">Pola:</label>
            <div class="form-input">
              <select id="pola" name="pola" title="pilih pola" value=<?=$pola;?> onchange="showKetDetail(this.value)">
                <option value=0> - pilih pola - </option>
                <?php  
                $sSqlOp1 = "select id, pola from nrpola";
                bikinOption($sSqlOp1, $pola,"pola");
                ?>
              </select>
            </div>
            <div class="form-label-right">
              Pola operasi <span id="lblMemPola" onclick="tampilMemoPola()" title="Memo" style="cursor:pointer; color:#007bff;">&#9432;</span>
            </div>
          </div>

          <?php
            $sql="SELECT ar.id,kolom,tipe_input,nama,keterangan,m.memo 
            FROM aktuator_role ar 
            LEFT JOIN memo m ON m.id = ar.id_memo 
            WHERE ar.id_node=$id_node ORDER BY id ASC";
            $barisData2 = $cUmum->ambilData($sql)->fetchAll(PDO::FETCH_ASSOC);
            if($barisData2){ 
              foreach ($barisData2 as $row) {
                $id = $row['id'];
                $kolom = $row['kolom'];
                $tipe_input = $row['tipe_input'];
                $nama = $row['nama'];
                $keterangan_ar = $row['keterangan'];
                $s_memo = $row['memo']; 
                $nil_value=$barisData[$kolom]??null;
                $echo_html= 
                '<div class="form-row">  
                  <label class="form-label-left" for="'.$kolom.'">('.$kolom.')' .$nama.'</label>
                  <div class="form-input">
                    <input type="'.$tipe_input.'" id="'.$kolom.'" name="'.$kolom.'" title="'.$s_memo
                      .'" value="'.$nil_value.'" required>
                  </div>
                  <div class="form-label-right">'.$keterangan_ar.'</div>
                </div>';
                echo $echo_html;
              }
            } 
          ?>
            
          <div class="form-row">
            <label class="form-label-left">Repeater:</label>
            <div class="form-input">
              <div class="radio-group">
                <?php
                if($repeater == 1){ 
                  echo '<label><input type="radio" name="repeater" value=0> Off</label>';
                  echo '<label><input type="radio" name="repeater" value=1 checked> On</label>';  
                }else{
                  echo '<label><input type="radio" name="repeater" value=0 checked> Off</label>';
                  echo '<label><input type="radio" name="repeater" value=1> On</label>';
                }
                ?>
              </div>
            </div>
            <div class="form-label-right">Aktifkan mode pengulangan otomatis</div>
          </div> 
          <div class="form-row">
            <label class="form-label-left" for="memo">Memo:</label>
            <div class="form-input">
              <textarea id="memo" name="memo" maxlength="2000"><?=$memo;?></textarea>
            </div>
            <div class="form-label-right">Catatan tambahan (memo)</div>
          </div>
          
          <div class="form-row" style="justify-content: center; margin-top: 20px;">
            <button type="button" class="btn btn-primary" onclick="submitForm()">
              <i class="fas fa-save"></i> <?=$btnCap?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Rest of your code remains the same -->
  <br>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Node Role List</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
          <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <table id="dataTable1" class="table table-bordered table-striped">
        <?php 
        $sql = "SELECT nr.keterangan NodeRole, nr.relay rl,nr.exeval xVal, CONCAT('V1:',nr.exe_v1 , ' V2:', 
        nr.exe_v2) Val, CONCAT('Ref:',nr.reff_node, ' Nil:' , nr.nilai_1) Ref,nr.updated
          FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan LIMIT 50";
        $sHitTabel=isiTabelSQL($sql);
        echo $sHitTabel;
        ?>
      </table>
    </div>
  </div>

  <div class="modal" id="modal_1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">modal_1</h3>
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
  </div>

<script>
  var sMemoPola = "Ket";

  function showKetDetail(id_pola) {
    $.ajax({
      type: 'POST',
      url: '../fungsi/ketPola',
      dataType: 'json',
      data: {
        'idPola': id_pola
      },
      success: function (rsp_data) {
        $('#lblMemPola').html('\u25A5');
        $("#pola").attr("title", rsp_data.keterangan);

        $('#modal_1 .modal-title').html(rsp_data.pola);
        $('#modal_1 .modal-body').html('<h5>' + rsp_data.keterangan + '</h5>' + rsp_data.memo);
      }
    });
  }

  function tampilMemoPola() {
    $('#modal_1').modal('show');
  }

  function submitForm() {
    // Get the node_id value
    const idNode_input = document.querySelector('input[name="id_node"]');
    const idNode = idNode_input ? parseInt(idNode_input.value) : 0;
    
    // Check if node_id is missing or 0
    if (!idNode || idNode === 0) {
      alert('Error: one or more required fields is missing or invalid. Please check the form data.');
      return false;
    }

    document.getElementById('nr_form').submit();
  }

  $(function () {
    $("#dataTable1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "order": [5, 'desc'],
      "buttons": ["copy", "excel", "pdf", "colvis"]
    }).buttons().container().appendTo('#dataTable1_wrapper .col-md-6:eq(0)');
  });

  //buat fungsi node_terpilih
  function node_terpilih(id) {  
    // const kode = 'id'; // Ganti dengan kode yang sesuai
    window.location.href = `../node_role_form$$idnd$$${id}`; 
  }
 
  </script>
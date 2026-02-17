<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 
if(empty($val2)){$val2 = "";} 
if(empty($val3)){$val3 = 0;} 
$formula_id = $val1 ? intval($val1) : 0;
$sql_formula = "SELECT * FROM nut_formula WHERE id = :id LIMIT 1";
$data_formula = $cUmum->ambil1Row($sql_formula, [':id' => $formula_id]);
$keterangan_val = $data_formula ? $data_formula['keterangan'] : ''; 
$sNamaFormula = $data_formula ? $data_formula['nama'] : '';
$sJenisTanaman = $data_formula ? $data_formula['jenis_tanaman'] : '';
$sTitleFile = "TARGET ION FORMULA NUTRISI";

$cTemp->setTitle($sTitleFile); 
$cTemp->loadHeader();

$sTitleFile = "TARGET ION NUTRISI" . ($sNamaFormula ? " - $sNamaFormula" : "");
 
?>

<?php
// Handle form submission for nut_formula_ion_target
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_ion_target'])){
  global $cUmum;
  $formula_id = intval($_POST['formula_id'] ?? 0);
  $ion_id = intval($_POST['ion_id'] ?? 0);
  $target_ppm = floatval($_POST['target_ppm'] ?? 0);

  
  $edit_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  if($edit_id > 0){
    $sql = "UPDATE nut_formula_ion_target SET  ion_id=:ion_id, target_ppm=:target_ppm WHERE id = :id";
    $params = [ 
      ':ion_id' => $ion_id,
      ':target_ppm' => $target_ppm,
      ':id' => $edit_id
    ];
    $affected = $cUmum->eksekusi($sql, $params);
    if($affected !== false){
      $sMsgIon = '<div class="alert alert-success">Target ion berhasil diperbarui.</div>';
      $val2=''; //reset form setelah simpan
      $ion_id=0; $target_ppm='';
    } else {
      $sMsgIon = '<div class="alert alert-danger">Gagal memperbarui target ion.</div>';
    }
    // Setelah update, redirect untuk menghindari resubmit form
    // header("Location: nd_target_ion$$$formula_id");
    // exit;
  } else {
    $sql = "INSERT INTO nut_formula_ion_target (formula_id, ion_id, target_ppm) 
            VALUES (:formula_id, :ion_id, :target_ppm)";
    $params = [
      ':formula_id' => $formula_id,
      ':ion_id' => $ion_id,
      ':target_ppm' => $target_ppm
    ];
    $affected = $cUmum->eksekusi($sql, $params);
    if($affected !== false){
      $sMsgIon = '<div class="alert alert-success">Target ion berhasil disimpan.</div>';
    } else {
      $sMsgIon = '<div class="alert alert-danger">Gagal menyimpan target ion.</div>';
    }
  }
}


// Jika ada param id di GET, load data untuk edit
$is_edit = false;

$edit_id = 0;
if($val2 == "id") {
  $edit_id = $val3; 
}  
if($edit_id > 0){
  $sql = "SELECT * FROM nut_formula_ion_target WHERE id = :id LIMIT 1";
  $params = [':id' => $edit_id];
  $data = $cUmum->ambil1Row($sql, $params);
  if($data){
    $is_edit = true;
    $ion_id=$data['ion_id'];
    $target_ppm=$data['target_ppm'];
  }
}

 
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <h2><?=$sTitleFile ?></h2>
      <span class="text-muted"><?$sTitleFile ?></span> 
      <div id="content_header"><?=$sJenisTanaman ?></div>
      <?php if(isset($sMsg)) echo $sMsg; ?>
    </div><!-- /.container-fluid -->
  </div>

 
  <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?=$keterangan_val?></h3>
      </div> 
    </div>
    <!-- Ion Target Section -->
    <div class="card mt-4">
      <div class="card-header">
        <h3 class="card-title">Target Ion</h3>
      </div>
      <div class="card-body">


        <form method="post" class="mb-6">
          <input type="hidden" name="formula_id" value="<?=$formula_id?>"> 
          <div class="row"> 
            <div class="col-md-6">
              <div class="form-group">
              <label>Komponen Ion</label>
              <select id="ion_id" name="ion_id" class="form-control" onchange="ion_terpilih(this.value)">
                <option value="0">- - - Pilih Ion - - -</option>
                <?php  
                $sSqlOp2 = "SELECT id,nama, senyawa,berat_senyawa,keterangan FROM nut_ion ORDER BY nama";
                bikinOption($sSqlOp2, $ion_id, "senyawa","(", "berat_senyawa",") ","nama", "  ", "keterangan");
                ?>
              </select> 
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Target PPM</label>
                <input type="number" step="0.01" name="target_ppm" class="form-control" 
                  required value="<?=htmlspecialchars($target_ppm)?>">
              </div>
            </div>
            <div class="col-md-3"> 
              <div class="form-group">
                <label>&nbsp;</label><br> 
                <?php if($is_edit): ?>
                  <input type="hidden" name="id" value="<?=$edit_id?>">
                  <a href="nd_target_ion$$<?=$formula_id?>" class="btn btn-secondary">Batal</a>
                  <button type="submit" name="save_ion_target" class="btn btn-success">Update</button>                       
                <?php else: ?>
                  <button type="submit" name="save_ion_target" class="btn btn-primary">Simpan/Tambahkan</button>
                <?php endif; ?>
              </div>
            </div> 
          </div>
        </form>


        <?php if(isset($sMsgIon)) echo $sMsgIon; ?>
        <div class="table-responsive">
        <table id="tbl" class="table table-bordered table-striped">
          <?php
          $sql = "SELECT t.id,i.nama ion,i.unsur,i.senyawa,i.keterangan, t.target_ppm 
          FROM nut_formula_ion_target t 
          JOIN nut_formula f ON t.formula_id = f.id 
          JOIN nut_ion i ON t.ion_id = i.id 
          WHERE t.formula_id=$formula_id;" ;  
          $sHitTabel=isiTabelSQL($sql,"../nd_target_ion$$".$formula_id);
          echo $sHitTabel;
          ?>
        </table>
      </div>
    </div>
      </div>
    </div>
  </div>
</div>

<script> 
  function ion_terpilih(id) {  
    // const kode = 'id'; // Ganti dengan kode yang sesuai
    // alert("Ion terpilih: " + id);
  }
 
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
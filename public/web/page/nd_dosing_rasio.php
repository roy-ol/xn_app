
<?php
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = 0;
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile);
}
if(empty($val1)){$val1 = "";}
if(empty($val2)){$val2 = "";}
if(empty($val3)){$val3 = 0;}

$sTitleFile = "Dosing Rasio Formula Nutrisi";
$cTemp->setTitle($sTitleFile);
$cTemp->loadHeader();

// Handle form submission for nut_dosing_ratio
$sMsg = "";
$is_edit = false;
$edit_id = 0;
$formula_id = 0;
$node_id = 0;
$namaFormula = "";
$namaNode = "";

$tangki_id = '';
$ml_per_liter = '';
$namaTangki = '';
$keteranganTangki = '';

if($val1 === "id") {
    $edit_id = intval($val2);
    if($edit_id > 0){
      $row = $cUmum->ambil1Row("SELECT dr.id, dr.ml_per_liter, dr.formula_id,
                  t.node_id, dr.tangki_id, t.nama, t.keterangan
          FROM nut_dosing_ratio dr
          JOIN nut_tangki t ON t.id = dr.tangki_id
          WHERE dr.id = :id ", [':id'=>$edit_id]);
      if($row){
        $is_edit = true;
        $formula_id   = intval($row['formula_id']);
        $tangki_id    = intval($row['tangki_id']);
        $ml_per_liter = floatval($row['ml_per_liter']);
        $node_id      = intval($row['node_id']);
        $namaTangki     = $row['nama'];
        $keteranganTangki = $row['keterangan'];
      }      
    }
} else {
    $formula_id = is_numeric($val1) ? intval($val1) : 0;
    $node_id    = is_numeric($val2) ? intval($val2) : 0;
}

if($formula_id > 0){
    $dataFormula = $cUmum->ambil1Row("SELECT nama FROM nut_formula WHERE id = :id", [':id'=>$formula_id]);
    $namaFormula = $dataFormula ? $dataFormula['nama'] : '';
}
if($node_id > 0){
    $dataNode = $cUmum->ambil1Row("SELECT nama FROM node WHERE id = :id", [':id'=>$node_id]);
    $namaNode = $dataNode ? $dataNode['nama'] : '';
}


if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_dosing_ratio'])){
  global $cUmum; 
  $ml_per_liter = floatval($_POST['ml_per_liter'] ?? 0);
  $edit_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  if($edit_id > 0){
    $sql = "UPDATE nut_dosing_ratio SET ml_per_liter=:ml_per_liter WHERE id=:id";
    $params = [':ml_per_liter'=>$ml_per_liter, ':id'=>$edit_id];
    $affected = $cUmum->eksekusi($sql, $params);
    if($affected !== false){
      $sMsg = '<div class="alert alert-success">Data berhasil diperbarui.</div>';
      $is_edit = false;
      $edit_id = 0;
      $ml_per_liter = '';
      // header("Location: nd_dosing_rasio$$".$formula_id."$$".$node_id); 
      // exit;
    } else {
      $sMsg = '<div class="alert alert-danger">Gagal memperbarui data.</div>';
    }
  } 
  
}

//generate dosing ratio untuk tangki yang belum memiliki data dosing ratio sesuai formula dan node aktuator yang dipilih
if($formula_id > 0 && $node_id > 0){
    $sql_generate = "INSERT INTO nut_dosing_ratio (formula_id, tangki_id, ml_per_liter)
    SELECT :formula_id,t.id,0
    FROM nut_tangki t
    LEFT JOIN nut_dosing_ratio r
        ON r.tangki_id = t.id
        AND r.formula_id = :formula_id
    WHERE t.node_id = :node_id
    AND r.id IS NULL";
    $cUmum->eksekusi($sql_generate, [
        ':formula_id' => $formula_id,
        ':node_id'    => $node_id
    ]); 

}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <h2><?=$sTitleFile?></h2>
      <?php if($sMsg) echo $sMsg; ?>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label>Formula Nutrisi</label>  
            <select name="formula_id" class="form-control" onchange="update_context()">
              <option value="0">- - - Pilih Formula - - -</option>
              <?php
              $sql_bahan = "SELECT id,nama,jenis_tanaman,keterangan FROM nut_formula ORDER BY id";
              bikinOption($sql_bahan,$formula_id,"nama"," | ","keterangan", " (", "jenis_tanaman" ,")");
              ?>
            </select>            
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label>Node</label><br>
            <select name="node_id" id="node_id" class="form-control" onchange="update_context()">
            <option value="0">- - - Pilih Node aktuator - - -</option>
            <?php  
            $sSqlOp2 = "SELECT n.id,n.nama,c.chip,k.nama as nama_kebun from node n INNER JOIN chip c ON c.id = n.id_chip 
            INNER JOIN tipe t ON t.id=c.id_tipe INNER JOIN kebun k ON k.id=c.id_kebun 
            where t.kelompok > 1 and k.id_perusahaan=" . $id_perusahaan;
            bikinOption($sSqlOp2, $node_id, "chip", " ", "nama"," ","nama_kebun");
            ?>
          </select> 
          </div>
        </div>
      </div>
    </div>
    
    
  </div>

  <?php if($formula_id == 0 || $node_id == 0): ?>
    <div class="alert alert-info">
        Silakan pilih Formula dan Node terlebih dahulu.
    </div>
  <?php else: ?>

    
<style>
.ion-ok {
    background-color: rgba(36, 207, 36, 0.27);
    border: 1px solid rgba(25, 135, 84, 0.45);
}

.ion-over {
    background-color: rgba(255, 54, 54, 0.27);
    border: 1px solid rgba(220, 53, 69, 0.45);
}

.ion-under {
    background-color: rgba(255, 255, 18, 0.27);
    border: 1px solid rgba(255, 193, 7, 0.45);
}

.ion-neutral {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.ion-ok { border-top: 3px solid #198754; }
.ion-over { border-top: 3px solid #dc3545; }
.ion-under { border-top: 3px solid #ffc107; }
</style>


  <div class="card mt-4"> 
    <div class="card-body"> 
    <!-- =======form untuk menampilkan target ion dan selisih aktual vs target======== -->
    <?php if($edit_id > 0): 
    $sql = "SELECT 
        b.nama AS bahan,
        i.id AS ion_id,
        i.senyawa AS ion,
        tk.konsentrasi_stok,
        gi.persen
    FROM nut_tangki_komposisi tk
    JOIN nut_bahan b ON b.id = tk.bahan_id
    JOIN nut_generic g ON g.id = b.generic_id
    JOIN nut_generic_ion gi ON gi.generic_id = g.id
    JOIN nut_ion i ON i.id = gi.ion_id
    WHERE tk.tangki_id = :tangki_id ";

    $dataIonTangki = $cUmum->ambilData($sql,[
        ':tangki_id'=>$tangki_id
    ])->fetchAll(PDO::FETCH_ASSOC);

 $dataStructured = [];

foreach($dataIonTangki as $row){
    $dataStructured[] = [
        'ion' => $row['ion'],
        'gr'  => (float)$row['konsentrasi_stok'],
        'persen' => (float)$row['persen']
    ];
}

echo "<script>const tangkiIonData = "
     .json_encode($dataStructured).
     ";</script>";
     ?>


      <div class="card mt-4 border-primary">
        <div class="card-header bg-primary text-white">
          Edit Dosing Tangki: <?=htmlspecialchars($namaTangki)?>
        </div>
        <div class="card-body">

          <form method="post">
            <input type="hidden" name="id" value="<?=$edit_id?>">
            <input type="hidden" name="formula_id" value="<?=$formula_id?>">
            <input type="hidden" name="node_id" value="<?=$node_id?>">

            <div class="row align-items-end">
              <div class="col-md-3">
                <label>ml per liter inject</label>
                <input type="number"
                    step="0.01"
                    id="ml_input"
                    name="ml_per_liter"
                    class="form-control"
                    value="<?=htmlspecialchars($ml_per_liter)?>">
              </div>

              <div class="col-md-3">
                <button type="submit"
                        name="save_dosing_ratio"
                        class="btn btn-success">
                  Simpan
                </button>

                <a href="nd_dosing_rasio$$<?=$formula_id?>$$<?=$node_id?>"
                  class="btn btn-secondary">
                  Batal
                </a>
              </div>

              <div class="col-md-5">
                <div class="mt-3">
                  <h6>Preview Ion Tangki Ini</h6>
                  <div id="preview-ion" class="d-flex flex-wrap gap-2"></div>
                </div>
              </div>
            </div>

          </form>

        </div>
      </div>
      <?php endif; ?>

      <!-- ======= end form untuk menampilkan target ion dan selisih aktual vs target======== -->

      <div class="card mt-3">
        <div class="card-header bg-light py-2">
          <h6 class="mb-0">Target Komposisi Ion (ppm) | selisih | aktual</h6>
        </div>
        <!-- ========== badge untuk target ion ========== -->
        <div class="card-body py-2">
          <div class="d-flex flex-wrap gap-2">

    <?php
          $sql = "SELECT i.unsur, i.senyawa AS ion,            
            COALESCE(fit.target_ppm, 0) AS target_ppm,
            COALESCE(actual.total_ppm, 0) AS actual_ppm,            
            (COALESCE(actual.total_ppm, 0) - COALESCE(fit.target_ppm, 0)) AS selisih_ppm
        FROM (
            -- semua ion dari target
            SELECT ion_id FROM nut_formula_ion_target
            WHERE formula_id = :formula_id
            
            UNION
            
            -- semua ion dari hasil inject
            SELECT gi.ion_id
            FROM nut_dosing_ratio dr
            JOIN nut_tangki t ON t.id = dr.tangki_id
            JOIN nut_tangki_komposisi tk ON tk.tangki_id = t.id
            JOIN nut_bahan b ON b.id = tk.bahan_id
            JOIN nut_generic g ON g.id = b.generic_id
            JOIN nut_generic_ion gi ON gi.generic_id = g.id
            WHERE dr.formula_id = :formula_id
            AND t.node_id = :node_id
          ) AS all_ion

        JOIN nut_ion i 
            ON i.id = all_ion.ion_id

        LEFT JOIN nut_formula_ion_target fit
            ON fit.ion_id = all_ion.ion_id
            AND fit.formula_id = :formula_id

        LEFT JOIN (SELECT gi.ion_id,
                SUM(dr.ml_per_liter * tk.konsentrasi_stok * (gi.persen / 100)) 
                    AS total_ppm                    
            FROM nut_dosing_ratio dr
            JOIN nut_tangki t ON t.id = dr.tangki_id
            JOIN nut_tangki_komposisi tk ON tk.tangki_id = t.id
            JOIN nut_bahan b ON b.id = tk.bahan_id
            JOIN nut_generic g ON g.id = b.generic_id
            JOIN nut_generic_ion gi ON gi.generic_id = g.id            
            WHERE dr.formula_id = :formula_id
            AND t.node_id = :node_id            
            GROUP BY gi.ion_id
        ) AS actual
            ON actual.ion_id = all_ion.ion_id
        ORDER BY i.senyawa;";

          $dataIon = $cUmum->ambilData($sql, [
              ':formula_id'=>$formula_id,
              ':node_id'=>$node_id
          ])->fetchAll(PDO::FETCH_ASSOC);

          foreach($dataIon as $ion):
              $target  = (float)$ion['target_ppm'];
              $actual  = (float)$ion['actual_ppm'];
              $selisih = (float)$ion['selisih_ppm'];
              // $tolerance = 5; // ppm tolerance
              $tolerance_percent = 5;
              if($target > 0){
                  $tolerance = $target * ($tolerance_percent / 100);
              }else{
                  $tolerance = 0;
              }                            
              $bg = 'ion-neutral';
              $text = 'text-dark';
              $icon = '';
              if($target == 0 && $actual > 0){
                  $bg = 'ion-over';
                  $text = 'text-danger';
                  $icon = '⚠';
              }elseif(abs($selisih) <= $tolerance){
                  $bg = 'ion-ok';
                  $text = 'text-success';
                  $icon = '✔';
              }elseif($selisih > 0){
                  $bg = 'ion-over';
                  $text = 'text-danger';
                  $icon = '▲';
              }else{
                  $bg = 'ion-under';
                  $text = 'text-warning';
                  $icon = '▼';
              }
        ?>

              <div class="px-3 py-3 border rounded <?= $bg ?> text-center"
                  style="min-width:110px;">
                <div style="font-size:14px;font-weight:600;" title="unsur dan (bentuk ion)">
                  <?=htmlspecialchars($ion['unsur']) . " (" . htmlspecialchars($ion['ion']) . ")"?>
                </div>                
                <div style="font-size:13px;" title="target dan selisih aktual">
                  <span class="<?=$text?>">
                    <?=number_format($target,1)?> <?=$icon?> <?=number_format($selisih,1)?>
                  </span>
                </div>
                <div style="font-size:14px;font-weight:600;" title="aktual berdasarkan ml per liter dosing dan komposisi tangki">
                  <?=number_format($actual,1)?> ppm
                </div>
              </div>
            <?php endforeach; ?>

          </div>
        </div>
        <!-- ========== end badge untuk target ion ========== -->

        <div class="alert alert-light border">
            <b>Formula:</b> <?=$namaFormula?> |
            <b>Node:</b> <?=$namaNode?> | 
        </div>
        
        <div class="table-responsive">
          <table id="tbl" class="table table-bordered table-striped">          
          <?php
            $sql = "SELECT dr.id, t.nama AS tangki, t.keterangan, 
                  GROUP_CONCAT(b.nama SEPARATOR ', ') AS bahan,
                  tk.konsentrasi_stok  as gram_per_liter,
                  dr.ml_per_liter AS inject_mpl
              FROM nut_dosing_ratio dr 
              JOIN nut_tangki t 
                  ON t.id = dr.tangki_id 
              LEFT JOIN nut_tangki_komposisi tk 
                  ON tk.tangki_id = t.id 
              LEFT JOIN nut_bahan b 
                  ON b.id = tk.bahan_id 
              WHERE dr.formula_id = :formula_id 
              AND t.node_id = :node_id
              GROUP BY dr.id, t.nama, t.keterangan, dr.ml_per_liter
              ORDER BY t.id;";
            $sHitTabel=isiTabelSQL($sql,"../nd_dosing_rasio", [':formula_id'=>$formula_id, ':node_id'=>$node_id]);
            echo $sHitTabel;
          ?>
          </table>
        </div>
      </div>
    </div> 
  <?php endif; ?>
  </div>
</div>

<script>
  $(function () {
    $("#tbl").DataTable({
      "order": [0, 'asc'],
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('.col-md-6:eq(0)');
  });
 
  function update_context() {
      var formula = document.querySelector('[name="formula_id"]').value;
      var node    = document.getElementById('node_id').value;
      if(formula > 0 && node > 0){
          window.location = "nd_dosing_rasio$$" + formula + "$$" + node;
      }
  } 


const inputMl = document.getElementById('ml_input');
const previewDiv = document.getElementById('preview-ion');

function hitungPreview(){

    if(!inputMl || !previewDiv || typeof tangkiIonData === 'undefined'){
        return; // aman jika bukan mode edit
    }

    let ml = parseFloat(inputMl.value) || 0;
    let hasil = {};

    tangkiIonData.forEach(row => {

        let persen = parseFloat(row.persen) / 100;
        let gr = parseFloat(row.gr);   // ← INI YANG BENAR

        let ppm = ml * gr * persen;

        if(!hasil[row.ion]){
            hasil[row.ion] = 0;
        }

        hasil[row.ion] += ppm;
    });

    renderPreview(hasil);
}

function renderPreview(data){

    let html = '';

    for(let ion in data){

        html += `
        <div class="d-inline-block me-2 mb-2 px-3 py-2 border rounded bg-light">
            <strong>${ion}</strong><br>
            ${data[ion].toFixed(2)} ppm
        </div>
        `;
    }

    previewDiv.innerHTML = html;
}

if(inputMl){
    inputMl.addEventListener('input', hitungPreview);
    hitungPreview();
} 

// inputMl.addEventListener('input', hitungPreview);

// // jalankan pertama kali
// hitungPreview();

</script>
   
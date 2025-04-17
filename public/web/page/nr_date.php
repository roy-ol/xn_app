<?php
if (1 == 0) { // IDE dummy params
  $val1 = $val2 = $val3 = "";
  $cUmum = new cUmum();
  $cUser = new cUser();
  $cTemp = new cTemplate($sNamaFile);
}

$val1 = $val1 ?? "";
$cTemp->setTitle("Node Role Date");
$cTemp->loadHeader();

$id_node = 0;
$id_role = 0;
$id_nrd = 0 ;
$tanggal = "";
$jam_mulai = "";
$jam_selesai = "";
$KetNode="Node Aktuator";
$id_perusahaan = $_SESSION['id_perusahaan'];

//periksa apakah ada post data
if (isset($_POST['id_node'])) {
  $id_node = $_POST['id_node'];
  $id_role = $_POST['id_role'];
  $tanggal = $_POST['tanggal'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];  
  $param["id_node"] = $id_node;  
  $param["id_role"] = $id_role;  
  //rubah format tanggal agar masuk ke database
  $tanggal = date('Y-m-d', strtotime($tanggal));
  $param["tanggal"] = $tanggal;  
  $jam_mulai = date('H:i:s', strtotime($jam_mulai));
  $param["jam_mulai"] = $jam_mulai;  
  $jam_selesai = date('H:i:s', strtotime($jam_selesai));
  $param["jam_selesai"] = $jam_selesai;  
  $param["updater"] = $cUser->userID();

  $id_nrd = $_POST['id_nrd']; 
  if($id_nrd > 0){
    $sSQL = "UPDATE node_role_date 
    SET id_node=:id_node,id_role=:id_role,tanggal=:tanggal,
    mulai=:jam_mulai,selesai=:jam_selesai, updater=:updater
     WHERE id = :id";
    $param["id"] = $id_nrd;  
    $cUmum->eksekusi($sSQL,$param);
  }
  else{
    $sSQL = "INSERT INTO node_role_date (id_node,id_role,tanggal,mulai,selesai,updater) 
    VALUES (:id_node,:id_role,:tanggal,:jam_mulai,:jam_selesai,:updater)";
    $cUmum->eksekusi($sSQL,$param);
  }
   
  $id_role = 0;
  $id_nrd = 0 ;
  $tanggal = "";
  $jam_mulai = "";
  $jam_selesai = "";
  $val1="id";
  $val2=$id_node;
}

if($val1 == "nrd" && $val2 > 0){
  $sSQL = "SELECT id,id_node,id_role,tanggal,mulai,selesai from node_role_date WHERE id = :id";
  $param["id"] = $val2;
  $r=$cUmum->ambil1Row($sSQL,$param); 
  $id_nrd=$val2;
  $id_node =intval($r["id_node"]);  
  $id_role =intval($r["id_role"]);  
  $tanggal = $r["tanggal"];
  $jam_mulai = $r["mulai"];
  $jam_selesai = $r["selesai"];
  $val1="id";	
  $val2=$id_node;  
}

if($val1 == "id" && $val2 > 0){
  $sSQL = "SELECT CONCAT_WS(' => ', nama, keterangan) AS ketNode FROM node WHERE id = :id"; 
  $KetNode = $cUmum->ambil1Data($sSQL, ['id' => $val2]);
  $id_node = $val2;
}


?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
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

      <div class="row">
        <!-- Kolom Kiri untuk Tabel Data -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar Jadwal</h3>
            </div>            
            <div class="card-body">
              <table class="table table-bordered">
                
                  <?php
                  // Query untuk menampilkan data jadwal yang sudah ada dari satu node
                  $sSql = "SELECT nr.id as nrd, nr.tanggal,nr.mulai,nr.selesai,r.keterangan as rule, m.memo keterangan
                  FROM node_role_date nr 
                  JOIN node_role r ON nr.id_role = r.id 
                  LEFT JOIN memo m ON m.id = r.id_memo
                  WHERE nr.id_node=$id_node ORDER BY tanggal,mulai";
                  $sTabelJadwal=isiTabelSQL($sSql,"../page/nr_date");
                  echo $sTabelJadwal;
                  ?>
                 
              </table>
              <br>
              <button class="btn btn-primary btn-sm float-right" 
              onclick="window.location.href = '../page/nr_date$$id$$<?=$id_node?>'">
              +Jadwal Baru
            </button> 
          </div>
          </div>
        </div>

        <!-- Kolom Kanan untuk Form Entry -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Tambah Jadwal Baru</h3>
            </div>
            <div class="card-body">
              <!-- bikin form post ke file ini -->
              <form action="" method="post">
                <input type="hidden" name="id_node" value=<?=$id_node;?>>
                <input type="hidden" name="id_nrd" value=<?=$id_nrd;?>> 
                <div class="form-group">
                  <label>Role</label>
                  <select id="id_role" name="id_role"  class="form-control" >
                    <option value=0>--pilih Role--</option>
                    <?php  
                      $sSqlOp1 = "SELECT nr.id, nr.keterangan FROM node_role nr 
                      WHERE nr.id_perusahaan =" . $id_perusahaan;
                      bikinOption($sSqlOp1, $id_role,"keterangan");
                    ?>
                  </select> 
                </div>
                
                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" 
                    value= "<?=htmlspecialchars($tanggal); ?>" /> 
                </div>
                
                <div class="row align-items-end">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Batas Jam Mulai</label> 
                      <div class="input-group date" id="timepicker0" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#timepicker0" 
                        value="<?=htmlspecialchars($jam_mulai); ?>" name="jam_mulai"/>                        
                        <div class="input-group-append" data-target="#timepicker0" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Batas Jam Selesai</label>
                      <div class="input-group date" id="timepicker1" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#timepicker1" 
                        value="<?=htmlspecialchars($jam_selesai); ?>"  name="jam_selesai"/>                        
                        <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-footer text-right">
                  <button type="submit" class="btn btn-primary"><?php 
                    if($id_nrd == 0){
                      echo "Simpan";
                    }else{  
                      echo "Update";  
                    }                  
                  ?>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  //Timepicker dengan format 24 jam
  $('#timepicker0').datetimepicker({
    format: 'HH:mm',
    stepping: 1
  });
  $('#timepicker1').datetimepicker({
    format: 'HH:mm',
    stepping: 1
  });

  //buat fungsi node_terpilih
  function node_terpilih(id) {  
    // const kode = 'id'; // Ganti dengan kode yang sesuai
    window.location.href = `../nr_date$$id$$${id}`; 
  }
 

</script>
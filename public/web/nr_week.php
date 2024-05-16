<?php  
require_once __DIR__ . '/menu.php';
    
?> 
<head>
<title>NR_Mingguan</title>
</head>
</head>
<link rel="stylesheet" href="css/cssumum1.css">
<body> 
<?php 
$id_nrw = 0;
$id_role = 0 ;
$id_node = 0 ;
$sNamaNode = "" ;
$sHtmlTabelNRW = "";
$mulai = "";
$selesai = "";
$h1=0; $h2=0; $h3=0; $h4=0; $h5=0; $h6=0; $h7=0;

$sURL_Action = "../web/fungsi/addNodeRoleWeek"; 
$sUrl_nrw = $_SERVER['PHP_SELF'];


if(isset($_GET['id_nrw'])){
  $id_nrw = $_GET['id_nrw'];
  $sSql = "SELECT n.nama, nrw.* FROM node_role_week nrw 
    JOIN node n ON n.id = nrw.id_node WHERE nrw.id=:id_nrw";
  $r_nrw = $cUmum->ambil1Row($sSql,['id_nrw'=>$id_nrw]);
  $id_node = $r_nrw['id_node'];
  $id_role = $r_nrw['id_role'];
  $mulai = $r_nrw['mulai'];
  $selesai = $r_nrw['selesai'];
  $h1 = $r_nrw['h1'];
  $h2 = $r_nrw['h2'];
  $h3 = $r_nrw['h3'];
  $h4 = $r_nrw['h4'];
  $h5 = $r_nrw['h5'];
  $h6 = $r_nrw['h6'];
  $h7 = $r_nrw['h7'];
  $sNamaNode = $r_nrw['nama'];
  $sURL_Action = "../web/fungsi/UpdateNRW"; 
  
  $sSQL =  "SELECT nw.id id_nrw,  CONCAT(mulai, ' ', selesai) AS jadwal, nr.keterangan AS nrole,
  CONCAT(IF(h1=1, 'Minggu, ', ''),IF(h2=1, 'Senin, ', ''), IF(h3=1, 'Selasa, ', ''),
         IF(h4=1, 'Rabu, ', ''), IF(h5=1, 'Kamis, ', ''), IF(h6=1, 'Jumat, ', ''), IF(h7=1, 'Sabtu, ', '')) AS hari_terpilih 
  FROM node_role_week nw INNER JOIN node_role nr ON nr.id = nw.id_role 
  WHERE nw.id_node = $id_node "; 
  // $param["id_node"] = $id_node; 
  $sHtmlTabelNRW = bikinTabelSQL2($sSQL,$sUrl_nrw);  
}

?>
<!-- Popup  Form -->
<div class="popup" id="popup">
  <div class="popup-content">   
    <div id="popup_memo"></div><br><br> 
    <!-- <label id="popup_memo"></label><br><br>  -->
    <button onclick="hidePopup()" style="margin: 0 auto; display: block;">Tutup</button>
  </div>
</div>
<!-- popup form -->


<form action="<?=$sURL_Action;?>" method="post">
  <select id="id_aktuator" name="id_node" value=<?=$id_node;?> style="background-color: #f9f9ff; display:none;" 
      onchange="showDetailAktuator(this.value)">
    <option value=0> --pilih Node--</option>
    <?php  
    $sSqlOp2 = "SELECT n.id,n.nama,c.chip from node n INNER JOIN chip c ON c.id = n.id_chip 
    INNER JOIN kebun k ON k.id=c.id_kebun where k.id_perusahaan=" . $id_perusahaan;
    bikinOption($sSqlOp2, $id_node, "chip", "-", "nama");
    ?>
  </select> 
  <br><label id="sNamaNode">pilih Node <?=$sNamaNode;?></label>
  <label id="lblMemAktuator" for="id_aktuator" onclick="tampilMemo(sMemAktuator)"> </label> 
  <br>
  <table>  
    <tr><td>Awal Eksekusi</td><td>
      <div class="input-group bootstrap-timepicker" >
        <input id="timepicker1" type="text" class="input-small" name="mulai" 
          style="width: 90px;" value="<?=$mulai;?>">
        <i class="glyphicon glyphicon-time input-group-addon"></i>Waktu mulai 
      </div>
    </td></tr>
    <tr><td>NodeRole</td><td>
      <select id="id_role" name="id_role" value=<?=$id_role;?>  style="background-color: #fdfdff;" 
       onchange="showDetailNR(this.value)">
      <option value=0 > --pilih Role--</option>
        <?php  
          $sSqlOp1 = "SELECT nr.id, nr.keterangan FROM node_role nr 
          WHERE nr.id_perusahaan =" . $id_perusahaan;
          bikinOption($sSqlOp1, $id_role,"keterangan");
        ?>
      </select> 
      <label id="lblMemNR" for="id_role" onclick="tampilMemo(sMemoNR)"> </label> 
    </td></tr>
    <tr><td>Batas Waktu</td><td>
      <div class="input-group bootstrap-timepicker">
          <input id="timepicker2" type="text" class="input-small" name="selesai" style="width: 90px;" value="<?=$selesai;?>">
          <i class="glyphicon glyphicon-time input-group-addon"></i>Batas mulai
      </div>
    </td></tr>
  </table> <br>
  <table>  
    <input type="checkbox" onclick="toggleAllDays()" title="Pilih Semua" > Pilih Semua Hari
    <tr>
        <td title="Minggu" class="center-text"><input <?php if($h1==1) echo 'checked="true"' ;?> type="checkbox" name="h1" onchange="updateSelectedDays()" title="Minggu"></td> 
        <td title="Senin"  class="center-text"><input <?php if($h2==1) echo 'checked="true"' ;?> type="checkbox" name="h2" onchange="updateSelectedDays()" title="Senin" ></td>  
        <td title="Selasa" class="center-text"><input <?php if($h3==1) echo 'checked="true"' ;?> type="checkbox" name="h3" onchange="updateSelectedDays()" title="Selasa"></td>  
        <td title="Rabu"   class="center-text"><input <?php if($h4==1) echo 'checked="true"' ;?> type="checkbox" name="h4" onchange="updateSelectedDays()" title="Rabu"  ></td>  
        <td title="Kamis"  class="center-text"><input <?php if($h5==1) echo 'checked="true"' ;?> type="checkbox" name="h5" onchange="updateSelectedDays()" title="Kamis" ></td>  
        <td title="Jum'at" class="center-text"><input <?php if($h6==1) echo 'checked="true"' ;?> type="checkbox" name="h6" onchange="updateSelectedDays()" title="Jum'at"></td>  
        <td title="Sabtu"  class="center-text"><input <?php if($h7==1) echo 'checked="true"' ;?> type="checkbox" name="h7" onchange="updateSelectedDays()" title="Sabtu" ></td>  
    </tr> 
    <tr>
      <td class="center-text" title="Minggu">H1</td> 
      <td class="center-text" title="Senin">H2</td>  
      <td class="center-text" title="Selasa">H3</td>  
      <td class="center-text" title="Rabu">H4</td>  
      <td class="center-text" title="Kamis">H5</td>  
      <td class="center-text" title="Jum'at">H6</td>  
      <td class="center-text" title="Sabtu">H7</td>  
    </tr> 
  </table>
  <table><tr><td>Jadwal Terpilih</td><td>
  <div id="selectedDays">--</div></td></tr></table> 
  <br>
  <input type="hidden" name="sUrl_nrw" value="<?=$_SERVER['PHP_SELF']?>"> 
  <input type="hidden" name="id_nrw" value="<?=$id_nrw;?>"> 
  <div>
    <input type="submit" value="simpan">
  </div>
</form>

<br><label id="sNamaNode2">Jadwal Mingguan <?=$sNamaNode;?></label>
<div id="sHtmlTabelNRW"><?=$sHtmlTabelNRW;?></div> 

<script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" rel="stylesheet"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
   
<script>
var sMemoNR=null;
var sMemAktuator=null;

// function tampilMemoNR() {
//   tampilMemo(sMemoNR);
// }

function tampilMemo(sIsiMemo) {
  // if(sMemoPola.length() < 2){
    if(sIsiMemo === null || sIsiMemo.length < 2){
      sIsiMemo = "Tidak ada Keterangan khusus untuk role ini "; 
    } 
    showPopup(sIsiMemo); 
    // alert(sMemoPola); //webview standar tidak bisa menampilkan alert 
}

// Fungsi untuk menampilkan popup
function showPopup(sMemo) {
  document.getElementById('popup_memo').innerHTML = sMemo;
  // document.getElementById('popup_memo').textContent = sMemo;
  document.getElementById('popup').style.display = 'block';
}

// Fungsi untuk menyembunyikan popup
function hidePopup() {
  document.getElementById('popup').style.display = 'none';
}

function showDetailAktuator(id_key){ 
  // Buat objek data dengan ID pola
  const data = {
      idKey: id_key
  };

  // Kirim permintaan AJAX dengan metode POST
  fetch('../web/fungsi/ketNode', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify(data)
  })
  .then(response => {
      console.log("Status respons:", response.status);
      return response.json();
  })
  .then(data => {
      // Tampilkan balon info dengan keterangan pola 
      const ketLabel = document.getElementById('lblMemAktuator'); 
      ketLabel.textContent = '\u25A5'; 
      // ketPolaLabel.textContent = '\u25BA'; 
      ketLabel.style.cursor = 'pointer'; 
      document.getElementById('sNamaNode').textContent ='Jadwal Node ' + data.nama;
      document.getElementById('sNamaNode2').textContent = 'Jadwal Mingguan ' + data.nama;
      document.getElementById('id_role').selectedIndex = 0;
      const now = new Date();
      const currentTime = now.toTimeString().split(' ')[0];
      document.getElementById('timepicker1').value = currentTime;
      document.getElementById('timepicker2').value = currentTime;  
      toggleAllDays();
      loadTabelNRweek(id_key);
      document.getElementById('id_aktuator').title = data.nama + ' ' + data.ketNode; //== pause ====
      sMemAktuator ='<table><tr><td>' + data.nama +' ' + data.chip + '</td><td>' + data.ketNode 
      +' =>' + data.ketChip + '</td></tr><tr><td>' + data.kebun + '</td><td>'+ data.ketKebun
      +'</td></tr></table><h4>Ket:</h4>' + data.memo ;
  })
  .catch(error => {
      console.error('Error:', error);
  });
}


function showDetailNR(id_role){ 
  // Buat objek data dengan ID pola
  const data = {
      idRole: id_role
  };

  // Kirim permintaan AJAX dengan metode POST
  fetch('../web/fungsi/ketRole', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify(data)
  })
  .then(response => {
      console.log("Status respons:", response.status);
      return response.json();
  })
  .then(data => {
      // Tampilkan balon info dengan keterangan pola 
      const ketLabel = document.getElementById('lblMemNR'); 
      ketLabel.textContent = '\u25A5'; 
      // ketPolaLabel.textContent = '\u25BA'; 
      ketLabel.style.cursor = 'pointer';
 
      document.getElementById('id_role').title = data.pola + ' (' + data.fullname + ')';
      // alert(data.memo); 
      sMemoNR ='<table><tr><td>Pola Role</td><td>' + data.pola + '</td></tr><tr><td>Updated by</td><td>'+ data.fullname 
        +'</td></tr></table><br><h4>Role:</h4>' + data.memo ;
  })
  .catch(error => {
      console.error('Error:', error);
  });
}

function toggleAllDays() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    var selectAllCheckbox = checkboxes[0];
    
    // Periksa apakah checkbox "Pilih Semua" (index 0) dicentang atau tidak
    var isChecked = selectAllCheckbox.checked;
    
    // Atur status centang semua checkbox berdasarkan status checkbox "Pilih Semua"
    for (var i = 1; i < checkboxes.length; i++) {
        checkboxes[i].checked = isChecked;
    }
    updateSelectedDays();
}


function updateSelectedDays() {
    var selectedDays = [];
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
    // Ambil nama hari yang dipilih
    for (var i = 1; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            selectedDays.push(checkboxes[i].getAttribute('title'));
        }
    }
    
    // Tampilkan nama-nama hari yang dipilih di dalam div dengan id "selectedDays"
    // document.getElementById("selectedDays").innerHTML =   selectedDays.join(", ") ;
    document.getElementById("selectedDays").innerHTML = "<label>" + selectedDays.join(", ") + "</label>";
}
 
$('#timepicker1').timepicker({
  defaultTime: 'current',
  showInputs: false, 
  showMeridian: false,
  timeFormat: 'HH:mm:ss',
  showSeconds: true, // Menampilkan detik
  secondStep: 1, // Langkah perubahan detik
  minuteStep: 1 
});
$('#timepicker2').timepicker({
  defaultTime: 'current',
  showInputs: false, 
  showMeridian: false,
  timeFormat: 'HH:mm:ss',
  showSeconds: true, // Menampilkan detik
  secondStep: 1, // Langkah perubahan detik
  minuteStep: 1 
});

function loadTabelNRweek(id_node){
  var sHtmlTabel="";
  var sUrlData="../web/fungsi/tabelNRweek";
  const data = {
    id_node: id_node,
    sUrl_nrw: '<?=$sUrl_nrw?>'
  };  
  fetch(sUrlData, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        // 'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: JSON.stringify(data)
  })  
  .then(response => {
    console.log("Status respons:", response.status);
    return response.json();
  })
  .then(data => {
    sHtmlTabel = data.tabel;
    document.getElementById('sHtmlTabelNRW').innerHTML = sHtmlTabel;
  })
  .catch(error => {
    console.error('Error:', error);
  }); 
}

function showOpsiAktuator(){
  var selectAktuator = document.getElementById('id_aktuator');
  if (selectAktuator.style.display === 'none') {
    selectAktuator.style.display = 'block';
  } else {
    selectAktuator.style.display = 'none';
  }  
}

document.getElementById('sNamaNode').addEventListener('click', function() {
  showOpsiAktuator();
}); 

</script>
</body>
</html>
</html>
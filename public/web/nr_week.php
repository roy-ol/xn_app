

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
$id_role = 0 ;
$id_node = 0 ;

$sURL_Action = "../web/fungsi/addNodeRoleWeek"; 
?>
<h2>Jadwal Mingguan</h2>
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
  <table>  
    <input type="checkbox" onclick="toggleAllDays()" title="Pilih Semua" > Pilih Semua Hari
    <tr>
        <td title="Minggu" class="center-text"><input type="checkbox" name="h1" onchange="updateSelectedDays()" title="Minggu"></td> 
        <td title="Senin"  class="center-text"><input type="checkbox" name="h2" onchange="updateSelectedDays()" title="Senin" ></td>  
        <td title="Selasa" class="center-text"><input type="checkbox" name="h3" onchange="updateSelectedDays()" title="Selasa"></td>  
        <td title="Rabu"   class="center-text"><input type="checkbox" name="h4" onchange="updateSelectedDays()" title="Rabu"  ></td>  
        <td title="Kamis"  class="center-text"><input type="checkbox" name="h5" onchange="updateSelectedDays()" title="Kamis" ></td>  
        <td title="Jum'at" class="center-text"><input type="checkbox" name="h6" onchange="updateSelectedDays()" title="Jum'at"></td>  
        <td title="Sabtu"  class="center-text"><input type="checkbox" name="h7" onchange="updateSelectedDays()" title="Sabtu" ></td>  
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
  <div id="selectedDays">--</div></td></tr></table> <br>
  <table> 
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
    <tr><td>Awal Eksekusi</td><td>
      <div class="input-group bootstrap-timepicker" >
        <input id="timepicker1" type="text" class="input-small" name="mulai" 
          style="width: 90px;">
        <i class="glyphicon glyphicon-time input-group-addon"></i>Waktu mulai 
      </div>
    </td></tr>
    <tr><td>Aktuator</td><td>
      <select id="id_aktuator" name="id_node" value=<?=$id_node;?>  style="background-color: #f9f9ff;"
       onchange="showDetailAktuator(this.value)">
      <option value=0> --pilih Node--</option>
        <?php  
          $sSqlOp2 = "SELECT n.id,n.nama,c.chip from node n INNER JOIN chip c ON c.id = n.id_chip 
          INNER JOIN kebun k ON k.id=c.id_kebun where k.id_perusahaan=" . $id_perusahaan;
          bikinOption($sSqlOp2, $id_node,"chip","-","nama",);
        ?>
      </select> 
      <label id="lblMemAktuator" for="id_aktuator" onclick="tampilMemo(sMemAktuator)"> </label> 
    </td></tr>
    <tr><td>Batas Waktu</td><td>
      <div class="input-group bootstrap-timepicker">
          <input id="timepicker2" type="text" class="input-small" name="selesai" style="width: 90px;" >
          <i class="glyphicon glyphicon-time input-group-addon"></i>Batas mulai
      </div>
    </td></tr>
  </table> 
  <br>
  <div>
    <input type="submit" value="simpan">
  </div>
</form>


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
  //  n.nama,c.chip, k.nama kebun,k.keterangan ketKebun,c.keterangan ketChip, 
  // n.keterangan ketNode,m.memo 
 
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
  showSecond:true,
  interval: 1 // 1 minutes
});
$('#timepicker2').timepicker({
  defaultTime: 'current',
  showInputs: false, 
  showMeridian: false,
  timeFormat: 'H:i:s',
  stepMinutes: 1
});

</script>
</body>
</html>
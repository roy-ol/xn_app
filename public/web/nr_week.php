<head>
    <title>NR_Mingguan</title>
</head>

<?php  
require_once __DIR__ . '/menu.php';
    
?> 
</head>
<body> 
<h2>Jadwal Mingguan</h2>
<form action="#" method="post">
  <table>  
    <input type="checkbox" onclick="toggleAllDays()" title="Pilih Semua" name="hh"> Pilih Semua Hari
    <tr>
        <td><input type="checkbox" name="h1" title="Minggu" onchange="updateSelectedDays()"></td> 
        <td><input type="checkbox" name="h2" title="Senin" onchange="updateSelectedDays()"> </td>  
        <td><input type="checkbox" name="h3" title="Selasa" onchange="updateSelectedDays()"></td>  
        <td><input type="checkbox" name="h4" title="Rabu" onchange="updateSelectedDays()"> </td>  
        <td><input type="checkbox" name="h5" title="Kamis" onchange="updateSelectedDays()"> </td>  
        <td><input type="checkbox" name="h6" title="Jum'at" onchange="updateSelectedDays()"></td>  
        <td><input type="checkbox" name="h7" title="Sabtu" onchange="updateSelectedDays()"> </td>  
    </tr> 
    <tr>
      <td title="Minggu">H1</td> 
      <td title="Senin">H2</td>  
      <td title="Selasa">H3</td>  
      <td title="Rabu">H4</td>  
      <td title="Kamis">H5</td>  
      <td title="Jum'at">H6</td>  
      <td title="Sabtu">H7</td>  
    </tr> 
</table>
<table><tr><td>Jadwal Terpilih</td><td>
<div id="selectedDays"><label>-</label></div></td></tr></table> <br>
<table>
  Waktu Eksekusi:
  <tr><td>Mulai</td><td><input type="text" class="timepicker" name="mulai"></td></tr>
  <tr><td>Mulai</td><td><input type="text" class="timepicker" name="selesai"></td></tr>
</table> 
<br>
<div>
  <input type="submit" value="Submit">
</div>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>

<script>
$(document).ready(function(){
  $('.timepicker').timepicker({
      timeFormat: 'H:i:s' ,
      step:10
  });
});
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
    document.getElementById("selectedDays").innerHTML = "<label>" + selectedDays.join(", ") + "</label>";
}
</script>
</body>
</html>
<!-- Main Footer -->
<?php 
$elapsed_time = microtime(true) - $start_loading_halaman; //true== memberikan waktu dalam format float, yaitu detik dan mikrodetik.
//tampilan elapsed 4 digit 
$elapsed_time = round($elapsed_time, 4); 
$elapsed_time = date("H:i:s") . " Load:" . $elapsed_time; 
?>
<footer class="main-footer">
  <label><?= $elapsed_time ?>s </label> <label id="elapsed-time"> durasi</label>  <label>XmartNode &copy; 2024 </label>
</footer>

</div>
<!-- ./wrapper -->
<script>
  function displayElapsedTime() {
    var startTime = new Date(); // Waktu mulai
    var interval = setInterval(updateElapsedTime, 1000); // Memperbarui waktu yang telah berlalu setiap 1 detik

    function updateElapsedTime() {
      var currentTime = new Date(); // Waktu saat ini
      var elapsedTime = Math.floor((currentTime - startTime) / 1000); // Waktu yang telah berlalu dalam detik

      var hours = Math.floor(elapsedTime / 3600);
      var minutes = Math.floor((elapsedTime % 3600) / 60);
      var seconds = elapsedTime % 60;

      // Memformat waktu menjadi format 00:00:00
      hours = (hours < 10) ? "0" + hours : hours;
      minutes = (minutes < 10) ? "0" + minutes : minutes;
      seconds = (seconds < 10) ? "0" + seconds : seconds;

      var elapsedTimeString = hours + ":" + minutes + ":" + seconds;

      document.getElementById("elapsed-time").textContent = elapsedTimeString;

    }
  }
</script>
</body>

</html>
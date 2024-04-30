<?php 
  require_once __DIR__ . '/fungsi/koneksi_umum.php';  
  
  // splashBerhasil("on Developing");  
?> 
<button onclick="tes1()">On Developing</button> 

<br>
<a href="javascript:DoPost()">Click Here</a> 

<form name='myForm' action='node_role_form.php' method='post'>
  <input type="hidden" name="nrid" value=1/>
</form>


<script language="javascript"> 

  function tes1(){
    alert("on Develope feature Test");
  }

  function DoPost(){//==pause 
    document.forms["myForm"].submit();
    // $.post('node_role_form.php', {nrid: 1}, function() { window.location.href = 'node_role_form.php' }); //pakai jQuery
  }

</script>
<!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->

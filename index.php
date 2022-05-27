<html><head>
<title>403 Forbidden</title>
</head><body>
<h1>Forbidden</h1>
<p>You don't have permission to access this resource.</p>
<hr>
<address>Apache/4.4.5 (Win64) OpenSSL/2.4.3h PHP/8.9.1 Server at Port 80 
<?php

if($_SERVER['REQUEST_METHOD']=='POST'){ 
	//Getting values  
	$sDataDataPost=file_get_contents('php://input');	
  echo "forbidden : ";
  echo $sDataDataPost; 
} elseif($_SERVER['REQUEST_METHOD']=='GET'){
  $url = " ";
  if(isset($_GET['url'])){
    $url = $_GET['url'];
  }  
  echo "forbidden : GET ";
  echo $url; 
}else{
  echo "forbidden : NOr GET Nor POST";
}
?>
</address>
</body></html>

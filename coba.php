<?php
 
require_once __DIR__ . '/app/init_class.php';

$myUser = new cUser();
if($myUser->loadUser("admin","asdf")){
  echo $myUser->fullname()."  ";
  echo ($nil = $myUser->regDroid())?$nil:"kosong";
} 

echo "  ";
$arr2 = ["token"=>"isi tambahan","token2"=>"nil dua","status"=>"sukses"];
// $arr2 = ["token"=>"isi tambahan"];
$myUser->dieJsonOK($arr2);
coba("123","ada isi");

$myUser->dieJsonGagal("testing");
// $myUser->parent::dieJsonGagal("testing");


function coba($tes1 , $t2 = null)
{
  global $myUser;
  // $mUser = $GLOBALS['myUser'];
  ($t2)? $myUser->dieJsonGagal($t2):$myUser->dieJsonGagal("NUll");
  
}
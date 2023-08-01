<?php 
date_default_timezone_set("Asia/Bangkok"); //setting waktu regional / timezone

spl_autoload_register(function( $File_class ){
  require_once __DIR__  . '/../class/'.$File_class.'.php'; 
}); 


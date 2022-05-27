<?php 
spl_autoload_register(function( $File_class ){
  require_once __DIR__  . '/../class/'.$File_class.'.php'; 
}); 
 
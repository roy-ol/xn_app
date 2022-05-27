<?php
$param = [];
$param = ["tes"=>"satu"];
$param += ["tes2"=>"dua"];
print_r($param);
var_dump($param);

$demo_array = array('Jack' => '10');
$demo_array['Michelle'] = '11'; // adding elements by pushing method
$demo_array['Shawn'] = '12';
echo "By Simple Method: <br>";
print_r($demo_array);
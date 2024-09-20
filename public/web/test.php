<?php
$to = "aking.wijang@gmail.com";
$subject = "Kirim php Mail";
$txt = "isi php mail";
$headers = "From: petugasmail@contoh.com" . "\r\n" .
"CC: rendra.hmd@gmail.com";

mail($to,$subject,$txt,$headers);
?>
<?php  
if(1==0) $cNode = new cNode();   //dummy if syntact hanya agar editor mengenali variabel $cNode sebagai class sebelumnya
// if($cNode->nodeByChip($data->c,$data->n) == false) die("Node"); 
$flag=$data->f; //contoh data masuk request f1 {"c":"XN0123456","f":5}
 

// $arrRespons['f']=0; //flag respons ke ESP tidak ada yang perlu dilakukan 
$respons['f']="2"; //flag balik dr jawaban logging status flag
// 2=update sleep time
 
$rData= $cNode->ambilData("SELECT count(id) jum, COALESCE(max(hit),0) max ,COALESCE(min(hit),0) min 
FROM `node_status` WHERE id_node= $id_node and flag=$flag" );
$jum=$rData['jum'];
$max=$rData['max'];
$min=$rData['min'];
if ($jum < 5) {  // batas Record yang disimpan di log node_status tiap id_node //berdasar id_node n flag..?
  $sql = "insert into node_status(id_node,flag,hit) values($id_node ,";
  $sql .= " $flag , " . ($max + 1) . ")" ; 
}else{
  $sql = "update node_status set hit =".($max+1)." where id_node=$id_node and hit=$min and flag=$flag " ;  
}
$r = $cNode->eksekusi($sql);
$dataAmbil=$cNode->ambil1Data("SELECT sleeptime from node_role where id_node=$id_node order by waktu desc limit 1");
if($rDataAmbil) $respons['sleep']=$dataAmbil ; 
$cNode->dieJsonOkTime($respons); 

?>
<?php
header('Content-Type: text/html; charset=utf-8');

$h = date('H'); 
$min = date('i');
$data = read_time_table();
var_dump($data);

function read_time_table() {
$day = date('w');

/* 주말/평일 구분 */
if($day==0||$day==6) $file_name = 'time_table_20220401_1.json';
else $file_name = 'time_table_20220401_0.json';

$fp = fopen($file_name, 'r');
$size = filesize($file_name);
if($size>0){
$data = fread($fp, $size);
fclose($fp);
return json_decode($data, true);
}
else{
echo '{"error":true,"msg":"Cannot read file"}';
fclose($fp);
exit;
}
}


?>

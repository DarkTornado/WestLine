<?php
header('Content-Type: text/html; charset=utf-8');

$h = date('H'); 
$min = date('i');
$data = read_time_table();
$data = explode("\n", $data);
$data = parse_time_table($data);
echo $data[0];

function read_time_table() {
$day = date('w');

/* 주말/평일 구분 */
if($day==0||$day==6) $file_name = 'time_table_20220401_1.csv';
else $file_name = 'time_table_20220401_0.csv';

$fp = fopen($file_name, 'r');
$size = filesize($file_name);
if($size>0){
$data = fread($fp, $size);
fclose($fp);
return $data;
}
else{
echo '{"error":true,"msg":"Cannot read file"}';
fclose($fp);
exit;
}
}


?>

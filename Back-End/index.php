<?php
header('Content-Type: application/json; charset=utf-8');

$h = (int)date('H'); 
$m = (int)date('i');
$s = (int)date('s');

$data = read_time_table();
$result = array();
foreach ($data as $train => $time) {
if(skip_check($train, $time)) continue;
$result[$train] = $time;
}
echo json_encode($result);


function skip_check($train, $time) {
global $h, $m, $s;

/* 아직 출발하지 않은 열차 필터링 */
$t = explode(':', $time[0]['time']);
$t[0] = (int)$t[0];
$t[1] = (int)$t[1];
$t[2] = (int)$t[2];
if($h < $t[0]) return true;
if($h == $t[0] && $m < $t[1]) return true;

/* 이미 운행이 종료된 열차 필터링 */
$t = explode(':', $time[count($time)-1]['time']);
$t[0] = (int)$t[0];
$t[1] = (int)$t[1];
$t[2] = (int)$t[2];
if($t[0] < $h) return true;
if($t[0] == $h && $t[1] < $m) return true;

return false;
}

function read_time_table() {
$day = date('w');

/* 주말/평일 구분 */
if($day==0||$day==6) $file_name = 'time_table_20220401_1.json';
else $file_name = 'time_table_20220401_0.json';

/* 시간표 파일 읽기 */
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

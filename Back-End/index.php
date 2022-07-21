<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); //일단 임시로 허용

$h = (int)date('H'); 
$m = (int)date('i');
$s = (int)date('s');
$now = time_to_sec($h, $m, $s);

$data = read_time_table();
$result = array();
$stas = array('소사', '소새울', '시흥대야', '신천', '신현', '시흥시청', '시흥능곡', '달미', '선부', '초지', '시우', '원시');
for ($n = 0;$n < count($stas); $n++) {
$result[$n] = array();
$result[$n]['sta'] = urlencode($stas[$n]);
$result[$n]['up'] = '';
$result[$n]['down'] = '';
}


foreach ($data as $train => $time) {
if(skip_check($train, $time)) continue;
$num = str_split($train, 1);
$ud = 'up';
$index = get_train_location($time);
if($num[4]%2==0) {
$ud = 'down';
$index = count($stas) - $index - 1;
}

$result[$index][$ud] = $train;
}
echo urldecode(json_encode($result));


function get_train_location($time) {
global $now;
for ($n = count($time) - 1; $n >= 0; $n--) {
$tym = time_to_sec($time[$n]['time']);
if($now==$tym) return $n;
if($now > $tym) return $n + 1;
}
return 0;
}

function time_to_sec() {
$count = func_num_args();
$params = func_get_args();
if($count==1){
$t = explode(':', $params[0]);
$t[0] = (int)$t[0];
$t[1] = (int)$t[1];
$t[2] = (int)$t[2];
return $t[0]*60*60 + $t[1] * 60 + $t[2];
}
else{
return $params[0]*60*60 + $params[1] * 60 + $params[2];
}
}

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

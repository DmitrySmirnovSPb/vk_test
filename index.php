<?php
require_once 'start.php';
$n = rand(8,15); 
$m = rand(8,15);
$map = [];
for ($i= 0 ; $i < ($n + 2); $i++){
  for($j = 0; $j < ($m +2); $j++){
    if($i == 0 || $i == $n + 1 || $j == 0 || $j == $m + 1) $map[$i][$j] = 0;
    else $map[$i][$j] = rand(0,9);
  }
}
function getС($num){
  return rand(1,$num);
}
$start = [getС(count($map)-2),getС(count($map[0])-2)];
$exit = [getС(count($map)-2),getС(count($map[0])-2)];
$map[$start[0]][$start[1]] = 'S';
if($start[0] == $exit[0] && $start[1] == $exit[1]) $exit = [getС(count($map)-2),getС(count($map[0])-2)];
$map[$exit[0]][$exit[1]] = 'E';
$json = json_encode($map);//*/
//$map = [[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],[0,2,6,9,7,7,"S",7,2,7,9,6,3,6,0],[0,0,5,3,6,5,3,5,8,1,3,3,4,2,0],[0,3,9,3,8,5,8,1,6,0,6,9,5,3,0],[0,1,0,1,4,1,1,7,0,9,2,2,0,1,0],[0,9,5,8,8,5,0,3,9,0,5,6,7,5,0],[0,3,1,4,7,0,0,1,2,2,2,5,2,4,0],[0,3,7,1,3,3,1,0,6,0,1,2,6,9,0],[0,8,6,6,9,2,4,4,3,7,0,5,4,7,0],[0,2,5,4,7,7,8,2,2,1,8,6,6,3,0],[0,3,3,1,8,3,7,3,2,5,0,5,"E",3,0],[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]];
$decision = new Decision($map);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Лабиринт</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="страница ищит самый короткий путь в лабиринте" />
	<meta name="keywords" content="" />
	<link rel="stylesheet" type="text/css" href=""/>
	<link rel="shortcut icon" href=""/>
  <style>
    td {
      width: 30px;
      height: 40px;
    }
    .type_0{
      background-color: gray;
    }
    .type_S{
      background-color: green;
    }
    .type_E{
      background-color: chartreuse;
    }
  </style>
</head>
<body>
<table border = "1">
<?php 
for ($i= 0 ; $i < count($map); $i++){ ?>
  <tr>
<?php
  for($j = 0; $j < count($map[$i]);$j++) { ?>
    <td class = "type_<?=$map[$i][$j]?>"><?=($j.':'.$i.'=>'.$map[$i][$j])?></td>
<?php } ?>
  </tr>
<?php } ?>
</table>
<?php
if($decision->history){
$summ = 0;
for($i = 0; $i < count($decision->history[1]);$i++){ ?>
  <p>Шаг №<?=($i+1)?> : Координаты (<?=$decision->history[1][$i]['x'].':'.$decision->history[1][$i]['y']?>) </p>
<?php } } ?>
</body>
</html>
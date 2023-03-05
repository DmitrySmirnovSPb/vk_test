<?php
class Decision {

private $map;        // Карта лабиринта
private $start;      // Точка старта
private $quit;       // Точка выхода
private $attempt;    // Номер попытки
public  $best_way;   // Лучший маршрут
public $history;    // Массив путей всех попыток
private $maxNum;     // Максимальное значение в ячейке
private $cp;         // Текущее положение расчёта
private $tostring;    // __toString
private $arStep = [[-1,0],[0,1],[1,0],[0,-1]];

public function __construct($map){
  $this->history = [];
  $this->attempt = 0;
  $this->maxNum = 9;
  $this->map = $map;
  $this->start = $this->getCoordinates('S')[0];
  $this->quit = $this->getCoordinates('E')[0];
  $this->best_way =$this->bestWay();
  $this->tostring = '';
}

private function bestWay(){
  $this->Way();
  $result = $this->optimization();
  $this->tostring .= '<h3> Попытка ;'.$this->attempt.'</h3>';
  $this->tostring .= '<pre>'.print_r($result, true).'</pre>';
  return $result;
}

private function Way(){
  $this->attempt++;
  $itg = $this->attempt;
  $stepNum = 0;
  $this->cp = ['x'=>$this->start[1],'y'=>$this->start[0]];
  $this->history[$itg][$stepNum]['x'] = $this->start[1];
  $this->history[$itg][$stepNum]['y'] = $this->start[0];
  $this->history[$itg][$stepNum]['s'] = 0;
  while (!($this->cp['x'] == $this->quit[1] && $this->cp['y'] == $this->quit[0])){
    $stepNum++;
    $step = $this->NextStep($stepNum);
    if($step === false) break;
    $this->history[$itg][$stepNum]['x'] = $step['x'];
    $this->history[$itg][$stepNum]['y'] = $step['y'];
    $this->history[$itg][$stepNum]['s'] = $step['s'];
    if($stepNum >= 1000)break;
  }
  return true;
}

private function NextStep($y){
  $min = [];
  $step = [];
  $quitX = $this->quit[1];
  $quitY = $this->quit[0];
  for($i = 0; $i < count($this->arStep);$i++){
    $k = 1;
    $step[$i]['x'] = $this->cp['x'] + $this->arStep[$i][1];
    $step[$i]['y'] = $this->cp['y'] + $this->arStep[$i][0];
    $step[$i]['s'] = $this->map[$step[$i]['y']][$step[$i]['x']];
    if($y > 1){
      if(abs($quitX - $step[$i]['x']) > abs($quitX - $this->cp['x'])) $k *= 1.5;
      elseif(abs($quitX - $step[$i]['x']) < abs($quitX - $this->cp['x'])) $k *= .5;
      if(abs($quitY - $step[$i]['y']) > abs($quitY - $this->cp['y'])) $k *= 1.5;
      elseif(abs($quitY - $step[$i]['y']) < abs($quitY - $this->cp['y'])) $k *= .5;
    }
    $b_1 = ($y > 1)?(!($this->history[$this->attempt][$y-2]['x'] == $step[$i]['x'] &&
      $this->history[$this->attempt][$y-2]['y'] == $step[$i]['y'])):true;

    $b_2 = $step[$i]['s'] !== 0;
    $b_3 =  !($step[$i]['s'] === 'S');
    $b_4 =  $step[$i]['s'] === 'E';
    if($b_4) {
      $this->cp = ['x'=>$quitX,'y'=>$quitY];
      return ['x'=>$quitX,'y'=>$quitY,'s'=>0];
    }

    if($b_1 && $b_2 && $b_3)
      $min[$i] = $k*$this->map[$step[$i]['y']][$step[$i]['x']];
  }
  if(count($min) < 1) return false;
  $result = array_keys($min, min($min))[0];
  $this->cp = $step[$result];
  return $step[$result];
}

private function getCoordinates($value){
  $result = [];
  for($i = 0 ; $i < count($this->map); $i++){
    for($j = 0 ; $j < count($this->map[0]); $j++){
      if($this->map[$i][$j] == $value) $result[] = [$i,$j];
    }
  }
  return $result;
}

private function optimization(){
  $result =[];
  $res =[];
  $tmp = 0;
  for($i = 0; $i < count($this->history[$this->attempt])-2; $i++){
    for($j = $i + 2; $j < count($this->history[$this->attempt]); $j++){
      for($s = 0; $s < count($this->arStep); $s++){
        $iX = $this->history[$this->attempt][$i]['x'] + $this->arStep[$s][1];
        $iY = $this->history[$this->attempt][$i]['y'] + $this->arStep[$s][0];
        if($iX == $this->history[$this->attempt][$j]['x'] && $iY == $this->history[$this->attempt][$j]['y'])
          $result[] = [$i, $j];
      }
    }
  }
  $tmp1 = 0;
  if(count($result) > 0){
    for($i = 0; $i < count($this->history[$this->attempt])-1; $i++){
      if($i <= $result[$tmp1][0] || $i >= $result[$tmp1][1]){
        $res[$tmp] = $this->history[$this->attempt][$i];
        $tmp++;
        if(count($result) - 1 > $tmp1 && $i >= $result[$tmp1][1]) $tmp1++;
      }
    }
    $this->history[$this->attempt] = $res;
  }
  return $this->history[$this->attempt];
}

public function __toString(){
  return $this->tostring;
}

}
?>
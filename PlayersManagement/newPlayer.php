<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";



  function searchFreeMapTiles(){



  }

  function getSurroundingNotOccupiedTiles($x,$y,$radius){
    $tiles = array();
    $xFrom = $x - $radius;
    $xTo = $x + $radius;
    $yFrom = $y - $radus;
    $yTo = $y + $radius;

    for ($i = $xFrom; $i <= $xTo; $i++) {
      for ($j = $yFrom; $j <= $yTo; $j++) {
        $tile = getTileMapFromDB($i,$j);
        if($tile['id_owner'] != null){
          return false;
        }
        $tiles[] = $tile;
      }
    }
    return $tiles;
  }

  function checkArea($span, $iterations){
    $pointsToCheck = array();

    for($i = 0 ; $i > -$iterations ; $i--){
      for($j = $i ; $j < -$i ; $j+=2 ){
        $pointsToCheck[] = array($i,$j);
        $pointsToCheck[] = array(-$i,-$j);
        $pointsToCheck[] = array(-$j,$i);
        $pointsToCheck[] = array($j,-$i);
      }

    }


    return $pointsToCheck;

  }

  echo( json_encode(checkArea(2,5)));


?>

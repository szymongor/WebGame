<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";



  function searchFreeMapTiles(){
    $radius = 3;
    $iteration = 1;
    $tilesRegion;
    while ($iteration<5) {
      $tilesToCheck = checkArea(10,$iteration);
      foreach ($tilesToCheck as $key => $value) {
        $tilesRegion = getSurroundingNotOccupiedTiles($value[0],$value[1],$radius);
        if($tilesRegion){
          $tilesRegion = $value;
          break;
        }
      }
      if($tilesRegion){
        break;
      }
    }
    echo(json_encode($tilesRegion));

  }

  function getSurroundingNotOccupiedTiles($x,$y,$radius){
    $tiles = array();
    $xFrom = $x - $radius;
    $xTo = $x + $radius;
    $yFrom = $y - $radius;
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

  function checkArea($span, $iteration){
    $pointsToCheck = array();
    $i=-$iteration;
    for($j = $i ; $j < -$i ; $j+=2 ){
      $pointsToCheck[] = array($span*$i,$span*$j);
      $pointsToCheck[] = array($span*(-$i),$span*(-$j));
      $pointsToCheck[] = array($span*(-$j),$span*($i));
      $pointsToCheck[] = array($span*($j),$span*(-$i));
    }
    return $pointsToCheck;
  }

  searchFreeMapTiles();
  //echo( json_encode(checkArea(10,2)) );


?>

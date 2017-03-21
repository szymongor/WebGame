<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";

  function initNewPlayer($playerId){
    $tile = searchFreeMapTiles();
    if(!$tile) return "There is no place for new player";
    $initBuildings = array(array(0,0,"Castle"),array(-1,-1,"House"),array(1,1,"House"));
    foreach ($initBuildings as $value) {
      $x=$value[0]+$tile[0];
      $y=$value[1]+$tile[1];
      setTileBuilding($x,$y,$value[2]);
    }
    setPlayerLoation($playerId,$tile[0],$tile[1]);
    return "Success";
  }

  function searchFreeMapTiles(){
    $radius = 3;
    $iteration = 1;
    $tilesRegion = false;
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

    return $tilesRegion;
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

  //searchFreeMapTiles();
  //echo( json_encode(initNewPlayer(14)) );


?>

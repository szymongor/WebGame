<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Rules.php";

  function initNewPlayer($playerId){
    $tiles = searchFreeMapTiles();
    if(!$tiles) return "There is no place for new player";
    $initBuildings = array(array(0,0,"Castle"),array(-1,-1,"House"),array(1,1,"House"));
    foreach ($initBuildings as $value) {
      $x=$value[0]+$tiles["coords"][0];
      $y=$value[1]+$tiles["coords"][1];
      setTileBuilding($x,$y,$value[2]);
    }
    setPlayerLoation($playerId,$tiles["coords"][0],$tiles["coords"][1]);
    initUserResourcesIncomeDB($playerId);
    initUserResources($playerId);

    $initResources = Rules::getRules("Resources")["InitResources"];
    transferResourcesDB($playerId,$initResources);

    foreach ($tiles['tiles'] as $value) {
      changeTileOwnerDB($playerId,$value['x_coord'],$value['y_coord']);
    }

    return "Success";
  }

  function searchFreeMapTiles(){
    $radius = 3;
    $iteration = 1;
    $tilesRegion = false;
    while ($iteration<5) {
      $tilesToCheck = checkArea(10,$iteration);
      foreach ($tilesToCheck as $key => $value) {
        $tilesRegion['tiles'] = getSurroundingNotOccupiedTiles($value[0],$value[1],$radius);
        if($tilesRegion['tiles']){
          $tilesRegion["coords"] = $value;
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
  //echo( json_encode(Rules::getRules("Resources")["InitResources"]) );
  //initNewPlayer(14);

?>

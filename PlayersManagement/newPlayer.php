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



?>

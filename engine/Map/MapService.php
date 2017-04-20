<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/Engine/Map/MapDAO.php";

  class MapService{

    private $Dao;

    public function __construct(){
      $this->Dao = new MapDAO();
    }

    public function getMapTile($x, $y){
      $tile = $this->Dao->getMapTile($x, $y);
      return $tile;
    }

    public function getMapRegion($xFrom,$xTo,$yFrom,$yTo,$playerId){
      $tilesFromDB = $this->Dao->getMapRegion($xFrom-1,$xTo+1,$yFrom-1,$yTo+1);
      $gridFromDB = $this->mapToGrid($tilesFromDB,$xFrom+1,$xTo-1,$yFrom+1,$yTo-1);
      $tilesSeenByPlayer = $this->emptyGrid($xFrom,$xTo,$yFrom,$yTo);

      foreach ($tilesFromDB as $value) {
        if($value['id_owner'] == $playerId){
          $tilesSeenByPlayer[$value['x_coord']][$value['y_coord']] = $value;
          if($value['x_coord']-1 >= $xFrom)
          $tilesSeenByPlayer[$value['x_coord']-1][$value['y_coord']] = $gridFromDB[$value['x_coord']-1][$value['y_coord']];
          if($value['x_coord']+1 < $xTo)
          $tilesSeenByPlayer[$value['x_coord']+1][$value['y_coord']] = $gridFromDB[$value['x_coord']+1][$value['y_coord']];
          if($value['y_coord']-1 >= $yFrom )
          $tilesSeenByPlayer[$value['x_coord']][$value['y_coord']-1] = $gridFromDB[$value['x_coord']][$value['y_coord']-1];
          if($value['y_coord']+1 < $yTo )
          $tilesSeenByPlayer[$value['x_coord']][$value['y_coord']+1] = $gridFromDB[$value['x_coord']][$value['y_coord']+1];
          if($value['x_coord']-1 >= $xFrom && $value['y_coord']-1 >= $yFrom )
          $tilesSeenByPlayer[$value['x_coord']-1][$value['y_coord']-1] = $gridFromDB[$value['x_coord']-1][$value['y_coord']-1];
          if($value['x_coord']+1 < $xTo && $value['y_coord']-1 >= $yFrom )
          $tilesSeenByPlayer[$value['x_coord']+1][$value['y_coord']-1] = $gridFromDB[$value['x_coord']+1][$value['y_coord']-1];
          if($value['x_coord']+1 < $xTo && $value['y_coord']+1 < $yTo )
          $tilesSeenByPlayer[$value['x_coord']+1][$value['y_coord']+1] = $gridFromDB[$value['x_coord']+1][$value['y_coord']+1];
          if($value['x_coord']-1 >= $xFrom && $value['y_coord']+1 < $yTo )
          $tilesSeenByPlayer[$value['x_coord']-1][$value['y_coord']+1] = $gridFromDB[$value['x_coord']-1][$value['y_coord']+1];
        }
      }

      return $tilesSeenByPlayer;
    }

    private function emptyGrid($xFrom,$xTo,$yFrom,$yTo){
      $grid = array();
      for($i = $xFrom ; $i < $xTo ; $i++){
        $row = array();
        for($j = $yFrom ; $j < $yTo ; $j++){
          $row[$j]=array();
        }
        $grid[$i] = $row;
      }
      return $grid;
    }

    private function mapToGrid($mapData,$xFrom,$xTo,$yFrom,$yTo){
      $gridMap = $this->emptyGrid($xFrom,$xTo,$yFrom,$yTo);
      foreach ($mapData as $value) {
          $gridMap[$value['x_coord']][$value['y_coord']]=$value;
      }
      return $gridMap;
    }

  }

    $MapService = new MapService();
    $response = $MapService->getMapRegion(0,7,0,7,12);

    echo( json_encode($response));
?>

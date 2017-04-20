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
      $tilesFromDB = $this->Dao->getMapRegion($xFrom,$xTo,$yFrom,$yTo);
      $tilesFromDB = $this->mapToGrid($tilesFromDB,$xFrom,$xTo,$yFrom,$yTo);
      $tilesSeenByPlayer = $this->emptyGrid($xFrom,$xTo,$yFrom,$yTo);
      return $tilesFromDB;
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

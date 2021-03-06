<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/Engine/Map/MapDAO.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/Engine/Army/ArmyDAO.php";

  class MapService{

    private $mapDAO;
    private $armyDAO;

    public function __construct(){
      $this->mapDAO = new MapDAO();
      $this->armyDAO = new ArmyDAO();
    }

    public function getMapTile($x, $y){
      $tile = $this->mapDAO->getMapTile($x, $y);
      return $tile;
    }

    public function getMapRegion($xFrom,$xTo,$yFrom,$yTo,$playerId){
      $tilesFromDB = $this->mapDAO->getMapRegion($xFrom-1,$xTo+1,$yFrom-1,$yTo+1);
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

    public function addArmyToTile($x, $y, $army){
      $armyId = $this->mapDAO->getArmyIdByLocation($x,$y);
      if($armyId == NULL){
        $armyId = $this->armyDAO->initArmy();
        $this->mapDAO->initTileArmy($x,$y,$armyId);
      }
      $this->armyDAO->transferArmy($armyId,$army);
    }

    public function getArmyFromTile($x, $y){
      $tile = $this->getMapTile($x,$y);
      if(isset($tile['army_id'])){
        $army = $this->armyDAO->getArmyById($tile['army_id']);
        return $army;
      }
      else{
        return getEmptyArmy();
      }
    }

  }

    $MapService = new MapService();
    //$army = array('Shaman' => 10, 'Wizard' => 20);
    $response = $MapService->getArmyFromTile(2,5);
    echo( json_encode($response));
?>

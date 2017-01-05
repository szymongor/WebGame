<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Building.php";

  class Player
  {
    private $playerId;

    public function __construct($id){
      $this->playerId=$id;
    }

    public function getPlayerId(){
      return $this->playerId;
    }

    public function getPlayerResources(){
      return getUserResources($this->playerId);
    }

    public function checkPlayerResourcesState($requiredResources){
      $playerResources = $this->getPlayerResources();
      foreach ($requiredResources as $key => $value) {
        if($playerResources[$key]<$value){
          return false;
        }
      }
      return true;
    }

    public function getPlayerResourcesIncome(){
      $playersBuildings = $this->getPlayersBuildings();
      $playersIncome = array();
      foreach ($playersBuildings as $value) {
        $playersBuilding = new Building($value['type']);
        $buildingIncome = $playersBuilding->calculateIncome($value['x_coord'],$value['y_coord'],$this->playerId);
        foreach ($buildingIncome as $key => $value) {
          if(isset($playersIncome[$key])){
            $playersIncome[$key] += $value;
          }
          else{
            $playersIncome[$key] = $value;
          }
        }
      }


      return $playersIncome;
    }

    public function updatePlayerResourcesIncome(){
      $playersIncome = $this->getPlayerResourcesIncome();
      setPlayersIncomeDB($this->playerId,$playersIncome);
    }

    public function getMapTile($x,$y){
      $map = getMapRegionFromDB($this->playerId, $x - 1 , $x + 1 , $y - 1, $y + 1);
      if(count($map) > 0 ){
        $tile = getTileMapFromDB($x,$y);

        if($tile['building_id'] != NULL){
          $tile['building'] = getBuildingByIDFromDB($tile['building_id']);
        }
        else{
          $tile['building'] = NULL;
        }
        unset($tile['building_id']);

        if($tile['army_id'] != NULL){
          $tile['army'] = getArmyFromDB($tile['army_id']);
        }
        else{
          $tile['army'] = NULL;
        }
        unset($tile['army_id']);

        return $tile;
      }
      else{
        $fogTile = array('x_coord' => $x, 'y_coord' => $y, 'id_owner' => NULL, 'biome' => 'Fog', 'building_id' => NULL);
        return $fogTile;
      }

    }

    public function isTileOwned($x,$y){
      $tile = $this->getMapTile($x,$y);
      if($tile['id_owner'] == $this->playerId){
        return true;
      }
      else{
        return false;
      }
    }

    public function getMapRegion($xFrom, $xTo, $yFrom, $yTo){
      $mapView = array();
      $ownedTiles = getMapRegionFromDB($this->playerId, $xFrom, $xTo, $yFrom, $yTo);
      $initArmy = getArmyTypesFromDB();
      for($i = $xFrom; $i <= $xTo ; $i++){
        $row = array();
        for($j = $yFrom; $j <= $yTo ; $j++){
          $fogTile = array('x_coord' => $i, 'y_coord' => $j, 'id_owner' => NULL, 'biome' => 'Fog', 'building_id' => NULL, 'army' => $initArmy);
          array_push($row,$fogTile);
        }
        array_push($mapView,$row);
      }

      foreach ($ownedTiles as $value) {
        $mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom] = $value;

      }



      foreach ($ownedTiles as $value) {
        if($value['x_coord']-1>=$xFrom && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']);
          unset($mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-$yFrom]['army_id']);
        }


        if($value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord'],$value['y_coord']-1);
          unset($mapView[$value['x_coord']-$xFrom][$value['y_coord']-1-$yFrom]['army_id']);
        }


        if($value['x_coord']+1<=$xTo && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']);
          unset($mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-$yFrom]['army_id']);
        }


        if($value['y_coord']+1<=$yTo && $mapView[$value['x_coord']-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord'],$value['y_coord']+1);
          unset($mapView[$value['x_coord']-$xFrom][$value['y_coord']+1-$yFrom]['army_id']);
        }


        if($value['x_coord']-1>=$xFrom && $value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']-1);
          unset($mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-1-$yFrom]['army_id']);
        }


        if($value['x_coord']-1>=$xFrom && $value['y_coord']+1<=$yTo && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']+1);
          unset($mapView[$value['x_coord']-1-$xFrom][$value['y_coord']+1-$yFrom]['army_id']);
        }


        if($value['x_coord']+1<=$xTo && $value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']-1);
          unset($mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-1-$yFrom]['army_id']);
        }


        if($value['x_coord']+1<=$xTo && $value['y_coord']+1<=$yTo && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']+1);
          unset($mapView[$value['x_coord']+1-$xFrom][$value['y_coord']+1-$yFrom]['army_id']);
        }



      }

      for($i = 0 ; $i < $xTo-$xFrom ; $i++){
        for($j = 0 ; $j < $yTo-$yFrom ; $j++){
          if($mapView[$i][$j]['building_id'] != NULL){
            $mapView[$i][$j]['building'] = getBuildingByIDFromDB($mapView[$i][$j]['building_id']);
          }
          else{
            $mapView[$i][$j]['building'] = NULL;
          }
          unset($mapView[$i][$j]['building_id']);
        }
      }

      foreach ($ownedTiles as $value){
        if($value['army_id'] != NULL){
          $mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom]['army'] = getArmyFromDB($value['army_id']);
        }
        else{
          $mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom]['army'] = $initArmy;
        }
        unset($mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom]['army_id']);

      }

      //$response = json_encode($mapView);
      return $mapView;
    }

    public function conquer($x, $y){
      $map = getMapRegionFromDB($this->playerId, $x - 1 , $x + 1 , $y - 1, $y + 1);
      if(count($map) > 0){
        changeTileOwner($this->playerId, $x, $y);
      }
       return $this->getMapRegion($x - 1 , $x + 1 , $y - 1, $y + 1);

    }

    public function getBuilding($x, $y){
      $tile = $this->getMapTile($x,$y);
      if($tile!=NULL || $tile["building"]!=NULL){
        return getBuildingFromDB($x, $y);
      }
      return "null";
    }

    public function getBuildingFunctions($x,$y){
      if( !$this->isTileOwned($x,$y)){
        return "Not owned";
      }
      else{
        $buildingDB = $this->getBuilding($x,$y);
        if($buildingDB == null){
          return "No building here!";
        }
        else{
          $buildingInfo = new Building($buildingDB["type"]);
          return $buildingInfo->getBuildingFunctions();
        }
      }

    }

    public function buildBuilding($x,$y,$buildingType){
      $tile = $this->getMapTile($x,$y);
      $result = true;
      $buildingInfo = Building::getBuildingInfo($buildingType);

      if($tile['id_owner'] != $this->playerId ){
        return "Tile not owned!";
      }
      if($tile['building'] != NULL){
        return "Tile already occupied!";
      }
      if(!$this->checkPlayerResourcesState($buildingInfo['Cost'])){
        return "Not enough resources!";
      }

      $cost = $buildingInfo['Cost'];

      foreach ($cost as $key => $value) {
        $cost[$key] *= -1;
      }

      transferResources($this->playerId, $cost);
      setTileBuilding($x,$y,$buildingType);
      $this->updatePlayerResourcesIncome();
      return $this->getMapTile($x,$y);
    }

    public function getPlayersBuildings(){
      $userBuildings = getPlayersBuildingsFromDB($this->playerId);
      return $userBuildings;
    }

    public function getPlayersArmy(){
      return getPlayersArmyByIdDB($this->playerId);
    }
  }

  //session_start();
  //print_r($_SESSION['Player']->updatePlayerResourcesIncome());

?>

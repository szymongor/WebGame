<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";

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

    public function getMapTile($x,$y){
      $map = getMapRegionFromDB($this->playerId, $x - 1 , $x + 1 , $y - 1, $y + 1);
      if(count($map) > 0 ){
        return getTileMapFromDB($x,$y);
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
      for($i = $xFrom; $i <= $xTo ; $i++){
        $row = array();
        for($j = $yFrom; $j <= $yTo ; $j++){
          $fogTile = array('x_coord' => $i, 'y_coord' => $j, 'id_owner' => NULL, 'biome' => 'Fog', 'building_id' => NULL);
          array_push($row,$fogTile);
        }
        array_push($mapView,$row);
      }

      foreach ($ownedTiles as $value) {
        $mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom] = $value;
      }



      foreach ($ownedTiles as $value) {
        if($value['x_coord']-1>=$xFrom && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-$yFrom]['biome'] == 'Fog')
        $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']);

        if($value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog')
        $mapView[$value['x_coord']-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord'],$value['y_coord']-1);

        if($value['x_coord']+1<=$xTo && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-$yFrom]['biome'] == 'Fog')
        $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']);

        if($value['y_coord']+1<=$yTo && $mapView[$value['x_coord']-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog')
        $mapView[$value['x_coord']-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord'],$value['y_coord']+1);

        if($value['x_coord']-1>=$xFrom && $value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog')
        $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']-1);

        if($value['x_coord']-1>=$xFrom && $value['y_coord']+1<=$yTo && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog')
        $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']+1);

        if($value['x_coord']+1<=$xTo && $value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog')
        $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']-1);

        if($value['x_coord']+1<=$xTo && $value['y_coord']+1<=$yTo && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog')
        $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']+1);


      }

      $response = json_encode($mapView);
      return $response;
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
      if($tile["building_id"]!=NULL){
        return json_encode(getBuildingFromDB($x, $y));
      }
      return "null";
    }

    public function buildBuilding($x,$y,$buildingType){
      $tile = $this->getMapTile($x,$y);
      $result = true;
      $buildingInfo = Building::getBuildingInfo($buildingType);

      if($tile['id_owner'] != $this->playerId ){
        return "Tile not owned!";
      }
      if($tile['building_id'] != NULL){
        return "Tile already occupied!";
      }
      if(!$this->checkPlayerResourcesState($buildingInfo['Cost'])){
        return "Not enough resources!";
      }

      transferResources($this->playerId, $buildingInfo['Cost']);
      setTileBuilding($x,$y,$buildingType);

      return $this->getMapTile($x,$y);
    }

  }
?>

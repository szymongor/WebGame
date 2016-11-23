<?php
  require $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";

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

    public function getMapTile($x,$y){
      return getTileMap($x,$y);
    }

    public function getMapRegion($xFrom, $xTo, $yFrom, $yTo){
      $mapView = array();
      $ownedTiles = getMapRegion($this->playerId, $xFrom, $xTo, $yFrom, $yTo);
      for($i = $xFrom; $i <= $xTo ; $i++){
        $row = array();
        for($j = $yFrom; $j <= $yTo ; $j++){
          $fogTile = array('x_coord' => $i, 'y_coord' => $j, 'id_owner' => NULL, 'biome' => 'Fog', 'building_id' => NULL);
          array_push($row,$fogTile);
        }
        array_push($mapView,$row);
      }

      foreach ($ownedTiles as $value) {
        $mapView[$value['x_coord']][$value['y_coord']] = $value;
      }

      $response = json_encode($mapView);
      return $response;
    }

  }
?>

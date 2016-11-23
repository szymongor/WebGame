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

    public function getMap($x,$y){
      return getTileMap($x,$y);
    }

  }
?>

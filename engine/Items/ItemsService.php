<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/Engine/Items/ItemsDAO.php";

  class ItemsService{

    private $itemsDAO;

    public function __construct(){
      $this->itemsDAO = new ItemsDAO();
    }

    public function getUserItems($playerId){
      return $this->itemsDAO->getUserItems($playerId);
    }

    public function transferItems($playerId, $items){
      return $this->itemsDAO->transferItems($playerId, $items);
    }

    public function checkPlayerItemsState($playerId, $requiredItems){
      $playersItems = $this->getUserItems($playerId);
      foreach ($requiredItems as $key => $value) {
        if($playersItems[$key]+$value<0){
          return false;
        }
      }
      return true;
    }

  }


  //$itemsService = new ItemsService();
  //$items = array('Tool'=>-575, 'Armor' => 10);
  //$response = $itemsService->checkPlayerItemsState(12,$items);
  //echo json_encode($response);


?>

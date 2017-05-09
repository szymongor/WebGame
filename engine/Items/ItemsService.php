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

  }


//  $itemsService = new ItemsService();
//  $items = array('Tool'=>100, 'Armor' => 10);
//  $response = $itemsService->transferItems(12,$items);
//  echo json_encode($response);


?>

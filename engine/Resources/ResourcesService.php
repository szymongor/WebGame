<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/DAO/DAOInterface.php";

class ResourcesService{

  private $DAO;

  public function __construct(){
    $this->DAO = new DAOInterface();
  }

  private function upDatePlayerResources($playerId){
    $lastUpdateTime = $this->DAO->getPlayersLastResourcesUpDate($playerId);
    $timeNow = time();
    $resourcesCapacity = $this->DAO->getPlayerResourcesCapacity($playerId);
    $currentResources = $this->DAO->getPlayerResources($playerId);
    $resourcesIncome = $this->DAO->getPlayerResourcesIncome($playerId);

    $timeSpan = $timeNow-$lastUpdateTime;
    if($timeSpan>=60)
    {
      $n = floor($timeSpan/60);
      $timePass = $lastUpdateTime+$n*60;
      $resourcesAfterUpdate = array('Wood' => 0,'Stone' => 0, 'Iron' => 0, 'Food' => 0);

      foreach ($resourcesAfterUpdate as $key => $value) {
        $resourcesAfterUpdate[$key] = $currentResources[$key] +$n*$resourcesIncome[$key];
        if($resourcesAfterUpdate[$key] > $resourcesCapacity[$key]){
          $resourcesAfterUpdate[$key] = $resourcesCapacity[$key];
        }
      }

      $this->DAO->setPlayersLastResourcesUpDate($playerId,$timePass);
      $this->DAO->setPlayerResources($playerId,$resourcesAfterUpdate);
    }
  }

  public function getPlayerResources($playerId){
    $this->upDatePlayerResources($playerId);
    $playerResources = $this->DAO->getPlayerResources($playerId);
    return $playerResources;
  }

  public function getPlayerResourcesCapacity($playerId){
    $this->DAO->getPlayerResourcesCapacity($playerId);
  }

  public function getPlayerResourcesIncome($playerId){
    $this->DAO->getPlayerResourcesIncome($playerId);


  }

  public function chceckSufficientResourcesAmount($playerId,$requiredResources){
    $playerResources = $this->getPlayerResources($playerId);
    foreach ($playerResources as $key => $value) {
      if(isset($requiredResources[$key])){
        if(0>$value+$requiredResources[$key]){
          return false;
        }
      }
    }
    return true;
  }

  public function transferResources($playerId,$resources){
    //TODO
  }

}

//$resService = new ResourcesService();
//echo json_encode($resService->getPlayerResources(12));

?>

<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Engine/connect.php";

class ResourcesManager{

  private $DB

  public function __construct(){
    $this->DB = new DbInterface();
  }

  private function upDatePlayerResources($playerId){
    $lastUpdateTime = $DB->getPlayersLastResourcesUpDate($playerId);
    $timeNow = time();
    $resourcesCapacity = $DB->getPlayerResourcesCapacity($playerId);
    $currentResources = $DB->getPlayerResources($playerId);
    $resourcesIncome = $DB->getPlayerResourcesIncome($playerId);

    $n = floor($timeSpan/60);
    $timePass = $timeLastUpdate+$n*60;
    $resourcesAfterUpdate = array('Wood' => 0,'Stone' => 0, 'Iron' => 0, 'Food' => 0);

    foreach ($resourcesAfterUpdate as $key => $value) {
      $resourcesAfterUpdate[$key] = $resourcesRow[$key] +$n*$incomeRow[$key];
      if($resourcesAfterUpdate[$key] > $resourcesCapacity[$key]){
        $resourcesAfterUpdate[$key] = $resourcesCapacity[$key];
      }
    }

    $DB->setPlayersLastResourcesUpDate($playerId,$timePass);
    //TO DO


  }

  public function getPlayerResources($playerId){

  }

}

?>

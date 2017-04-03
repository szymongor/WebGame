<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/DB/DbInterface.php";

class ResourcesManager{

  private $DB;

  public function __construct(){
    $this->DB = new DbInterface();
  }

  //private
  public function upDatePlayerResources($playerId){
    $lastUpdateTime = $this->DB->getPlayersLastResourcesUpDate($playerId);
    $timeNow = time();
    $resourcesCapacity = $this->DB->getPlayerResourcesCapacity($playerId);
    $currentResources = $this->DB->getPlayerResources($playerId);
    $resourcesIncome = $this->DB->getPlayerResourcesIncome($playerId);

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

      $this->DB->setPlayersLastResourcesUpDate($playerId,$timePass);
      $this->DB->setPlayerResources($playerId,$resourcesAfterUpdate);
    }
  }

  public function getPlayerResources($playerId){

  }


}

$resMng = new ResourcesManager();
$resMng->upDatePlayerResources(12);

?>

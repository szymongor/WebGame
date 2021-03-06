<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Player.php";

  class Task{

    private $task;
    private $verify;

    public function __construct($taskFromDB){
      $this->task = $taskFromDB;
      $this->verify = "ok";
    }

    private function getTaskEffect(){
      return json_decode($this->task['task_effect'],true);
    }

    private function getTaskId(){
      return $this->task['task_id'];
    }

    private function addResources($resourcesData){
      $playerId;
      if(isset($resourcesData['destination'])){
        if(isset($resourcesData['destination']['buildingOwner'])){
          $playerId = getOwnerByBuildingIdDB($resourcesData['destination']['buildingOwner']);
        }
        elseif (isset($resourcesData['destination']['owner'])) {
          $playerId = $resourcesData['destination']['owner'];
        }
        if(isset($resourcesData['transfer'])){
          transferResourcesDB($playerId,$resourcesData['transfer']);
        }
      }
    }

    private function addArmy($armyData){
      $playerId;
      if(isset($armyData['destination'])){
        if(isset($armyData['destination']['buildingOwner'])){
          $playerId = getOwnerByBuildingIdDB($armyData['destination']['buildingOwner']);;
        }
        elseif (isset($armyData['destination']['owner'])) {
          $playerId = $armyData['destination']['owner'];
        }
        if(isset($armyData['transfer'])){
          transferPlayersArmyDB($playerId,$armyData['transfer']);
        }
      }
    }

    private function addItems($itemsData){
      $playerId;
      if(isset($itemsData['destination'])){
        if(isset($itemsData['destination']['buildingOwner'])){
          $playerId = getOwnerByBuildingIdDB($itemsData['destination']['buildingOwner']);
        }
        elseif (isset($itemsData['destination']['owner'])) {
          $playerId = $itemsData['destination']['owner'];
        }
        if(isset($itemsData['transfer'])){
          transferItemsDB($playerId,$itemsData['transfer']);
        }
      }
    }

    private function updateBuilding($buildingData){
      upDateBuildingDB($buildingData['buildingId'],$buildingData['buildingData']);
      $buildingOwner = new Player(getOwnerByBuildingIdDB($buildingData['buildingId']));
      $buildingOwner->updateStats();
    }

    private function upgradeTechnology($technologyData){
        upgradeTechnologyDB($technologyData['idOwner'],
          $technologyData['name'], $technologyData['level']);
    }

    public function checkTask(){
      if($this->$verify == "ok"){
        return true;
      }
      else{
        return false;
      }
    }

    public function setVerification($ver){
      $this->$verify = $ver;
    }

    public function executeTask(){
      $taskEffect = $this->getTaskEffect();
      if(isset($taskEffect['resources'])){
        $this->addResources($taskEffect['resources']);
      }
      if (isset($taskEffect['army'])) {
        $this->addArmy($taskEffect['army']);
      }
      if (isset($taskEffect['items'])) {
        $this->addItems($taskEffect['items']);
      }
      if(isset($taskEffect['build'])){
        $this->updateBuilding($taskEffect['build']);
      }
      if(isset($taskEffect['technology'])){
        $this->upgradeTechnology($taskEffect['technology']);
      }
      deleteTaskDB($this->getTaskId());
    }

  }

//  $fromDB['task_id']=0;
//  $fromDB['task_effect'] = '{"items":{"destination":{"buildingOwner":"102"},"transfer":{"Sword":"1"}}}';
//  $task = new Task($fromDB);
//  $task->executeTask();

?>

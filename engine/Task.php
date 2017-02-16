<<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";

  class Task{

    private $task;

    public function __construct($taskFromDB){
      $this->task = $taskFromDB;
    }

    private function getTaskEffect(){
      return $task['task_effect'];
    }

    private function getTaskId(){
      return $task['task_id'];
    }

    private function addResources($resourcesData){
      $playerId;
      if(isset($resourcesData['destination'])){
        if(isset($resourcesData['destination']['buildingOwner'])){
          $playerId = getOwnerByBuildingIdDB($resourcesData['destination']['buildingOwner']);;
        }
        elseif (isset($resourcesData['destination']['owner']) {
          $playerId = $resourcesData['destination']['owner'];
        }
        if(isset($resourcesData['transfer']){
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
        elseif (isset($armyData['destination']['owner']) {
          $playerId = $armyData['destination']['owner'];
        }
        if(isset($armyData['transfer']){
          transferPlayersArmyDB($playerId,$armyData['transfer']);
        }
      }
    }

    private function addItems($itemsData){
      $playerId;
      if(isset($itemsData['destination'])){
        if(isset($itemsData['destination']['buildingOwner'])){
          $playerId = getOwnerByBuildingIdDB($itemsData['destination']['buildingOwner']);;
        }
        elseif (isset($itemsData['destination']['owner']) {
          $playerId = $itemsData['destination']['owner'];
        }
        if(isset($itemsData['transfer']){
          transferItemsDB($playerId,$itemsData['transfer']);
        }
      }
    }

    private function updateBuilding($buildingData){
      upDateBuildingDB($task['task_building'],$buildingData);
    }

    public function isTaskReady(){
      $timeNow = time();
      if($task['timeEnd'] < $timeNow){
        return true;
      }
      else{
        return false;
      }
    }

    public function executeTask(){
      $taskEffect = $this->getTaskEffect();
      if(isset($taskEffect['resources'])){
        $this->addResources($taskEffect['resources']);
      }
      elseif (isset($taskEffect['army']) {
        $this->addArmy($taskEffect['army']);
      }
      elseif (isset($taskEffect['items']) {
        $this->addItems($taskEffect['items']);
      }
      elseif(isset($taskEffect['build']){
        $this->updateBuilding($taskEffect['build']);
      }
      deleteTaskDB($this->getTaskId());
    }

  }




?>

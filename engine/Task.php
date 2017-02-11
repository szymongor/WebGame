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
      $taskEffect = $this->getTaskEffect();
      if(isset($taskEffect['add'])){
        if(isset($taskEffect['add']['resources'])){
          //TO DO
        }
      }
    }

    private function addArmy($armyData){
      $playerId;
      if(isset($armyData['destination'])){
        if(isset($armyData['destination']['buildingOwner'])){
          $playerId = getOwnerByBuildingIdDB($armyData['destination']['buildingOwner']);;
        }
      }
      if(isset($armyData['transfer']){
        transferPlayersArmyDB($playerId,$armyData['transfer']);
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
        //TO DO
      }
      elseif(isset($taskEffect['build']){
        $this->updateBuilding($taskEffect['build']);
      }
      deleteTaskDB($this->getTaskId());
    }

  }




?>

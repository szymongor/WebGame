<?php

  class TaskBuilder{

    private $task;
    private $execute;

    public function __construct(){
      $this->task = array();
      private $this->execute = array();
    }

    public function addResources($buildingId, $resourcesArray){
      $this->task['resources']['destination']['buildingOwner'] = $buildingId;
      $this->task['resources']['transfer'] = $resourcesArray;
    }

    public function addArmy($buildingId, $armyArray){
      $this->task['army']['destination']['buildingOwner'] = $buildingId;
      $this->task['army']['transfer'] = $armyArray;
    }

    public function addItems($buildingId, $itemsArray){
      $this->task['items']['destination']['buildingOwner'] = $buildingId;
      $this->task['items']['transfer'] = $itemsArray;
    }

    public function buildBuilding($buildingId, $buildingArray){
      $this->task['build']['buildingId'] = $buildingId;
      $this->task['build']['buildingData'] = $buildingArray;
    }

    public function addTechnology($idOwner, $technologyName, $level){
      $this->task['technology']['idOwner'] = $idOwner;
      $this->task['technology']['name'] = $technologyName;
      $this->task['technology']['level'] = $level;
    }

    public function getTask(){
      return $this->task;
    }

  };


  //$resourcesArray = array('Wood' =>  30);

  //$taskBuilder = new TaskBuilder();
  //$taskBuilder->addResources(32,$resourcesArray);

  //echo(json_encode($taskBuilder->getTask()));

?>

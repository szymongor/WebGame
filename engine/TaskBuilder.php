<?php

  class TaskBuilder{

    private $task;

    public function __construct(){
      $this->task = array();
    }

    public function addResources($buildingId, $resourcesTable){
      $this->task['resources']['destination']['buildingOwner'] = $buildingId;
      $this->task['resources']['transfer'] = $resourcesTable;
    }

    public function addArmy($buildingId, $armyTable){
      $this->task['army']['destination']['buildingOwner'] = $buildingId;
      $this->task['army']['transfer'] = $armyTable;
    }

    public function buildBuilding($buildingId, $buildingType){
      $this->task['build']['buildingId'] = $buildingId;
      $this->task['build']['buildingType'] = $armyTable;
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

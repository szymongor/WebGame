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

    public function getTask(){
      return $this->task;
    }

  };


  //$resourcesArray = array('Wood' =>  30);

  //$taskBuilder = new TaskBuilder();
  //$taskBuilder->addResources(32,$resourcesArray);

  //echo(json_encode($taskBuilder->getTask()));

?>

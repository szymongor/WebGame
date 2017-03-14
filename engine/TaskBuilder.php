<?php

  class TaskBuilder{

    private $task;
    private $execute;

    public function __construct(){
      $this->task = array();
      $this->executeBeforeTasks = array();
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
      if($level == 1){
        echo($level);
        $execBefore = array("Type"=>"InitTechnology","TechnologyName"=>$technologyName,"Owner"=>$idOwner);
        array_push($this->executeBeforeTasks, $execBefore);
      }
      else{
        $execBefore = array("Type"=>"TechnologyUpgraded","TechnologyName"=>$technologyName,"Owner"=>$idOwner);
        array_push($this->executeBeforeTasks, $execBefore);
      }
    }

    public function getTask(){
      return $this->task;
    }


    public function execute(){
      foreach ($this->executeBeforeTasks as $value) {
        switch($value['Type']){
          case "InitTechnology":
            addTechnologyDB($value['Owner'],$value['TechnologyName']);
            break;
          case "TechnologyUpgraded":
            technologyUpgradedDB($value['Owner'],$value['TechnologyName']);
            break;
        }
      }
    }

  };


  //$resourcesArray = array('Wood' =>  30);

  //$taskBuilder = new TaskBuilder();
  //$taskBuilder->addResources(32,$resourcesArray);

  //echo(json_encode($taskBuilder->getTask()));

?>

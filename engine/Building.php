<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/TaskBuilder.php";

  class Building
  {
    private $buildingDataDB;
    private $buildingId;
    private $buildingType;
    private $buildingLevel;
    /*
    public function __construct($buildingType,$buildingLevel){
      $this->buildingType=$buildingType;
      $this->buildingLevel=$buildingLevel;
    }
    */

    public function __construct($buildingId){
      $buildingData = getBuildingByIDFromDB($buildingId);
      if($buildingData != NULL){
        $this->buildingDataDB = $buildingData;
        $this->buildingType = $buildingData['type'];
        if(isset($buildingData['level'])){
          $this->buildingLevel = $buildingData['level'];
        }

        $this->buildingId = $buildingData['building_id'];
      }
    }

    public static function getBuildingListToBuild(){
      $files = glob($_SERVER['DOCUMENT_ROOT'].'/Reg/engine/Buildings/*.{json}', GLOB_BRACE);
      $buildingsList = array();
      foreach($files as $file) {
        $buildingFile = fopen($file, "r");
        $buildingInfo = json_decode(fread($buildingFile,filesize($file)),true);
        fclose($buildingFile);
        $infoToBuild = array();
        $infoToBuild['Type']=$buildingInfo['Type'];
        $infoToBuild['Cost']=$buildingInfo['Cost'];
        array_push($buildingsList,$infoToBuild);
      }
      $response = json_encode($buildingsList);
      return $response;
    }

    public static function getBuildingInfo($buildingType){
      $filePath = $_SERVER['DOCUMENT_ROOT'].'/Reg/engine/Buildings/'.$buildingType.'.json';
      $file = fopen($filePath, "r");
      $buildingInfo = json_decode(fread($file,filesize($filePath)),true);
      fclose($file);
      return $buildingInfo;
    }

    public function getBuildingCapacity(){
      $buildingInfo = Building::getBuildingInfo($this->buildingType);
      $buildingCapacity = Rules::getRules("Resources")["BaseResourcesCapacity"];
      foreach ($buildingCapacity as $key => $value) {
        $buildingCapacity[$key] = 0;
      }
      if(isset($buildingInfo["ResourcesCapacity"])){
        foreach ($buildingInfo["ResourcesCapacity"] as $key => $value) {
          $buildingCapacity[$key] += $value;
        }
      }
      return $buildingCapacity;
    }

    public function getBuildingFunctions(){
      $buildingInfo = Building::getBuildingInfo($this->buildingType);
      $buildingFunctions = array();
      if(isset($buildingInfo['Functions'])){
        $buildingFunctions = $buildingInfo['Functions'];
      }
      return $buildingFunctions;
    }

    public function getTaskType($functionName){
      $buildingFunctions = $this->getBuildingFunctions()[0];
      foreach ($buildingFunctions as $key => $functions) {
        foreach ($functions as $key => $buildingFunction) {
          if( isset($buildingFunction['TaskType'])  && $buildingFunction['Name'] == $functionName){
            $taskType = $buildingFunction['TaskType'];
            return $taskType;
          }
        }
      }
      return "Not specified task type";

    }

    public function calculateTaskCost($taskName,$amount){
      $buildingFunctions = $this->getBuildingFunctions()[0];
      $function;
      foreach ($buildingFunctions as $key => $functions) {
        foreach ($functions as $key => $buildingFunction) {
          if($buildingFunction['Name'] == $taskName){
            $function = $buildingFunction;
            break;
          }
        }
      }
      if(!isset($function)){
        return "No such function!";
      }

      $taskCost= $function["Cost"];
      foreach ($taskCost as $key => $value) {
        $taskCost[$key] = $amount*$value;
      }

      $time = $function['Time'] * $amount;

      $costs['Resources'] = $taskCost;
      $costs['Time'] = $time;
      return $costs;
    }

    public function makeTask($function, $amount, $playerId){
      $taskType = $this->getTaskType($function);
      $task = new TaskBuilder();
      switch($taskType){
        case "addArmy":
          $army = array($function => $amount);
          $task->addArmy($this->buildingId,$army);
          break;
      }
      $taskCosts = $this->calculateTaskCost($function,$amount);
      $taskStr = json_encode($task->getTask());
      transferResourcesDB($playerId,$taskCosts['Resources']);
      addTaskDB($playerId,$this->buildingId,$taskStr,time()+$taskCosts['Time']);
      //echo(json_encode($this->calculateTaskCost($function,$amount)));
      return $task->getTask();
    }

    public function calculateIncome($x,$y,$userId){
      $buildingIncome = array();
      $buildingInfo = Building::getBuildingInfo($this->buildingType);
      $buildingRange = 1;
      if(isset($buildingInfo['Range'])){
        $buildingRange = $buildingInfo['Range'];
      }

      $surroundings = getMapRegionFromDB($userId,$x-$buildingRange,$x+$buildingRange,$y-$buildingRange,$y+$buildingRange);

      if(isset($buildingInfo['Income'])){
        foreach ($buildingInfo['Income'] as $income) {
          foreach ($income['Resources'] as $resource => $amount){
            if(isset($buildingIncome[$resource])){
              $buildingIncome[$resource] += $amount;
            }
            else{
              $buildingIncome[$resource] = $amount;
            }

          }
          if(isset($income['Source'])){
            $numberOfSources = 0;
            foreach ($income['Source'] as $sourceBiome) {
              foreach ($surroundings as $tile) {
                if($tile['biome']==$sourceBiome){
                  $numberOfSources++;
                }
              }
            }
            foreach ($buildingIncome as $key => $value) {
              $buildingIncome[$key] = $value * $numberOfSources;
            }
          }
        }
      }
      return $buildingIncome;
    }

    public function calculateCapacity(){

    }
  }

  //print_r(Building::calculateIncome(2,4,12));
?>

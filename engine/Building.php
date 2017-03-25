<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/TaskBuilder.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/utils.php";

  class Building
  {
    private $buildingDataDB;
    private $buildingId;
    private $buildingType;
    private $buildingLevel;
    private $buildingOwner;
    /*
    public function __construct($buildingType,$buildingLevel){
      $this->buildingType=$buildingType;
      $this->buildingLevel=$buildingLevel;
    }
    */

    public function __construct($buildingId){
      $this->buildingOwner = getOwnerByBuildingIdDB($buildingId);
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
      if(!file_exists($filePath)){
        return array();
      }
      else{
        $file = fopen($filePath, "r");
        $buildingInfo = json_decode(fread($file,filesize($filePath)),true);
        fclose($file);
        return $buildingInfo;
      }

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
        if(isset($buildingInfo['Functions'][0]['Technology'])){
          $buildingFunctions[0]['Technology'] = $this->getBuildingTechnologiesToDevelop($buildingInfo['Functions'][0]['Technology']);
        }
      }
      return $buildingFunctions;
    }

    private function getBuildingTechnologiesToDevelop($buildingTechnologiesData){
      $buildingTechnologies = array();
      $ownerTechnologies = getPlayersTechnologiesDB($this->buildingOwner);
      foreach ($buildingTechnologiesData as $key => $value) {
        $playersTechnology = findPlayerTechnology($ownerTechnologies,$value['Name']);
        if($playersTechnology){
        }
        else{
          $buildingTechnologies[] = $value;
        }
      }
      return $buildingTechnologies;
    }

    public function getBuildingFunction($taskName){
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
      else{
        return $function;
      }
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
      $buildingFunction = $this->getBuildingFunction($taskName);
      if($buildingFunction == "No such function!"){
        return "No such function!";
      }

      $taskCost= $buildingFunction["Cost"];
      foreach ($taskCost as $key => $value) {
        $taskCost[$key] = $amount*$value;
      }

      $time = $buildingFunction['Time'] * $amount;

      $costs['Resources'] = $taskCost;
      $costs['Time'] = $time;
      return $costs;
    }

    public function requiredTaskTechnology($taskName, $level){
      $buildingFunction = $this->getBuildingFunction($taskName);
      if($buildingFunction == "No such function!"){
        return "No such function!";
      }
      if(isset($buildingFunction['TaskType']) && $buildingFunction['TaskType'] == "addTechnology"){
        if(isset($buildingFunction["RequiredTechnologies"][$level])){
          return $buildingFunction["RequiredTechnologies"][$level];
        }
        else{
          return array("NotPossible"=>"1");
        }
      }

      if(isset($buildingFunction["RequiredTechnologies"])){
        return $buildingFunction["RequiredTechnologies"];
      }
      else{
        return array();
      }
    }

    public function makeTask($function, $amount, $playerId){
      $taskType = $this->getTaskType($function);
      $task = new TaskBuilder();
      switch($taskType){
        case "addArmy":
          $army = array($function => $amount);
          $task->addArmy($this->buildingId,$army);
          break;
        case "addItem":
          $items = array($function => $amount);
          $task->addItems($this->buildingId,$items);
          break;
        case "addTechnology":
          $task->addTechnology($playerId, $function, $amount);
          break;
      }
      $taskCosts = $this->calculateTaskCost($function,$amount);
      transferResourcesDB($playerId,$taskCosts['Resources']);
      transferItemsDB($playerId,$taskCosts['Resources']);

      addTaskDB($playerId,$this->buildingId,$task,$taskCosts['Time']);
      return "Success";
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

    public function buildingData(){
      $data['building_id'] = $this->buildingId;
      $data['type'] = $this->buildingType;
      $data['level'] = $this->buildingLevel;
      return $data;
    }
  }

  //print_r(Building::calculateIncome(2,4,12));
?>

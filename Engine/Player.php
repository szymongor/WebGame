<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Building.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Battle.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/utils.php";

  class Player
  {
    private $playerId;

    public function __construct($id){
      $this->playerId=$id;
    }

    public function getPlayerId(){
      return $this->playerId;
    }

    public function getPlayerResources(){
      return getUserResourcesDB($this->playerId);
    }

    public function checkPlayerResourcesState($requiredResources){
      $playerResources = $this->getPlayerResources();
      foreach ($requiredResources as $key => $value) {
        if(0>$playerResources[$key]+$value){
          return false;
        }
      }
      return true;
    }

    public function checkPlayersArmyState($requiredArmy){
      $playersArmy = $this->getPlayersArmy();
      foreach ($requiredArmy as $key => $value) {
        $requiredArmy[$key] = -$value;
      }
      return chceckSufficientAmount($playersArmy, $requiredArmy);
    }

    public function checkPlayersTechnologies($requiredTechnologies){
      $playersTechnologies = $this->getPlayersTechnologies();
      foreach ($requiredTechnologies as $key => $value) {
        if(!checkTechnology($playersTechnologies,$key,$value)){
          return false;
        }
      }
      return true;
    }

    public function getPlayerResourcesIncome(){
      return getUserResourcesIncomeDB($this->playerId);
    }

    public function getPlayersItems(){
      return getUserItemsDB($this->playerId);
    }

    public function calculatePlayerResourcesIncome(){
      $playersBuildings = $this->getPlayersBuildings();
      $playersIncome = array();
      foreach ($playersBuildings as $value) {
        //$playersBuilding = new Building($value['type'],$value['level']);
        $playersBuilding = new Building($value['building_id']);
        $buildingIncome = $playersBuilding->calculateIncome($value['x_coord'],$value['y_coord'],$this->playerId);
        foreach ($buildingIncome as $key => $value) {
          if(isset($playersIncome[$key])){
            $playersIncome[$key] += $value;
          }
          else{
            $playersIncome[$key] = $value;
          }
        }
      }


      return $playersIncome;
    }

    public function updatePlayerResourcesIncome(){
      $playersIncome = $this->calculatePlayerResourcesIncome();
      setPlayersIncomeDB($this->playerId,$playersIncome);
    }

    public function getPlayerResourcesCapacity(){
      return getUserResourcesCapacityDB($this->playerId);
    }

    public function updatePlayerResourcesCapacity(){
      $playersResourcesCapacity = $this->calculatePlayerResourcesCapacity();
      setPlayersResourcesCapacityDB($this->playerId,$playersResourcesCapacity);
    }

    public function updateStats(){
      $this->updatePlayerResourcesIncome();
      $this->updatePlayerResourcesCapacity();
    }

    public function calculatePlayerResourcesCapacity(){
      $playersBuildings = $this->getPlayersBuildings();
      $playersResourcesCapacity = Rules::getRules("Resources")['BaseResourcesCapacity'];
      foreach ($playersBuildings as $value) {
        $playersBuilding = new Building($value['building_id']);
        $buildingIncome = $playersBuilding->getBuildingCapacity();
        foreach ($buildingIncome as $key => $value) {
          $playersResourcesCapacity[$key] += $value;
        }
      }
      return $playersResourcesCapacity;
    }

    public function getMapTile($x,$y){
      $map = getMapRegionFromDB($this->playerId, $x - 1 , $x + 1 , $y - 1, $y + 1);
      if(count($map) > 0 ){
        $tile = getTileMapFromDB($x,$y);

        if($tile['building_id'] != NULL){
          $tile['building'] = getBuildingByIDFromDB($tile['building_id']);
        }
        else{
          $tile['building'] = NULL;
        }
        unset($tile['building_id']);

        if($tile['army_id'] != NULL){
          $tile['army'] = getArmyFromDB($tile['army_id']);
        }
        else{
          $tile['army'] = NULL;
        }
        unset($tile['army_id']);

        return $tile;
      }
      else{
        $fogTile = array('x_coord' => $x, 'y_coord' => $y, 'id_owner' => NULL, 'biome' => 'Fog', 'building_id' => NULL);
        return $fogTile;
      }

    }

    public function isTileOwned($x,$y){
      $tile = $this->getMapTile($x,$y);
      if($tile['id_owner'] == $this->playerId){
        return true;
      }
      else{
        return false;
      }
    }

    public function getMapRegion($xFrom, $xTo, $yFrom, $yTo){
      $mapView = array();
      $ownedTiles = getMapRegionFromDB($this->playerId, $xFrom, $xTo, $yFrom, $yTo);
      $initArmy = getArmyTypesFromDB();
      for($i = $xFrom; $i <= $xTo ; $i++){
        $row = array();
        for($j = $yFrom; $j <= $yTo ; $j++){
          $fogTile = array('x_coord' => $i, 'y_coord' => $j, 'id_owner' => NULL, 'biome' => 'Fog', 'building_id' => NULL, 'army' => $initArmy);
          array_push($row,$fogTile);
        }
        array_push($mapView,$row);
      }

      foreach ($ownedTiles as $value) {
        $mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom] = $value;

      }



      foreach ($ownedTiles as $value) {
        if($value['x_coord']-1>=$xFrom && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']);
          unset($mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-$yFrom]['army_id']);
        }


        if($value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord'],$value['y_coord']-1);
          unset($mapView[$value['x_coord']-$xFrom][$value['y_coord']-1-$yFrom]['army_id']);
        }


        if($value['x_coord']+1<=$xTo && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']);
          unset($mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-$yFrom]['army_id']);
        }


        if($value['y_coord']+1<=$yTo && $mapView[$value['x_coord']-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord'],$value['y_coord']+1);
          unset($mapView[$value['x_coord']-$xFrom][$value['y_coord']+1-$yFrom]['army_id']);
        }


        if($value['x_coord']-1>=$xFrom && $value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']-1);
          unset($mapView[$value['x_coord']-1-$xFrom][$value['y_coord']-1-$yFrom]['army_id']);
        }


        if($value['x_coord']-1>=$xFrom && $value['y_coord']+1<=$yTo && $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']-1-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord']-1,$value['y_coord']+1);
          unset($mapView[$value['x_coord']-1-$xFrom][$value['y_coord']+1-$yFrom]['army_id']);
        }


        if($value['x_coord']+1<=$xTo && $value['y_coord']-1>=$yFrom && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-1-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']-1);
          unset($mapView[$value['x_coord']+1-$xFrom][$value['y_coord']-1-$yFrom]['army_id']);
        }


        if($value['x_coord']+1<=$xTo && $value['y_coord']+1<=$yTo && $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']+1-$yFrom]['biome'] == 'Fog'){
          $mapView[$value['x_coord']+1-$xFrom][$value['y_coord']+1-$yFrom] = getTileMapFromDB($value['x_coord']+1,$value['y_coord']+1);
          unset($mapView[$value['x_coord']+1-$xFrom][$value['y_coord']+1-$yFrom]['army_id']);
        }



      }

      for($i = 0 ; $i < $xTo-$xFrom ; $i++){
        for($j = 0 ; $j < $yTo-$yFrom ; $j++){
          if($mapView[$i][$j]['building_id'] != NULL){
            $mapView[$i][$j]['building'] = getBuildingByIDFromDB($mapView[$i][$j]['building_id']);
          }
          else{
            $mapView[$i][$j]['building'] = NULL;
          }
          unset($mapView[$i][$j]['building_id']);
        }
      }

      foreach ($ownedTiles as $value){
        if($value['army_id'] != NULL){
          $mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom]['army'] = getArmyFromDB($value['army_id']);
        }
        else{
          $mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom]['army'] = $initArmy;
        }
        unset($mapView[$value['x_coord']-$xFrom][$value['y_coord']-$yFrom]['army_id']);

      }

      //$response = json_encode($mapView);
      return $mapView;
    }

    public function attackTile($x, $y, $armyData){
      if(!$this->checkPlayersArmyState($armyData)){
        return "You dont have such army";
      }
      else{
        $combat = new Battle($armyData, $x,$y);
        $combat->performBattle();
        //$combat->getBattleLog()." ".
        return $combat->getBattleResult();
      }
    }

    public function conquer($x, $y, $armyData){
      $tileLocation = "";
      if($this->isTileOwned($x,$y)){
        $tileLocation = "Owned tile.";
      }
      else{
        $map = getMapRegionFromDB($this->playerId, $x - 1 , $x + 1 , $y - 1, $y + 1);
        if(count($map) > 0){
          $tileLocation = "Connected";
        }
        else{
          $tileLocation = "Not connected tile.";
        }
      }

      if($tileLocation != "Connected"){
        $ret = $tileLocation." Pick another tile.";
      }
      else{
        $ret = $this->attackTile($x, $y, $armyData);
        if($ret == "Win"){
          changeTileOwnerDB($this->playerId,$x,$y);
        }
      }
       return $ret;
    }

    public function getBuilding($x, $y){
      $tile = $this->getMapTile($x,$y);
      if($tile!=NULL || $tile["building"]!=NULL){
        return getBuildingFromDB($x, $y);
      }
      return "null";
    }

    public function getBuildingFromTile($x,$y){
      if( !$this->isTileOwned($x,$y)){
        //return "Not owned!";
        return NULL;
      }
      else{
        $buildingDB = $this->getBuilding($x,$y);
        if($buildingDB == null){
          //return "No building here!";
          return NULL;
        }
        else{
          $building = new Building($buildingDB["building_id"]);
          return $building;
        }
      }
    }

    public function addBuildingTask($x,$y,$taskName,$amount){
      $building = $this->getBuildingFromTile($x,$y);
      if($building == NULL){
        return "No building here!";
      }
      $taskCost = $building->calculateTaskCost($taskName,$amount);
      if($taskCost == "No such function!"){
        return "No such function!";
      }

      $requiredTechnologies = $building->requiredTaskTechnology($taskName);

      $checkResources = $this->checkPlayerResourcesState($taskCost['Resources']);
      $checkTechnology = $this->checkPlayersTechnologies($requiredTechnologies);

      //Refactor /\ all checks here \/
      $checkRequirements = $this->checkTasksRequirements($x,$y,$taskName,$amount);


      if($checkResources){
        if($checkTechnology){
          echo json_encode($building->makeTask($taskName,$amount,$this->playerId));
        }
        else{
            echo "Missing required technology";
        }

      }
      else{
        echo "NotSuffice";
      }
      //return $taskCost;
    }

    private function checkTasksRequirements($x,$y,$taskName,$amount){
      $building = $this->getBuildingFromTile($x,$y);
      if($building == NULL){
        return "No building here!";
      }

      $taskType = $building->getTaskType($taskName);
      switch($taskType){
        case "Technology":
          //TO DO
          break;
      }


      return "Ok";



    }

    public function buildBuilding($x,$y,$buildingType){
      $tile = $this->getMapTile($x,$y);
      $result = true;
      $buildingInfo = Building::getBuildingInfo($buildingType);

      if($tile['id_owner'] != $this->playerId ){
        return "Tile not owned!";
      }
      if($tile['building'] != NULL){
        return "Tile already occupied!";
      }
      if(!$this->checkPlayerResourcesState($buildingInfo['Cost'])){
        return "Not enough resources!";
      }

      $cost = $buildingInfo['Cost'];
      transferResourcesDB($this->playerId, $cost);
      setTileBuilding($x,$y,$buildingType." Construction");
      //$this->updatePlayerResourcesIncome();
      //$this->updatePlayerResourcesCapacity();
      $buildingId = getBuildingFromDB($x,$y)['building_id'];
      $buildingArray = array('type' => $buildingType);

      $taskBuilder = new taskBuilder();
      $taskBuilder->buildBuilding($buildingId, $buildingArray);
      $taskStr = $taskBuilder->getTask();
      addTaskDB($this->playerId,$buildingId,$taskStr,$buildingInfo['BuildingTime']);

      return $this->getMapTile($x,$y);
    }

    public function getPlayersBuildings(){
      $userBuildings = getPlayersBuildingsFromDB($this->playerId);
      return $userBuildings;
    }

    public function getPlayersArmy(){
      return getPlayersArmyByIdDB($this->playerId);
    }

    public function getBuildingFunctions($x,$y){
      $building = $this->getBuildingFromTile($x,$y);
      $response;
      if($building != NULL){
        $response = $building->getBuildingFunctions();
      }
      else{
        $response = "No building here";
      }
      return $response;
    }

    public function getBuildingsTasks($x,$y){
      $response;
      if($this->isTileOwned($x,$y)){
        $response = getBuildingsTasksDB($x,$y);
      }
      else{
        $response = "You are not the owner";
      }
      return $response;
    }

    public function getPlayersTechnologies(){
      return getPlayersTechnologiesDB($this->playerId);
    }

  }


  //print_r($_SESSION['Player']->updatePlayerResourcesIncome());

  //$_SESSION['Player']->updatePlayerResourcesCapacity();


  //  session_start();
  //  $res = [
	//  	"Swordman" => 10,
	//  	"Shieldbearer" => 0,
  //    "Bowman" => 9,
  //  ];
   //
  //  echo json_encode($_SESSION['Player']->attackTile($res,6,1));

?>

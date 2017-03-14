<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/connect.php"; //refactor path?
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/utils.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Rules.php";

	function getUser($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT `user` FROM `users` WHERE id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function getUserResourcesDB($userId){
		upDateResources($userId);
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr= sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources` WHERE user_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		if($row == NULL){
			initUserResources($userId);
			$result = @$db_connect->query($queryStr);
			$row = $result->fetch_assoc();
		}
			mysqli_close($db_connect);
			return $row;
	}

	function getUserResourcesIncomeDB($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr= sprintf("SELECT * FROM `user_resources_income` WHERE user_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		if($row == NULL){
			initUserResources($userId);
			$result = @$db_connect->query($queryStr);
			$row = $result->fetch_assoc();
		}
			mysqli_close($db_connect);
			return $row;
	}

	function getUserResourcesCapacityDB($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr= sprintf("SELECT * FROM `user_resources_capacity` WHERE user_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);

		if($row == NULL){
			return initUserResourcesCapacityDB($userId);
		}else{
			return $row;
		}
	}

	function initUserResourcesCapacityDB($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr= sprintf("INSERT INTO `user_resources_capacity`(`user_id`) VALUES (%s)", $userId);
		@$db_connect->query($queryStr);

		$rules = Rules::getRules("Resources");
		foreach ($rules["BaseResourcesCapacity"] as $key => $value) {
			$queryStr= sprintf("UPDATE `user_resources_capacity` SET `%s`=%s WHERE User_id = %s", $key ,$value, $userId);
			@$db_connect->query($queryStr);

		}

		$queryStr= sprintf("SELECT * FROM `user_resources_capacity` WHERE user_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function getUserItemsDB($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr= sprintf("SELECT * FROM `user_items` WHERE user_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		if($row == NULL){
			return initUserItemsDB($userId);
		}else{
			return $row;
		}
	}

	function initUserResources($userId){
		upDateResources($userId);
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);

		$queryStr= sprintf("INSERT INTO `user_resources`(`user_id`) VALUES (%s)",$userId);
		@$db_connect->query($queryStr);

		$queryStr= sprintf("INSERT INTO `user_resources_income`(`user_id`) VALUES (%s)",$userId);
		@$db_connect->query($queryStr);

		$queryStr= sprintf("INSERT INTO `user_resources_update`(`user_id`, `last_update`) VALUES (%s,%s)",$userId,time());
		@$db_connect->query($queryStr);

		mysqli_close($db_connect);
	}

	function initUserItemsDB($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr= sprintf("INSERT INTO `user_items`(`user_id`) VALUES (%s)", $userId);
		@$db_connect->query($queryStr);
		$queryStr= sprintf("SELECT * FROM `user_items` WHERE user_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function addItemDB($userId, $itemName, $amount){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("UPDATE `user_items` SET `%s`= %s + %s WHERE user_id=%s",
		$itemName,$itemName,$amount,$userId);
		@$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function transferItemsDB($userId, $itemsArray){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$currentItems = getUserItemsDB($userId);
		$sufficeAmount = chceckSufficientAmount($currentItems,$itemsArray);
		$response;
		if($sufficeAmount)
		{
			foreach ($itemsArray as $transferedItems => $amount)
			{
				addItemDB($userId, $transferedItems, $amount);
			}
			$response = true;
		}
		else{
			$response = false;
		}
		mysqli_close($db_connect);
		return $response;
	}

	function upDateResources($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$result = @$db_connect->query(sprintf("SELECT last_update FROM `user_resources_update` WHERE user_id = %s",$userId));
		$row = $result->fetch_assoc();
		$timeLastUpdate = $row['last_update'];
		$timeNow = time();
		$resourcesCapacity = getUserResourcesCapacityDB($userId);
		if($timeLastUpdate = NULL)
		{
			//initialize user_resource_update with timestamp = now
			@$db_connect->query(sprintf("UPDATE `user_resources_update` SET `last_update`= %s WHERE user_id=%s",$timeNow,$userId));
		}
		else
		{
			$timeLastUpdate = $row['last_update'];
			$timeNow = time();
			$timeSpan = $timeNow-$timeLastUpdate;
			if($timeSpan>=60)
			{
				//get user resources
				$resourcesResult = @$db_connect->query(sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources` WHERE user_id = %s",$userId));
				//$resourcesResult = getUserResourcesDB($userId);
				//$incomeResult = @$db_connect->query(sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources_income` WHERE user_id = %s",$userId));
				//$incomeResult = getUserResourcesIncomeDB($userId);
				$resourcesRow = $resourcesResult->fetch_assoc();
				$incomeRow = getUserResourcesIncomeDB($userId);

				$n = floor($timeSpan/60);
				$timePass = $timeLastUpdate+$n*60;
				$resourcesAfterUpdate = array('Wood' => 0,'Stone' => 0, 'Iron' => 0, 'Food' => 0,  );

				foreach ($resourcesAfterUpdate as $key => $value) {
					$resourcesAfterUpdate[$key] = $resourcesRow[$key] +$n*$incomeRow[$key];
					if($resourcesAfterUpdate[$key] > $resourcesCapacity[$key]){
						$resourcesAfterUpdate[$key] = $resourcesCapacity[$key];
					}
				}

				@$db_connect->query(sprintf("UPDATE `user_resources_update` SET `last_update`= %s WHERE user_id=%s",$timePass,$userId));
				@$db_connect->query(sprintf("UPDATE `user_resources` SET`Wood`=%s,`Stone`=%s,`Iron`=%s,`Food`=%s WHERE user_id=%s",
				$resourcesAfterUpdate['Wood'],$resourcesAfterUpdate['Stone'],$resourcesAfterUpdate['Iron'],$resourcesAfterUpdate['Food'],$userId));
			}
		}
		mysqli_close($db_connect);
	}

	function setPlayersIncomeDB($userId, $resourcesIncome){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);

		foreach ($resourcesIncome as $resourceName => $value) {
			$queryStr = sprintf("UPDATE `user_resources_income` SET `%s`= %s WHERE user_id = %s",
			$resourceName,$value,$userId);
			@$db_connect->query($queryStr);
		}
		mysqli_close($db_connect);
	}

	function setPlayersResourcesCapacityDB($userId,$resourcesCapacity){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);

		foreach ($resourcesCapacity as $resourceName => $value) {
			$queryStr = sprintf("UPDATE `user_resources_capacity` SET `%s`= %s WHERE user_id = %s",
			$resourceName,$value,$userId);
			@$db_connect->query($queryStr);
		}
		mysqli_close($db_connect);
	}

	function transferResourcesDB($userId, $resourcesArray){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		upDateResources($userId);
		$currentResources = getUserResourcesDB($userId);
		$sufficeAmount = chceckSufficientAmount($currentResources,$resourcesArray);
		if($sufficeAmount)
		{

			foreach ($resourcesArray as $transferedResource => $amount)
			{
				addResource($userId, $transferedResource, $amount);
			}
		}
		mysqli_close($db_connect);

	}

	function addResource($userId,$resourceName,$resouceAmount){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("UPDATE `user_resources` SET `%s`= %s + %s WHERE user_id=%s",
		$resourceName,$resourceName,$resouceAmount,$userId);
		@$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function upDateBuildingDB($buildingId, $buildingData){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		foreach ($buildingData as $key => $value) {
			$queryStr = sprintf("UPDATE `buildings` SET `%s`= \"%s\" WHERE building_id = %s",$key,$value,$buildingId);
			$db_connect->query($queryStr);
		}
		mysqli_close($db_connect);
	}

	function getBuildingFromDB($xCoord,$yCoord){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);

		$queryStr = sprintf("SELECT * FROM `buildings`
		WHERE building_id = (SELECT `building_id` FROM `map` WHERE x_coord= %s AND y_coord = %s)",
		$xCoord,$yCoord);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function getBuildingByIDFromDB($idBuilding){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT `building_id`, `type` FROM `buildings`
		WHERE building_id = %s",
		$idBuilding);

		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function getOwnerByBuildingIdDB($idBuilding){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT `id_owner` FROM `map` WHERE building_id = %s",$idBuilding);
		$result = @$db_connect->query($queryStr);
		if($result){
			$row = $result->fetch_assoc()['id_owner'];
		}
		else{
			$row = NULL;
		}
		mysqli_close($db_connect);
		return $row;
	}

	function changeTileOwnerDB($userId,$xCoord,$yCoord){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("UPDATE `map` SET `id_owner`=%s WHERE x_coord = %s AND y_coord = %s",$userId,$xCoord,$yCoord);
		@$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function getTileMapFromDB($x,$y){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$query = sprintf("SELECT * FROM `map` WHERE x_coord = %s AND y_coord = %s",
    $x,$y);
    $result = @$db_connect->query($query);

    $row = $result->fetch_assoc();
    if($row == NULL)
    {
			require_once "../databaseNames.php";
			$biome = array_rand($Biomes);
			$query = sprintf("INSERT INTO `map`(`x_coord`, `y_coord`, `biome`) VALUES (%s,%s,'%s')",
      $x,$y,$biome);
			@$db_connect->query($query);
    }
    else
    {
			//$jsonResponse = json_encode($row);
			mysqli_close($db_connect);
      return $row;
    }
		$query = sprintf("SELECT `x_coord`,`y_coord`,`id_owner`, `biome`, `building_id` FROM `map` WHERE x_coord = %s AND y_coord = %s",
    $x,$y);
    $result = @$db_connect->query($query);
    $row = $result->fetch_assoc();

		//$jsonResponse = json_encode($row);
		mysqli_close($db_connect);
		return $row;
  }

	function getArmyTypesFromDB(){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `army` LIMIT 1");

		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		unset($row['id']);

		foreach ($row as $key => $value) {
			$row[$key] = 0;
		}


		mysqli_close($db_connect);
		return $row;
	}

	function getArmyFromDB($armyId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `army`
		WHERE id = %s",
		$armyId);

		$result = @$db_connect->query($queryStr);
		if($result == null){
			$row = getEmptyArmy();
		}
		else{
			$row = $result->fetch_assoc();
			unset($row['id']);
		}



		mysqli_close($db_connect);
		return $row;

	}

	function getArmyIdByLocationFromDB($x,$y){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT `army_id` FROM `map` WHERE x_coord = %s AND y_coord = %s",
		$x,$y);

		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row["army_id"];
	}

	function getArmyByLocationDB($x,$y){
		return getArmyFromDB(getArmyIdByLocationFromDB($x,$y));
	}

	function getPlayersArmyId($playerId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `user_army` WHERE user_id = %s",
		$playerId);

		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		$armyId;
		if($row == NULL){
			$armyId = initArmy();
			$queryStr = sprintf("INSERT INTO `user_army`(`user_id`, `army_id`) VALUES (%s,%s)",
			$playerId,$armyId);
			@$db_connect->query($queryStr);
		}
		else{
			$armyId = $row["army_id"];
		}

		return $armyId;
	}

	function getPlayersArmyByIdDB($playerId){
		$armyId = getPlayersArmyId($playerId);
		return getArmyFromDB($armyId);
	}

	function initArmy(){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);

		$queryStr = sprintf("INSERT INTO `army`() VALUES ();");
		$queryStr .= sprintf("SELECT LAST_INSERT_ID();");

		$armyId;
		if (mysqli_multi_query($db_connect,$queryStr))
		{
			do
				{
				if ($result=mysqli_store_result($db_connect)) {
					while ($row=mysqli_fetch_row($result))
					{
						$armyId=$row[0];
					}
					// Free result set
					mysqli_free_result($result);
					}
				}
			while (mysqli_next_result($db_connect));
		}
		return $armyId;
	}

	function initTileArmy($x,$y){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$armyId = initArmy();
		if($armyId != NULL){
			$queryStr = sprintf("UPDATE `map` SET `army_id`=%s WHERE x_coord = %s AND y_coord = %s",$armyId,$x,$y);
			@$db_connect->query($queryStr);
		}
		return $armyId;
	}

	function addArmyUnitsDB($armyId, $unitType, $amount){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("UPDATE `army` SET `%s`= %s + %s WHERE id=%s",
		$unitType,$unitType,$amount,$armyId);
		@$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function addArmyToTileDB($x,$y,$armyAmount){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$army = getArmyIdByLocationFromDB($x,$y);
		$armyId;
		if($army == NULL){
			$armyId = initTileArmy($x,$y);
		}
		else{
			$armyId = $army;
		}

		foreach ($armyAmount as $unitType => $amount) {
			addArmyUnitsDB($armyId,$unitType,$amount);
		}

		mysqli_close($db_connect);
	}

	function transferPlayersArmyDB($playerId,$armyAmount){
		$playersArmy = getPlayersArmyByIdDB($playerId);
		$sufficeAmount = chceckSufficientAmount($playersArmy, $armyAmount);
		if($sufficeAmount){
			$armyId = getPlayersArmyId($playerId);
			foreach ($armyAmount as $type => $amount) {
				addArmyUnitsDB($armyId,$type,$amount);
			}
		}
	}

	function getMapRegionFromDB($userId,$xFrom,$xTo,$yFrom,$yTo){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT *  FROM `map` WHERE x_coord >=%s AND x_coord <= %s AND y_coord >=%s AND y_coord <=%s AND id_owner = %s ",$xFrom,$xTo,$yFrom,$yTo,$userId);
		$result = @$db_connect->query($queryStr);
		$mapArray = array();
		while($mapRow = $result->fetch_assoc()){
			array_push($mapArray,$mapRow);
		}
		mysqli_close($db_connect);
		return $mapArray;

	}

	function setTileBuilding($x,$y,$buildingType){
		$success;
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$tile = getTileMapFromDB($x,$y);
		if($tile["building_id"]==NULL)
		{
			$queryStr = sprintf("INSERT INTO `buildings`(`type`) VALUES ('%s'); ",$buildingType);
			$queryStr .= sprintf("SELECT LAST_INSERT_ID();");

			$buildingId;
			if (mysqli_multi_query($db_connect,$queryStr))
			{
			  do
			    {
			    if ($result=mysqli_store_result($db_connect)) {
			      while ($row=mysqli_fetch_row($result))
			      {
			        $buildingId=$row[0];
			      }
			      // Free result set
			      mysqli_free_result($result);
			      }
			    }
			  while (mysqli_next_result($db_connect));
			}

			$queryStr = sprintf("UPDATE `map` SET `building_id`=%s WHERE x_coord = %s AND y_coord = %s",$buildingId,$x,$y);
			@$db_connect->query($queryStr);
			$success = true;
		}
		else
		{
			$success = false;
		}


		mysqli_close($db_connect);
		return $success;
	}

	function getPlayersBuildingsFromDB($userId){
		global $host, $db_user, $db_password, $db_name;
 		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);

 		$queryStr = sprintf("SELECT B.`building_id`, B.`type`, B.`level`, M.`x_coord`, M.`y_coord` FROM `buildings` as B JOIN `map` AS M
			WHERE B.building_id = M.building_id AND M.id_owner = %s",
 		$userId);
 		$result = @$db_connect->query($queryStr);
		$userBuildings = array();
		if($result){
			while($row = $result->fetch_assoc()){
				$userBuildings[]= $row;
			}
		}

 		mysqli_close($db_connect);
 		return $userBuildings;
	}

	function getPlayersTasksDB($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `tasks` WHERE owner_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$tasksArray = array();
		while($task = $result->fetch_assoc()){
			array_push($tasksArray,$task);
		}
		mysqli_close($db_connect);
		return $tasksArray;
	}

	function addTaskDB($ownerId,$buildingId,$taskBuilder,$time){
		global $host, $db_user, $db_password, $db_name;
		$taskEffect = $taskBuilder->getTask();
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$effect = str_replace("\"","\\\"",json_encode($taskEffect));
		$queryStr = sprintf("INSERT INTO `tasks`(`owner_id`, `task_building`, `task_effect`, `timeEnd`) VALUES (%s,%s,\"%s\",%s)",
		 $ownerId,$buildingId,$effect,time()+$time);
		$db_connect->query($queryStr);
		//refactor if query succeed
		$taskBuilder->execute();
		mysqli_close($db_connect);
	}

	function deleteTaskDB($taskId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("DELETE FROM `tasks` WHERE task_id = %s", $taskId);
		$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function popAllReadyTasksDB(){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `tasks` WHERE timeEnd < %s",time());
		$result = @$db_connect->query($queryStr);
		$tasksArray = array();
		while($task = $result->fetch_assoc()){
			deleteTaskDB($task['task_id']);
			array_push($tasksArray,$task);
		}
		mysqli_close($db_connect);
		return $tasksArray;
	}

	function getBuildingsTasksDB($x,$y){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `tasks` WHERE task_building
			is not null AND task_building = (SELECT `building_id` FROM `map` WHERE x_coord = %s AND y_coord = %s)",$x,$y);
		$result = @$db_connect->query($queryStr);
		$tasksArray = array();
		if($result){
			while($task = $result->fetch_assoc()){
				array_push($tasksArray,$task);
			}
		}
		mysqli_close($db_connect);
		return $tasksArray;
	}

	function getPlayersTechnologiesDB($playerId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `technologies` WHERE owner_id = %s",$playerId);
		$result = @$db_connect->query($queryStr);
		$technologiesArray = array();
		if($result){
			while($task = $result->fetch_assoc()){
				array_push($technologiesArray,$task);
			}
		}
		mysqli_close($db_connect);
		return $technologiesArray;
	}

	function addTechnologyDB($playerId, $technologyName){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("INSERT INTO `technologies`(`owner_id`, `technology`,`currently_upgraded`) VALUES (%s,\"%s\",1)",$playerId,$technologyName);
		$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function upgradeTechnologyDB($ownerId, $technologyName, $technologyLevel){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("UPDATE `technologies` SET `level`= %s,`currently_upgraded`=0 WHERE owner_id = %s AND technology = \"%s\"",
		$technologyLevel,$ownerId,$technologyName);
		$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function technologyUpgradedDB($ownerId, $technologyName){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("UPDATE `technologies` SET `currently_upgraded`=1 WHERE owner_id = %s AND technology = \"%s\"",
		$ownerId,$technologyName);
		$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}
	//$res = [
	//	"level" => 3,
	//	"type" => "\"Barack\""
	//];
	//upDateBuilding(68,$res);
	//echo(json_encode(getPlayersTechnologiesDB(12)));
	//echo json_encode(getAllUrgentTasksDB());
 	//deleteTask(1);
	//echo(json_encode(getBuildingsTasksDB(2,2)));
	//addItemDB(12,"Tools",4);

?>

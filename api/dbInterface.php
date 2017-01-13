<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/connect.php"; //refactor path?

	function getUser($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT `user` FROM `users` WHERE id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function getUserResources($userId){
		upDateResources($userId);
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr= sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources` WHERE user_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
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

	function upDateResources($userId){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$result = @$db_connect->query(sprintf("SELECT last_update FROM `user_resources_update` WHERE user_id = %s",$userId));
		$row = $result->fetch_assoc();
		$timeLastUpdate = $row['last_update'];
		$timeNow = time();


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

				$incomeResult = @$db_connect->query(sprintf("SELECT `Wood_income`, `Stone_income`, `Iron_income`, `Food_income` FROM `user_resources_income` WHERE user_id = %s",$userId));

				$resourcesRow = $resourcesResult->fetch_assoc();
				$incomeRow = $incomeResult->fetch_assoc();

				$n = floor($timeSpan/60);
				$timePass = $timeLastUpdate+$n*60;

				$Wood = $resourcesRow['Wood'] +$n*$incomeRow['Wood_income'];
				$Stone = $resourcesRow['Stone'] + $n*$incomeRow['Stone_income'];
				$Iron = $resourcesRow['Iron'] + $n*$incomeRow['Iron_income'];
				$Food = $resourcesRow['Food'] + $n*$incomeRow['Food_income'];
				@$db_connect->query(sprintf("UPDATE `user_resources_update` SET `last_update`= %s WHERE user_id=%s",$timePass,$userId));
				@$db_connect->query(sprintf("UPDATE `user_resources` SET`Wood`=%s,`Stone`=%s,`Iron`=%s,`Food`=%s WHERE user_id=%s",
				$Wood,$Stone,$Iron,$Food,$userId));
			}
		}
		mysqli_close($db_connect);
	}

	function setPlayersIncomeDB($userId, $income){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);

		foreach ($income as $resourceName => $value) {
			$queryStr = sprintf("UPDATE `user_resources_income` SET `%s_income`= %s WHERE user_id = %s",
			$resourceName,$value,$userId);
			@$db_connect->query($queryStr);
		}
		mysqli_close($db_connect);
	}

	# $resourcesArray example
	#$res = [
	#	"Wood" => -5000,
	#	"Iron" => +500
	#];

	function transferResources($userId, $resourcesArray){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		upDateResources($userId);
		$currentResources = getUserResources($userId);
		$sufficeAmount = true;
		#check amount of all needed resources
		foreach ($resourcesArray as $transferedResource => $amount)
		{
			if($currentResources[$transferedResource]+$amount < 0)
			{
				$sufficeAmount = false;
			}
		}
		#if enough then add/substract

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
		#UPDATE `user_resources` SET`Wood`=Wood + 2000 WHERE user_id=12
		$queryStr = sprintf("UPDATE `user_resources` SET `%s`= %s + %s WHERE user_id=%s",
		$resourceName,$resourceName,$resouceAmount,$userId);
		@$db_connect->query($queryStr);
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

	function changeTileOwner($userId,$xCoord,$yCoord){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("UPDATE `map` SET `id_owner`=%s WHERE x_coord = %s AND y_coord = %s",$userId,$xCoord,$yCoord);
		@$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function getTileMapFromDB($x,$y){
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
    /*$query = sprintf("SELECT `x_coord`,`y_coord`,`id_owner`, `biome`,`building_id` FROM `map` WHERE x_coord = %s AND y_coord = %s",
    $x,$y);*/
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
		$row = $result->fetch_assoc();
		unset($row['id']);


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

	function getPlayersArmyByIdDB($playerId){
		//SELECT * FROM `user_army` WHERE user_id = 12
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


		mysqli_close($db_connect);
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

	function addArmyDB($x,$y,$armyAmount){
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

/*
	function getBuildingsToBuild(){
		$buildingsInfo = array();
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `gs_buildingstypes`");
		$result = @$db_connect->query($queryStr);

		$row = $result->fetch_assoc();

		while($row != NULL)
		{
			$buildingInfo = array(
					"id"=>$row["id"],
					"Type"=>$row["Type"]
			);
			if($row["Cost"] != NULL)
			{
				$cost = array();
				$queryStrCost = sprintf("SELECT * FROM `gs_costs` WHERE id = %s",$row["Cost"]);
				$resultCost = @$db_connect->query($queryStrCost);
				$rowCost = $resultCost->fetch_assoc();
				foreach ($rowCost as $key => $value)
				{
					if($key != "id")
					{
						$cost[$key] = $value;
					}
				}
				$buildingInfo["Cost"]=$cost;
			}

			if($row["technology_requirements_id"] != NULL)
			{
				//get tech req.
			}
			$row = $result->fetch_assoc();
			array_push($buildingsInfo,$buildingInfo);
		}

		$jsonResponse = json_encode($buildingsInfo);
		echo ($jsonResponse);


		mysqli_close($db_connect);
	}
*/
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
		while($row = $result->fetch_assoc()){
			$userBuildings[]= $row;
		}
 		mysqli_close($db_connect);
 		return $userBuildings;
	}

	//$res = [
	//	"Swordman" => 4,
	//	"Shieldbearer" => 4
	//];

	//echo(json_encode(addArmyDB(-3, 1, $res)));
?>

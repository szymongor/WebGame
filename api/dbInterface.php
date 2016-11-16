<?php
require_once "../connect.php";

	function getUser($userId)
	{
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT `user` FROM `users` WHERE id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function getUserResources($userId)
  {
		upDateResources($userId);
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr= sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources` WHERE user_id = %s",$userId);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function upDateResources($userId)
	{
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

	# $resourcesArray example
	#$res = [
	#	"Wood" => -5000,
	#	"Iron" => +500
	#];

	function transferResources($userId, $resourcesArray)
	{
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

	function addResource($userId,$resourceName,$resouceAmount)
	{
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		#UPDATE `user_resources` SET`Wood`=Wood + 2000 WHERE user_id=12
		$queryStr = sprintf("UPDATE `user_resources` SET `%s`= %s + %s WHERE user_id=%s",
		$resourceName,$resourceName,$resouceAmount,$userId);
		@$db_connect->query($queryStr);
		mysqli_close($db_connect);
	}

	function getBuilding($xCoord,$yCoord)
	{
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);

		$queryStr = sprintf("SELECT * FROM `buildings` WHERE building_id = (SELECT `building_id` FROM `map` WHERE x_coord = %s AND y_coord = %s)",
		$xCoord,$yCoord);
		$result = @$db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($db_connect);
		return $row;
	}

	function changeTileOwner($userId,$xCoord,$yCoord)
	{
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("UPDATE `map` SET `id_owner`=%s WHERE x_coord = %s AND y_coord = %s",$userId,$xCoord,$yCoord);
		@$db_connect->query($queryStr);
	}

	function getTileMap($x,$y)
  {
		global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
    $query = sprintf("SELECT `x_coord`,`y_coord`,`id_owner`, `biome`,`building_id` FROM `map` WHERE x_coord = %s AND y_coord = %s",
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
      //echo($biome);
    }
    else
    {
			$jsonResponse = json_encode($row);
      return $jsonResponse;
    }
		$query = sprintf("SELECT `x_coord`,`y_coord`,`id_owner`, `biome`, `building_id` FROM `map` WHERE x_coord = %s AND y_coord = %s",
    $x,$y);
    $result = @$db_connect->query($query);
    $row = $result->fetch_assoc();

		$jsonResponse = json_encode($row);
		return $jsonResponse;
  }

?>

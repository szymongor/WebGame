<?php
require_once "../connect.php";

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

	#echo(json_encode(getUserResources(12)));
?>

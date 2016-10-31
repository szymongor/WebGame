<?php

	session_start();
		
		if (!isset($_SESSION['logged_on']))
		{
			echo('Not logged');
			exit();
		}
		else
		{
			require_once "../connect.php";
			$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
			$method = $_SERVER['REQUEST_METHOD'];
			if ($db_connect->connect_errno!=0)
					{
						echo "Error: ".$db_connect->connect_errno;
						exit();
					}
					else
					{
						switch($method)
						{
							
							case 'GET':
									upDateResources($db_connect);
									$result = @$db_connect->query(sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources` WHERE user_id = %s",$_SESSION['id']));
									
									$row = $result->fetch_assoc();
									$Wood = $row['Wood'];
									$Stone = $row['Stone'];
									$Iron = $row['Iron'];
									$Food = $row['Stone'];
									
									$resources_json = json_encode($row);
									
									echo($resources_json);
								
								break;
							case 'PUT':
								//upDateResources($db_connect);
								//$result = @$db_connect->query(sprintf("SELECT last_update FROM `user_resources_update` WHERE user_id = %s",$_SESSION['id']));
								//$row = $result->fetch_assoc();
								//$timeLastUpdate = $row['last_update'];
								//$timeNow = time();
								//$timeSpan = $timeNow-$timeLastUpdate;
								
								break;		
						}
						//$db_connect.close();
			}
			

			//echo();
		}
		
		function upDateResources($db_connect)
		{
			$result = @$db_connect->query(sprintf("SELECT last_update FROM `user_resources_update` WHERE user_id = %s",$_SESSION['id']));
			$row = $result->fetch_assoc();
			$timeLastUpdate = $row['last_update'];
			$timeNow = time();
			
			
			if($timeLastUpdate = NULL)
			{
				//initialize user_resource_update with timestamp = now
				@$db_connect->query(sprintf("UPDATE `user_resources_update` SET `last_update`= %s WHERE user_id=%s",$timeNow,$_SESSION['id']));
			}
			else
			{
				$timeLastUpdate = $row['last_update'];
				$timeNow = time();
				$timeSpan = $timeNow-$timeLastUpdate;
				if($timeSpan>=60)
				{	
					//get user resources
					$resourcesResult = @$db_connect->query(sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources` WHERE user_id = %s",$_SESSION['id']));
					
					$incomeResult = @$db_connect->query(sprintf("SELECT `Wood_income`, `Stone_income`, `Iron_income`, `Food_income` FROM `user_resources_income` WHERE user_id = %s",$_SESSION['id']));
					
					$resourcesRow = $resourcesResult->fetch_assoc();
					$incomeRow = $incomeResult->fetch_assoc();
									
					$n = floor($timeSpan/60);					
					$timePass = $timeLastUpdate+$n*60;
										
					$Wood = $resourcesRow['Wood'] +$n*$incomeRow['Wood_income'];
					$Stone = $resourcesRow['Stone'] + $n*$incomeRow['Stone_income'];
					$Iron = $resourcesRow['Iron'] + $n*$incomeRow['Iron_income'];
					$Food = $resourcesRow['Food'] + $n*$incomeRow['Food_income'];					
					@$db_connect->query(sprintf("UPDATE `user_resources_update` SET `last_update`= %s WHERE user_id=%s",$timePass,$_SESSION['id']));
					@$db_connect->query(sprintf("UPDATE `user_resources` SET`Wood`=%s,`Stone`=%s,`Iron`=%s,`Food`=%s WHERE user_id=%s",
					$Wood,$Stone,$Iron,$Food,$_SESSION['id']));
				}
			}
			
		}

?>
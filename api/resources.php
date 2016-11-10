<?php

	session_start();

		if (!isset($_SESSION['logged_on']))
		{
			echo('Not logged');
			exit();
		}
		else
		{
			require "dbInterface.php";
			#require_once "../connect.php";
			#$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
			$method = $_SERVER['REQUEST_METHOD'];
					switch($method)
					{

						case 'GET':
								//upDateResources($db_connect);
								//$result = @$db_connect->query(sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources` WHERE user_id = %s",$_SESSION['id']));
								//$row = $result->fetch_assoc();
								$row = getUserResources($_SESSION['id']);

								$resources_json = json_encode($row);

								echo($resources_json);

							break;
						case 'PUT':

							break;
							$db_connect.close();
					}
					//$db_connect.close();
		}


		

?>

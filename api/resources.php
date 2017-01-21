<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Player.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/utils.php";
	session_start();

		if (!isset($_SESSION['logged_on']))
		{
			echo('Not logged');
			exit();
		}
		else
		{
			$method = $_SERVER['REQUEST_METHOD'];
					switch($method)
					{
						case 'GET':
							$request = getRequestType($_SERVER['REQUEST_URI']);
							switch($request){
								case 'Resources':
								//eg. http://localhost/reg/api/resources.php/Resources
									$row = $_SESSION['Player']->getPlayerResources();
									$resources_json = json_encode($row);
									echo($resources_json);
									break;
								case 'ResourcesCapacity':
								//eg. http://localhost/reg/api/resources.php/ResourcesCapacity
									$row = $_SESSION['Player']->getPlayerResourcesCapacity();
									$resources_json = json_encode($row);
									echo($resources_json);
									break;
								case 'Army':
								//eg http://localhost/reg/api/resources.php/Army
									$row = $_SESSION['Player']->getPlayersArmy();
									$resources_json = json_encode($row);
									echo($resources_json);
									break;
							}

							break;
						case 'PUT':
							break;
					}
		}
?>

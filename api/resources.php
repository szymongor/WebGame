<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Player.php";
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
							$request = explode('/', $_SERVER['REQUEST_URI'])[4];
							switch($request){
								case 'Resources':
								//eg. http://localhost/reg/api/resources.php/Resources
									$row = $_SESSION['Player']->getPlayerResources();
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

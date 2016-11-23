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
								$row = $_SESSION['Player']->getPlayerResources();
								$resources_json = json_encode($row);
								echo($resources_json);
							break;
						case 'PUT':
							break;
					}
		}
?>

<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Player.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Building.php";
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
							switch($request)
							{
								case "toBuild":
									//eg. http://localhost/reg/api/building.php/toBuild
									$response = Building::getBuildingListToBuild();
									echo($response);
									break;
								case "build":
									//buildBuilding();
									break;
							}

              break;
          }
    }
?>

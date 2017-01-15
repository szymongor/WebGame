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
								case "buildingList":
									//eg. http://localhost/reg/api/building.php/buildingList
									$response = Building::getBuildingListToBuild();
									echo($response);
									break;
								case "build":
									//eg. http://localhost/reg/api/building.php/build/?x=2&y=4&BuildingType=House
									$response = json_encode($_SESSION['Player']->buildBuilding($_GET['x'], $_GET['y'],$_GET['BuildingType']));
									echo($response);
									break;
								case "buildingFunctions":
									//eg. http://localhost/reg/api/building.php/buildingFunctions/?x=3&y=2
									$response = json_encode($_SESSION['Player']->getBuildingFunctions($_GET['x'], $_GET['y']));
									echo($response);
									break;
								case "addTask":
									//eg. http://localhost/reg/api/building.php/addTask/?x=3&y=2&Task=%22Tools%22&Amount=50
									$response = json_encode($_SESSION['Player']->addBuildingTask($_GET['x'], $_GET['y'],$_GET['Task'],$_GET['Amount']));
									//echo($_GET['x'].":". $_GET['y']."/".$_GET['Task'].":". $_GET['Amount']);
									echo($response);
									break;

							}

              break;
          }
    }
?>

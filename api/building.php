<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Player.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Building.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/TaskManager.php";
	session_start();

		if (!isset($_SESSION['logged_on']))
		{
			echo('Not logged');
			exit();
		}
		else
		{
			$_SESSION['TaskManager']->updateTasks();
      $method = $_SERVER['REQUEST_METHOD'];
					switch($method)
					{
						case 'GET':
							$request = getRequestType($_SERVER['REQUEST_URI']);
							switch($request)
							{
								case "buildingList":
									//eg. http://localhost/reg/api/building.php/buildingList
									$response = Building::getBuildingListToBuild();
									echo($response);
									break;
								case "build":
									//eg. http://localhost/reg/api/building.php/build/?x=2&y=4&BuildingType=House
									if(checkVariables()){
										$response = json_encode($_SESSION['Player']->buildBuilding($_GET['x'], $_GET['y'],$_GET['BuildingType']));
									}
									else{
										$response = "Wrong data format";
									}
									echo($response);
									break;
								case "buildingFunctions":
									//eg. http://localhost/reg/api/building.php/buildingFunctions/?x=3&y=2
									if(checkVariables()){
										$response = json_encode($_SESSION['Player']->getBuildingFunctions($_GET['x'], $_GET['y']));
									}
									else{
										$response = "Wrong data format";
									}
									echo($response);
									break;
								case "addTask":
									//eg. http://localhost/reg/api/building.php/addTask/?x=2&y=2&Task=Swordman&Amount=5
									if(checkVariables()){
										$response = json_encode($_SESSION['Player']->addBuildingTask($_GET['x'], $_GET['y'],$_GET['Task'],$_GET['Amount']));
									}
									else{
										$response = "Wrong data format";
									}
									echo($response);
									break;
								case "currentBuildingTasks":
									//eg. http://localhost/reg/api/building.php/currentBuildingTasks/?x=2&y=2
									if(checkVariables()){
										$response = json_encode($_SESSION['Player']->getBuildingsTasks($_GET['x'], $_GET['y']));
									}
									else{
										$response = "Wrong data format";
									}
									echo($response);
									break;

							}

              break;
          }
    }
?>

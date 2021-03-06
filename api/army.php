<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Player.php";
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
				case 'POST':
				$request = getRequestType($_SERVER['REQUEST_URI']);
				switch($request){
					case 'conquer':
					//eg. http://localhost/reg/api/army.php/conquer/?x=4&y=4
						if(checkVariables()){
							$response = json_encode($_SESSION['Player']->conquer($_GET['x'], $_GET['y'],$_POST['Army']));
						}
						else{
							$response = "Wrong data format";
						}
						echo($response);
						break;
					case 'attack':
					//eg. http://localhost/reg/api/army.php/attack/?x=4&y=4
						if(checkVariables()){
							$response = json_encode($_SESSION['Player']->attackTile($_GET['x'], $_GET['y'],$_POST['Army']));
						}
						else{
							$response = "Wrong data format";
						}
						echo($response);
					break;
					case 'addArmy':
					//eg. http://localhost/reg/api/army.php/addArmy/?x=4&y=4
						if(checkVariables()){
							$response = json_encode($_SESSION['Player']->addArmyToTile($_GET['x'], $_GET['y'],$_POST['Army']));
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

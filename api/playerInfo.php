<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
	session_start();

	if (!isset($_SESSION['logged_on']))
	{
		echo('Not logged');
		exit();
	}
	else
	{
			$request = getRequestType($_SERVER['REQUEST_URI']);
			switch($request)
			{
				case "info":
					//eg. http://localhost/reg/api/playerInfo.php/info
					$player = getUserInfoDB($_SESSION['id']);
			    $response['id']= $_SESSION['id'];
					$response['location']['x']=$player['xCoordHQ'];
					$response['location']['y']=$player['yCoordHQ'];
			    $playerJSON = json_encode($response);
			    echo($playerJSON);
					break;
				case 'playerName':
					//eg. http://localhost/reg/api/playerInfo.php/playerName?id=12
					echo(checkVariables());
					if(checkVariables()){
						$response = getUser($_GET['id']);
					}
					else{
						$response = "Wrong data format";
					}
					$playerJSON = json_encode($response);
					if($response == null){
						echo("No such player");
					}
					else{
						echo($playerJSON);
					}
					break;
			}

  }

?>

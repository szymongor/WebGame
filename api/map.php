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
				case 'tile':
					// eg. http://localhost/reg/api/map.php/tile/?x=0&y=7
					$response = $_SESSION['Player']->getMapTile($_GET['x'],$_GET['y']);
					echo(json_encode($response));
		      break;
				case 'building':
					//eg. http://localhost/reg/api/map.php/building/?x=0&y=7
					$response = $_SESSION['Player']->getBuilding($_GET['x'],$_GET['y']);
					echo(json_encode($response));
					break;
				case 'region':
					// eg. http://localhost/reg/api/map.php/region/?xFrom=0&xTo=7&yFrom=0&yTo=7
					$response = $_SESSION['Player']->getMapRegion($_GET['xFrom'],$_GET['xTo'],$_GET['yFrom'],$_GET['yTo']);
					echo(json_encode($response));
					break;
			}
			break;

    }
  }
?>

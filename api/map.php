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
			$response = $_SESSION['Player']->getMap($_GET['x'],$_GET['y']);
      //$response = getTileMap($_GET['x'],$_GET['y']);
			echo($response);
      break;
    }
  }
?>

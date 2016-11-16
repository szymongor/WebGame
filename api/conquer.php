<?php

	session_start();

	if (!isset($_SESSION['logged_on']))
	{
		echo('Not logged');
		exit();
	}
	else
	{
		require "dbInterface.php";
		$method = $_SERVER['REQUEST_METHOD'];
			switch($method)
			{
				case 'GET':
				changeTileOwner($_SESSION['id'],$_GET['x'],$_GET['y']);
        $response = getTileMap($_GET['x'],$_GET['y']);
				echo($response);
        break;
      }
  }



?>

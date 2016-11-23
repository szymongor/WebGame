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
				//eg. 
        $response = $_SESSION['Player']->conquer($_GET['x'], $_GET['y']);
				echo($response);
        break;
      }
  }



?>

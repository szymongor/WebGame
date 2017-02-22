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
					//eg. http://localhost/reg/api/conquer.php?x=4&y=4
	        //$response = $_SESSION['Player']->conquer($_GET['x'], $_GET['y']);
					$response = checkVariables();

					echo($response);
	        break;
      }
  }



?>

<?php

	session_start();

	if (!isset($_SESSION['logged_on']))
	{
		echo('Not logged');
		exit();
	}
	else
	{
    $response['id']= $_SESSION['id'];
    $playerJSON = json_encode($response);
    echo($playerJSON);
  }

?>

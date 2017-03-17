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
		$response['location']['x']=2;
		$response['location']['y']=4;

    $playerJSON = json_encode($response);
    echo($playerJSON);
  }

?>

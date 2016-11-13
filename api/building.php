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
              $building = getBuilding($_GET['xCoord'],$_GET['yCoord']);
              $building_json = json_encode($building);
              echo($building_json);
              break;
          }
    }

?>

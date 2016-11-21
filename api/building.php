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
						//todo -> refactor this:
							$request = explode('/', $_SERVER['REQUEST_URI'])[4];
							switch($request)
							{
								case "toBuild":
									getBuildingsToBuild();
									break;
								case "build":
									buildBuilding();
									break;
								case "map":
									getBuilding();
								 break;
							}

              break;
          }
    }

		function getBuilding()
		{
			$building = getBuildingFromDB($_GET['xCoord'],$_GET['yCoord']);
			$building_json = json_encode($building);
			echo($building_json);
		}

		function buildBuilding()
		{
			$tile = json_decode(getTileMap($_GET['xCoord'],$_GET['yCoord']),true);
			$success;
			//echo($tile['id_owner']." : ". $_SESSION['id']."<br/>");
			if($tile['id_owner']==$_SESSION['id'])
			{
				//echo("Equals<br/>");
				$success = setTileBuilding($_GET['xCoord'],$_GET['yCoord'],$_GET['buildingType']);
			}
			else
			{
				$success = false;
			}

			if($success)
			{
				getBuilding();
			}
			else
			{
				echo("Failed!");
			}
		}
?>

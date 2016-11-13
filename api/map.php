<?php

	session_start();

	if (!isset($_SESSION['logged_on']))
	{
		echo('Not logged');
		exit();
	}
	else
	{
    require_once "../connect.php";
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$method = $_SERVER['REQUEST_METHOD'];
		if ($db_connect->connect_errno!=0)
		{
			echo "Error: ".$db_connect->connect_errno;
			exit();
		}
		else
		{
			switch($method)
			{
				case 'GET':
        $response = getTileMap($db_connect,$_GET['x'],$_GET['y']);
				echo($response);
        break;
      }
			//$db_connect.close();
    }
  }

  function getTileMap($db_connect,$x,$y)
  {
    $query = sprintf("SELECT `x_coord`,`y_coord`,`id_owner`, `biome` FROM `map` WHERE x_coord = %s AND y_coord = %s",
    $x,$y);
    $result = @$db_connect->query($query);

    $row = $result->fetch_assoc();
    if($row == NULL)
    {
			require_once "../databaseNames.php";
			$biome = array_rand($Biomes);
			$query = sprintf("INSERT INTO `map`(`x_coord`, `y_coord`, `biome`) VALUES (%s,%s,'%s')",
      $x,$y,$biome);
			@$db_connect->query($query);
      //echo($biome);
    }
    else
    {
			$jsonResponse = json_encode($row);
      return $jsonResponse;
    }
		$query = sprintf("SELECT `x_coord`,`y_coord`,`id_owner`, `biome`, `building` FROM `map` WHERE x_coord = %s AND y_coord = %s",
    $x,$y);
    $result = @$db_connect->query($query);
    $row = $result->fetch_assoc();

		$jsonResponse = json_encode($row);
		return $jsonResponse;
  }

?>

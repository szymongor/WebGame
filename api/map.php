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
          initMap($db_connect,$_GET['x'],1,$_GET['y'],1);
          break;
        }
				//$db_connect.close();
      }
    }

    function initMap($db_connect,$xfrom,$xto,$yfrom,$yto)
    {
      $query = sprintf("SELECT `id_owner`, `biome`, `building` FROM `map` WHERE x_coord = %s AND y_coord = %s",
      $xfrom,$yfrom);
      $result = @$db_connect->query($query);

      $row = $result->fetch_assoc();
      if($row == NULL)
      {
        echo("NULL");
      }
      else
      {
				$jsonResponse = json_encode($row);
        echo($jsonResponse);
      }



    }

?>

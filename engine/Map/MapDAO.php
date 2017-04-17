<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/connect.php";

  class MapDAO{

    private $db_connect;

    public function __construct(){}

    private function startConnection(){
      global $host, $db_user, $db_password, $db_name;
      $this->db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
      if ($this->db_connect->connect_error) {
        return false;
        //die("Connection failed: " . $this->db_connect->connect_error);
      }
      else{
        return true;
      }
    }

    public function setPlayerLoation($playerId,$x,$y){
  		$this->startConnection();
  		$queryStr = sprintf("UPDATE `users` SET `xCoordHQ`=%s,`yCoordHQ`=%s WHERE id=%s",$x,$y,$playerId);
  		@$this->db_connect->query($queryStr);
  		mysqli_close($this->db_connect);
  	}

    public function getBuildingByCoords($xCoord,$yCoord){
  		$this->startConnection();
  		$queryStr = sprintf("SELECT * FROM `buildings`
  		WHERE building_id = (SELECT `building_id` FROM `map` WHERE x_coord= %s AND y_coord = %s)",
  		$xCoord,$yCoord);
  		$result = @$this->db_connect->query($queryStr);
  		$row = $result->fetch_assoc();
  		mysqli_close($this->db_connect);
  		return $row;
  	}

    public function getMapTile($x,$y){
  		$this->startConnection();

  		$query = sprintf("SELECT * FROM `map` WHERE x_coord = %s AND y_coord = %s",
      $x,$y);
      $result = @$this->db_connect->query($query);

      $row = $result->fetch_assoc();
      if($row == NULL)
      {
  			$biome = array_rand($Biomes);
  			$query = sprintf("INSERT INTO `map`(`x_coord`, `y_coord`, `biome`) VALUES (%s,%s,'%s')",
        $x,$y,$biome);
  			@$this->db_connect->query($query);
      }
      else
      {
  			//$jsonResponse = json_encode($row);
  			mysqli_close($this->db_connect);
        return $row;
      }
  		$query = sprintf("SELECT `x_coord`,`y_coord`,`id_owner`, `biome`, `building_id` FROM `map` WHERE x_coord = %s AND y_coord = %s",
      $x,$y);
      $result = @$this->db_connect->query($query);
      $row = $result->fetch_assoc();
  		//$jsonResponse = json_encode($row);
  		mysqli_close($this->db_connect);
  		return $row;
    }

  }

  $mapDAO = new MapDAO();

  $response = $mapDAO->getMapTile(3,3);

  echo(json_encode($response));



?>

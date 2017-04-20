<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/databaseNames.php";
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
      global $Biomes;
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

    public function getMapRegion($xFrom,$xTo,$yFrom,$yTo){
  		$this->startConnection();
  		$queryStr = sprintf("SELECT * FROM `map` WHERE x_coord >=%s AND x_coord <= %s AND y_coord >=%s AND y_coord <=%s ",$xFrom,$xTo,$yFrom,$yTo);
  		$result = @$this->db_connect->query($queryStr);
  		$mapArray = array();
  		while($mapRow = $result->fetch_assoc()){
  			array_push($mapArray,$mapRow);
  		}
  		mysqli_close($this->db_connect);
  		return $mapArray;
  	}

    public function getPlayersBuildings($playerId){
      $this->startConnection();

   		$queryStr = sprintf("SELECT B.`building_id`, B.`type`, B.`level`, M.`x_coord`, M.`y_coord` FROM `buildings` as B JOIN `map` AS M
        WHERE B.building_id = M.building_id AND M.id_owner = %s",
   		$playerId);
   		$result = @$this->db_connect->query($queryStr);
  		$playersBuildings = array();
  		if($result){
  			while($row = $result->fetch_assoc()){
  				$playersBuildings[]= $row;
  			}
  		}
   		mysqli_close($this->db_connect);
   		return $playersBuildings;
    }
  }

  //$mapDAO = new MapDAO();

  //$response = $mapDAO->getPlayersBuildings(12);

  //echo(json_encode($response));



?>

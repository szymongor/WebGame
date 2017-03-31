<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/connect.php";

class DbInterface{

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

  public function getPlayer($playerId){
    if($this->startConnection()){
      $queryStr = sprintf("SELECT `user` FROM `users` WHERE id = %s",$playerId);
  		$result = $this->db_connect->query($queryStr);
      mysqli_close($this->db_connect);
      $row = $result->fetch_assoc();
      return $row;

    }
    else{
      return "Connection failed: " . $this->db_connect->connect_error;
    }

  }

  public function setPlayerLoation($playerId,$x,$y){
    if($this->startConnection()){
      $queryStr = sprintf("UPDATE `users` SET `xCoordHQ`=%s,`yCoordHQ`=%s WHERE id=%s",$x,$y,$playerId);
  		@$this->db_connect->query($queryStr);
  		mysqli_close($this->db_connect);
    }
    else{
      return "Connection failed: " . $this->db_connect->connect_error;
    }

	}

  public function getPlayerResourcesDB($playerId){
		if($this->startConnection()){
      $queryStr= sprintf("SELECT `Wood`, `Stone`, `Iron`, `Food` FROM `user_resources` WHERE user_id = %s",$playerId);
  		$result = @$this->db_connect->query($queryStr);
  		$row = $result->fetch_assoc();
  		if($row == NULL){
  			if($this->initUserResources($playerId)){
          $result = @$this->db_connect->query($queryStr);
    			$row = $result->fetch_assoc();
          return $row;
        }
        else{
          return "No such player";
        }
  		}
      mysqli_close($this->db_connect);
			return $row;
    }
    else{
      return "Connection failed: " . $this->db_connect->connect_error;
    }
	}

  public function initUserResources($playerId){
    if($this->getPlayer($playerId)){
      if($this->startConnection()){
        $queryStr= sprintf("INSERT INTO `user_resources`(`user_id`) VALUES (%s)",$playerId);
    		@$this->db_connect->query($queryStr);

    		$queryStr= sprintf("INSERT INTO `user_resources_income`(`user_id`) VALUES (%s)",$playerId);
    		@$this->db_connect->query($queryStr);

    		$queryStr= sprintf("INSERT INTO `user_resources_update`(`user_id`, `last_update`) VALUES (%s,%s)",$playerId,time());
    		@$this->db_connect->query($queryStr);

    		mysqli_close($this->db_connect);
        return true;
      }
      else{
        //return "Connection failed: " . $this->db_connect->connect_error;
        return false;
      }

    }
    else{
      return false;
    }


	}

  public function getPlayerResourcesCapacity($playerId){
		$this->startConnection();
		$queryStr= sprintf("SELECT * FROM `user_resources_capacity` WHERE user_id = %s",$playerId);
		$result = @$this->db_connect->query($queryStr);

		mysqli_close($this->db_connect);

		if(!$result){
			return initUserResourcesCapacityDB($playerId);
		}else{
			$row = $result->fetch_assoc();
			return $row;
		}
	}


}
$db = new DbInterface();

echo(json_encode($db->getPlayerResourcesDB(12)));

?>

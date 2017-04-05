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

  public function getPlayerResources($playerId){
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

  public function setPlayerResources($playerId,$resources){
    $queryStr = "UPDATE `user_resources` SET ";
    foreach ($resources as $key => $value) {
      $queryStr.= "`$key`= $value ,";
    }
    $queryStr = rtrim($queryStr,",");
    $queryStr.=  "WHERE user_id=".$playerId;

    if($this->startConnection()){
      $this->db_connect->query($queryStr);
      mysqli_close($this->db_connect);
    }
    else{
      return "Connection failed: " . $this->db_connect->connect_error;
    }

  }

  //private
  public function initUserResources($playerId){
    if($this->getPlayer($playerId)){
      if($this->startConnection()){
        $queryStr= sprintf("INSERT INTO `user_resources`(`user_id`) VALUES (%s)",$playerId);
    		@$this->db_connect->query($queryStr);

    		$queryStr= sprintf("INSERT INTO `user_resources_income`(`user_id`) VALUES (%s)",$playerId);
    		@$this->db_connect->query($queryStr);

        $queryStr= sprintf("INSERT INTO `user_resources_capacity`(`user_id`) VALUES (%s)",$playerId);
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

  public function getPlayersLastResourcesUpDate($playerId){
    if($this->startConnection()){
      $result = @$this->db_connect->query(sprintf("SELECT last_update FROM `user_resources_update` WHERE user_id = %s",$playerId));
  		if($result){
        $row = $result->fetch_assoc();
    		$timeLastUpdate = $row['last_update'];
        mysqli_close($this->db_connect);
        return $timeLastUpdate;
      }
      else{
        mysqli_close($this->db_connect);
        return "No such item in db";
      }
    }
    else{
      return "Connection failed: " . $this->db_connect->connect_error;
    }
  }

  public function setPlayersLastResourcesUpDate($playerId,$time){
    $this->startConnection();
    @$this->db_connect->query(sprintf("UPDATE `user_resources_update` SET `last_update`= %s WHERE user_id=%s",$time,$playerId));
    mysqli_close($this->db_connect);
  }

  public function getPlayerResourcesIncome($playerId){
    if($this->startConnection()){
      $queryStr= sprintf("SELECT * FROM `user_resources_income` WHERE user_id = %s",$playerId);
  		$result = @$this->db_connect->query($queryStr);
  		$row = $result->fetch_assoc();
  		if($row == NULL){
  			if($this->initUserResources($playerId)){
          $result = @$this->db_connect->query($queryStr);
    			$row = $result->fetch_assoc();
          unset($row['user_id']);
          return $row;
        }
        else{
          return "No such player";
        }
  		}
      mysqli_close($this->db_connect);
      unset($row['user_id']);
			return $row;
    }
    else{
      return "Connection failed: " . $this->db_connect->connect_error;
    }
  }

  public function getPlayerResourcesCapacity($playerId){
    if($this->startConnection()){
      $queryStr= sprintf("SELECT * FROM `user_resources_capacity` WHERE user_id = %s",$playerId);
  		$result = @$this->db_connect->query($queryStr);
  		$row = $result->fetch_assoc();
  		if($row == NULL){
  			if($this->initUserResources($playerId)){
          $this->startConnection();
          $result = @$this->db_connect->query($queryStr);
    			$row = $result->fetch_assoc();
          unset($row['User_id']);
          mysqli_close($this->db_connect);
          return $row;
        }
        else{
          return "No such player";
        }
  		}
      mysqli_close($this->db_connect);
      unset($row['User_id']);
			return $row;
    }
    else{
      return "Connection failed: " . $this->db_connect->connect_error;
    }
  }
}
//$db = new DbInterface();
//$res = array('Wood' => 12, 'Iron' => 21);
//echo(json_encode($db->getPlayerResourcesCapacity(12)));



?>

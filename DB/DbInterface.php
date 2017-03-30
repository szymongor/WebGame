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
  		$row = $result->fetch_assoc();
  		mysqli_close($this->db_connect);
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
}

$db = new DbInterface();

echo(json_encode($db->getPlayer(12)));

?>

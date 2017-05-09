<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/connect.php";


class ItemsDAO{

  private $db_connect;

  public function __construct(){  }

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

  private function initPlayersItems($playerId){
		$this->startConnection();
		$queryStr= sprintf("INSERT INTO `user_items`(`user_id`) VALUES (%s)", $playerId);
		@$this->db_connect->query($queryStr);
		$queryStr= sprintf("SELECT * FROM `user_items` WHERE user_id = %s",$playerId);
		$result = @$this->db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($this->db_connect);
		return $row;
	}

  public function getUserItems($playerId){
		$this->startConnection();
		$queryStr= sprintf("SELECT * FROM `user_items` WHERE user_id = %s",$playerId);
		$result = @$this->db_connect->query($queryStr);
		$row = $result->fetch_assoc();
		mysqli_close($this->db_connect);
		if($row == NULL){
			return $this->initPlayersItems($playerId);
		}else{
			return $row;
		}
	}

  public function transferItems($playerId, $items){
    $queryStr = "UPDATE `user_items` SET ";
    foreach ($items as $key => $value) {
      $queryStr.= "`$key`= $key + $value ,";
    }
    $queryStr = rtrim($queryStr,",");
    $queryStr.=  "WHERE user_id=".$playerId;
    $this->startConnection();
    $this->db_connect->query($queryStr);
    mysqli_close($this->db_connect);
  }

}


//$itemsDao = new ItemsDAO();
//$items = array('Tool'=>100, 'Armor' => 10);
//$response = $itemsDao->transferItems(12,$items);
//echo json_encode($response);

?>

<?php

require_once $_SERVER['DOCUMENT_ROOT']."/Reg/connect.php";

class TechnologyDAO{

  private $db_connect;

  public function __construct(){    }

  private function startConnection(){
    global $host, $db_user, $db_password, $db_name;
    $this->db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($this->db_connect->connect_error) {
      return false;
    }
    else{
      return true;
    }
  }

  public function getPlayersTechnologies($playerId){
    $this->startConnection();
		$queryStr = sprintf("SELECT * FROM `technologies` WHERE owner_id = %s",$playerId);
		$result = @$this->db_connect->query($queryStr);
		$technologiesArray = array();
		if($result){
			while($task = $result->fetch_assoc()){
				array_push($technologiesArray,$task);
			}
		}
		mysqli_close($this->db_connect);
		return $technologiesArray;
  }

}

  //$dao = new TechnologyDAO();
  //$response = $dao->getPlayersTechnologies(12);
  //echo json_encode($response);

?>

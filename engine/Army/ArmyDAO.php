<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/connect.php";


  class ArmyDAO{

    private $db_connect;

    public function __construct(){    }

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

    public function getArmyById($armyId){
  		$this->startConnection();
  		$queryStr = sprintf("SELECT * FROM `army`
  		WHERE id = %s",
  		$armyId);

  		$result = @$this->db_connect->query($queryStr);
  		if($result == null){
  			$row = getEmptyArmy();
  		}
  		else{
  			$row = $result->fetch_assoc();
  			unset($row['id']);
  		}
  		mysqli_close($this->db_connect);
  		return $row;
  	}

    public function transferArmy($armyId,$army){
      $queryStr = "UPDATE `army` SET ";
      foreach ($army as $key => $value) {
        $queryStr.= "`$key`= $key + $value ,";
      }
      $queryStr = rtrim($queryStr,",");
      $queryStr.=  "WHERE id=".$armyId;

      if($this->startConnection()){
        $this->db_connect->query($queryStr);
        mysqli_close($this->db_connect);
      }
      else{
        return "Connection failed: " . $this->db_connect->connect_error;
      }
    }

    public function getPlayersArmyId($playerId){
  		$this->startConnection();
  		$queryStr = sprintf("SELECT * FROM `user_army` WHERE user_id = %s",
  		$playerId);

  		$result = @$this->db_connect->query($queryStr);
  		$row = $result->fetch_assoc();
  		$armyId;
  		if($row == NULL){
  			$armyId = initArmy();
  			$queryStr = sprintf("INSERT INTO `user_army`(`user_id`, `army_id`) VALUES (%s,%s)",
  			$playerId,$armyId);
  			@$this->db_connect->query($queryStr);
  		}
  		else{
  			$armyId = $row["army_id"];
  		}
      mysqli_close($this->db_connect);
  		return $armyId;
  	}

    public function getPlayersArmyById($playerId){
  		$armyId = $this->getPlayersArmyId($playerId);
  		return $this->getArmyById($armyId);
  	}

    public function initArmy(){
  		$this->startConnection();
  		$queryStr = sprintf("INSERT INTO `army`() VALUES ();");
  		$queryStr .= sprintf("SELECT LAST_INSERT_ID();");
  		$armyId;
  		if (mysqli_multi_query($this->db_connect,$queryStr))
  		{
  			do
  				{
  				if ($result=mysqli_store_result($this->db_connect)) {
  					while ($row=mysqli_fetch_row($result))
  					{
  						$armyId=$row[0];
  					}
  					// Free result set
  					mysqli_free_result($result);
  					}
  				}
  			while (mysqli_next_result($this->db_connect));
  		}
      mysqli_close($this->db_connect);
  		return $armyId;
  	}
  }

  //$armyDao = new ArmyDAO();
  //$army = array('Shaman' => 100, 'Wizard' => 200);
  //echo json_encode($armyDao->getArmyById(8));

?>

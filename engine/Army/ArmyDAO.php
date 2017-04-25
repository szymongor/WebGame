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

  }

  //$armyDao = new ArmyDAO();
  //$army = array('Shaman' => 100, 'Wizard' => 200);
  //$armyDao->transferArmy(9,$army);

?>

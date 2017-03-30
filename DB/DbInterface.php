<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/connect.php";

class DbInterface{

  private $db_connect;

  public function __construct(){
    global $host, $db_user, $db_password, $db_name;
    $this->db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($this->db_connect->connect_error) {
      die("Connection failed: " . $this->db_connect->connect_error);
    }

  }


}


$dbInterface = new DbInterface();

?>

<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Army/ArmyUnit.php";

  class Army{

    private $units;

    public function __construct($armyData){
      $this->initUnits($armyData);
    }

    private function initUnits($armyData){
      $this->units = array();
      foreach ($armyData as $key => $value) {
        $unit = new ArmyUnit($key,$value);
        array_push($this->units,$unit);
      }
    }

    public function getUnits(){
      $str = "";
      foreach ($this->units as $value) {
        $str = $str.$value->getUnitStats();
      }
      return $str;
    }

  }

?>

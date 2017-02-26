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

    public function checkArmy(){

      foreach ($this->units as $key => $value) {
        if($value->getAmount() <= 0 ){
          unset($this->units[$key]);
        }
      }
      if(count($this->units) == 0){
        return "Defeat";
      }
      else{
        return "Alive";
      }
    }

    public function getUnits(){
      $units = array();
      foreach ($this->units as $value) {
        $units[$value->getType()] = $value->getAmount();
      }
      return $units;
    }

    public function getRandomUnit(){
      $rand = $this->units[array_rand($this->units)];

      return $rand;
    }

  }

?>

<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Rules.php";

  class ArmyUnit{

    private $amount;
    private $health;
    private $defense;
    private $attack;
    private $statsInfo;

    public function __construct($type, $amount){
      $this->amount = $amount;
      $this->loadStatsInfo($type);
    }

    private function loadStatsInfo($type){
      $this->statsInfo = Rules::getRules("ArmyUnits")[$type];
    }

    private function calculateStats(){

    }

    public function getStatsInfo(){
      return $this->statsInfo;
    }



  }

  $unit = new ArmyUnit("Swordman", 10);;

  echo(json_encode($unit->getStatsInfo()));


?>

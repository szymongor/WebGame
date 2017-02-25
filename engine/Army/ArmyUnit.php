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
      $this->calculateStats();
    }

    private function loadStatsInfo($type){
      $this->statsInfo = Rules::getRules("ArmyUnits")[$type];
    }

    private function calculateStats(){
      $a = $this->amount;
      $stats = $this->statsInfo;
      $this->health = $stats['Health'] * $a;
      $this->defense = $stats['Defense'] * $a;
      $this->attack = $stats['Atack'] * $a;
    }

    private function calculateAmount(){
      $amount = floor($this->health / $this->statsInfo['Health']);
    }

    public function getStatsInfo(){
      return $this->statsInfo;
    }



  }

  $unit = new ArmyUnit("Swordman", 10);;

  echo(json_encode($unit->getStatsInfo()));


?>

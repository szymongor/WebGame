<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Rules.php";

  class ArmyUnit{

    private $amount;
    private $health;
    private $defense;
    private $attack;
    private $statsInfo;
    private $type;

    public function __construct($type, $amount){
      $this->type = $type;
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
      $amount = ceil($this->health / $this->statsInfo['Health']);
      $this->amount = $amount;

      $stats = $this->statsInfo;
      $a = $this->amount;
      $this->defense = $stats['Defense'] * $a;
      $this->attack = $stats['Atack'] * $a;
    }

    private function getStatsInfo(){
      return $this->statsInfo;
    }

    public function getType(){
      return $this->type;
    }

    public function getUnitStats(){
      $stats = "Type: ".$this->type."</br>";
      $stats = $stats." Amount: ".$this->amount."</br>";
      $stats = $stats." Health: ".$this->health."</br>";
      $stats = $stats." Defense: ".$this->defense."</br>";
      $stats = $stats." Attack: ".$this->attack."</br>";
      return $stats;
    }

    public function dealDamage($dmg){
      if($this->defense < $dmg){
        $this->health = $this->health + $this->defense - $dmg;
        $this->calculateAmount();
      }
    }

    public function getDamage($target){
      $dmg = $this->attack;
      if(isset($this->statsInfo['Effectiveness'][$target])){
        $dmg = $dmg*$this->statsInfo['Effectiveness'][$target];
      }
      return $dmg;
    }


  }

  //$unit = new ArmyUnit("Shieldbearer", 10);

?>

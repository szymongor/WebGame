<?php

  class Combat{

    private $attackingArmy;
    private $defendingArmy;

    public function __construct($attackingArmy,$x,$y){
      $this->attackingArmy=$attackingArmy;
      $this->defendingArmy = getArmyByLocationDB($x,$y);
    }

    public function performBattle(){
      foreach ($this->attackingArmy as $key => $value) {
        $val = $value;
        $this->attackingArmy[$key] = $val - $this->defendingArmy[$key];
        $this->defendingArmy[$key] = $this->defendingArmy[$key] - $val;
        if($this->attackingArmy[$key] < 0){
          $this->attackingArmy[$key] = 0;
        }
        if($this->defendingArmy[$key] < 0){
          $this->defendingArmy[$key] = 0;
        }
      }
    }

    public function getBattleResult(){
      $result = true;
      foreach ($this->defendingArmy as $key => $value) {
        if($value > 0 ){
          $result = false;
        }
      }
      return $result;
    }

    public function getDefendingArmy(){
      return $this->defendingArmy;
    }
  }

?>

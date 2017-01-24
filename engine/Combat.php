<?php

  class Combat{

    private $attackingArmy;
    private $defendingArmy;

    public function __construct($attackingArmy,$x,$y){
      $this->attackingArmy=$attackingArmy;
      $this->attackingArmy=getArmyByLocationDB($x,$y);
    }

    public function performBattle(){
      foreach ($attackingArmy as $key => $value) {
        $val = $value;
        $attackingArmy[$key] = $val - $defendingArmy[$key];
        $defendingArmy[$key] = $defendingArmy[$key] - $val;
        if($attackingArmy[$key] < 0){
          $attackingArmy[$key] = 0;
        }
        if($defendingArmy[$key] < 0){
          $defendingArmy[$key] = 0;
        }
      }
    }

    public function getBattleResult(){
      $result = true;
      foreach ($defendingArmy as $key => $value) {
        if($value > 0 ){
          $result = false;
        }
      }
      return $result;
    }
    
  }

?>

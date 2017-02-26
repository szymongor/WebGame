<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Army/Army.php";
  class Battle{

    private $attackingArmy;
    private $defendingArmy;
    private $battleLog;

    public function __construct($attackingArmy,$x,$y){
      $this->battleLog = "";
      $this->attackingArmy= new Army($attackingArmy);
      $this->defendingArmy = new Army(getArmyByLocationDB($x,$y));

      $this->battleLog = $this->battleLog."Attacking army: ".json_encode( $this->attackingArmy->getUnits());
      $this->battleLog = $this->battleLog."Defending army: ".json_encode( $this->defendingArmy->getUnits());

    }

    public function performBattle(){
      while($this->attackingArmy->checkArmy() != "Defeat"
       && $this->defendingArmy->checkArmy() != "Defeat"){
         $this->round();
       }
    }

    private function round(){
      $attackingUnit = $this->attackingArmy->getRandomUnit();
      $defendingUnit = $this->defendingArmy->getRandomUnit();

      $defendingDMG = $defendingUnit->getDamage($attackingUnit->getType());
      $attackingUnit->dealDamage($defendingDMG);

      $this->battleLog = $this->battleLog."Defenders: ".$defendingUnit->getType()." ->".$attackingUnit->getType()." dmg: ".$defendingDMG;

      if($this->attackingArmy->checkArmy() != "Defeat"){
        $attackingUnit = $this->attackingArmy->getRandomUnit();
        $defendingUnit = $this->defendingArmy->getRandomUnit();
        $atackingDMG = $attackingUnit->getDamage($defendingUnit->getType());
        $defendingUnit->dealDamage($atackingDMG);
      }

    }

    public function getBattleResult(){
      if($this->attackingArmy->checkArmy() == "Defeat"){
        return "Defeat";
      }
      else{
        return "Win";
      }
    }

    public function getDefendingArmy(){
      return $this->defendingArmy;
    }

    public function getAttackingArmy(){
      return $this->attackingArmy;
    }

    public function getBattleLog(){
      return $this->battleLog;
    }

  }

?>

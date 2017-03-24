<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Army/Army.php";
  class Battle{

    private $attackingArmy;
    private $defendingArmy;
    private $battleLog;
    private $battleTile;
    private $aggressorId;

    public function __construct($aggressorId,$attackingArmy,$x,$y){
      $this->battleLog = "";
      $this->battleTile = array($x,$y);
      $this->aggressorId = $aggressorId;
      withdrawPlayersArmyDB($aggressorId,$attackingArmy);
      $this->attackingArmy = new Army($attackingArmy);
      $this->defendingArmy = new Army(getArmyByLocationDB($x,$y));
      $this->battleLog = $this->battleLog."Attacking army: ".json_encode( $this->attackingArmy->getUnits());
      $this->battleLog = $this->battleLog."Defending army: ".json_encode( $this->defendingArmy->getUnits());
    }

    private function manageArmy(){
      $x = $this->battleTile[0];
      $y = $this->battleTile[1];
      $defendingArmy = $this->defendingArmy->getUnits();
      setArmyTileDB($x,$y,$defendingArmy);
      transferPlayersArmyDB($this->aggressorId,$this->attackingArmy->getUnits() );
    }

    public function performBattle(){
      while($this->attackingArmy->checkArmy() != "Defeat"
       && $this->defendingArmy->checkArmy() != "Defeat"){
         $this->round();
       }
       $this->manageArmy();
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
        $this->battleLog = $this->battleLog."Attackers: ".$attackingUnit->getType()." ->".$defendingUnit->getType()." dmg: ".$atackingDMG;
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

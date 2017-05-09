<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Reg/Engine/Technology/TechnologyDAO.php";

class TechnologyService{

  private $technologyDAO;

  public function __construct(){
    $this->technologyDAO = new TechnologyDAO();
  }

  public function getPlayersTechnologies($playerId){
    return $this->technologyDAO->getPlayersTechnologies($playerId);
  }

  public function addTechnology($playerId, $technologyName){
		$this->technologyDAO->addTechnology($playerId, $technologyName);
	}

  public function upgradeTechnology($playerId, $technologyName, $technologyLevel){
		$this->technologyDAO->upgradeTechnology($playerId, $technologyName, $technologyLevel);
	}

  public function setTechnologyUpgraded($playerId, $technologyName){
		$this->technologyDAO->setTechnologyUpgraded($playerId, $technologyName);
	}


}

  //$technologyService = new TechnologyService();

  //$response = $technologyService->getPlayersTechnologies(12);

  //echo json_encode($response);

?>

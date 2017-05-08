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



}

$technologyService = new TechnologyService();

$response = $technologyService->getPlayersTechnologies(12);

echo json_encode($response);

?>

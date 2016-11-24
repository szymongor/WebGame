<?php
  require $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";

  class Building
  {
    private $buildingType;
    private $buildingId;
    public function __construct($buildingType){
      $this->$buildingType=$buildingType;
    }

    public static function getBuildingListToBuild(){
      return getBuildingsToBuild();
    }

  }


?>

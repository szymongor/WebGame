<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";

  class Building
  {
    private $buildingType;
    private $buildingId;
    public function __construct($buildingType){
      $this->$buildingType=$buildingType;
    }

    public static function getBuildingListToBuild(){
      $files = glob($_SERVER['DOCUMENT_ROOT'].'/Reg/engine/Buildings/*.{json}', GLOB_BRACE);
      $buildingsList = array();
      foreach($files as $file) {
        $buildingFile = fopen($file, "r");
        $buildingInfo = json_decode(fread($buildingFile,filesize($file)),true);
        fclose($buildingFile);
        $infoToBuild = array();
        $infoToBuild['Type']=$buildingInfo['Type'];
        $infoToBuild['Cost']=$buildingInfo['Cost'];
        array_push($buildingsList,$infoToBuild);
      }
      $response = json_encode($buildingsList);
      return $response;
    }

    public static function getBuildingInfo($buildingType){
      $filePath = $_SERVER['DOCUMENT_ROOT'].'/Reg/engine/Buildings/'.$buildingType.'.json';
      $file = fopen($filePath, "r");
      $buildingInfo = json_decode(fread($file,filesize($filePath)),true);
      fclose($file);
      return $buildingInfo;
    }
  }
?>

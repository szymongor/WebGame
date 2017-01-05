<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";

  class Building
  {
    private $buildingType;

    public function __construct($buildingType,$buildingLevel){
      $this->buildingType=$buildingType;
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

    public function getBuildingFunctions(){
      $buildingInfo = Building::getBuildingInfo($this->buildingType);
      $buildingFunctions = array();
      if(isset($buildingInfo['Functions'])){
        $buildingFunctions = $buildingInfo['Functions'];
      }
      return $buildingFunctions;
    }

    public function calculateIncome($x,$y,$userId){
      $buildingIncome = array();
      $buildingInfo = Building::getBuildingInfo($this->buildingType);
      $buildingRange = 1;
      if(isset($buildingInfo['Range'])){
        $buildingRange = $buildingInfo['Range'];
      }

      $surroundings = getMapRegionFromDB($userId,$x-$buildingRange,$x+$buildingRange,$y-$buildingRange,$y+$buildingRange);

      if(isset($buildingInfo['Income'])){
        foreach ($buildingInfo['Income'] as $income) {
          foreach ($income['Resources'] as $resource => $amount){
            if(isset($buildingIncome[$resource])){
              $buildingIncome[$resource] += $amount;
            }
            else{
              $buildingIncome[$resource] = $amount;
            }

          }
          if(isset($income['Source'])){
            $numberOfSources = 0;
            foreach ($income['Source'] as $sourceBiome) {
              foreach ($surroundings as $tile) {
                if($tile['biome']==$sourceBiome){
                  $numberOfSources++;
                }
              }
            }
            foreach ($buildingIncome as $key => $value) {
              $buildingIncome[$key] = $value * $numberOfSources;
            }
          }
        }
      }
      return $buildingIncome;
    }
  }

  //print_r(Building::calculateIncome(2,4,12));
?>

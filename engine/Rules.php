<?php

class Rules{

  public function __construct(){  }

  public static function getRules($rulesType){
    $filePath = $_SERVER['DOCUMENT_ROOT'].'/Reg/engine/Rules/'.$rulesType.'.json';
    $file = fopen($filePath, "r");
    $rules = json_decode(fread($file,filesize($filePath)),true);
    fclose($file);
    return $rules;
  }

}

//echo json_encode(Rules::getRules("Resources")["BaseResourcesCapacity"]);

?>

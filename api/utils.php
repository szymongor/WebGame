<?php

  function getRequestType($requestURI){
    $requestType = explode('/', $requestURI);
    foreach ($requestType as $key => $value) {
      if(strpos($value, '.php') && count($requestType) >= $key){
        return $requestType[$key+1];
      }
    }

    return "None";
  }

  function chceckSufficientAmount($storageArray, $requiredArray){
    $sufficeAmount = true;

    foreach ($requiredArray as $key => $value) {
      if(!(isset($storageArray[$key]) && $storageArray[$key] + $value >= 0)){
        $sufficeAmount = false;
      }
    }
    return $sufficeAmount;
  }

?>

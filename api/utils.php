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

  function getEmptyArmy(){
    global $host, $db_user, $db_password, $db_name;
		$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
		$queryStr = sprintf("SELECT * FROM `army` LIMIT 1");
		$result = @$db_connect->query($queryStr);
		mysqli_close($db_connect);

    $row = $result->fetch_assoc();
    unset($row['id']);

    foreach ($row as $key => $value) {
      $row[$key] = 0;
    }

    return $row;
  }

  function checkVariables(){
    $response = true;
    foreach ($_GET as $key => $value) {
      if(!is_numeric($value)){
        //Refactor? check if value is in {"Swordman","Bowman", ...}
        if($value != preg_replace("/[^a-zA-Z0-9]+/", "", $value)){
          if($value!="Stone-Pit"){
            $response = false;
          }

        }
      }
    }

    foreach($_POST as $key => $value){
      if($key == 'Army'){
        if(!checkArmyFormat($value)){
          $response = false;
        }
      }
    }

    return $response;
  }

  function checkArmyFormat($armyData){
    $correctFormat = true;
    foreach ($armyData as $key => $value) {
      if(!is_numeric($value)){
        $correctFormat = false;
      }
    }
    return $correctFormat;
  }

  function checkTechnology($playersTechnologies, $requiredTechnology, $level){
    foreach ($playersTechnologies as $key => $value) {
      if($value["technology"] == $requiredTechnology){
        if($value["level"] >= $level){
          return true;
        }
        else{
          return false;
        }
      }
    }
    return false;
  }

  function findPlayerTechnology($playersTechnologies, $technologyName){
    foreach ($playersTechnologies as $key => $value) {
      if($value["technology"] == $technologyName){
        return $value;
      }
    }
    return false;
  }

?>

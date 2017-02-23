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
        if($value != preg_replace("/[^a-zA-Z0-9]+/", "", $value)){
          $response = false;
        }
      }
    }
    return $response;
  }

?>

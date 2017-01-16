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

?>

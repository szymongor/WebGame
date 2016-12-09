<?php

  $to = "gornioczek.szymon@gmail.com";
  //$subject = "This is Sparta";
  $msg = "Gratulacje";
  $header = "From form";

  $result = mail($to,$msg,$header);

  if($result == true){
    echo "Great Succes!";
  }else{
    echo "Oh no!";
  }


?>

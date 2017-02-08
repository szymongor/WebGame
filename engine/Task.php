<<?php



  class Task{

    private $task;

    public function __construct($taskFromDB){
      $this->task = $taskFromDB;
    }

    public function isTaskReady(){
      $timeNow = time();
      if($task['timeEnd'] < $timeNow){
        return true;
      }
      else{
        return false;
      }
    }

  }




?>

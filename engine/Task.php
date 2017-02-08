<<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";

  class Task{

    private $task;

    public function __construct($taskFromDB){
      $this->task = $taskFromDB;
    }

    private function getTaskEffect(){
      return $task['task_effect'];
    }

    private function addResources(){
      $taskEffect = $this->getTaskEffect();
      if(isset($taskEffect['add'])){
        if(isset($taskEffect['add']['resources'])){
          //TO DO
        }
      }
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

    public function executeTask(){
      $taskEffect = $this->getTaskEffect();

      //TO DO

    }

  }




?>

<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";


  class TaskManager{

    $tasks;

    public __construct(){
      $this->tasks = array();
    }

    public function getAllReadyTasks(){
      $tasksDB = getAllReadyTasksDB();
      foreach ($tasksDB as $value) {
        $task = new Task($value);
        array_push($this->tasks, $task);
      }
    }

    public function updateTask(){
      foreach ($this->tasks as $value) {
        $value->executeTask();
      }
    }

  }


?>

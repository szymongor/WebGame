<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Task.php";


  class TaskManager{

    private $tasks;

    public function __construct(){
      $this->tasks = array();
    }

    private function getAllReadyTasks(){
      $tasksDB = getAllReadyTasksDB();
      foreach ($tasksDB as $value) {
        $task = new Task($value);
        array_push($this->tasks, $task);
      }
    }

    public function updateTasks(){
      $this->getAllReadyTasks();
      foreach ($this->tasks as $value) {
        $value->executeTask();
      }
    }


  }

  $taskMng = new TaskManager();
  $taskMng->updateTasks();


?>

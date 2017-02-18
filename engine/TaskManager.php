<?php
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/api/dbInterface.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/Reg/engine/Task.php";


  class TaskManager{

    private $tasks;

    public function __construct(){
      $this->tasks = array();
    }

    public function getAllReadyTasks(){
      $tasksDB = getAllReadyTasksDB();
      foreach ($tasksDB as $value) {
        $task = new Task($value);
        array_push($this->tasks, $task);
        echo(json_encode($value));
        echo("</br>");
      }
    }

    public function updateTasks(){
      foreach ($this->tasks as $value) {
        $value->executeTask();
      }
    }


  }

  $taskMng = new TaskManager();
  $taskMng->getAllReadyTasks();
  $taskMng->updateTasks();


?>

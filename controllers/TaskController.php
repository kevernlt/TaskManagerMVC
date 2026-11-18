<?php
class TaskController {
    public function index() {
        $taskModel = new Task();
        $tasks = $taskModel->getAllTasks();

        require_once 'views/tasks.php';
    }

    public function perTask($id){
        $taskModel = new Task();
        $tasksAll = $taskModel->getAllTasks();
        if(isset($tasksAll[$id-1])){
            $tasks = [$tasksAll[$id-1]];
            require_once 'views/tasks.php';
        }
        else require_once 'views/404.php';
    }
}
<?php
class TaskController {
    public function index() {
        $taskModel = new Task();
        $tasks = $taskModel->getAllTasks();

        require_once 'views/tasks.php';
    }

    public function perTask($id){
        $taskModel = new Task();
        $tasks = $taskModel->getAllTasks()[$id-1];

        require_once 'views/tasks.php';
    }
}
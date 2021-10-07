<?php

namespace app\Controllers;

use App\Controllers\RepositoryController;
use App\Models\Task;


class TaskController extends RepositoryController
{

    public function show(): void
    {
        $taskCollection = $this->repository->allTasks();


        require_once 'app/Views/Tasks/index.html';


    }

    public function add(): void
    {
        //validate POST

        $addTask = new Task($_POST['title']);
        $this->save($addTask);

        header('Location:/tasks');


    }

    public function save(Task $task):void
    {
        $this->repository->addOneTask($task);

    }

    public function searched(): void
    {

        $id = $_GET['searchId'];
        $searchedTask = $this->repository->searchById($id);

        require_once 'app/Views/Tasks/searched.html';



    }
    public function delete():void
    {
        $taskId = $_POST['id'];
        $searchedTask = $this->repository->searchById($taskId);
        $this->repository->delete($searchedTask);

        header('Location:/tasks');



    }
}
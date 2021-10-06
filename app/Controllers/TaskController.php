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
        $this->repository->addOneTask($addTask);

        header('Location:/tasks');


    }

    public function searched(): void
    {

        $id = $_GET['searchId'];
        $searchedTask = $this->repository->searchById($id);

        require_once 'app/Views/Tasks/searched.html';

    }
    public function delete():void
    {
        var_dump($_POST);
        $taskId = $_POST['id'];
        $searchedTask = $this->repository->searchById($taskId);
        $this->repository->delete($searchedTask);

        header('Location:/tasks');



    }
}
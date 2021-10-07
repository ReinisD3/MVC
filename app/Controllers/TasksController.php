<?php

namespace app\Controllers;

use App\Models\Task;
use App\Repositories\CsvTasksRepository;
use App\Repositories\SQLTasksRepository;
use app\Repositories\TasksRepositoryInterface;



class TasksController
{
    private TasksRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = new SQLTasksRepository();
    }

    public function show(): void
    {
        $taskCollection = $this->repository->getRecords();


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
        $this->repository->addOne($task);

    }

    public function search(): void
    {

        $id = $_GET['searchId'];
        $searchedTask = $this->repository->searchById($id);

        require_once 'app/Views/Tasks/search.html';



    }
    public function delete():void
    {
        $taskId = $_POST['id'];
        $searchedTask = $this->repository->searchById($taskId);
        $this->repository->deleteOne($searchedTask);

        header('Location:/tasks');


    }
}
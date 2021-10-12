<?php

namespace app\Controllers;

use App\Exceptions\FormValidationException;
use App\Models\Task;
use App\Repositories\CsvTasksRepository;
use App\Repositories\MysqlTasksRepository;
use app\Repositories\TasksRepositoryInterface;
use App\Validation\TasksValidation;
use App\GetUserById;


class TasksController
{
    private TasksRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = new MysqlTasksRepository();
    }

    public function index(): void
    {
        $taskCollection = $this->repository->getRecords();


        require_once 'app/Views/Tasks/index.html';


    }

    public function add(): void
    {
        try {
            $v = new TasksValidation();
            $v->validate($_POST);
            $addTask = new Task($_POST['title']);
            $this->save($addTask);

        } catch (FormValidationException $e) {

            $_SESSION['_errors'] = $v->getErrors();

        }
        header('Location:/tasks');

    }

    public function save(Task $task):void
    {
        $this->repository->addOne($task);

    }

    /**
     * @throws FormValidationException
     */
    public function search(): void
    {
        $id = $_GET['searchId'];
        $searchedTask = $this->repository->searchById($id);
        try{
           if(empty($searchedTask)) throw new FormValidationException();
            require_once 'app/Views/Tasks/search.html';

        }catch (FormValidationException $e){
            $_SESSION['_errors']['searchError'] = "No tasks for id : $id found";
            header('Location:/tasks');
        }





    }
    public function delete():void
    {
        $taskId = $_POST['id'];
        $searchedTask = $this->repository->searchById($taskId);
        $this->repository->deleteOne($searchedTask);

        header('Location:/tasks');


    }
}
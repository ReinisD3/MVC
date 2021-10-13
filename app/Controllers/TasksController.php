<?php

namespace app\Controllers;


use App\Exceptions\FormValidationException;
use App\Input\Process;
use App\Models\Redirect;
use App\Models\Task;
use App\Models\View;
use App\Repositories\CsvTasksRepository;
use App\Repositories\MysqlTasksRepository;
use app\Repositories\TasksRepositoryInterface;
use App\Validation\TasksValidation;


class TasksController
{
    private TasksRepositoryInterface $repository;
    private TasksValidation $validator;

    public function __construct()
    {
        $this->repository = new MysqlTasksRepository();
        $this->validator = new TasksValidation();

    }

    public function index(): View
    {
        $tasks = $this->repository->getRecords();

        return new View('Tasks/index.twig', $tasks, 'tasks');

    }

    public function add(): Redirect
    {
        try {

            $this->validator->validateAdd($_POST);
            $taskToAdd = new Task(Process::input($_POST['title']));
            $this->repository->addOne($taskToAdd);

        } catch (FormValidationException $e) {

            $_SESSION['errors'] = $this->validator->getErrors();

        }
        return new Redirect('/tasks');
    }

    /**
     * @throws FormValidationException
     */
    public function search(): object
    {
        $id = Process::input($_GET['searchId']);
        $searchedTask = $this->repository->searchById($id);
        try {
            $this->validator->validateSearch($searchedTask);
            return new View('Tasks/search.twig', $searchedTask, 'task');

        } catch (FormValidationException $e) {

            $_SESSION['errors'] = $this->validator->getErrors();
            return new Redirect('/tasks');
        }

    }

    public function delete(): Redirect
    {
        $taskId = $_POST['id'];
        $searchedTask = $this->repository->searchById($taskId);
        $this->repository->deleteOne($searchedTask);

        return new Redirect('/tasks');


    }
}
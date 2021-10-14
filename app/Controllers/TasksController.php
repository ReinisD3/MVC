<?php

namespace app\Controllers;


use App\Exceptions\FormValidationException;
use App\Exceptions\RepositoryValidationException;
use App\Validation\Input\Process;
use App\Redirect;
use App\Models\Task;
use App\View;
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
            $this->validator->validateSave($_POST);
            $this->repository->save(new Task(Process::input($_POST['title'])));

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
        try {
            $searchedTask = $this->repository->searchById(Process::input($_GET['searchId']));
            $this->validator->validateSearch($searchedTask);
            return new View('Tasks/search.twig', $searchedTask, 'task');

        } catch (RepositoryValidationException $e) {

            $_SESSION['errors'] = $this->validator->getErrors();
            return new Redirect('/tasks');
        }

    }

    public function delete(): Redirect
    {
        $searchedTask = $this->repository->searchById($_POST['id']);
        $this->repository->deleteOne($searchedTask);

        return new Redirect('/tasks');

    }
    public function edit() : View
    {
        return new View('Tasks/edit.twig',$_GET['id'],'taskId');
    }
    public function editSave():Redirect
    {
        try{
            $this->validator->validateSave($_POST);

            $task = $this->repository->searchById($_POST['id']);
            $task->setTitle($_POST['title']);
            $this->repository->save($task);
        }catch(FormValidationException $e)
        {
            $_SESSION['errors'] = $this->validator->getErrors();
            return new Redirect('/tasks/search/edit');
        }



        return new Redirect('/tasks');
    }
}
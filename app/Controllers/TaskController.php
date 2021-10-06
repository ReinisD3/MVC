<?php

namespace app\Controllers;

use App\Controllers\Repository\CsvDb;
use App\Models\Task;

class TaskController
{

    public function show(): void
    {

        $taskCollection = (new CsvDb('Db/db.csv'))->loadTasks();


        require_once 'app/Views/tasks.html';

    }

    public function add(): void
    {
        $addTask = new Task((int)$_POST['id'], $_POST['description']);
        (new CsvDb('Db/db.csv'))->add($addTask);

        self:$this->show();


    }

    public function searched(): void
    {

        $id = $_GET['searchId'];
        $searchedTask = (new CsvDb('Db/db.csv'))->searchById($id);

        require_once 'app/Views/searchedTask.html';

    }
    public function delete():void
    {
        $taskId = $_POST['id'];
        $searchedTask = (new CsvDb('Db/db.csv'))->searchById($taskId);
        (new CsvDb('Db/db.csv'))->delete($searchedTask);

        header('Location:/tasks');



    }
}
<?php

namespace app\Repository;

use app\Models\Task;
use app\Models\Collections\TaskCollection;

interface TasksInterface
{

    public function allTasks():?TaskCollection;

    public function addOneTask(Task $task): void;

    public function searchById(string $id): ?Task;

    public function delete(Task $task): void;

}
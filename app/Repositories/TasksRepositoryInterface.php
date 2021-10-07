<?php

namespace app\Repository;

use app\Models\Task;
use app\Models\Collections\TaskCollection;

interface TasksRepositoryInterface
{

    public function allTasks():?TaskCollection;

    public function addOne(Task $task): void;

    public function searchById(string $id): ?Task;

    public function deleteOne(Task $task): void;

}
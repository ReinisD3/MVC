<?php

namespace app\Repositories;

use app\Models\Task;
use app\Models\Collections\TaskCollection;

interface TasksRepositoryInterface
{

    public function getRecords():?TaskCollection;

    public function save(Task $task): void;

    public function searchById(string $id): ?Task;

    public function deleteOne(Task $task): void;

}
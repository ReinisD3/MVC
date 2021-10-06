<?php

namespace app\Controllers\Repository;

use app\Models\Task;
use app\Models\Collections\TaskCollection;

interface DbInterface
{

    public function loadTasks():?TaskCollection;

    public function add(Task $task): void;

    public function searchById(string $id): ?Task;

    public function delete(Task $task): void;

}
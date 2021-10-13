<?php

namespace app\Models\Collections;

use app\Models\Task;

class TaskCollection
{
    private array $tasks = [];

    public function __construct(array $tasks = [])
    {
        foreach ($tasks as $task) {
            $this->add($task);
        }
    }

    public function add(Task $task): void
    {
        $this->tasks[] = $task;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

}
<?php

namespace app\Models\Collections;

use app\Models\Task;

class TaskCollection
{
    private array $taskCollection = [];

    public function __construct(array $tasks = [])
    {
        foreach ($tasks as $task) {
            $this->add($task);
        }
    }

    public function add(Task $task): void
    {
        $this->taskCollection[] = $task;
    }

    public function taskCollection(): array
    {
        return $this->taskCollection;
    }

}
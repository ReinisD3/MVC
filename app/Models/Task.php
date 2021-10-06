<?php

namespace app\Models;

class Task
{
    private string $id;
    private string $description;

    public function __construct(string $id, string $description)
    {
        $this->id = $id;
        $this->description = $description;

    }

    public function id(): string
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }
    public function toArray(Task $task):array
    {
        return [$task->id(),$task->description()];
    }

}
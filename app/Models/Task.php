<?php

namespace app\Models;

use Carbon\Carbon;

class Task
{
    private ?string $id;
    private string $title;
    private ?string $createdAt;

    public function __construct( string $title,?string $createdAt = null, ?string $id = null )
    {
        $this->id = $id;
        $this->title = $title;
        $this->createdAt = $createdAt ?? Carbon::now();
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }
    public function createdAt(): ?string
    {
        return $this->createdAt;
    }
    public function toArray(Task $task):array
    {
        return [$task->id(),$task->title(),$task->createdAt()];
    }


}
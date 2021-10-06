<?php

namespace app\Controllers;

use App\Repository\CsvTasksRepository;
use App\Repository\SQLRepository;
use app\Repository\TasksInterface;

abstract class RepositoryController
{
    protected TasksInterface $repository;

    public function __construct()
    {
        $this->repository = new SQLRepository();
    }
}
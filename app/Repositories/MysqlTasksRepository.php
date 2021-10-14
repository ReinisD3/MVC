<?php

namespace app\Repositories;


use App\Repositories\TasksRepositoryInterface;
use App\Models\Collections\TaskCollection;
use App\Models\Task;
use PDO;

class MysqlTasksRepository implements TasksRepositoryInterface
{
    private array $config;
    private PDO $pdo;

    public function __construct()
    {
        $this->config = json_decode(file_get_contents('config.json'), true);

        $this->pdo = new PDO($this->config['connection'] . ';dbname=' . $this->config['name'],
            $this->config['username'],
            $this->config['password']);


    }

    public function getRecords(): TaskCollection
    {
        $statement = $this->pdo->prepare("select * from tasks");

        $statement->execute();

        $tasks = $statement->fetchALl(PDO::FETCH_CLASS);
        $taskCollection = new TaskCollection();
        foreach ($tasks as $task) {
            $taskCollection->add(new Task(
                $task->title,
                $task->createdAt,
                $task->id
            ));
        }
        return $taskCollection;
    }

    public function addOne(Task $task): void
    {
        /** @var Task $task */
        $sql = "INSERT INTO tasks (title, createdAt) 
        VALUES ('{$task->title()}', '{$task->createdAt()}')";
        $this->pdo->exec($sql);

    }

    public function searchById(string $id): ?Task
    {

        $sql = "SELECT * FROM tasks WHERE id={$id}";
        $statement = $this->pdo->prepare($sql);

        $statement->execute();
        $searchedTask = $statement->fetchALl(PDO::FETCH_CLASS);

        if (empty($searchedTask)) {
            return null;
        }
        return new Task(
            $searchedTask[0]->title,
            $searchedTask[0]->createdAt,
            $searchedTask[0]->id
        );

    }

    public function deleteOne(Task $task): void
    {
        $sql = "DELETE FROM tasks WHERE id={$task->id()}";

        $this->pdo->exec($sql);
    }
}
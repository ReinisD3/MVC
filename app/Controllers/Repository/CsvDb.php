<?php

namespace app\Controllers\Repository;

use App\Controllers\Repository\DbInterface;
use App\Models\Collections\TaskCollection;
use App\Models\Task;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class CsvDb implements DbInterface
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function loadTasks(): TaskCollection
    {
        $reader = Reader::createFromPath($this->filename, 'r');
        $reader->setDelimiter(';');
        $stmt = Statement::create();
        $tasks = $stmt->process($reader);

        $taskCollection = new TaskCollection();
        foreach ($tasks as $task) {
            $taskCollection->add(new Task($task[0], $task[1]));
        }
        return $taskCollection;
    }

    public function add(Task $task): void
    {
        $writer = Writer::createFromPath($this->filename, 'a+');
        $writer->setDelimiter(';');
        $writer->insertOne($task->toArray($task));
    }

    public function searchById(string $id): ?Task
    {
        $reader = Reader::createFromPath($this->filename, 'r');
        $reader->setDelimiter(';');

        $stmt = (new Statement())
            ->where(function (array $task) use ($id) {
                if ($task[0] === $id) return $task;
            })
            ->limit(1);

        $task = $stmt->process($reader)->fetchOne();

        return empty($task) ? null : new Task(
            $task[0],
            $task[1]
        );

    }
    public function delete(Task $task): void
    {
        $reader = Reader::createFromPath($this->filename, 'r');
        $reader->setDelimiter(';');

        $stmt = (new Statement())
            ->where(function (array $tasks) use ($task) {
                if ($tasks[0] !== $task->id())
                    return $task;
            });


        $tasks = $stmt->process($reader);

        $copy = json_decode(json_encode($tasks), true);

        $writer = Writer::createFromPath($this->filename, 'w+');
        $writer->setDelimiter(';');
        $writer->insertall($copy);

    }
}
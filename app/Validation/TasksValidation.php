<?php

namespace App\Validation;

use App\Exceptions\Errors;
use App\Exceptions\FormValidationException;
use App\Exceptions\RepositoryValidationException;
use App\Models\Task;

class TasksValidation
{
    private Errors $errors;

    public function __construct()
    {
        $this->errors = new Errors();
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }

    /**
     * @throws FormValidationException
     */
    public function validateAdd(array $data): void
    {
        if ($data['title'] == '') {
            $this->errors->add('taskTitle', 'Add title name here');
        }

        if (count($this->errors->all()) > 0) {
            throw new FormValidationException();
        }
    }

    public function validateSearch(?Task $task): void
    {
        if (empty($task)) {
            $this->errors->add('id', "No tasks for id found");
        }
        if (count($this->errors->all()) > 0) {
            throw new RepositoryValidationException();
        }
    }
}

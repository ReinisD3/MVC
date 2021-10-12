<?php

namespace App\Validation;

use App\Exceptions\FormValidationException;

class TasksValidation
{
    private array $errors = [];

    public function getErrors():array
    {
        return $this->errors;
    }

    /**
     * @throws FormValidationException
     */
    public  function validate(array $data):void
    {
        if($data['title'] == '') $this->errors['taskTitle'] = 'Add title name here';

        if(count($this->errors) > 0) throw new FormValidationException();
    }
}

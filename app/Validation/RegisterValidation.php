<?php

namespace App\Validation;

use App\Exceptions\FormValidationException;
use Respect\Validation\Validator as v;

class RegisterValidation
{
    private array $errors = [];

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @throws FormValidationException
     */
    public  function validate(array $data):void
    {
        if($data['name'] == '') $this->errors['registerName'] = 'Add name here';

        if(!v::stringType()->length(6,null)->validate($data['password']))
            $this->errors['registerPassword'] = 'Need at least 6 characters ';
        if(substr_count($data['password'],' ') > 0)
            $this->errors['registerPassword'] = 'No spaces allowed ';
        if(!v::email()->validate($data['email']))
            $this->errors['registerEmail'] = 'Wrong email input';

        if(count($this->errors) > 0) throw new FormValidationException();
    }
}

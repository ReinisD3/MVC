<?php

namespace App\Models;

class User
{


    private string $name;
    private string $password;
    private string $email;
    private ?string $id;

    public function __construct(string $name, string $email, string $password, ?string $id = null )
    {
        $this->name = $name;
        $this->password = password_hash($password,PASSWORD_BCRYPT);
        $this->email = $email;
        $this->id = $id;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function name(): string
    {
        return $this->name;
    }
    public function id():string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

}
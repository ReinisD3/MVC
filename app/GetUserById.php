<?php

namespace App;
use App\Models\User;
use App\Repositories\MysqlUsersRepository;

class GetUserById
{
    public static function find(string $id) : ?User
    {
        $database = new MysqlUsersRepository();
        return $database->getById($id);

    }
}

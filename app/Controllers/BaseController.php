<?php

namespace App\Controllers;

use App\Repositories\MysqlUsersRepository;

abstract class BaseController
{
    public function getUserName(?string $id):?string
    {
        $rep = new MysqlUsersRepository();
        return $id == null ? null : ($rep->getById($id))->name();

    }
}
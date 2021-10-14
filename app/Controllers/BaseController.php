<?php

namespace App\Controllers;

use App\Repositories\MysqlUsersRepository;

abstract class BaseController
{
    private MysqlUsersRepository $rep;
    public function __construct()
    {
        $this->rep = new MysqlUsersRepository();
    }

    public function getUserName(?string $id):?string
    {

        return $id == null ? null : ($this->rep->getById($id))->name();

    }
}
<?php

namespace App\Repositories;

use app\Models\Collections\UsersCollection;

interface UsersRepositoryInterface
{
    public function getRecords():UsersCollection;
}
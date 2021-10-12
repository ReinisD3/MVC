<?php

namespace App\Repositories;

use app\Models\Collections\UsersCollection;
use App\Models\User;

interface UsersRepositoryInterface
{
    public function getRecords():UsersCollection;
    public function validateLogin(string $email, string $password):?User;
    public function add(User $user):void;
}
<?php

namespace app\Controllers;

use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepositoryInterface;
use App\Models\User;

class UsersController
{
    private UsersRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = new MysqlUsersRepository();
    }

    public function index(): void
    {
        $usersCollection = $this->repository->getRecords();

        require_once 'app/Views/Users/index.html';

    }

    public function login(): void
    {
        require_once 'app/Views/Users/login.html';

//
//        function test_input($data) {
//            $data = trim($data);
//            $data = stripslashes($data);
//            $data = htmlspecialchars($data);
//            return $data;
//        }
    }
    public function logout():void
    {
        $_SESSION["loggedIn"] = false;
        header('Location:/');

    }

    public function validateLogin(): void
    {
        $email = trim($_GET['email']);
        $password = trim($_GET['password']);
        $loggedUser = $this->repository->validateLogin($email, $password);
        if (!empty($loggedUser)) {
            $_SESSION["loggedIn"] = true;
            $_SESSION["userName"] = $loggedUser->name();
        } else {
            echo "<script type='text/javascript'>alert('User not found! Try Again or make new User account.');</script>";
            $_SESSION["loggedIn"] = false;
        }
        require_once 'app/Views/index.html';
    }


    public function registerTemplate(): void
    {
        require_once 'app/Views/Users/register.html';
    }

    public function register(): void
    {
        $newUser = new User(
            trim($_POST['name']),
            trim($_POST['email']),
            trim($_POST['password'])
        );
        $this->repository->add($newUser);

        header('Location:/');
    }
}
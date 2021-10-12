<?php

namespace app\Controllers;

use App\Exceptions\FormValidationException;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepositoryInterface;
use App\Models\User;
use App\Validation\RegisterValidation;
use App\InputProcesses\TestInput;
use App\GetUserById;

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


    }
    public function logout():void
    {
        $_SESSION["loggedIn"] = false;
        header('Location:/');

    }

    public function validateLogin(): void
    {
        $email = TestInput::test_input($_GET['email']);
        $password = TestInput::test_input($_GET['password']);
        $loggedUser = $this->repository->validateLogin($email, $password);
        if (!empty($loggedUser)) {
            $_SESSION["loggedIn"] = true;
            $_SESSION["id"] = $loggedUser->id();
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
        try {
          $register = new RegisterValidation();
          $register->validate($_POST);

            $this->repository->add(new User(
                TestInput::test_input($_POST['name']),
                TestInput::test_input($_POST['email']),
                TestInput::test_input($_POST['password'])
            ));

            header('Location:/');
        }
        catch (FormValidationException $e)
        {

            $_SESSION['_errors'] = $register->getErrors();
            header('Location:/users/register');

        }



    }
}
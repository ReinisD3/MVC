<?php

namespace app\Controllers;

use App\Exceptions\FormValidationException;
use App\Exceptions\RepositoryValidationException;
use App\Models\Redirect;
use App\Models\View;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepositoryInterface;
use App\Models\User;
use App\Validation\UsersValidation;
use App\Validation\Input\Process;


class UsersController
{
    private UsersRepositoryInterface $repository;
    private UsersValidation $validator;

    public function __construct()
    {
        $this->repository = new MysqlUsersRepository();
        $this->validator = new UsersValidation();
    }

    public function index(): View
    {
        return new View('Users/login.twig');

    }

    public function logout(): Redirect
    {
        unset($_SESSION['id']);
        unset($GLOBALS['userLogged']);
        return new Redirect('/');

    }

    public function login(): Redirect
    {
        $loggedUser = $this->repository->validateLogin(
            Process::input($_GET['email']),
            Process::input($_GET['password']
            ));
        try {
            $this->validator->validateLogin($loggedUser);
            $_SESSION['id'] = $loggedUser->id();

        } catch (RepositoryValidationException $e) {

            echo "<script type='text/javascript'>alert('User not found! Try Again or make new User account.');</script>";
        }
        return new Redirect('/');
    }


    public function register(): View
    {
        return new View('Users\register.twig');
    }

    public function registerExecute(): Redirect
    {
        try {
            $this->validator->validateRegister($_POST);
            $this->repository->add(new User(
                Process::input($_POST['name']),
                Process::input($_POST['email']),
                Process::input($_POST['password'])
            ));
            echo "<script type='text/javascript'>alert('User registered');</script>";
            return new Redirect('/users/index');
        }
        catch (FormValidationException $e)
        {
            $_SESSION['errors'] = $this->validator->getErrors();
            return new Redirect('/users/register');
        }

    }
}
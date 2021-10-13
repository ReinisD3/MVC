<?php

namespace app\Controllers;

use App\Exceptions\FormValidationException;
use App\Models\Redirect;
use App\Models\View;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepositoryInterface;
use App\Models\User;
use App\Validation\UsersValidation;
use App\Input\Process;


class UsersController extends BaseController
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
        $email = Process::input($_GET['email']);
        $password = Process::input($_GET['password']);
        $loggedUser = $this->repository->validateLogin($email, $password);
        try {
            $this->validator->validateLogin($loggedUser);
            $_SESSION['id'] = $loggedUser->id();

        } catch (FormValidationException $e) {

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
        } catch (FormValidationException $e) {
            $_SESSION['errors'] = $this->validator->getErrors();
            return new Redirect('/users/register');
        }


    }
}
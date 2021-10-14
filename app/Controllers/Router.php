<?php

namespace app\Controllers;

use App\Models\Redirect;
use App\Models\View;
use FastRoute;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use App\Controllers\BaseController;

class Router extends BaseController
{
    private FastRoute\Dispatcher $dispatcher;
    private Environment $twig;

    public function __construct()
    {
        parent::__construct();
        $loader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);


        $this->dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', 'IndexController@index');

            $r->addRoute('GET', '/users/index', 'UsersController@index');
            $r->addRoute('GET', '/users/logout', 'UsersController@logout');
            $r->addRoute('GET', '/users/login', 'UsersController@login');
            $r->addRoute('GET', '/users/register', 'UsersController@register');
            $r->addRoute('POST', '/users/register', 'UsersController@registerExecute');

            $r->addRoute('GET', '/tasks', 'TasksController@index');
            $r->addRoute('POST', '/tasks', 'TasksController@add');
            $r->addRoute('GET', '/tasks/search', 'TasksController@search');
            $r->addRoute('POST', '/tasks/search/delete', 'TasksController@delete');

        });
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function start(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                [$handler, $method] = explode('@', $handler);

                $controller = 'App\Controllers\\' . $handler;
                $controller = new $controller();
                $process = $controller->$method();

                if ($process instanceof View) {
                    echo $this->twig->render($process->getFileName(), [
                        'userName' => $this->getUserName($_SESSION['id']),
                        $process->getDataName() => $process->getData()
                    ]);
                }
                if ($process instanceof Redirect) {
                    header("Location:{$process->getLocation()}");
                    exit;
                }
                break;
        }
    }

}
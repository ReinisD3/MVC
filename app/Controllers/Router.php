<?php

namespace app\Controllers;

use FastRoute;

class Router
{

    public static function start():void
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r)
        {
            $r->addRoute('GET', '/', 'IndexController@index');

            $r->addRoute('GET', '/users/login', 'UsersController@login');
            $r->addRoute('GET', '/users/logout', 'UsersController@logout');
            $r->addRoute('GET', '/users/login/validate', 'UsersController@validateLogin');
            $r->addRoute('GET', '/users/register', 'UsersController@registerTemplate');
            $r->addRoute('POST', '/users/register', 'UsersController@register');

            $r->addRoute('GET', '/tasks', 'TasksController@index');
            $r->addRoute('POST', '/tasks', 'TasksController@add');
            $r->addRoute('GET', '/tasks/search', 'TasksController@search');
            $r->addRoute('POST', '/tasks/search/delete', 'TasksController@delete');

        });

// Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?'))        {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars


                [$handler, $method] = explode('@', $handler);
                $controller = 'App\Controllers\\' . $handler;
                $controller = new $controller();
                $controller->$method();
//

                break;
        }
    }


}
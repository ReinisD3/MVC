<?php

namespace app\Controllers;

//require_once 'vendor/autoload.php';
use FastRoute;

class Router
{

    public static function start():void
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r)
        {
            $r->addRoute('GET', '/', 'TaskController@show');
            $r->addRoute('GET', '/tasks', 'TaskController@show');
            $r->addRoute('POST', '/tasks', 'TaskController@add');
            $r->addRoute('GET', '/tasks/searched', 'TaskController@searched');
            $r->addRoute('POST', '/tasks/searched/delete', 'TaskController@delete');

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
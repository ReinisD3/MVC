<?php

require_once 'vendor/autoload.php';

use App\Controllers\Router;


session_start();

$router = new Router();
$router->start();

unset($_SESSION['errors']);





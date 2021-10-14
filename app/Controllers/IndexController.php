<?php

namespace App\Controllers;

use App\View;

class IndexController
{
    public function index(): View
    {
        return new View('index.twig');
    }
}
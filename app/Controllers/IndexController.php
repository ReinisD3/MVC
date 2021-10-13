<?php

namespace App\Controllers;

use App\Models\View;

class IndexController extends BaseController
{
    public function index(): View
    {
        return new View('index.twig');
    }
}
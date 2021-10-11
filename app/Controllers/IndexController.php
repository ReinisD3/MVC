<?php

namespace App\Controllers;

class IndexController
{
    public function index():void
    {
        require_once 'app/Views/index.html';
    }
}
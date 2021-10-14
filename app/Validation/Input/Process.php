<?php

namespace App\Validation\Input;

class Process
{

    public static function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;

    }
}

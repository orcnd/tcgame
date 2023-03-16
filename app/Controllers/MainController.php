<?php
namespace App\Controllers;

use App\Kernel\ForceLogin;

class MainController
{
    public function index()
    {
        view('home');
    }

    
}

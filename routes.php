<?php
namespace App;
use App\Kernel\Routes;

Routes::set('/', 'GET', function () {
    echo 'Hello World';
});

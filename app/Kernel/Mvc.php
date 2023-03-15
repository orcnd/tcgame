<?php
namespace App\Kernel;
class Mvc
{
    function __construct()
    {
        @session_start();
        $this->controller_init();
    }
    private function c404()
    {
        header('HTTP/1.0 404 Not Found');
    }

    private function controller_init()
    {
        $callback = Routes::get(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD']
        );
        if ($callback !== false) {
            call_user_func($callback);
        } else {
            $this->c404();
        }
    }
}

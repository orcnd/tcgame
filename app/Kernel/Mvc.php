<?php
namespace App\Kernel;
class Mvc
{
    function __construct()
    {
        @session_start();
        $this->controller_init();
    }

    /** controller init */
    private function controller_init() : void
    {
        $status = Route::run(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD']
        );
        if ($status === false) {
            show_404();
        }
    }
}

trait ForceLogin
{
    public function __construct()
    {
        if (!Auth::check()) {
            redirect('/login');
        }
    }
}
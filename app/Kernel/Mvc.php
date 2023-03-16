<?php
namespace App\Kernel;
class Mvc
{
    function __construct()
    {
        @session_start();
        $this->controller_init();
    }

    /**
     * 404
     *
     * @return void
     */
    private function c404()
    {
        show_404();
    }

    /**
     * controller init
     *
     * @return void
     */
    private function controller_init()
    {
        $callback = Route::get(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD']
        );
        if ($callback !== false) {
            if (is_array($callback) && count($callback) == 2) {
                $controller = new $callback[0]();
                $callback = [$controller, $callback[1]];
            }

            call_user_func($callback);
        } else {
            $this->c404();
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

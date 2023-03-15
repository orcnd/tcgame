<?php
namespace App\Kernel;
class Routes
{
    private static $routes = [];

    /**
     * setting route
     *
     * @param string $route route name
     * @param string $method http method
     * @param callable $callback callback function
     *
     * @return void
     */
    public static function set(
        string $route,
        string $method,
        callable $callback
    ) {
        $method = strtoupper(trim($method));

        self::$routes[] = [
            'route' => $route,
            'method' => $method,
            'callback' => $callback,
        ];
    }

    /**
     * get route
     *
     * @param string $route route name
     * @param string $method http method
     *
     * @return callable|bool
     */
    public static function get($route, $method)
    {
        $route = trim($route);
        $method = strtoupper(trim($method));
        foreach (self::$routes as $theRoute) {
            if (
                $theRoute['route'] == $route &&
                $theRoute['method'] == $method
            ) {
                return $theRoute['callback'];
            }
        }
        return false;
    }
}

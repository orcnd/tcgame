<?php
namespace App\Kernel;
class Route
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
        callable|array $callback
    ) : void {
        $method = strtoupper(trim($method));

        self::$routes[] = [
            'route' => $route,
            'method' => $method,
            'callback' => $callback,
        ];
    }

    /**
     * run route
     *
     * @param string $route route name
     * @param string $method http method
     *
     * @return bool
     */
    public static function run($route, $method) : bool
    {
        $route = trim($route);
        $method = strtoupper(trim($method));
        foreach (self::$routes as $theRoute) {

            //kudos to https://stackoverflow.com/questions/11722711/url-routing-regex-php/11723153#11723153
            // convert urls like '/users/:uid/posts/:pid' to regular expression
            $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($theRoute['route'])) . "$@D";
            $matches = [];
            // check if the current request matches the expression
            if($method == $theRoute['method'] && preg_match($pattern, $route, $matches)) {
                // remove the first match
                array_shift($matches);

                //csrf token check
                if ($method == 'POST') {
                    if (
                        isset($_SERVER['HTTP_X-CSRF-TOKEN']) ||
                        isset($_POST['_token'])
                    ) {
                        $token = isset($_SERVER['HTTP_X-CSRF-TOKEN'])
                            ? $_SERVER['HTTP_X-CSRF-TOKEN']
                            : $_POST['_token'];
                        if (csrf_token($token) === false) {
                            show_400('Bad Request Token');
                        }
                    }
                }

                // call the callback with the matched positions as params
                $callback=$theRoute['callback'];
                if (is_array($callback) && count($callback) == 2) {
                    $controller = new $callback[0]();
                    $callback = [$controller, $callback[1]];
                }

                call_user_func($callback, $matches);
                return true;
            }
        }        
        return false;
    }

    /**
     * get route
     *
     * @param string $route route name
     * @param string $method http method
     *
     * @return callable|bool
     */
    public static function get($route, $method) : bool|callable
    {
        $route = trim($route);
        $method = strtoupper(trim($method));
        foreach (self::$routes as $theRoute) {

            //kudos to https://stackoverflow.com/questions/11722711/url-routing-regex-php/11723153#11723153
            // convert urls like '/users/:uid/posts/:pid' to regular expression
            $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route['url'])) . "$@D";
            $matches = [];
            // check if the current request matches the expression
            if($method == $route['method'] && preg_match($pattern, $route, $matches)) {
                // remove the first match
                array_shift($matches);
                // call the callback with the matched positions as params
                return call_user_func_array($route['callback'], $matches);
            }
            

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

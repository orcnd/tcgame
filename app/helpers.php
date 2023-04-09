<?php
if (!function_exists('view')) {
    function view($adr, $vars = [], $print = true)
    {
        extract($vars);
        ob_start();

        $viewFile = __DIR__ . '/Views/' . $adr . '.php';

        if (!file_exists($viewFile)) {
            show_404(true);
        }

        include $viewFile;
        $vr = ob_get_contents();
        ob_end_clean();
        if (!$print) {
            return $vr;
        } else {
            echo $vr;
            return true;
        }
    }
}


if (!function_exists('auth')) {
    /**
     * auth
     *
     * @return \App\Kernel\Auth
     */
    function auth()
    {
        return new \App\Kernel\Auth();
    }
}

if (!function_exists('odump')) {
    /**
     * dumping data with style
     */
    function odump($v)
    {
        echo '<pre>';
        var_dump($v);
        echo '</pre>';
    }
}

if (!function_exists('show_404')) {
    /**
     * shows 404 page
     *
     * @param bool $basicMode if true, it will not use the view
     */
    function show_404($basicMode = false)
    {
        header('HTTP/1.0 404 Not Found');
        // this provides a basic 404 page for the case that the view is not found
        if ($basicMode === false) {
            view('404');
        }
        exit();
    }
}

if (!function_exists('show_400')) {
    /**
     * show 400 page
     *
     * @param string|null $detail
     */
    function show_400($detail = null)
    {
        header('HTTP/1.0 400 Bad Request');
        if ($detail !== null) {
            echo $detail;
        }
        exit();
    }
}

if (!function_exists('redirect')) {
    /**
     * redirect
     */
    function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }
}

if (!function_exists('ForceLogin')) {
    /**
     * force user to login
     */
    function ForceLogin()
    {
        if (!auth()->check()) {
            redirect('/login');
        }
    }
}


if (!function_exists('availableUserNames')) {
    function availableUserNames()
    {
        return explode(',', trim('A,B,C,D,Tom,Kaan,Joe,Martin,Nurdan,Gokhan'));
    }
}


if (!function_exists('old')) {
    /**
     * returns old value of post
     */
    function old($key)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return '';
    }
}


if (!function_exists('csrf_token')) {
    /**
     * returns csrf token
     *
     * @param string|null $controlToken if not null, it will be compared with the session token
     *
     * @return string|boolean
     */
    function csrf_token($controlToken = null)
    {
        @session_start();
        $tokenToBe = md5(session_id() . 'token');
        if ($controlToken !== null) {
            if ($controlToken === $tokenToBe) {
                return true;
            }
            return false;
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = $tokenToBe;
        }
        return $_SESSION['csrf_token'];
    }
}


if (!function_exists('input_csrf_token')) {
    /**
     * returns csrf token input
     *
     * @param bool $return if true, it will return the input, if false, it will echo the input
     *
     * @return string
     */
    function input_csrf_token($return = false): string
    {
        if ($return === true) {
            return '<input type="hidden" name="_token" value="' .
                csrf_token() .
                '">';
        } else {
            echo '<input type="hidden" name="_token" value="' . csrf_token() . '">';
            return '';
        }
    }
}


if (!function_exists('json')) {
    /**
     * returns json
     */
    function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}

if (!function_exists('betterDate')) {
    /**
     * return human readable eu date
     *
     * @param string|null $time
     */
    function betterDate($time = null)
    {
        if ($time === null) {
            $time = time();
        } else {
            $time = strtotime($time);
        }
        return date('d/m/Y H:i:s', $time);
    }
}
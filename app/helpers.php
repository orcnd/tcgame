<?php
function view($adr, $vars = [], $print = true)
{
    extract($vars);
    ob_start();

    $viewFile = __DIR__ . '/views/' . $adr . '.php';

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
/**
 * auth
 *
 * @return \App\Kernel\Auth
 */
function auth()
{
    return new \App\Kernel\Auth();
}

/**
 * dumping data with style
 */
function odump($v)
{
    echo '<pre>';
    var_dump($v);
    echo '</pre>';
}

/**
 * shows 404 page
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

/**
 * redirect
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit();
}

/**
 * force user to login
 */
function ForceLogin()
{
    if (!auth()->check()) {
        redirect('/login');
    }
}

function availableUserNames()
{
    return explode(',', trim('A,B,C,D,Tom,Kaan,Joe,Martin,Nurdan,Gokhan'));
}

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

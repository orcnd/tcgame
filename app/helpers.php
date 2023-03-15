<?php
function view($adr, $vars = [], $print = true)
{
    extract($vars);

    ob_start();
    include __DIR__ . '/views/' . $adr . '.php';
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
 * dumping data with style
 */
function odump($v)
{
    echo '<pre>';
    var_dump($v);
    echo '</pre>';
}

<?php

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';
use App\Init;
$base = new Init();
echo $base->initialize();

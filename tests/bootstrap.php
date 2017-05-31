<?php
/** @var $loader \Composer\Autoload\ClassLoader */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

if (!function_exists('dd')) {
    function dd()
    {
        array_map(function ($x) {
            print_r($x);
        }, func_get_args());
        die;
    }
}

<?php
/** @var $loader \Composer\Autoload\ClassLoader */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

function dd() {
    array_map(function($x) { var_dump($x); }, func_get_args());
    die;
}
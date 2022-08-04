<?php

if (!isset($argv[1])) {
    exit('参数不足');
}

include_once __DIR__ . '/src/autoload.php';

$c = '\\Biuio\\Meter\\test\\' . $argv[1];
$class = new $c();
$class->start();

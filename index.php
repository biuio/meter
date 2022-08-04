<?php

if (!isset($argv[1])) {
    exit('参数不足');
}

include_once __DIR__ . '/src/test/autoload.php';

$c = '\\meter645\\test\\' . $argv[1];
$class = new $c();
$class->start();

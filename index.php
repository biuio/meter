<?php

if (!isset($argv[1])) {
    exit('参数不足');
}

include_once './autoload.php';

$c = '\\app\\' . $argv[1];
$class = new $c();
$class->start();

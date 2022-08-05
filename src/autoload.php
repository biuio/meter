<?php


spl_autoload_register(function ($class) {
    $file = (__DIR__) . '/' . substr(str_replace('\\', '/', $class), 12) . '.php';
    if (!file_exists($file)) {
        throw new Exception('类文件不存在：' . $file);
    }
    include_once $file;
});

<?php

spl_autoload_register(function (string $class) {
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . "\\" . $class;
    $fullFile = str_replace("\\",DIRECTORY_SEPARATOR, $fullPath) .".php";
    if(file_exists($fullFile)) {
        require_once $fullFile;
    }
});
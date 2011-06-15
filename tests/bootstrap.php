<?php

spl_autoload_register(function($class)
{
    if (strpos($class, 'Structr\\Test\\') === 0) {
        $file = __DIR__ . '/../tests/'
                . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    } elseif (strpos($class, 'Structr\\') === 0) {
        $file = __DIR__ . '/../src/'
                . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
});

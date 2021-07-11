<?php

namespace RoutesXeki;

require __DIR__ . '/functions.php';

spl_autoload_register(function ($class) {
    if (strpos($class, 'RoutesXeki\\') === 0) {
        $name = substr($class, strlen('RoutesXeki'));
        require __DIR__ . strtr($name, '\\', DIRECTORY_SEPARATOR) . '.php';
    }
});

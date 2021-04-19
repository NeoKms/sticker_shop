<?php
require_once 'lib/Twig/Autoloader.php';
require_once 'lib/vendor/autoload.php';
Twig_Autoloader::register();

spl_autoload_register("autoload");

function autoload($className)
{
    $dirs = [
        'controller',
        'data/migrate',
        'lib',
        'lib/smarty',
        'lib/commands',
        'model/'
    ];
    $found = false;
    foreach ($dirs as $dir) {
        $fileName = __DIR__ . '/' . $dir . '/' . $className . '.class.php';
        if (is_file($fileName)) {
            require_once($fileName);
            $found = true;
        }
    }
    if (!$found) {
        return false;
    }
    return true;
}

<?php
spl_autoload_register(function ($class)
{
    if (!substr_count($class, 'jteam')) {
        return false;
    }

    $class = str_replace('jteam\\', '', $class);
    $class = str_replace('\\', '/', $class);

    $pluginPath = dirname(__FILE__).'/';

    $file = $pluginPath.$class.'.php';

    if (!file_exists($file)) {
        throw new Exception($file .' is not exists');
    }

    require_once $file;
});
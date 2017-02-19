<?php
/**
 * Created by PhpStorm.
 * User: Joyouslife
 * Date: 09.02.2017
 * Time: 3:19
 */

use jteam\core\App as App;

try {
    require_once 'autoloader.php';
    $config = include_once 'config.php';
    $app = new App($config, dirname(__FILE__).'/plugin.php');
} catch (Exception $e) {
    echo $e->getMessage();
}
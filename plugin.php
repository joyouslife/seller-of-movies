<?php
/**
 * Plugin Name: Seller of Movies
 * Version: 1.0.0
 * Author: Alexandr Kucheriavyi
 * Author URI: http://kucheriavyi.net/
 */

use jteam\core\App as App;

try {
    require_once 'autoloader.php';
    $config = include_once 'config.php';
    $app = new App($config, __FILE__);
} catch (Exception $e) {
    echo $e->getMessage();
}
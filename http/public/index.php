<?php
/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @name        index .php
 * @version     0.1
 * @created     2015/12/18 12:49
 */

/**
 * Zend Developer Tools
 * If server version of PHP is lower than 5.4.0 add
 */
define('REQUEST_MICROTIME', microtime(true));
$dir = __DIR__;
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

/**
 * Decline static file requests back to the PHP built-in webserver
 */
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

require 'init_autoloader.php';

chdir($dir);
session_start();
Zend\Mvc\Application::init(require '../config/application.config.php')->run();
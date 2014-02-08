<?php

$app_start_time = microtime(true);

// Setting up error reporting and display errors for the startup process
error_reporting(-1);
ini_set('display_errors', 1);

// Set default timezone to avoid timezone warnings
date_default_timezone_set('Europe/Paris');

// Server resources
ini_set('memory_limit', '128M');
ini_set('max_execution_time', 60);
ini_set('post_max_size', '2M');
ini_set('upload_max_filesize', '2M');

// Encoding
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

$config = include 'drumbs/config.php';
$autoload_file = $paths['vendor'] . 'autoload.php';


if (!file_exists($autoload_file)) {
    die('Please run "composer install" first.');
} else {
    /* @var $autoload \Composer\Autoload\ClassLoader */
    $autoload = include $autoload_file;
    $autoload->register();

    /* @var $drumbs \Drumbs */

    $drumbs = new Drumbs($config);

    if (preg_match(Drumbs::REGEX_DEFAULT, $drumbs->request()->path) or preg_match(Drumbs::REGEX_FILTERS, $drumbs->request()->path)) {
        $drumbs->process($drumbs->request()->path);
    } else {
        $drumbs->response()->send404();
    }

    $drumbs->start();
}
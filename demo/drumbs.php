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

require '../vendor/autoload.php';

$config = include '../config.sample.php';
$config['public_path'] = realpath(__DIR__);

$drumbs = new \Mjolnic\Drumbs\Server($config);

try {
    $drumbs->process();
} catch (Exception $exc) {
    error_log($exc->getTraceAsString());
    $drumbs->halt();
}
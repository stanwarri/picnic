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

$actions = new Mjolnic\Drumbs\Actions();
$config = array(
    // Public path were the drumbs.php file is hosted
    'public_path' => realpath(__DIR__),
    // Regular expression of allowed original paths where images can be manipulated
    'path_mask' => '/.*/',
    // Allowed task names (or folders) and their actions (array keys are used inside a preg_match)
    'allowed_tasks' => array(
        // Commented for security reasons. Here are some demos:
        "th_bw" => array($actions, 'grayscale'),
        "th_150_150_ri" => array($actions, 'resizeInside'),
        "th_150_150_ro" => array($actions, 'resizeOutside'),
        "th_150_150_(FFFFFF|000000)_cn" => array($actions, 'resizeContainCentered'),
        "th_(200_100|400_200|800_400)_cv" => array($actions, 'resizeCoverCentered'),
        "th_0_ac" => array($actions, 'autoCrop'),
    )
);

$drumbs = new \Mjolnic\Drumbs\Server($config);

try {
    $drumbs->process();
} catch (Exception $exc) {
    error_log($exc->getTraceAsString());
    $drumbs->halt();
}
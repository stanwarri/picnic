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

$actionsClass = '\\Mjolnic\\Thumbs\\Actions';
$config = array(
    // Public path were the thumbs.php file is hosted
    'public_path' => realpath(__DIR__),
    // Task prefix regular expression
    'prefix_mask' => 'th-[a-z0-9A-Z]{1,10}', // this is used in laravel route
    // Regular expression of allowed original paths where images can be manipulated
    'path_mask' => '/.*/',
    // Allowed task names (or folders) and their actions (array keys are used inside a preg_match)
    'allowed_tasks' => array(
        // Commented for security reasons. Here are some demos:
        "th-bw" => array($actionsClass, 'grayscale'),
        "th-ri_150_150" => array($actionsClass, 'resizeInside'),
        "th-ro_150_150" => array($actionsClass, 'resizeOutside'),
        "th-cn_150_150(_FFFFFF|_000000|_FF00AA)?" => array($actionsClass, 'resizeContainCentered'),
        "th-cv_(200_100|400_200|800_400)" => array($actionsClass, 'resizeCoverCentered'),
        "th-ac_0" => array($actionsClass, 'autoCrop'),
    )
);

$thumbs = new \Mjolnic\Thumbs\Server($config);

try {
    $thumbs->process();
} catch (Exception $exc) {
    error_log($exc->getTraceAsString());
    $thumbs->halt();
}
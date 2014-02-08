<?php

$paths = array(
    'root' => realpath(dirname(__FILE__) . '/../') . DIRECTORY_SEPARATOR
);
$paths['public'] = $paths['root'];
$paths['app'] = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
$paths['vendor'] = $paths['app'] . 'vendor' . DIRECTORY_SEPARATOR;

return array(
    'paths' => $paths,
    // you can easily define your formats in this json file manually or through external scripts
    'allowed_formats' => json_decode(file_get_contents($paths['app'] . 'formats.json'), true),
    'allowed_paths' => '/.*/',
    'filters' => include 'filters.php'
);
<?php

Route::get('/{path}/{prefix}{task?}/{filename}', function($path, $prefix, $task, $filename) {
    $picnice = new \Thor\Picnice\Server(array(
        'public_path' => Config::get('picnice::public_path'),
        'path_mask' => Config::get('picnice::path_mask'),
        'allowed_tasks' => Config::get('picnice::allowed_tasks')
    ));
    $picnice->process();
})->where(array('path' => trim(Config::get('picnice::path_mask'), '/'),
    'prefix' => Config::get('picnice::prefix_mask'), 'task' => '[a-zA-Z0-9_]+',
    'filename' => '(.*)\.(png|jpg|jpeg|gif)'));

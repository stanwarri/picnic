<?php

Route::get('/{path}/{prefix}{task?}/{filename}', function($path, $prefix, $task, $filename) {
    $postimage = new \Thor\Postimage\Server(array(
        'public_path' => Config::get('postimage::public_path'),
        'path_mask' => Config::get('postimage::path_mask'),
        'allowed_tasks' => Config::get('postimage::allowed_tasks')
    ));
    $postimage->process();
})->where(array('path' => trim(Config::get('postimage::path_mask'), '/'),
    'prefix' => Config::get('postimage::prefix_mask'), 'task' => '[a-zA-Z0-9_]+',
    'filename' => '(.*)\.(png|jpg|jpeg|gif)'));

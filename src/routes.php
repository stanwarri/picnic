<?php

Route::get('/{path}/{prefix}{task?}/{filename}', function($path, $prefix, $task, $filename) {
    $thumbs = new \Mjolnic\Thumbs\Server(array(
        'public_path' => Config::get('thumbs::public_path'),
        'path_mask' => Config::get('thumbs::path_mask'),
        'allowed_tasks' => Config::get('thumbs::allowed_tasks')
    ));
    $thumbs->process();
})->where(array('path' => trim(Config::get('thumbs::path_mask'), '/'),
    'prefix' => Config::get('thumbs::prefix_mask'), 'task' => '[a-zA-Z0-9_]+',
    'filename' => '(.*)\.(png|jpg|jpeg|gif)'));

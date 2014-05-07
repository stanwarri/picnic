<?php

Route::get('/{path}/{prefix}{task?}/{filename}', function($path, $prefix, $task, $filename) {
    $pixilate = new \Thor\Pixilate\Server(array(
        'public_path' => Config::get('pixilate::public_path'),
        'path_mask' => Config::get('pixilate::path_mask'),
        'allowed_tasks' => Config::get('pixilate::allowed_tasks')
    ));
    $pixilate->process();
})->where(array('path' => trim(Config::get('pixilate::path_mask'), '/'),
    'prefix' => Config::get('pixilate::prefix_mask'), 'task' => '[a-zA-Z0-9_]+',
    'filename' => '(.*)\.(png|jpg|jpeg|gif)'));

<?php

Route::get('/{path}/{prefix}{task?}/{filename}', function($path, $prefix, $task, $filename) {
    $picnic = new \Mjolnic\Picnic\Server(array(
        'public_path' => Config::get('picnic::public_path'),
        'path_mask' => Config::get('picnic::path_mask'),
        'allowed_tasks' => Config::get('picnic::allowed_tasks')
    ));
    $picnic->process();
})->where(array('path' => trim(Config::get('picnic::path_mask'), '/'),
    'prefix' => Config::get('picnic::prefix_mask'), 'task' => '[a-zA-Z0-9_]+',
    'filename' => '(.*)\.(png|jpg|jpeg|gif)'));

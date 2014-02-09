<?php

Route::get('/{path}/th_{task}/{filename}', function($path, $task, $filename) {
    $drumbs = new \Mjolnic\Drumbs\Server(array(
        'public_path' => Config::get('drumbs::public_path'),
        'path_mask' => Config::get('drumbs::path_mask'),
        'allowed_tasks' => Config::get('drumbs::allowed_tasks')
    ));
    $drumbs->process();
})->where(array('path' => trim(Config::get('drumbs::path_mask'), '/'), 'task' => '[a-zA-Z0-9_]+', 'filename' => '(.*)\.(png|jpg|jpeg|gif)'));

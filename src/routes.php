<?php

Route::get('/{path}/th_{task}/{filename}', function($path, $task, $filename) {
    $config = Config::get('drumbs');
    var_dump($config);
    var_dump($task);
    var_dump($filename);
    die();
    
    $drumbs = new \Mjolnic\Drumbs\Server($config);
    try {
        $drumbs->process();
    } catch (Exception $exc) {
        error_log($exc->getTraceAsString());
        $drumbs->halt();
    }
})->where(array('path'=>trim(Config::get('drumbs::path_mask'), '/'), 'task' => '[a-zA-Z0-9_]+', 'filename' => '(.*)\.(png|jpg|jpeg|gif)'));

<?php

// Sample configuration file for Laravel 4

$actionsClass = '\\Thor\\Postimage\\Actions';

return array(
    // Public path were the postimage.php file is hosted
    'public_path' => public_path(),
    // Task prefix regular expression
    'prefix_mask' => 'th-[a-z0-9A-Z]{1,10}', // this is used in laravel route
    // Regular expression of allowed original paths where images can be manipulated
    'path_mask' => '/.*/',
    // Allowed task names (or folders) and their actions (array keys are used inside a preg_match)
    'allowed_tasks' => array(
//      Commented for security reasons. Here are some demos:
//        "th-bw" => array($actionsClass, 'grayscale'),
//        "th-ri_150_150" => array($actionsClass, 'resizeInside'),
//        "th-ro_150_150" => array($actionsClass, 'resizeOutside'),
//        "th-cn_150_150(_FFFFFF|_000000|_FF00AA)?" => array($actionsClass, 'resizeContainCentered'),
//        "th-cv_(200_100|400_200|800_400)" => array($actionsClass, 'resizeCoverCentered'),
//        "th-ac_0" => array($actionsClass, 'autoCrop'),
    )
);

<?php

// Sample configuration file

// $actions = new Mjolnic\Drumbs\Actions();

return array(
    // Public path were the drumbs.php file is hosted
    'public_path' => public_path(),
    // Regular expression of allowed original paths where images can be manipulated
    'path_mask' => '/.*/',
    // Allowed task names (or folders) and their actions (array keys are used inside a preg_match)
    'allowed_tasks' => array(
//      Commented for security reasons. Here are some demos:
//        "th_bw" => array($actions, 'grayscale'),
//        "th_150_150_ri" => array($actions, 'resizeInside'),
//        "th_150_150_ro" => array($actions, 'resizeOutside'),
//        "th_150_150_(FFFFFF|000000)_cn" => array($actions, 'resizeContainCentered'),
//        "th_(200_100|400_200|800_400)_cv" => array($actions, 'resizeCoverCentered'),
//        "th_0_ac" => array($actions, 'autoCrop'),
    )
);

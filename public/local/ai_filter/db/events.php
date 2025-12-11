<?php
return [
    [
        'eventname'   => '\core\event\ai_request_prepared',
        'callback'    => '\local_ai_filter\observer::ai_pre_request',
        'includefile' => '/local/ai_filter/classes/observer.php',
        'priority'    => 1000
    ]
];

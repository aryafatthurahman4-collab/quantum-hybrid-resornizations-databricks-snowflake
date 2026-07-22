<?php
return [
    'default' => env('QUEUE_CONNECTION', 'sync'),
    'connections' => [
        'sync' => ['driver' => 'sync'],
        'database' => ['driver' => 'database', 'table' => env('QUEUE_TABLE', 'jobs'), 'queue' => 'default', 'retry_after' => 90],
    ],
];

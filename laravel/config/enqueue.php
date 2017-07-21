<?php

return [
    'client' => [
        'transport' => [
            'default' => 'file://'.realpath(__DIR__.'/../storage/enqueue')
        ],
        'client' => [
              'router_topic'             => 'default',
              'router_queue'             => 'default',
              'default_processor_queue'  => 'default',
        ],
    ],
];
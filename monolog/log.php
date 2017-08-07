<?php

use Enqueue\Fs\FsConnectionFactory;
use Enqueue\Monolog\QueueHandler;
use Monolog\Logger;

require_once __DIR__.'/vendor/autoload.php';

$context = (new FsConnectionFactory('file://'.__DIR__.'/queue'))->createContext();

// create a log channel
$log = new Logger('name');
$log->pushHandler(new QueueHandler($context));

// add records to the log
$log->warning('Foo');
$log->error('Bar');
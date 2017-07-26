<?php

use Monolog\Handler\QueueInteropHandler;
use Monolog\Logger;

require_once __DIR__.'/vendor/autoload.php';

$context = (new \Enqueue\Fs\FsConnectionFactory('file://'.__DIR__.'/queue'))->createContext();

// create a log channel
$log = new Logger('name');
$log->pushHandler(new QueueInteropHandler($context));

// add records to the log
$log->warning('Foo');
$log->error('Bar');
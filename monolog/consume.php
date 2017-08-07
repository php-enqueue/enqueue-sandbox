<?php

use Enqueue\Consumption\QueueConsumer;
use Enqueue\Fs\FsConnectionFactory;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

require_once __DIR__.'/vendor/autoload.php';

$context = (new FsConnectionFactory('file://'.__DIR__.'/queue'))->createContext();

$consumer = new QueueConsumer($context);
$consumer->bind('log', function(PsrMessage $message) {
    echo $message->getBody().PHP_EOL;

    return PsrProcessor::ACK;
});

$consumer->consume();

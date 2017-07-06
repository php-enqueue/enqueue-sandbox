<?php
use Enqueue\Psr\PsrProcessor;

require_once __DIR__.'/vendor/autoload.php';
include __DIR__.'/config.php';

$consumer = $context->createConsumer($eventQueue);

while (true) {
    if ($message = $consumer->receive(5000)) {
        $result = $asyncProcessor->process($message, $context);

        switch ((string) $result) {
            case PsrProcessor::ACK:
                $consumer->acknowledge($message);
                break;
            case PsrProcessor::REJECT:
                $consumer->reject($message);
                break;
            case PsrProcessor::REQUEUE:
                $consumer->reject($message, true);
                break;
            default:
                throw new \LogicException('Result is not supported');
        }
    }
}

<?php
namespace AppBundle\Async;

use Enqueue\Client\TopicSubscriberInterface;
use Enqueue\Consumption\Result;
use Enqueue\Psr\PsrContext;
use Enqueue\Psr\PsrMessage;
use Enqueue\Psr\PsrProcessor;

class SayHelloProcessor implements PsrProcessor, TopicSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        echo "Hello ".$message->getBody()."!\n";

        return Result::ACK;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedTopics()
    {
        return [Topics::SAY_HELLO];
    }
}

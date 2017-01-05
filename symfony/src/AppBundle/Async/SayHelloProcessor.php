<?php
namespace AppBundle\Async;

use Enqueue\Client\TopicSubscriberInterface;
use Enqueue\Consumption\Result;
use Enqueue\Psr\Context;
use Enqueue\Psr\Message;
use Enqueue\Psr\Processor;

class SayHelloProcessor implements Processor, TopicSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(Message $message, Context $context)
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

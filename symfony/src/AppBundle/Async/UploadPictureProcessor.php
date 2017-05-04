<?php
namespace AppBundle\Async;

use Enqueue\Client\TopicSubscriberInterface;
use Enqueue\Psr\PsrContext;
use Enqueue\Psr\PsrMessage;
use Enqueue\Psr\PsrProcessor;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class UploadPictureProcessor implements PsrProcessor, TopicSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        //Process picture upload.
        //$msg will be an instance of `PhpAmqpLib\Message\AMQPMessage` with the $msg->body being the data sent over RabbitMQ.

        $isUploadSuccess = someUploadPictureMethod();
        if (!$isUploadSuccess) {
            // If your image upload failed due to a temporary error you can return false
            // from your callback so the message will be rejected by the consumer and
            // requeued by RabbitMQ.
            // Any other value not equal to false will acknowledge the message and remove it
            // from the queue
            return self::REJECT;
        }

        return self::ACK;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedTopics()
    {
        return [
            'upload_picture' => ['queueName' => 'upload_picture', 'queueNameHardcoded' => true]
        ];
    }
}

function someUploadPictureMethod() { return true; }
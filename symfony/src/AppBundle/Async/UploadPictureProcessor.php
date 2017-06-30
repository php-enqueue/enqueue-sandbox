<?php
namespace AppBundle\Async;

use Enqueue\Client\CommandSubscriberInterface;
use Enqueue\Psr\PsrContext;
use Enqueue\Psr\PsrMessage;
use Enqueue\Psr\PsrProcessor;
use Enqueue\Util\JSON;

class UploadPictureProcessor implements PsrProcessor, CommandSubscriberInterface
{
    public function process(PsrMessage $message, PsrContext $context)
    {
        $isUploadSuccess = someUploadPictureMethod(JSON::decode($message->getBody()));

        if (!$isUploadSuccess) {
            return self::REJECT;
        }

        return self::ACK;
    }

    public static function getSubscribedCommand()
    {
        return [
            'processorName' => 'upload_picture',
            // these are optional, setting these option we make the migration smooth and backward compatible.
            'queueName' => 'upload-picture',
            'queueNameHardcoded' => true,
            'exclusive' => true,
        ];
    }
}

function someUploadPictureMethod() { return true; }
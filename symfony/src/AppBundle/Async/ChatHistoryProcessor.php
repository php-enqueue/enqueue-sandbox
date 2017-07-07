<?php
namespace AppBundle\Async;

use Enqueue\Consumption\QueueSubscriberInterface;
use Enqueue\Consumption\Result;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use Symfony\Component\Filesystem\Filesystem;

class ChatHistoryProcessor implements PsrProcessor, QueueSubscriberInterface
{
    /**
     * @var
     */
    private $storeDir;

    /**
     * @param $storeDir
     */
    public function __construct($storeDir)
    {
        $this->storeDir = $storeDir;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $context)
    {
        $fs = new Filesystem();
        $fs->touch($this->storeDir.'/chat_history');

        return Result::reply($context->createMessage(file_get_contents($this->storeDir.'/chat_history')));
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedQueues()
    {
        return ['chat_request_history'];
    }
}

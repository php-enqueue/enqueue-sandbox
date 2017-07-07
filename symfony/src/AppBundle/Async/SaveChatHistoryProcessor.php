<?php
namespace AppBundle\Async;

use Enqueue\Consumption\QueueSubscriberInterface;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\LockHandler;

class SaveChatHistoryProcessor implements PsrProcessor, QueueSubscriberInterface
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

        $chatMessages = array_slice(file($this->storeDir.'/chat_history', FILE_IGNORE_NEW_LINES), -19, 19);
        $chatMessages[] = trim($message->getBody());

        file_put_contents($this->storeDir.'/chat_history', implode(PHP_EOL, $chatMessages));

        return self::ACK;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedQueues()
    {
        return ['save_chat_history'];
    }
}

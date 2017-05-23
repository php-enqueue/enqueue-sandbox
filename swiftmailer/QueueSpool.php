<?php
namespace Demo\Swiftmailer;

use Enqueue\Psr\ExceptionInterface as PsrException;
use Enqueue\Psr\PsrContext;
use Swift_Mime_SimpleMessage;
use Swift_Transport;

class QueueSpool extends \Swift_ConfigurableSpool
{
    /**
     * @var PsrContext
     */
    private $context;

    /**
     * @param PsrContext $context
     */
    public function __construct(PsrContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function queueMessage(Swift_Mime_SimpleMessage $message)
    {
        try {
            $queue = $this->context->createQueue('swiftmailer_spool');

            $message = $this->context->createMessage(serialize($message));

            $this->context->createProducer()->send($queue, $message);
        } catch (PsrException $e) {
            throw new \Swift_IoException(sprintf('Unable to send message to message queue.'), null, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        $queue = $this->context->createQueue('swiftmailer_spool');
        $consumer = $this->context->createConsumer($queue);

        $isTransportStarted = false;

        $failedRecipients = (array) $failedRecipients;
        $count = 0;
        $time = time();

        while (true) {
            if ($psrMessage = $consumer->receive(1000)) {
                if (false == $isTransportStarted) {
                    $transport->start();
                    $isTransportStarted = true;
                }


                $message = unserialize($psrMessage->getBody());

                $count += $transport->send($message, $failedRecipients);

                $consumer->acknowledge($psrMessage);
            }

            if ($this->getMessageLimit() && $count >= $this->getMessageLimit()) {
                break;
            }

            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                break;
            }
        }

        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return true;
    }
}
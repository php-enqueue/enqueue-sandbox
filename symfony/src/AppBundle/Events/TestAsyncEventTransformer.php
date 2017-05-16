<?php
namespace AppBundle\Events;

use Enqueue\Bundle\Events\EventTransformer;
use Enqueue\Client\Message;
use Enqueue\Psr\PsrMessage;
use Enqueue\Util\JSON;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;

class TestAsyncEventTransformer implements EventTransformer
{
    /**
     * {@inheritdoc}
     *
     * @param GenericEvent $event
     */
    public function toMessage($eventName, Event $event = null)
    {
        if (false == $event instanceof GenericEvent) {
            throw new \LogicException('The event must be instance of GenericEvent.');
        }

        $message = new Message();
        $message->setBody([
            'subject' => $event->getSubject(),
            'arguments' => $event->getArguments()
        ]);

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function toEvent($eventName, PsrMessage $message)
    {
        $data = JSON::decode($message->getBody());

        return new GenericEvent($data['subject'], $data['arguments']);
    }
}
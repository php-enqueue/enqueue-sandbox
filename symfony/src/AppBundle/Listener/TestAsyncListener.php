<?php
namespace AppBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

class TestAsyncListener
{
    public function onEvent(GenericEvent $event)
    {
        var_dump($event);
    }
}
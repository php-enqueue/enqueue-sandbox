<?php
use Symfony\Component\EventDispatcher\GenericEvent;

require_once __DIR__.'/vendor/autoload.php';

include __DIR__.'/config.php';

$dispatcher->dispatch('foo', new GenericEvent('theSubject'));
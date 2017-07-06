<?php
use Enqueue\AsyncEventDispatcher\AsyncListener;
use Enqueue\AsyncEventDispatcher\AsyncProcessor;
use Enqueue\AsyncEventDispatcher\PhpSerializerEventTransformer;
use Enqueue\AsyncEventDispatcher\AsyncEventDispatcher;
use Enqueue\AsyncEventDispatcher\SimpleRegistry;
use Enqueue\Fs\FsConnectionFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

require_once __DIR__.'/vendor/autoload.php';

// it could be any other enqueue/psr-queue compatible context.
$context = (new FsConnectionFactory('file://'.__DIR__.'/queues'))->createContext();
$eventQueue = $context->createQueue('symfony_events');

$registry = new SimpleRegistry(['foo' => 'default'], ['default' => new PhpSerializerEventTransformer($context, true)]);

$asyncListener = new AsyncListener($context, $registry, $eventQueue);

$dispatcher = new EventDispatcher();

// sends a message to MQ so the ProxyEventDispatcher listeneres are dispatched in consume script.
$dispatcher->addListener('foo', [$asyncListener, 'onEvent']);

// the normal sync listner
$dispatcher->addListener('foo', function(GenericEvent $event) {
    echo $event->getSubject().PHP_EOL;
});

// another sync listener which shows that async listeners could dispatch sub events and they works
$dispatcher->addListener('bar', function(GenericEvent $event) {
    echo $event->getSubject().PHP_EOL;
});

$proxyDispatcher = new AsyncEventDispatcher($dispatcher, $asyncListener);
// the listener we want to be executed async.
$proxyDispatcher->addListener('foo', function(GenericEvent $event, $eventName, EventDispatcherInterface $dispatcher) {
    echo 'Async: '.$event->getSubject().PHP_EOL;

    $dispatcher->dispatch('bar', new GenericEvent('theBarSubject'));
});

$asyncProcessor = new AsyncProcessor($registry, $proxyDispatcher);

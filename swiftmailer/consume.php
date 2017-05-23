<?php

require_once __DIR__.'/vendor/autoload.php';

$transport = new Swift_SpoolTransport(new \Demo\Swiftmailer\QueueSpool(
    \Enqueue\dsn_to_context('file:/'.__DIR__.'/queue')
));

/** @var \Demo\Swiftmailer\QueueSpool $spool */
$spool = $transport->getSpool();
$spool->setTimeLimit(3);

$realTransport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername(getenv('GUSER'))
    ->setPassword(getenv('GPASS'))
;

$spool->flushQueue($realTransport);

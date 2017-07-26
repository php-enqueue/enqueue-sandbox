<?php

// consume.php
// GUSER=yourAccountName@gmail.com GPASS="yourGmailPassword" php consume.php

require_once __DIR__.'/vendor/autoload.php';

$transport = new Swift_SpoolTransport(new \Swift_QueueSpool(
    (new \Enqueue\Fs\FsConnectionFactory('file://'.__DIR__.'/queue'))->createContext()
));

/** @var \Swift_QueueSpool $spool */
$spool = $transport->getSpool();
$spool->setTimeLimit(3);

$realTransport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername(getenv('GUSER'))
    ->setPassword(getenv('GPASS'))
;

$spool->flushQueue($realTransport);

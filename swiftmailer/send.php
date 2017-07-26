<?php

// send.php
// could be run as GUSER=yourAccountName@gmail.com GPASS="yourGmailPassword" php send.php

require_once __DIR__.'/vendor/autoload.php';

$transport = new Swift_SpoolTransport(new \Swift_QueueSpool(
    (new \Enqueue\Fs\FsConnectionFactory('file://'.__DIR__.'/queue'))->createContext()
));

$mailer = new Swift_Mailer($transport);

$message = (new Swift_Message('Wonderful Subject'))
    ->setFrom(getenv('GUSER'))
    ->setTo(getenv('GUSER'))
    ->setBody('Here is the message itself. Sent at: '.date('Y-m-d H:i:s'))
;

$result = $mailer->send($message);


// The message was sent to MQ. Now run
// GUSER=yourAccountName@gmail.com GPASS="yourGmailPassword" php consume.php
// to send them for real

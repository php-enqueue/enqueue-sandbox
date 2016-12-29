<?php
namespace AppBundle\Async;

use Enqueue\Consumption\MessageProcessorInterface;
use Enqueue\Consumption\Result;
use Enqueue\Psr\Context;
use Enqueue\Psr\Message;
use Enqueue\Util\JSON;
use FOS\ElasticaBundle\Provider\ProviderRegistry;

class ElasticaPopulateProcessor implements MessageProcessorInterface
{
    /**
     * @var ProviderRegistry
     */
    private $providerRegistry;

    /**
     * @param ProviderRegistry $providerRegistry
     */
    public function __construct(ProviderRegistry $providerRegistry)
    {
        $this->providerRegistry = $providerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Message $message, Context $context)
    {
        $options = JSON::decode($message->getBody());

        $provider = $this->providerRegistry->getProvider($options['indexName'], $options['typeName']);
        $provider->populate(null, $options);

        $replyMessage = $context->createMessage(true);
        $replyQueue = $context->createQueue($message->getReplyTo());
        $context->createProducer()->send($replyQueue, $replyMessage);

        return Result::ACK;
    }
}

<?php
namespace AppBundle\Elasticsearch;

use Enqueue\AmqpExt\AmqpConnectionFactory;
use Enqueue\Util\JSON;
use FOS\ElasticaBundle\Doctrine\ORM\Provider;

class AsyncProvider extends Provider
{
    private $batchSize;

    /**
     * {@inheritDoc}
     */
    protected function doPopulate($options, \Closure $loggerClosure = null)
    {
        $this->batchSize = null;
        if ($options['real_populate']) {
            $this->batchSize = $options['offset'] + $options['batch_size'];

            return parent::doPopulate($options, $loggerClosure);
        }

        $factory = new AmqpConnectionFactory([
            'host' => getenv('SYMFONY__RABBITMQ__HOST'),
            'port' => getenv('SYMFONY__RABBITMQ__AMQP__PORT'),
            'vhost' => getenv('SYMFONY__RABBITMQ__VHOST'),
            'login' => getenv('SYMFONY__RABBITMQ__USER'),
            'password' => getenv('SYMFONY__RABBITMQ__PASSWORD'),
            'persisted' => true,
        ]);

        $amqpContext = $factory->createContext();

        $queryBuilder = $this->createQueryBuilder($options['query_builder_method']);
        $nbObjects = $this->countObjects($queryBuilder);
        $offset = $options['offset'];

        $queue = $amqpContext->createQueue('fos_elastica.populate');
        $queue->addFlag(AMQP_DURABLE);
        $amqpContext->declareQueue($queue);

        $resultQueue = $amqpContext->createTemporaryQueue();
        $consumer = $amqpContext->createConsumer($resultQueue);

        $producer = $amqpContext->createProducer();

        $nbMessages = 0;
        for (; $offset < $nbObjects; $offset += $options['batch_size']) {
            $options['offset'] = $offset;
            $options['real_populate'] = true;
            $message = $amqpContext->createMessage(JSON::encode($options));
            $message->setReplyTo($resultQueue->getQueueName());
            $producer->send($queue, $message);

            $nbMessages++;
        }

        while ($nbMessages) {
            if ($message = $consumer->receive(1000)) {
                if (null !== $loggerClosure) {
                    $loggerClosure($options['batch_size'], $nbObjects);
                }

                $nbMessages--;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function countObjects($queryBuilder)
    {
        return $this->batchSize ? $this->batchSize : parent::countObjects($queryBuilder);
    }

    /**
     * {@inheritDoc}
     */
    protected function configureOptions()
    {
        parent::configureOptions();

        $this->resolver->setDefaults([
            'real_populate' => false,
        ]);
    }
}

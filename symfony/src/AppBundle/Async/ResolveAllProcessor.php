<?php
namespace AppBundle\Async;

use Enqueue\Client\TopicSubscriberInterface;
use Enqueue\Consumption\QueueSubscriberInterface;
use Enqueue\Psr\PsrContext;
use Enqueue\Psr\PsrMessage;
use Enqueue\Psr\PsrProcessor;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

class ResolveAllProcessor implements PsrProcessor, TopicSubscriberInterface, QueueSubscriberInterface
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var DataManager
     */
    private $dataManager;
    /**
     * @param CacheManager $cacheManager
     * @param FilterManager $filterManager
     * @param DataManager $dataManager
     */
    public function __construct(CacheManager $cacheManager, FilterManager $filterManager, DataManager $dataManager)
    {
        $this->cacheManager = $cacheManager;
        $this->filterManager = $filterManager;
        $this->dataManager = $dataManager;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $psrMessage, PsrContext $psrContext)
    {
        $path = $psrMessage->getBody();
        foreach ($this->filterManager->getFilterConfiguration()->all() as $filter => $config) {
            if (false == $this->cacheManager->isStored($path, $filter)) {
                $binary = $this->dataManager->find($filter, $path);
                $this->cacheManager->store(
                    $this->filterManager->applyFilter($binary, $filter),
                    $path,
                    $filter
                );
            }

            $this->cacheManager->resolve($path, $filter);
        }

        return self::ACK;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedTopics()
    {
        return [
            Topics::RESOLVE_ALL => [
                'queueName' => Topics::RESOLVE_ALL,
                'queueNameHardcoded' => true
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedQueues()
    {
        return [Topics::RESOLVE_ALL];
    }
}

<?php
namespace AppBundle\Async;

use Enqueue\Client\CommandSubscriberInterface;
use Enqueue\JobQueue\Job;
use Enqueue\JobQueue\JobRunner;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;

class JobQueueUniqueCommandProcessor implements PsrProcessor, CommandSubscriberInterface
{
    const COMMAND = 'job_queue_unique_command';

    /**
     * @var JobRunner
     */
    private $jobRunner;

    public function __construct(JobRunner $jobRunner)
    {
        $this->jobRunner = $jobRunner;
    }

    public function process(PsrMessage $message, PsrContext $context)
    {
        $result = $this->jobRunner->runUnique($message->getMessageId(), 'aJobName', function (JobRunner $jobRunner, Job $job) {
            var_dump($job->getData());
            sleep(10);
            return true; // if you want to ACK message or false to REJECT
        });

        return $result ? self::ACK : self::REJECT;
    }

    public static function getSubscribedCommand()
    {
        return self::COMMAND;
    }
}

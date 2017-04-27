<?php
namespace AppBundle\Command;

use AppBundle\Async\Topics;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SendSayHelloMessageCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this->setName('app:send:say-hello');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Enqueue\Client\MessageProducer $messageProducer **/
        $messageProducer = $this->container->get('enqueue.producer');

        $messageProducer->send(Topics::SAY_HELLO, ['name' => 'John Doe']);

        $output->writeln('Message sent.');
    }
}
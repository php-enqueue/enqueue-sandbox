<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class RunFromControllerCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this->setName('app:run-from-controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectDir = $this->container->getParameter('kernel.project_dir');

        file_put_contents($projectDir.'/var/run-from-controller', time());
    }
}

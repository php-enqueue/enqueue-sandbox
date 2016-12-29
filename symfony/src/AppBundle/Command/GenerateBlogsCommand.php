<?php
namespace AppBundle\Command;

use AppBundle\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GenerateBlogsCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('app:generate-blogs')
            ->addOption('number', null, InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $faker = \Faker\Factory::create();
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        for ($i = 1; $i <= $input->getOption('number'); $i++) {
            $blog = new Blog();
            $blog->setText($faker->paragraphs(3, true));

            $em->persist($blog);

            if (0 == ($i % 100)) {
                $em->flush();
                $em->clear();

                $output->writeln('Saved 100');
            }

        }

        $em->flush();
        $em->clear();

        $output->writeln('Saved the rest');
    }
}

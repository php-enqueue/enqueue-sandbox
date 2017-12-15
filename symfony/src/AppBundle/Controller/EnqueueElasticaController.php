<?php

namespace AppBundle\Controller;

use AppBundle\Async\JobQueueUniqueCommandProcessor;
use AppBundle\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Enqueue\AmqpExt\AmqpConnectionFactory;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;
use Enqueue\JobQueue\Doctrine\Entity\Job;
use Enqueue\JobQueue\Doctrine\JobStorage;
use Interop\Amqp\AmqpContext;
use Interop\Amqp\AmqpTopic;
use Liip\ImagineBundle\Async\ResolveCache;
use Liip\ImagineBundle\Async\Topics as LiipImagineTopics;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Amqp\Broker;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/enqueue-elastica", name="enqueue_elastica")
 */
class EnqueueElasticaController extends Controller
{
    /**
     * @Route("/index-inserted-object")
     */
    public function indexInsertedObjectAction(Request $request)
    {
        $faker = \Faker\Factory::create();
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $blog = new Blog();
        $blog->setText($faker->paragraphs(3, true));

        $em->persist($blog);
        $em->flush();

        return new Response('OK');
    }

    /**
     * @Route("/index-updated-object")
     */
    public function indexUpdatedObjectAction(Request $request)
    {
        $faker = \Faker\Factory::create();
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $blog = $em->getRepository(Blog::class)->findOneBy([]);
        $blog->setText($faker->paragraphs(3, true));

        $em->flush();

        return new Response('OK');
    }

    /**
     * @Route("/index-removed-object")
     */
    public function indexRemoveObjectAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $blog = $em->getRepository(Blog::class)->findOneBy([]);

        $em->remove($blog);
        $em->flush();

        return new Response('OK');
    }
}

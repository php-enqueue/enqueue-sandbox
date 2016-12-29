<?php

namespace AppBundle;

use AppBundle\Async\Topics;
use Enqueue\Bundle\DependencyInjection\Compiler\AddTopicMetaPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(AddTopicMetaPass::create()->add(Topics::SAY_HELLO));
    }
}

<?php

declare(strict_types=1);

namespace Avando\AveBundle;

use Avando\AveBundle\DependencyInjection\Compiler\PreserveAiMetadataPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AvandoAveBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new PreserveAiMetadataPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}

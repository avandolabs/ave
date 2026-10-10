<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
    ;

    $services->load('Avando\\AveBundle\\', '../src/')
        ->exclude('../src/{ContaoManager,DependencyInjection,Entity,AvandoAveBundle.php}')
    ;



    $services->get(\Avando\AveBundle\EventListener\MinifyHtmlListener::class)
        ->arg('$debug', '%kernel.debug%')
    ;
};

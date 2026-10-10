<?php

declare(strict_types=1);

namespace Avando\AveBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Yaml\Yaml;

class AvandoAveExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Die Bildgrößen des Themes (card_*, content_*, list_thumbnail) kommen aus
     * dem Bundle, nicht aus der Projektkonfiguration — sonst hätte sie nur die
     * Demo-Installation. Vorangestellt, damit ein Projekt eine Größe unter
     * contao.image.sizes gleichen Namens überschreiben kann.
     */
    public function prepend(ContainerBuilder $container): void
    {
        $file = \dirname(__DIR__, 2).'/config/image_sizes.yaml';
        $container->addResource(new FileResource($file));

        $container->prependExtensionConfig('contao', Yaml::parseFile($file)['contao']);
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        // Als Parameter, damit AttributionExtension ihn per #[Autowire] bekommt
        // und kein Dienst die ganze Konfiguration kennen muss.
        $container->setParameter('avando_ave.license', $config['license'] ?? '');

        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__, 2).'/config'));
        $loader->load('services.php');
    }
}

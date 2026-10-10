<?php

declare(strict_types=1);

namespace Avando\AveBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('avando_ave');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('license')
                    ->defaultValue('')
                    ->info('Pro-Token aus der Lieferung des gekauften Pakets, fuer alle Startpunkte. Ueblicher Weg ist das Feld im Startpunkt (Backend); dieser Wert ist fuer Konfiguration als Code.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

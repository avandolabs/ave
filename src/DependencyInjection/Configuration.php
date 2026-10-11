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
                ->booleanNode('ai_label')
                    ->defaultTrue()
                    ->info('KI-Kennzeichnung von AVE: Hinweis an markierten Bildern, schema.org-Angabe, Erhalt der Herkunftsmarkierung beim Verkleinern. false schaltet alles ab, fuer alle Startpunkte — etwa wenn Contao oder eine Erweiterung das selbst uebernimmt. Die Haken an den Dateien bleiben erhalten.')
                ->end()
                ->arrayNode('legacy_hooks')
                    ->info('Holt fuer Twig-Layouts die Seiten-Hooks getPageLayout, generatePage, outputFrontendTemplate und modifyFrontendPage nach, damit Erweiterungen wie die Oveleon-Cookiebar laufen.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->arrayNode('skip')
                            ->info('Rueckrufe, die nicht nachgeholt werden, als "dienst_id::methode" oder "Klasse::methode".')
                            ->scalarPrototype()->end()
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

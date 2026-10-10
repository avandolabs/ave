<?php

declare(strict_types=1);

namespace Avando\AveBundle\ContaoManager;

use Avando\AveBundle\AvandoAveBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(AvandoAveBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}

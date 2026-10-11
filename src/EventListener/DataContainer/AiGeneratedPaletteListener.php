<?php

declare(strict_types=1);

namespace Avando\AveBundle\EventListener\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Path;

/**
 * Zeigt den Haken „KI-generiert" nur bei Bildern.
 *
 * Ordner und Dokumente haben keinen Ort, an dem ein Hinweis erscheinen
 * könnte — der Haken wäre dort eine Einstellung ohne Wirkung. Dieselbe
 * Unterscheidung trifft Contao selbst für den Bildausschnitt.
 */
#[AsCallback(table: 'tl_files', target: 'config.onpalette')]
final class AiGeneratedPaletteListener
{
    /**
     * @param list<string> $imageExtensions
     */
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        #[Autowire('%contao.image.valid_extensions%')]
        private readonly array $imageExtensions,
    ) {
    }

    public function __invoke(string $palette, DataContainer $dc): string
    {
        $path = (string) $dc->id;

        if ('' === $path) {
            return $palette;
        }

        $isImage = !is_dir($this->projectDir.'/'.$path)
            && \in_array(Path::getExtension($path, true), $this->imageExtensions, true);

        if ($isImage) {
            return $palette;
        }

        return PaletteManipulator::create()
            ->removeField('ave_ai_generated')
            ->applyToString($palette)
        ;
    }
}

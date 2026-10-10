<?php

declare(strict_types=1);

namespace Avando\AveBundle\Twig;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Public path for a file picked in the back end (binary UUID).
 *
 * figure() deckt Bilder ab, aber ein Hintergrundvideo ist kein Bild: es wird
 * nicht skaliert, nicht in WebP gewandelt und nicht in ein <picture> gepackt —
 * es braucht nur seinen Pfad. Contao bringt dafuer keine Twig-Funktion mit;
 * content_url() loest Seiten und Inhalte auf, keine Dateien.
 *
 * Bewusst eine eigene Extension und nicht in ThemeVarsExtension: die beantwortet,
 * welche CSS-Dateien existieren, und braucht dafuer nur das Projektverzeichnis.
 * Ihr das Contao-Framework mitzugeben, um eine Datei-URL zu bilden, machte aus
 * zwei kleinen Fragen eine Klasse mit zwei Gruenden zur Aenderung — und brach
 * prompt sieben Tests, die sie mit einem Argument bauen.
 */
final class FileUrlExtension extends AbstractExtension
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        private readonly ContaoFramework $framework,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ave_file_url', $this->fileUrl(...)),
        ];
    }

    /**
     * Leerer String bei fehlender, geloeschter oder nie gewaehlter Datei.
     *
     * Das Template entscheidet daraufhin, gar kein <video> auszugeben. Eine
     * Sektion ohne Video ist ein gueltiger Zustand — ein <video> mit leerer
     * Quelle nicht: der Browser wuerde die aktuelle Seite als Quelle laden.
     */
    public function fileUrl(?string $uuid): string
    {
        if (!$uuid) {
            return '';
        }

        $this->framework->initialize();

        $model = $this->framework->getAdapter(FilesModel::class)->findByUuid($uuid);

        if (null === $model || !is_file($this->projectDir.'/'.$model->path)) {
            return '';
        }

        return '/'.$model->path;
    }
}

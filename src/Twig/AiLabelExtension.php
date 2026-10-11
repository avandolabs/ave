<?php

declare(strict_types=1);

namespace Avando\AveBundle\Twig;

use Contao\CoreBundle\Image\Studio\Figure;
use Contao\FilesModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Validator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DbalException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * KI-Kennzeichnung: Bekommt dieses Bild einen Hinweis — und wenn ja, welchen?
 *
 * Zwei Quellen, getrennt nach Zuständigkeit:
 *
 *   - OB: der Haken „KI-generiert" an der Datei (tl_files.ave_ai_generated).
 *     Ein Motiv ist KI-generiert oder nicht, egal wo es eingebunden ist.
 *   - WIE und WO: der Startpunkt der Website (tl_page.ave_ai_*). Ein Hinweis,
 *     der je nach Element anders aussieht, wird übersehen.
 *
 * Ausgegeben wird er von component/_figure (alle Twig-Elemente) und von
 * image.html.twig (Legacy-Ausgabe: News- und Event-Listen, Downloads). Beide
 * fragen hier, damit sie nicht auseinanderlaufen.
 *
 * Nimmt, was am Aufrufort zur Hand ist: eine Figure, ein FilesModel, eine
 * UUID (binär oder als Zeichenkette) oder einen Pfad.
 *
 * Fehlt die Spalte noch (Update eingespielt, contao:migrate nicht gelaufen),
 * erscheint kein Hinweis — die Seite bricht nicht ab.
 */
final class AiLabelExtension extends AbstractExtension
{
    public const POSITIONS = ['bottom-right', 'bottom-center', 'bottom-left', 'top-right', 'top-left', 'right-vertical', 'center', 'below'];
    public const SIZES = ['sm', 'md', 'lg'];
    public const STYLES = ['dark', 'light', 'accent', 'none'];

    private const DEFAULTS = ['position' => 'bottom-right', 'size' => 'md', 'style' => 'dark', 'text' => ''];

    /** @var array<string, bool> je Datei nur eine Abfrage pro Request */
    private array $flags = [];

    /** @var array<int, array{position: string, size: string, style: string, text: string}> */
    private array $settings = [];

    public function __construct(
        private readonly Connection $connection,
        private readonly RequestStack $requestStack,
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ave_ai_label', $this->label(...)),
        ];
    }

    /**
     * @return array{position: string, size: string, style: string, text: string}|null
     */
    public function label(mixed $image): array|null
    {
        $path = $this->path($image);

        if (null === $path || !($this->flags[$path] ??= $this->isFlagged($path))) {
            return null;
        }

        $rootId = $this->rootId();

        return $this->settings[$rootId] ??= $this->loadSettings($rootId);
    }

    /**
     * Projektrelativer Pfad ("files/…"), wie er in tl_files steht.
     */
    private function path(mixed $image): string|null
    {
        if ($image instanceof Figure) {
            $image = $image->getImage()->getFilePath();
        }

        if ($image instanceof FilesModel) {
            return $image->path;
        }

        if (!\is_string($image) || '' === $image) {
            return null;
        }

        if (Validator::isBinaryUuid($image) || Validator::isStringUuid($image)) {
            return $this->pathByUuid(Validator::isBinaryUuid($image) ? $image : StringUtil::uuidToBin($image));
        }

        if (Path::isAbsolute($image)) {
            if (!Path::isBasePath($this->projectDir, $image)) {
                return null;
            }

            return Path::makeRelative($image, $this->projectDir);
        }

        return ltrim($image, '/');
    }

    private function pathByUuid(string $uuid): string|null
    {
        try {
            $path = $this->connection->fetchOne('SELECT path FROM tl_files WHERE uuid = ?', [$uuid]);
        } catch (DbalException) {
            return null;
        }

        return \is_string($path) && '' !== $path ? $path : null;
    }

    private function isFlagged(string $path): bool
    {
        try {
            return '1' === (string) $this->connection->fetchOne(
                'SELECT ave_ai_generated FROM tl_files WHERE path = ?',
                [$path],
            );
        } catch (DbalException) {
            return false;
        }
    }

    private function rootId(): int
    {
        $page = $this->requestStack->getMainRequest()?->attributes->get('pageModel');

        if (!$page instanceof PageModel) {
            return 0;
        }

        return (int) ($page->rootId ?: ('root' === $page->type ? $page->id : 0));
    }

    /**
     * Unbekannte oder leere Werte fallen auf die Vorgaben zurück — der Hinweis
     * erscheint also immer, auch wenn der Startpunkt nie gespeichert wurde.
     *
     * @return array{position: string, size: string, style: string, text: string}
     */
    private function loadSettings(int $rootId): array
    {
        if ($rootId <= 0) {
            return self::DEFAULTS;
        }

        try {
            $row = $this->connection->fetchAssociative(
                'SELECT ave_ai_position, ave_ai_size, ave_ai_style, ave_ai_text FROM tl_page WHERE id = ?',
                [$rootId],
            );
        } catch (DbalException) {
            return self::DEFAULTS;
        }

        if (false === $row) {
            return self::DEFAULTS;
        }

        return [
            'position' => \in_array($row['ave_ai_position'], self::POSITIONS, true) ? $row['ave_ai_position'] : self::DEFAULTS['position'],
            'size' => \in_array($row['ave_ai_size'], self::SIZES, true) ? $row['ave_ai_size'] : self::DEFAULTS['size'],
            'style' => \in_array($row['ave_ai_style'], self::STYLES, true) ? $row['ave_ai_style'] : self::DEFAULTS['style'],
            'text' => trim((string) $row['ave_ai_text']),
        ];
    }
}

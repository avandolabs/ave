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
 *
 * ABSCHALTBAR, damit AVE Platz machen kann, falls Contao oder eine Erweiterung
 * KI-Bilder einmal selbst kennzeichnet — sonst stünde der Hinweis doppelt da:
 * im Startpunkt (ave_ai_enabled, je Website) oder für alle Websites per
 * `avando_ave: ai_label: false`. Abgeschaltet liefert label() immer null, und
 * die Templates geben exakt die Contao-Ausgabe weiter.
 */
final class AiLabelExtension extends AbstractExtension
{
    public const POSITIONS = ['bottom-right', 'bottom-center', 'bottom-left', 'top-right', 'top-left', 'right-vertical', 'center', 'below'];
    public const SIZES = ['sm', 'md', 'lg'];
    public const STYLES = ['dark', 'light', 'accent', 'none'];

    /**
     * schema.org-Wert für „mit KI erzeugt“ (IPTC Digital Source Type). Der
     * Haken unterscheidet nicht zwischen ganz und teilweise erzeugt; gewählt
     * ist der allgemeine Fall, den auch die Generatoren selbst eintragen.
     */
    public const SCHEMA_DIGITAL_SOURCE = 'https://schema.org/TrainedAlgorithmicMediaDigitalSource';

    private const DEFAULTS = ['enabled' => true, 'position' => 'bottom-right', 'size' => 'md', 'style' => 'dark', 'text' => ''];

    /** @var array<string, bool> je Datei nur eine Abfrage pro Request */
    private array $flags = [];

    /** @var array<int, array{enabled: bool, position: string, size: string, style: string, text: string}> */
    private array $settings = [];

    public function __construct(
        private readonly Connection $connection,
        private readonly RequestStack $requestStack,
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        #[Autowire('%avando_ave.ai_label%')]
        private readonly bool $enabled = true,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ave_ai_label', $this->label(...)),
            new TwigFunction('ave_ai_schema_org', $this->schemaOrg(...)),
        ];
    }

    /**
     * @return array{position: string, size: string, style: string, text: string}|null
     */
    public function label(mixed $image): array|null
    {
        if (!$this->enabled) {
            return null;
        }

        $rootId = $this->rootId();
        $settings = $this->settings[$rootId] ??= $this->loadSettings($rootId);

        if (!$settings['enabled']) {
            return null;
        }

        $path = $this->path($image);

        if (null === $path || !($this->flags[$path] ??= $this->isFlagged($path))) {
            return null;
        }

        unset($settings['enabled']);

        return $settings;
    }

    /**
     * Die schema.org-Daten der Figure, ergänzt um digitalSourceType — oder
     * null, wenn das Bild keinen Hinweis bekommt. Contao legt den Eintrag
     * schon an (component/_picture, image.html.twig); add_schema_org() mit
     * derselben Kennung ersetzt ihn, statt einen zweiten anzulegen.
     *
     * @return array<string, mixed>|null
     */
    public function schemaOrg(mixed $figure): array|null
    {
        if (!$figure instanceof Figure || null === $this->label($figure)) {
            return null;
        }

        return [...$figure->getSchemaOrgData(), 'digitalSourceType' => self::SCHEMA_DIGITAL_SOURCE];
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
     * @return array{enabled: bool, position: string, size: string, style: string, text: string}
     */
    private function loadSettings(int $rootId): array
    {
        if ($rootId <= 0) {
            return self::DEFAULTS;
        }

        try {
            $row = $this->connection->fetchAssociative(
                'SELECT ave_ai_enabled, ave_ai_position, ave_ai_size, ave_ai_style, ave_ai_text FROM tl_page WHERE id = ?',
                [$rootId],
            );
        } catch (DbalException) {
            return self::DEFAULTS;
        }

        if (false === $row) {
            return self::DEFAULTS;
        }

        return [
            'enabled' => '' !== (string) ($row['ave_ai_enabled'] ?? '1'),
            'position' => \in_array($row['ave_ai_position'], self::POSITIONS, true) ? $row['ave_ai_position'] : self::DEFAULTS['position'],
            'size' => \in_array($row['ave_ai_size'], self::SIZES, true) ? $row['ave_ai_size'] : self::DEFAULTS['size'],
            'style' => \in_array($row['ave_ai_style'], self::STYLES, true) ? $row['ave_ai_style'] : self::DEFAULTS['style'],
            'text' => trim((string) $row['ave_ai_text']),
        ];
    }
}

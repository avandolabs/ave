<?php

declare(strict_types=1);

namespace Avando\AveBundle\Twig;

use Contao\CoreBundle\File\Metadata;
use Contao\CoreBundle\Image\Studio\Figure;
use enshrined\svgSanitize\Sanitizer;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Path;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

/**
 * Setzt eine SVG als Code in die Seite statt als <img> — für Logos mit der
 * Klasse `logo-tint`, die ihre Farbe aus der Umgebung nehmen.
 *
 * Warum überhaupt einbetten: Ein <img> ist für das Stylesheet eine
 * geschlossene Kiste, seine Farben stehen in der Datei. Eingebettet sind die
 * Teile der Zeichnung gewöhnliche Elemente, und components.css kann
 * `.logo-mark` und `.logo-word` getrennt färben — mit der Akzentfarbe und der
 * Schriftfarbe dessen, worauf das Logo gerade steht (Kopf, Fuss, Inhalt).
 * Mit der Persona und dem Farbschema wechseln diese Farben, und das Logo
 * wechselt mit, ohne zweite Datei und ohne Skript.
 *
 * Bereinigt wird IMMER, auch wenn Contao die Datei beim Hochladen schon
 * bereinigt hat: als <img> kann eine SVG keine Skripte ausführen, eingebettet
 * schon — und eine Datei kann auch per FTP oder Deployment nach files/
 * gekommen sein, ohne je durch den Upload zu laufen. Dieselbe Bibliothek wie
 * Contaos FileUpload, zusätzlich ohne Verweise auf fremde Server.
 *
 * Gibt null zurück, wenn etwas nicht passt (keine SVG, ausserhalb des
 * Projekts, nach dem Bereinigen leer). Das Template zeigt dann das gewöhnliche
 * <img> — ein Logo in Originalfarben ist besser als gar keins.
 */
final class InlineSvgExtension extends AbstractExtension
{
    /** @var array<string, string|null> */
    private array $cache = [];

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ave_inline_svg', $this->inlineSvg(...)),
        ];
    }

    public function inlineSvg(?Figure $figure): ?Markup
    {
        if (null === $figure) {
            return null;
        }

        $image = $figure->getImage();

        return $this->render(
            $image->getFilePath(true),
            self::withAlt($image->getImg(), $figure->hasMetadata() ? $figure->getMetadata() : null),
            static function () use ($image): array {
                $size = $image->getOriginalDimensions()->getSize();

                return [$size->getWidth(), $size->getHeight()];
            },
        );
    }

    /**
     * Den Alt-Text aus den Metadaten der Figure ins img-Array holen.
     *
     * Contao führt ihn NICHT in getImg(), sondern in den Metadaten (Datei-
     * verwaltung oder „Metadaten überschreiben“ am Element). render() las nur
     * $img['alt'] — und bekam darum nie einen. Jedes eingebettete Logo war
     * aria-hidden, ein Logo-Link damit ohne Namen, auch mit gepflegtem
     * Alt-Text. Gefunden am 14.10.2026 mit Lighthouse („link-name“) auf der Demo.
     *
     * @param array<string, mixed> $img
     *
     * @return array<string, mixed>
     */
    public static function withAlt(array $img, ?Metadata $metadata): array
    {
        if (null !== $metadata && '' === trim((string) ($img['alt'] ?? ''))) {
            $img['alt'] = $metadata->getAlt();
        }

        return $img;
    }

    /**
     * Die eigentliche Arbeit, getrennt von Contaos Bildobjekt — damit sie sich
     * ohne laufendes Contao prüfen lässt.
     *
     * @param array<string, mixed>               $img  Attribute des <img>, das hier sonst stünde
     * @param (callable(): array{int, int})|null $size ursprüngliche Grösse, nur gefragt, wenn die viewBox fehlt
     */
    public function render(string $path, array $img, ?callable $size = null): ?Markup
    {
        $svg = $this->load($path);

        if (null === $svg) {
            return null;
        }

        $doc = new \DOMDocument();

        if (!@$doc->loadXML($svg, \LIBXML_NONET)) {
            return null;
        }

        $root = $doc->documentElement;

        if (null === $root || 'svg' !== $root->localName) {
            return null;
        }

        // Dieselbe Grösse wie das <img>, das hier sonst stünde — sonst
        // verschöbe sich das Layout, je nachdem ob eingefärbt wird.
        foreach (['width', 'height'] as $attr) {
            if (!empty($img[$attr])) {
                $root->setAttribute($attr, (string) $img[$attr]);
            }
        }

        // Ohne viewBox liesse sich die Zeichnung nicht skalieren; aus der
        // ursprünglichen Grösse nachtragen, wo der Export sie weggelassen hat.
        if (!$root->hasAttribute('viewBox') && null !== $size) {
            [$w, $h] = $size();
            $root->setAttribute('viewBox', '0 0 '.$w.' '.$h);
        }

        $root->setAttribute('class', trim($root->getAttribute('class').' logo-svg'));
        $root->setAttribute('focusable', 'false');

        // Der Alternativtext des Bildes wird zum Namen der Zeichnung. Leer
        // heisst wie beim <img>: Schmuck, für Screenreader unsichtbar.
        $alt = trim((string) ($img['alt'] ?? ''));

        if ('' !== $alt) {
            $root->setAttribute('role', 'img');
            $root->setAttribute('aria-label', $alt);
        } else {
            $root->setAttribute('aria-hidden', 'true');
        }

        return new Markup((string) $doc->saveXML($root), 'UTF-8');
    }

    /**
     * Liest und bereinigt die Datei, einmal je Pfad und Änderungszeit.
     */
    private function load(string $path): ?string
    {
        if ('svg' !== strtolower(Path::getExtension($path))) {
            return null;
        }

        $real = realpath($path);

        // Nur Dateien im Projekt: files/ für Originale, assets/ für die von
        // Contao skalierten Kopien. Ein Pfad, der hinausführt, wird nicht
        // gelesen, gleich wie er zustande kam.
        $root = realpath($this->projectDir) ?: $this->projectDir;

        if (false === $real || !Path::isBasePath($root, $real) || !is_file($real)) {
            return null;
        }

        $key = $real.'|'.(string) filemtime($real);

        if (\array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        $sanitizer = new Sanitizer();
        $sanitizer->removeRemoteReferences(true);
        $sanitizer->removeXMLTag(true);

        $clean = $sanitizer->sanitize((string) file_get_contents($real));

        return $this->cache[$key] = (\is_string($clean) && '' !== trim($clean)) ? $clean : null;
    }
}

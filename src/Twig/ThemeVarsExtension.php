<?php

declare(strict_types=1);

namespace Avando\AveBundle\Twig;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Tells the layout which of the optional files/theme/CSS/ stylesheets exist,
 * so it can skip the <link> instead of emitting a 404.
 *
 * None of them is guaranteed. theme-vars.css is written by the Design Studio,
 * which ships as a separate Pro module — the free theme has no hard dependency
 * on it, since every token has a default in tokens.css and the free variant
 * uses the system-font stack (no @font-face). theme.css, custom.css and
 * view-transitions.css live under files/, where an admin may rename or delete
 * them. A missing file is a valid state, not an error.
 */
final class ThemeVarsExtension extends AbstractExtension
{
    private const CSS_DIR = 'files/theme/CSS/';

    /** @var array<string, bool> */
    private array $cache = [];

    /** @var list<string>|null */
    private ?array $fonts = null;

    private ?bool $built = null;

    private ?bool $bundleBuilt = null;

    /**
     * Größte theme-vars.css, die noch in den Kopf geschrieben wird. Die vom
     * Studio erzeugte liegt bei 0,3 bis 3 KB; mit eingebetteten Schriftlisten
     * oder Handarbeit kann sie wachsen, und ab einer gewissen Größe ist eine
     * zwischengespeicherte Datei billiger als dieselben Bytes in jeder Seite.
     */
    private const INLINE_MAX = 16384;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ave_theme_css_exists', $this->themeCssExists(...)),
            // Kept for templates written against the original single-purpose
            // helper (and for anyone overriding the layout).
            new TwigFunction('ave_theme_vars_exists', $this->themeVarsExists(...)),
            new TwigFunction('ave_theme_font_files', $this->themeFontFiles(...)),
            new TwigFunction('ave_css_built', $this->cssBuilt(...)),
            new TwigFunction('ave_css_bundle_built', $this->cssBundleBuilt(...)),
            new TwigFunction('ave_theme_vars_inline', $this->themeVarsInline(...)),
        ];
    }


    /**
     * The self-hosted font files the theme actually uses, for preloading.
     *
     * Ohne Vorladen wird eine Schrift erst entdeckt, wenn theme-vars.css geladen
     * UND geparst ist — dort stehen die @font-face-Regeln. Gemessen am
     * 06.08.2026 auf der Startseite: Dokument bei 0 ms, theme-vars.css (das 14.
     * von 16 Stylesheets) bei 100 ms, die Schriftdateien erst bei 156 und 162
     * ms. Bis dahin zeigt `font-display: swap` den Systemstapel — genau das
     * kurze Umspringen, das bei jedem Seitenwechsel zu sehen war.
     *
     * Die Liste kommt aus theme-vars.css selbst und nicht aus dem
     * Schriftenordner: dort liegen alle je heruntergeladenen Familien (aktuell
     * 30), vorgeladen gehören nur die zwei, die diese Website benutzt. Damit
     * gilt es auch für von Hand geschriebene @font-face-Regeln.
     *
     * @return list<string> URLs, relativ zum Projektwurzelverzeichnis
     */
    public function themeFontFiles(): array
    {
        if (null !== $this->fonts) {
            return $this->fonts;
        }

        $file = $this->projectDir.'/'.self::CSS_DIR.'theme-vars.css';

        if (!is_file($file)) {
            return $this->fonts = [];
        }

        $css = (string) file_get_contents($file);
        $treffer = [];

        // Nur innerhalb von @font-face, damit ein url() aus einer beliebigen
        // anderen Deklaration (Hintergrundbild) nicht als Schrift durchgeht.
        preg_match_all('/@font-face\s*\{([^}]*)\}/', $css, $bloecke);

        foreach ($bloecke[1] as $block) {
            if (preg_match("/url\\(\\s*['\"]?([^'\")]+\\.woff2)['\"]?\\s*\\)/i", $block, $m)) {
                $treffer[] = ltrim(trim($m[1]), '/');
            }
        }

        return $this->fonts = array_values(array_unique($treffer));
    }

    /**
     * Does files/theme/CSS/<name> exist? $name is a bare file name — anything
     * carrying a path separator is rejected rather than resolved, so a template
     * can never probe outside the stylesheet directory.
     */
    public function themeCssExists(string $name): bool
    {
        if ('' === $name || basename($name) !== $name) {
            return false;
        }

        // The layout asks once per optional stylesheet; memoise so a rendered
        // page costs one stat per file rather than one per call site.
        return $this->cache[$name] ??= is_file($this->projectDir.'/'.self::CSS_DIR.$name);
    }

    /**
     * Liegt die zusammengefasste theme.min.css vor?
     *
     * Sie entsteht mit `ave:css:build` und ersetzt neun einzelne Links durch
     * einen. Fehlt sie — im Entwicklungsstand oder direkt nach dem Klonen —,
     * verlinkt das Layout die Quellen; das ist kein Fehler, sondern der
     * bequemere Zustand zum Arbeiten.
     */
    public function cssBuilt(): bool
    {
        return $this->built ??= is_file(\dirname(__DIR__, 2).'/public/dist/theme.min.css');
    }

    /** Liegt die All-in-one-Datei ave.min.css vor (`ave:css:build`)? */
    public function cssBundleBuilt(): bool
    {
        return $this->bundleBuilt ??= is_file(\dirname(__DIR__, 2).'/public/dist/ave.min.css');
    }

    /**
     * Der Inhalt von theme-vars.css, wenn er gefahrlos als <style> in den Kopf
     * darf — sonst null, und das Layout verlinkt die Datei wie bisher.
     *
     * Warum einbetten: Die Datei ist winzig (Studio-Grundstand 0,3 KB), blockiert
     * als eigenes Stylesheet aber trotzdem das erste Zeichnen und kostet eine
     * Anfrage. Gemessen am 14.10.2026 auf der Demo: eines von neun blockierenden
     * Stylesheets.
     *
     * Nicht eingebettet wird,
     *  - was größer als INLINE_MAX ist,
     *  - was `</` enthält (könnte das <style>-Element schließen),
     *  - was ein relatives url() enthält: das löste die Datei gegen
     *    files/theme/CSS/ auf, eingebettet aber gegen die Seiten-URL. Das
     *    Studio schreibt Schriftpfade absolut (/files/theme/fonts/…).
     *  - wenn ein Design Studio installiert ist, das den eingebetteten Block
     *    noch nicht kennt: Es schaltet für die Vorschau `link[href*=theme-vars]`
     *    ab und lädt ihn nach dem Speichern neu. Ab Studio 1.1 trägt es dafür
     *    die Konstante THEME_VARS_INLINE.
     */
    public function themeVarsInline(): ?string
    {
        if (!$this->themeVarsExists()) {
            return null;
        }

        $studio = 'Avando\\AveEditor\\AvandoAveEditorBundle';

        if (class_exists($studio) && !\defined($studio.'::THEME_VARS_INLINE')) {
            return null;
        }

        $file = $this->projectDir.'/'.self::CSS_DIR.'theme-vars.css';
        $size = @filesize($file);

        if (false === $size || $size > self::INLINE_MAX) {
            return null;
        }

        $css = (string) file_get_contents($file);

        if (str_contains($css, '</')) {
            return null;
        }

        preg_match_all('/url\(\s*([\'"]?)([^\'")]*)\1\s*\)/i', $css, $m);

        foreach ($m[2] as $url) {
            if (!preg_match('#^(/|data:|https?:|\#)#i', trim($url))) {
                return null;
            }
        }

        return trim($css);
    }

    public function themeVarsExists(): bool
    {
        return $this->themeCssExists('theme-vars.css');
    }
}

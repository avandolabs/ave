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

    public function themeVarsExists(): bool
    {
        return $this->themeCssExists('theme-vars.css');
    }
}

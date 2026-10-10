<?php

declare(strict_types=1);

namespace Avando\AveBundle\Theme;

use Contao\CoreBundle\Util\SymlinkUtil;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

/**
 * Sorgt dafuer, dass files/theme/ vom Browser aus erreichbar ist.
 *
 * Contao liefert aus files/ nur Ordner aus, die eine Datei `.public` tragen;
 * fuer sie legt `contao:symlinks` einen Verweis unter public/files/ an. Fehlt
 * eines von beiden, antwortet der Server auf files/theme/CSS/theme-vars.css
 * mit 404 — das Layout bindet die Datei trotzdem ein, weil sie auf der Platte
 * liegt, und die Seite steht ohne Studio-Gestaltung und ohne custom.css da.
 *
 * Auf einer frischen Installation legt erst das Studio den Ordner an, und zwar
 * ohne `.public`. Deshalb fragen beide Wege hier nach: die Migration bei jeder
 * Installation und jedem Update, das Studio vor jedem Schreiben.
 *
 * Was Contao 5.7 und 6 hierfuer gemeinsam haben: die `.public`-Datei und
 * SymlinkUtil. Mehr wird nicht benutzt.
 */
final class ThemeFolder
{
    public const PATH = 'files/theme';

    private readonly Filesystem $filesystem;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        #[Autowire('%contao.web_dir%')]
        private readonly string $webDir,
    ) {
        $this->filesystem = new Filesystem();
    }

    /**
     * Ist der Ordner markiert UND unter public/ erreichbar? Ein uebergeordneter
     * oeffentlicher Ordner (files/.public) zaehlt mit: dann haengt files/theme
     * schon an dessen Verweis.
     */
    public function isPublished(): bool
    {
        return $this->isMarked() && file_exists($this->publicPath());
    }

    public function publish(): void
    {
        $folder = $this->projectDir.'/'.self::PATH;
        $marker = $folder.'/.public';

        // Wie Contaos Folder::unprotect(): ein Ordner namens .public waere
        // ein Fehlbedienungsrest, den wir nicht still ueberschreiben.
        if (is_dir($marker)) {
            throw new \RuntimeException(\sprintf('Cannot publish "%s": it contains a directory called ".public".', self::PATH));
        }

        $this->filesystem->mkdir($folder);

        if (!$this->isMarked()) {
            $this->filesystem->touch($marker);
        }

        $link = $this->publicPath();

        if (file_exists($link)) {
            return;
        }

        // Ein Verweis, dessen Ziel fehlt (Ordner geloescht und neu angelegt,
        // Projekt verschoben), wird ersetzt statt uebersprungen.
        if (is_link($link)) {
            $this->filesystem->remove($link);
        }

        SymlinkUtil::symlink(
            self::PATH,
            Path::makeRelative($link, $this->projectDir),
            $this->projectDir,
        );
    }

    private function isMarked(): bool
    {
        $path = self::PATH;

        do {
            if (is_file($this->projectDir.'/'.$path.'/.public')) {
                return true;
            }

            $path = \dirname($path);
        } while ('.' !== $path);

        return false;
    }

    private function publicPath(): string
    {
        return $this->webDir.'/'.self::PATH;
    }
}

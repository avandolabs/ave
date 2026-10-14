<?php

declare(strict_types=1);

namespace Avando\AveBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Fasst die Theme-Stylesheets zu einer ausgelieferten Datei zusammen.
 *
 * Die Quellen bleiben, wie sie sind: neun Dateien, nach Zuständigkeit getrennt,
 * mit ausführlicher Prosa. Genau die kostet aber Übertragung — gemessen am
 * 06.08.2026 sind 47% der 213 KB Kommentar (100 KB), und weil es Fliesstext
 * ist, komprimiert er schlecht: gzip 60,2 KB gegen 18,0 KB ohne.
 *
 * Dazu die Anfragen. Der Browser holt neun einzelne Dateien, gemessen von der
 * ersten Anfrage bei 101 ms bis zur letzten Antwort bei 149 ms; erst danach
 * kann er zeichnen (erster Inhalt bei 196 ms). Als eine Datei entfällt dieses
 * Nachladen.
 *
 * Bewusst KONSERVATIV: entfernt werden Kommentare und Leerraum am Zeilenanfang
 * bzw. Leerzeilen — mehr nicht. Eine zeichenweise Minifizierung müsste
 * `calc(a + b)` von `a+b` unterscheiden, Selektorkombinatoren erhalten und
 * @media-Bedingungen respektieren; sie brächte nach gzip fast nichts mehr
 * (18,0 KB sind bereits erreicht) und könnte still etwas zerstören.
 *
 * Die Reihenfolge stammt aus dem Layout, nicht aus dem Dateisystem: tokens.css
 * erklärt die @layer-Reihenfolge und muss zuerst stehen.
 */
#[AsCommand(
    name: 'ave:css:build',
    description: 'Fasst die Theme-Stylesheets zu dist/theme.min.css und dist/ave.min.css zusammen',
)]
class BuildCssCommand extends Command
{
    /**
     * Genau die Reihenfolge, in der das Layout sie verlinkt.
     *
     * @var list<string>
     */
    public const SOURCES = [
        'tokens.css',
        'base.css',
        'layout.css',
        'typography.css',
        'forms.css',
        'components.css',
        'navigation.css',
        'icons.css',
        'animations.css',
    ];

    /**
     * Das Erzeugnis liegt NEBEN den Quellen, nicht zwischen ihnen. Zuerst lag es
     * in public/theme/ — und wurde damit von jedem Wächter mitgelesen, der die
     * Quellen prüft. MutedStrengthTest schlug sofort an: er unterscheidet die
     * Wurzel-Deklaration von einer Sektions-Überschreibung am Dateikontext, und
     * der geht beim Zusammenfassen verloren. Fünf Tests eine Ausnahme
     * beizubringen wäre der falsche Weg gewesen; getrennte Ordner sagen von
     * selbst, was Quelle ist und was daraus entsteht.
     */
    public const OUTPUT_DIR = 'dist';

    public const OUTPUT = 'theme.min.css';

    /**
     * Alles, was eine Seite ohne eigene Starter-Dateien braucht, in EINER
     * Datei: Open Props, normalize, die neun Theme-Quellen, Starter-Bausteine
     * und Seitenübergang. Gemessen am 14.10.2026 (Lighthouse, Handy): sechs
     * blockierende Stylesheets aus dem Bundle, dazu ein @import in
     * normalize-layer.css, das erst nach dessen Laden entdeckt wird — eine
     * dritte Stufe in der Kette.
     *
     * theme.min.css bleibt daneben bestehen: Ersetzt ein Projekt theme.css oder
     * view-transitions.css durch eine eigene Datei in files/, kann das Layout
     * die Starter-Teile nicht mitbündeln und fällt auf die Einzeldateien zurück.
     */
    public const BUNDLE = 'ave.min.css';

    /**
     * Teile vor den Theme-Quellen, relativ zu public/. normalize.min.css wird
     * in @layer av.normalize gehüllt — dasselbe, was normalize-layer.css per
     * @import … layer() tut, nur ohne die zusätzliche Anfrage.
     *
     * @var list<string>
     */
    public const BUNDLE_BEFORE = [
        'open-props/open-props.min.css',
        'open-props/normalize.min.css',
    ];

    /** @var list<string> Teile nach den Theme-Quellen, relativ zu public/. */
    public const BUNDLE_AFTER = [
        'starter/theme.css',
        'starter/view-transitions.css',
    ];

    private readonly string $themeDir;

    /**
     * Der Pfad wird aus der Bundle-Lage abgeleitet, nicht aus dem
     * Projektwurzelverzeichnis: das Bundle liegt im echten Einbau unter
     * vendor/, im Entwicklungsstand daneben.
     */
    public function __construct(?string $themeDir = null)
    {
        $this->themeDir = $themeDir ?? \dirname(__DIR__, 2).'/public/theme';

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('check', null, InputOption::VALUE_NONE, 'Nur prüfen, ob die gebaute Datei aktuell ist');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $gebaut = self::build($this->themeDir);
        $ziel = self::outputPath($this->themeDir);
        $buendel = self::buildBundle($this->themeDir);
        $buendelZiel = self::bundlePath($this->themeDir);

        if ($input->getOption('check')) {
            foreach ([$ziel => $gebaut, $buendelZiel => $buendel] as $datei => $soll) {
                if (!is_file($datei) || file_get_contents($datei) !== $soll) {
                    $io->error(basename($datei).' ist nicht aktuell — `ave:css:build` ausführen.');

                    return Command::FAILURE;
                }
            }

            $io->success('theme.min.css und ave.min.css sind aktuell.');

            return Command::SUCCESS;
        }

        $roh = 0;

        foreach (self::SOURCES as $name) {
            $roh += (int) filesize($this->themeDir.'/'.$name);
        }

        @mkdir(\dirname($ziel), 0o775, true);
        file_put_contents($ziel, $gebaut);
        file_put_contents($buendelZiel, $buendel);

        $io->success(\sprintf(
            '%d Dateien → %s: %s statt %s (gzip %s statt %s)',
            \count(self::SOURCES),
            self::OUTPUT,
            $this->kb(\strlen($gebaut)),
            $this->kb($roh),
            $this->kb(\strlen((string) gzencode($gebaut, 9))),
            $this->kb($roh > 0 ? \strlen((string) gzencode($this->raw($this->themeDir), 9)) : 0),
        ));

        $io->success(\sprintf(
            '%s: %s (gzip %s) — Open Props, normalize, Theme und Starter in einer Datei',
            self::BUNDLE,
            $this->kb(\strlen($buendel)),
            $this->kb(\strlen((string) gzencode($buendel, 9))),
        ));

        return Command::SUCCESS;
    }

    /** Wohin das Erzeugnis geschrieben wird, aus dem Quellordner abgeleitet. */
    public static function outputPath(string $themeDir): string
    {
        return \dirname($themeDir).'/'.self::OUTPUT_DIR.'/'.self::OUTPUT;
    }

    /**
     * Die ausgelieferte Datei aus den Quellen — als reine Funktion, damit ein
     * Test sie nachrechnen kann, ohne den Befehl zu starten.
     */
    public static function build(string $themeDir): string
    {
        $teile = ["/* Avando AVE — zusammengefasst aus ".implode(', ', self::SOURCES).".\n"
            ." * Automatisch erzeugt von `ave:css:build`; nicht von Hand bearbeiten.\n"
            ." * Die Quellen mit der vollständigen Begründungsprosa liegen daneben. */\n"];

        foreach (self::SOURCES as $name) {
            $teile[] = self::strip((string) file_get_contents($themeDir.'/'.$name));
        }

        return rtrim(implode("\n", $teile))."\n";
    }

    public static function bundlePath(string $themeDir): string
    {
        return \dirname($themeDir).'/'.self::OUTPUT_DIR.'/'.self::BUNDLE;
    }

    /**
     * ave.min.css aus denselben Quellen, in der Reihenfolge, in der das Layout
     * sie bisher einzeln verlinkt hat. Die Reihenfolge der @layer-Ebenen bleibt
     * dabei gleich: av.normalize erscheint wie vorher als erste Ebene, noch vor
     * der Liste in tokens.css.
     */
    public static function buildBundle(string $themeDir): string
    {
        $public = \dirname($themeDir);
        $teile = ["/* Avando AVE — Open Props, normalize, Theme und Starter in einer Datei.\n"
            ." * Automatisch erzeugt von `ave:css:build`; nicht von Hand bearbeiten. */\n"];

        foreach (self::BUNDLE_BEFORE as $datei) {
            $css = self::strip((string) file_get_contents($public.'/'.$datei));
            $teile[] = str_ends_with($datei, '/normalize.min.css') ? "@layer av.normalize {\n".$css."\n}" : $css;
        }

        foreach (self::SOURCES as $name) {
            $teile[] = self::strip((string) file_get_contents($themeDir.'/'.$name));
        }

        foreach (self::BUNDLE_AFTER as $datei) {
            $teile[] = self::strip((string) file_get_contents($public.'/'.$datei));
        }

        return rtrim(implode("\n", $teile))."\n";
    }

    private function raw(string $themeDir): string
    {
        $s = '';

        foreach (self::SOURCES as $name) {
            $s .= (string) file_get_contents($themeDir.'/'.$name);
        }

        return $s;
    }

    /**
     * Kommentare raus, Einrückung und Leerzeilen raus. Zeichenkettenbewusst:
     * ein `/*` in einem String oder url() darf keinen Kommentar eröffnen.
     * (Gemessen: im Theme kommt das nirgends vor — der Scanner ist trotzdem so
     * gebaut, weil er sonst eine Falle für den nächsten Inhalt wäre.)
     */
    public static function strip(string $css): string
    {
        $out = '';
        $len = \strlen($css);
        $quote = null;

        for ($i = 0; $i < $len; ++$i) {
            $c = $css[$i];

            if (null !== $quote) {
                $out .= $c;

                if ('\\' === $c && $i + 1 < $len) {
                    $out .= $css[++$i];
                    continue;
                }

                if ($c === $quote) {
                    $quote = null;
                }

                continue;
            }

            if ("'" === $c || '"' === $c) {
                $quote = $c;
                $out .= $c;
                continue;
            }

            if ('/' === $c && $i + 1 < $len && '*' === $css[$i + 1]) {
                $ende = strpos($css, '*/', $i + 2);
                $i = false === $ende ? $len : $ende + 1;
                continue;
            }

            $out .= $c;
        }

        // Einrückung und Leerzeilen. Zeilenenden bleiben, damit die Datei im
        // Browser-Werkzeug noch lesbar ist und ein Fehler eine Zeile nennt.
        $out = preg_replace('/^[ \t]+/m', '', $out) ?? $out;

        return trim((string) preg_replace('/\n{2,}/', "\n", $out));
    }

    private function kb(int $bytes): string
    {
        return number_format($bytes / 1024, 1, ',', '.').' KB';
    }
}

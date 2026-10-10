<?php

declare(strict_types=1);

namespace Avando\AveBundle\Twig;

use Avando\AveBundle\License\ProToken;
use Contao\PageModel;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DbalException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Beantwortet dem Layout die eine Frage: Attributionszeile ausgeben oder nicht?
 *
 * Die Zeile im Seitenfuss ist die Gegenleistung der freien Nutzung. Wer eine
 * kommerzielle Lizenz hat, schaltet sie ab, indem er das Pro-Token im
 * Startpunkt der Website eintraegt (Feld „Pro-Token", tl_page.ave_license).
 * Der Startpunkt ist die Domain — genau die Einheit, fuer die die Lizenz gilt
 * (LICENSE.md § 3). Speichern genuegt: Contao versieht jede Seite mit den
 * Cache-Tags ihres ganzen Pfads, der Startpunkt leert also alle Unterseiten.
 *
 * Fuer Installationen, deren Konfiguration als Code ausgerollt wird, gilt das
 * Token auch in der config.yaml — dann fuer alle Startpunkte:
 *
 *     avando_ave:
 *         license: 'AVE-XXXXX-XXXXX-XXXXX-XXXXX'
 *
 * Warum kein `attribution: false`:
 * Ein Schalter, den man erraten kann, sagt nichts darueber, ob jemand bezahlt
 * hat. Das Token tut es — nicht als Kopierschutz (siehe ProToken), sondern als
 * nachvollziehbarer, bequemer legitimer Weg.
 */
final class AttributionExtension extends AbstractExtension
{
    public function __construct(
        #[Autowire('%avando_ave.license%')]
        private readonly string $license = '',
        private readonly RequestStack|null $requestStack = null,
        private readonly Connection|null $connection = null,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ave_show_attribution', $this->showAttribution(...)),
        ];
    }

    /**
     * Im Zweifel JA. Ein Tippfehler im Token, ein leeres Feld, eine Seite ohne
     * ermittelbaren Startpunkt — in all diesen Faellen steht die Zeile wieder
     * da. Der umgekehrte Standard waere der gefaehrliche: eine Installation,
     * die ihre Attribution still verliert, faellt niemandem auf, denn sie sieht
     * besser aus.
     */
    public function showAttribution(): bool
    {
        if (ProToken::isValid($this->license)) {
            return false;
        }

        return !ProToken::isValid($this->rootPageToken());
    }

    private function rootPageToken(): string
    {
        $page = $this->requestStack?->getMainRequest()?->attributes->get('pageModel');

        if (!$page instanceof PageModel || null === $this->connection) {
            return '';
        }

        $rootId = (int) ($page->rootId ?: ('root' === $page->type ? $page->id : 0));

        if ($rootId <= 0) {
            return '';
        }

        // Nach einem Update, vor contao:migrate, fehlt die Spalte noch. Dann
        // soll die Seite die Zeile zeigen, nicht mit einem Fehler abbrechen.
        try {
            return (string) $this->connection->fetchOne(
                'SELECT ave_license FROM tl_page WHERE id = ?',
                [$rootId],
            );
        } catch (DbalException) {
            return '';
        }
    }
}

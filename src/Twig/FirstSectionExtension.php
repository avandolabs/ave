<?php

declare(strict_types=1);

namespace Avando\AveBundle\Twig;

use Symfony\Contracts\Service\ResetInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Ist dies der erste Artikel einer Spalte auf dieser Seite?
 *
 * Gebraucht für das größte sichtbare Element beim Laden (LCP): Bei AVE ist das
 * fast immer das Hintergrundbild der ersten Sektion im Hauptbereich. Es steht
 * aber nur als CSS-Variable im style-Attribut (--section-bg-sm/md/lg) und wird
 * vom Browser erst entdeckt, wenn alle Stylesheets geladen und angewendet sind.
 * Gemessen am 14.10.2026 auf der Demo (Lighthouse, Handy): LCP 3,2 s, davon
 * 72 % Wartezeit bis zum Zeichnen. mod_article lädt das Bild deshalb vor —
 * aber NUR für die erste Sektion, denn ein vorgeladenes Bild weiter unten
 * nähme dem oberen die Bandbreite weg.
 *
 * Zählt pro Anfrage; ResetInterface setzt den Zähler zwischen Anfragen zurück
 * (wichtig unter FrankenPHP/RoadRunner, wo der Container weiterlebt).
 */
final class FirstSectionExtension extends AbstractExtension implements ResetInterface
{
    /** @var array<string, true> */
    private array $seen = [];

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ave_first_in_column', $this->firstInColumn(...)),
        ];
    }

    /** true beim ersten Aufruf je Spalte, danach false. */
    public function firstInColumn(?string $column): bool
    {
        $column = (string) $column;

        if ('' === $column || isset($this->seen[$column])) {
            return false;
        }

        return $this->seen[$column] = true;
    }

    public function reset(): void
    {
        $this->seen = [];
    }
}

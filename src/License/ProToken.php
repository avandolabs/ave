<?php

declare(strict_types=1);

namespace Avando\AveBundle\License;

/**
 * Prueft das Pro-Token — an genau einer Stelle, damit Backend-Feld und
 * Seitenlayout nie verschieden urteilen.
 *
 * Warum hier nur der HASH steht, nie das Token:
 * Das Theme wird auch kostenlos ausgeliefert. Staende der Wert im Klartext im
 * Quelltext, haette ihn jeder, der das freie Paket herunterlaedt — eine
 * Volltextsuche genuegt. Aus dem SHA-256 laesst er sich nicht zurueckrechnen;
 * das Token selbst liegt nur dem gekauften Paket bei.
 *
 * Was das leistet und was nicht:
 * Es ist kein Kopierschutz. Ein Token, das ein Kunde weitergibt, gilt
 * ueberall, und eine Zeile CSS blendet die Attribution ohne jedes Token aus.
 * Durchgesetzt wird die Lizenz durch LICENSE.md, nicht durch diese Klasse.
 * Was sie leistet: der legitime Weg ist der bequeme — ein Feld im Startpunkt,
 * geprueft beim Speichern — und er bleibt nachvollziehbar.
 */
final class ProToken
{
    /**
     * SHA-256 des Pro-Tokens. Der Klartext steht in der Lieferung des gekauften
     * Pakets — und an keiner anderen Stelle dieses Quelltextes.
     */
    public const HASH = '556e50988f00d4e1d4823d59da01e15a83c70372f144f4c814ba8d2927ed125b';

    /**
     * Umschliessender Leerraum wird verziehen (Einfuegen aus einer E-Mail),
     * alles andere nicht: Gross-/Kleinschreibung zaehlt, leer ist ungueltig.
     */
    public static function isValid(string $token): bool
    {
        $token = trim($token);

        if ('' === $token) {
            return false;
        }

        // hash_equals, nicht ===: der Vergleich soll nicht ueber seine Laufzeit
        // verraten, wie weit ein geratener Wert stimmt.
        return hash_equals(self::HASH, hash('sha256', $token));
    }
}

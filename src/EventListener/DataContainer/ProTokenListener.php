<?php

declare(strict_types=1);

namespace Avando\AveBundle\EventListener\DataContainer;

use Avando\AveBundle\License\ProToken;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

/**
 * Prueft das Pro-Token beim Speichern des Startpunkts.
 *
 * Das ist der eigentliche Gewinn gegenueber der config.yaml: ein falsches
 * Token faellt sofort im Formular auf, statt still die Zeile stehen zu lassen.
 * Gespeichert wird nur, was gueltig ist — oder leer.
 */
#[AsCallback(table: 'tl_page', target: 'fields.ave_license.save')]
final class ProTokenListener
{
    public function __invoke(mixed $value): string
    {
        $token = trim((string) $value);

        if ('' === $token) {
            return '';
        }

        if (!ProToken::isValid($token)) {
            throw new \RuntimeException($GLOBALS['TL_LANG']['tl_page']['ave_license_invalid'] ?? 'Invalid pro token.');
        }

        return $token;
    }
}

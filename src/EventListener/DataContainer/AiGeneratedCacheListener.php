<?php

declare(strict_types=1);

namespace Avando\AveBundle\EventListener\DataContainer;

use Contao\CoreBundle\Cache\CacheTagManager;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

/**
 * Leert den Seiten-Cache, wenn sich der Haken „KI-generiert" an einer Datei
 * ändert.
 *
 * Contao versieht eine Seite nicht mit den Cache-Tags der Bilder, die sie
 * zeigt. Ohne diesen Listener bekäme ein Bild seinen Hinweis auf gecachten
 * Seiten erst, wenn der Cache abläuft — und das ausgerechnet bei einem
 * Hinweis, auf den man sich verlassen will. Welche Seiten das Bild zeigen,
 * weiß niemand; deshalb trifft es die Startpunkte. Jede Seite trägt die Tags
 * ihres ganzen Pfads (PageTrailCacheTagsListener), so leert ein Startpunkt
 * alle Seiten seiner Website. Das kommt selten vor und ist ein Klick im
 * Backend — ein voller Cache-Neuaufbau ist dafür der ehrlichere Preis als ein
 * fehlender Hinweis.
 */
#[AsCallback(table: 'tl_files', target: 'fields.ave_ai_generated.save')]
final class AiGeneratedCacheListener
{
    public function __construct(
        private readonly Connection $connection,
        private readonly CacheTagManager $cacheTagManager,
    ) {
    }

    public function __invoke(mixed $value, DataContainer $dc): mixed
    {
        $before = (string) $this->connection->fetchOne(
            'SELECT ave_ai_generated FROM tl_files WHERE path = ?',
            [(string) $dc->id],
        );

        if ($before === (string) $value) {
            return $value;
        }

        $roots = $this->connection->fetchFirstColumn("SELECT id FROM tl_page WHERE type = 'root'");

        $this->cacheTagManager->invalidateTags(array_map(
            static fn (int|string $id): string => 'contao.db.tl_page.'.$id,
            $roots,
        ));

        return $value;
    }
}

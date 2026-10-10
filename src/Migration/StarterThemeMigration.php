<?php

declare(strict_types=1);

namespace Avando\AveBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

/**
 * Legt bei einer frischen Installation das Gerüst an, das sonst nur im
 * SQL-Dump der Demo steckt: ein Theme „Avando AVE“, darin das Seitenlayout
 * (page/layout, Header mit Navigation, Hauptspalte) und das Navigationsmodul.
 * Danach fehlt nur noch, das Layout der Startseite zuzuweisen.
 *
 * Läuft nur, solange es kein Layout mit page/layout gibt — eine bestehende
 * Installation, die das Theme schon nutzt, bleibt unberührt. Logo, Buttons und
 * Footer sind Inhalt der jeweiligen Website und werden nicht angelegt.
 */
class StarterThemeMigration extends AbstractMigration
{
    private const LAYOUT_TEMPLATE = 'page/layout';

    public function __construct(private readonly Connection $connection)
    {
    }

    public function getName(): string
    {
        return 'Avando AVE: Theme und Seitenlayout anlegen';
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_theme', 'tl_layout', 'tl_module'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_layout');

        if (!isset($columns['template'], $columns['type'], $columns['modules'])) {
            return false;
        }

        return 0 === (int) $this->connection->fetchOne(
            'SELECT COUNT(*) FROM tl_layout WHERE template = ?',
            [self::LAYOUT_TEMPLATE],
        );
    }

    public function run(): MigrationResult
    {
        $time = time();

        $this->insert('tl_theme', [
            'tstamp' => $time,
            'name' => 'Avando AVE',
            'author' => 'Avando',
        ]);
        $themeId = (int) $this->connection->lastInsertId();

        $this->insert('tl_module', [
            'pid' => $themeId,
            'tstamp' => $time,
            'name' => 'Avando AVE – Navigation',
            'type' => 'navigation',
            'navigationTpl' => 'nav_default',
        ]);
        $navigationId = (int) $this->connection->lastInsertId();

        $this->insert('tl_layout', [
            'pid' => $themeId,
            'tstamp' => $time,
            'name' => 'Avando AVE',
            'type' => 'modern',
            'template' => self::LAYOUT_TEMPLATE,
            'rows' => '2rwh',
            'cols' => '1cl',
            'modules' => serialize([
                ['mod' => (string) $navigationId, 'col' => 'header', 'enable' => '1'],
                ['mod' => '0', 'col' => 'main', 'enable' => '1'],
            ]),
            'viewport' => 'width=device-width,initial-scale=1.0',
        ]);

        return $this->createResult(true, 'Theme „Avando AVE“ mit Seitenlayout und Navigation angelegt. Das Layout jetzt der Startseite zuweisen.');
    }

    /**
     * Connection::insert() setzt Spaltennamen ungeschützt ein; `rows` ist seit
     * MySQL 8.0.2 ein reserviertes Wort. Darum alle Namen quoten.
     *
     * @param array<string, int|string> $data
     */
    private function insert(string $table, array $data): void
    {
        $quoted = [];

        foreach ($data as $column => $value) {
            $quoted[$this->connection->quoteIdentifier($column)] = $value;
        }

        $this->connection->insert($table, $quoted);
    }
}

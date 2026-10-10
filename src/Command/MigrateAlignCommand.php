<?php

declare(strict_types=1);

namespace Avando\AveBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Splits the combined section_align field into section_align_x / section_align_y.
 *
 * The old field packed both axes into one string, which forced the "right +
 * vertically centred" option to combine align-content-center with
 * align-content-end. On a .section those resolve to two different properties
 * (justify-content and align-items) and the combination worked; on an element
 * group both are place-content, so the later one won and the result was
 * "bottom right". Splitting the field removes the ambiguity — this command
 * carries existing rows over.
 */
#[AsCommand(
    name: 'ave:migrate:align',
    description: 'Split section_align into separate horizontal/vertical fields (one-time migration).',
)]
class MigrateAlignCommand extends Command
{
    /** Tables carrying the section design fields. */
    private const TABLES = ['tl_article', 'tl_content', 'tl_news', 'tl_calendar_events'];

    /**
     * Old value → [horizontal, vertical].
     *
     * The horizontal classes are unchanged (they were never ambiguous). The
     * vertical ones become av-y-*, which means the same thing in every layout
     * context.
     */
    private const MAP = [
        'align-text-center' => ['align-text-center', ''],
        'align-text-end' => ['align-text-end', ''],
        'align-content-center' => ['', 'av-y-center'],
        'align-text-center align-content-center' => ['align-text-center', 'av-y-center'],
        'align-text-end align-content-center align-content-end' => ['align-text-end', 'av-y-center'],
        // Predates the current option list, still present in existing data. On
        // an element group align-content-end is place-content:end, i.e. bottom
        // AND right — mapped so the page keeps rendering as it does today
        // rather than to a guess at the original intent.
        'align-text-end align-content-end' => ['align-text-end', 'av-y-end'],
    ];

    public function __construct(private readonly Connection $connection)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Show what would change without writing to DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption('dry-run');

        if (!$dryRun && [] !== $missing = $this->tablesMissingTheNewColumns()) {
            $io->error(sprintf(
                "The new columns do not exist yet in: %s\nRun \"contao:migrate\" first — it creates section_align_x / section_align_y from the DCA.",
                implode(', ', $missing),
            ));

            return Command::FAILURE;
        }

        $total = 0;
        $unknown = [];

        foreach (self::TABLES as $table) {
            $total += $this->migrateTable($table, $dryRun, $io, $unknown);
        }

        if ([] !== $unknown) {
            $io->warning(sprintf(
                "Left untouched — no mapping for: %s\nThese still render through the legacy classes; set the new fields by hand.",
                implode(', ', array_unique($unknown)),
            ));
        }

        $io->success(sprintf('%d row(s) %s.', $total, $dryRun ? 'would be migrated (dry-run)' : 'migrated'));

        return Command::SUCCESS;
    }

    /**
     * Tables that still lack the split columns. Checked up front so the run
     * cannot die halfway through with half the rows converted.
     *
     * @return list<string>
     */
    private function tablesMissingTheNewColumns(): array
    {
        $missing = [];
        $schema = $this->connection->createSchemaManager();

        foreach (self::TABLES as $table) {
            try {
                if (!$schema->tablesExist([$table])) {
                    continue; // optional extension (news/calendar) not installed
                }

                $columns = array_map(strtolower(...), array_keys($schema->listTableColumns($table)));

                if (!\in_array('section_align_x', $columns, true) || !\in_array('section_align_y', $columns, true)) {
                    $missing[] = $table;
                }
            } catch (\Throwable) {
                // Unreadable schema is not proof of absence — let the run
                // proceed and fail loudly on the actual statement instead.
                continue;
            }
        }

        return $missing;
    }

    /**
     * @param list<string> $unknown
     */
    private function migrateTable(string $table, bool $dryRun, SymfonyStyle $io, array &$unknown): int
    {
        try {
            $rows = $this->connection->fetchAllAssociative(
                sprintf("SELECT id, section_align FROM %s WHERE section_align != ''", $table),
            );
        } catch (\Throwable $e) {
            // A table without the design fields (or without the new columns yet)
            // is skipped rather than aborting the whole run.
            $io->writeln(sprintf('  <comment>%s skipped: %s</comment>', $table, $e->getMessage()));

            return 0;
        }

        $count = 0;

        foreach ($rows as $row) {
            $old = trim((string) $row['section_align']);

            if (!isset(self::MAP[$old])) {
                $unknown[] = $old;
                continue;
            }

            [$x, $y] = self::MAP[$old];

            $io->writeln(sprintf('  %s#%d  "%s" → x="%s" y="%s"', $table, $row['id'], $old, $x, $y));

            if (!$dryRun) {
                // Clear the legacy value in the same statement: the templates
                // still emit it, so leaving it would apply both old and new
                // classes at once.
                $this->connection->update(
                    $table,
                    ['section_align_x' => $x, 'section_align_y' => $y, 'section_align' => ''],
                    ['id' => $row['id']],
                );
            }

            ++$count;
        }

        return $count;
    }
}

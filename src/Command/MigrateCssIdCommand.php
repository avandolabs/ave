<?php

declare(strict_types=1);

namespace Avando\AveBundle\Command;

use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ave:migrate:cssid',
    description: 'Remove callback-merged ce_style/ce_bg/ce_color tokens from tl_content.cssID (one-time migration).',
)]
class MigrateCssIdCommand extends Command
{
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
        $io     = new SymfonyStyle($input, $output);
        $dryRun = $input->getOption('dry-run');

        $rows = $this->connection->fetchAllAssociative(
            "SELECT id, cssID, ce_style, ce_bg, ce_color FROM tl_content
             WHERE ce_style != '' OR ce_bg != '' OR ce_color != ''",
        );

        if (!$rows) {
            $io->success('Nothing to migrate.');

            return Command::SUCCESS;
        }

        $updated = 0;

        foreach ($rows as $row) {
            // StringUtil::deserialize, not unserialize(): it passes
            // allowed_classes => false, so a tampered column cannot instantiate
            // objects here. Contao writes this field the same way.
            $cssId = StringUtil::deserialize((string) $row['cssID'], true);

            if (!isset($cssId[0], $cssId[1])) {
                $cssId += ['', ''];
            }

            $raw     = (string) ($cssId[1] ?? '');
            $pipePos = strpos($raw, '|');

            if ($pipePos !== false) {
                $classPart = substr($raw, 0, $pipePos);
                $pipePart  = substr($raw, $pipePos);
            } else {
                $classPart = $raw;
                $pipePart  = '';
            }

            // Collect all tokens written by the old callbacks
            $managed = [];
            foreach (['ce_style', 'ce_bg', 'ce_color'] as $field) {
                foreach (explode(' ', (string) $row[$field]) as $token) {
                    $token = trim($token);
                    if ($token !== '') {
                        $managed[] = $token;
                    }
                }
            }

            $remaining = array_filter(
                explode(' ', $classPart),
                static fn (string $c) => $c !== '' && !\in_array($c, $managed, true),
            );

            $newClass = trim(implode(' ', $remaining) . ' ' . $pipePart);
            $newValue = serialize([$cssId[0] ?? '', $newClass]);

            if ($newValue === $row['cssID']) {
                continue;
            }

            $io->writeln(sprintf(
                '[%d] %s → %s',
                $row['id'],
                $row['cssID'],
                $newValue,
            ));

            if (!$dryRun) {
                $this->connection->update('tl_content', ['cssID' => $newValue], ['id' => $row['id']]);
            }

            ++$updated;
        }

        if ($updated === 0) {
            $io->success('All records already clean.');

            return Command::SUCCESS;
        }

        $io->success(sprintf(
            '%d record(s) %s.',
            $updated,
            $dryRun ? 'would be updated (dry-run)' : 'updated',
        ));

        return Command::SUCCESS;
    }
}

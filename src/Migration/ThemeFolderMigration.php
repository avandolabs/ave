<?php

declare(strict_types=1);

namespace Avando\AveBundle\Migration;

use Avando\AveBundle\Theme\ThemeFolder;
use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;

/**
 * Macht files/theme/ bei Installation und Update oeffentlich (siehe
 * ThemeFolder). Laeuft nur, solange etwas fehlt — die Markierung oder der
 * Verweis unter public/.
 */
class ThemeFolderMigration extends AbstractMigration
{
    public function __construct(private readonly ThemeFolder $themeFolder)
    {
    }

    public function getName(): string
    {
        return 'Avando AVE: Ordner files/theme öffentlich machen';
    }

    public function shouldRun(): bool
    {
        return !$this->themeFolder->isPublished();
    }

    public function run(): MigrationResult
    {
        $this->themeFolder->publish();

        return $this->createResult(true, 'files/theme ist öffentlich (.public und Verweis unter public/files/theme).');
    }
}

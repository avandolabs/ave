<?php

declare(strict_types=1);

namespace Avando\AveBundle\Dca;

class ContentElementFields
{
    // Icons are at bundle/public/icons/ — this file is at bundle/src/Dca/
    private const ICONS_DIR = __DIR__ . '/../../public/icons/';

    public static function getBtnIconOptions(): array
    {
        $none    = $GLOBALS['TL_LANG']['tl_content']['ce_btn_icon_none'] ?? 'Kein Icon';
        $options = ['' => $none];

        foreach (glob(self::ICONS_DIR . '*.svg') ?: [] as $path) {
            $name          = basename($path, '.svg');
            $options['icon--' . $name] = ucfirst(str_replace('-', ' ', $name));
        }

        return $options;
    }
}

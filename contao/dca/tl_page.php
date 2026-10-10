<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/*
 * Pro-Token im Startpunkt der Website.
 *
 * Der Startpunkt ist die Domain — die Einheit, fuer die eine kommerzielle
 * Lizenz gilt (LICENSE.md § 3). Geprueft wird beim Speichern
 * (ProTokenListener), ausgewertet im Layout (AttributionExtension).
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['ave_license'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 64, 'decodeEntities' => true, 'tl_class' => 'w50'],
    'sql'       => "varchar(64) NOT NULL default ''",
];

PaletteManipulator::create()
    ->addLegend('ave_legend', 'website_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('ave_license', 'ave_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('root', 'tl_page')
    ->applyToPalette('rootfallback', 'tl_page')
;

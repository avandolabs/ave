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

/*
 * KI-Kennzeichnung: wie und wo der Hinweis an Bildern erscheint, die in der
 * Dateiverwaltung als KI-generiert markiert sind (tl_files.ave_ai_generated).
 *
 * Im Startpunkt, weil die Darstellung zur Website gehört, nicht zum einzelnen
 * Bild: ein Hinweis, der mal oben, mal unten steht, wird übersehen. Leere
 * Werte (Startpunkt vor dem Update angelegt) fallen in AiLabelExtension auf
 * die Vorgaben zurück. Ausgewertet in AiLabelExtension.
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['ave_ai_position'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['bottom-right', 'bottom-left', 'top-right', 'top-left', 'right-vertical', 'center', 'below'],
    'reference' => &$GLOBALS['TL_LANG']['tl_page']['ave_ai_positions'],
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default 'bottom-right'",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['ave_ai_size'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['sm', 'md', 'lg'],
    'reference' => &$GLOBALS['TL_LANG']['tl_page']['ave_ai_sizes'],
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => "varchar(8) NOT NULL default 'md'",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['ave_ai_style'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['dark', 'light', 'accent'],
    'reference' => &$GLOBALS['TL_LANG']['tl_page']['ave_ai_styles'],
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => "varchar(8) NOT NULL default 'dark'",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['ave_ai_text'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 64, 'decodeEntities' => true, 'tl_class' => 'w50'],
    'sql'       => "varchar(64) NOT NULL default ''",
];

PaletteManipulator::create()
    ->addLegend('ave_legend', 'website_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('ave_license', 'ave_legend', PaletteManipulator::POSITION_APPEND)
    ->addLegend('ave_ai_legend', 'ave_legend', PaletteManipulator::POSITION_AFTER)
    ->addField(['ave_ai_position', 'ave_ai_size', 'ave_ai_style', 'ave_ai_text'], 'ave_ai_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('root', 'tl_page')
    ->applyToPalette('rootfallback', 'tl_page')
;

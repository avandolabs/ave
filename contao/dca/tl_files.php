<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/*
 * KI-Kennzeichnung: Haken an der DATEI, nicht am Inhaltselement.
 *
 * Ein Motiv ist KI-generiert oder nicht — unabhängig davon, wie oft und wo es
 * eingebunden ist. Ein Haken genügt also, und der Hinweis erscheint überall:
 * Bild- und Text-Element, Galerie, News- und Event-Listen. Wie und wo er
 * erscheint, legt der Startpunkt fest (tl_page.ave_ai_*), ausgewertet in
 * AiLabelExtension.
 *
 * Eigene Spalte statt eines Eintrags in `meta`: Die Eigenschaft hängt nicht an
 * der Sprache, und der metaWizard kennt nur Textfelder.
 */
$GLOBALS['TL_DCA']['tl_files']['fields']['ave_ai_generated'] = [
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'clr'],
    'sql'       => "char(1) NOT NULL default ''",
];

PaletteManipulator::create()
    ->addField('ave_ai_generated', 'meta', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_files')
;

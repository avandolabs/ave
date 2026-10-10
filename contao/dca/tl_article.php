<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/* ── section_layout ──────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_layout'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [
        '',
        'layout-columns-2',
        'layout-columns-3',
        'layout-columns-4',
        'layout-columns-6',
        'layout-columns-asymmetric',
        'layout-columns-asymmetric--reverse',
        'layout-auto-grid',
        'layout-feature',
        'layout-sidebar',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_layout_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33 clr'],
    'sql'       => "varchar(64) NOT NULL default ''",
];

/* ── section_size ────────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_size'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'section--sm', 'section--lg'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_size_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

/* ── section_height ──────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_height'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'vh-third', 'vh-half', 'vh-large', 'vh-full'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_height_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

/* ── section_bg ──────────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_bg'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [
        '',
        'bg-surface-1',
        'bg-surface-2',
        'bg-primary',
        'bg-secondary',
        'bg-image',
        'bg-image-dark',
        'bg-image-light',
        'bg-image-brand',
        'bg-image-gradient',
        'bg-video',
        'bg-video-dark',
        'bg-video-light',
        'bg-video-brand',
        'bg-video-gradient',
        'bg-gradient-subtle',
        'bg-gradient-primary',
        'bg-gradient-dark',
        'bg-gradient-animated',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_bg_options'],
    'eval'      => ['includeBlankOption' => false, 'submitOnChange' => true, 'tl_class' => 'w33'],
    'sql'       => "varchar(64) NOT NULL default ''",
];

/* ── section_bg_image ────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_bg_image'] = [
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => [
        'filesOnly'  => true,
        'fieldType'  => 'radio',
        'extensions' => 'jpg,jpeg,png,webp,avif,gif,svg',
        'tl_class'   => 'clr',
    ],
    'sql'       => "binary(16) NULL",
];

/* ── Hintergrundvideo ─────────────────────────────────────────────────
   Zwei Felder, beide notwendig — nicht zwei Wege zum selben Ziel:
   Das Standbild traegt, solange das Video laedt, wenn es angehalten ist und
   wenn es gar nicht erst startet (kein JavaScript, reduzierte Bewegung, ein
   Browser der das Format nicht kann). Ohne Standbild waere die Sektion in all
   diesen Faellen leer — und das sind zusammen die haeufigeren. */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_bg_video'] = [
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => [
        'filesOnly'  => true,
        'fieldType'  => 'radio',
        'extensions' => 'mp4,webm,ogv',
        'tl_class'   => 'clr',
    ],
    'sql'       => "binary(16) NULL",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['section_bg_video_poster'] = [
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => [
        'filesOnly'  => true,
        'fieldType'  => 'radio',
        'extensions' => 'jpg,jpeg,png,webp,avif',
        'tl_class'   => 'clr',
    ],
    'sql'       => "binary(16) NULL",
];

/* ── section_text ────────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_text'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'text-scale--sm', 'text-scale--lg', 'text-scale--display'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_text_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33 clr'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

/* ── section_align ───────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_align_x'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'align-text-center', 'align-text-end'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_align_x_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['section_align_y'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'av-y-center', 'av-y-end'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_align_y_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

// Legacy combined field. Out of the palette — kept so data written before the
// split still renders until ave:migrate:align has run.
$GLOBALS['TL_DCA']['tl_article']['fields']['section_align'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [''],
    'eval'      => ['doNotShow' => true],
    'sql'       => "varchar(64) NOT NULL default ''",
];

/* ── section_color ───────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['section_color'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'style-light', 'text-on-light', 'color-primary', 'color-secondary'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_color_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

/* ── extra_style ─────────────────────────────────────────────────────── */
$GLOBALS['TL_DCA']['tl_article']['fields']['extra_style'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 512, 'tl_class' => 'w50'],
    'sql'       => "varchar(512) NOT NULL default ''",
];

/* ── Palette ─────────────────────────────────────────────────────────── */
PaletteManipulator::create()
    ->addLegend('design_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('section_layout',  'design_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('section_size',    'design_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('section_height',  'design_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('section_bg',      'design_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('section_text',    'design_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('section_align_x',   'design_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('section_align_y',   'design_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('section_color',   'design_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('extra_style',     'cssID',         PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_article');

/* section_bg_image only visible when a bg-image* preset is active */
$GLOBALS['TL_DCA']['tl_article']['palettes']['__selector__'][] = 'section_bg';
foreach (['bg-image', 'bg-image-dark', 'bg-image-light', 'bg-image-brand', 'bg-image-gradient'] as $_bgVariant) {
    $GLOBALS['TL_DCA']['tl_article']['subpalettes']['section_bg_' . $_bgVariant] = 'section_bg_image';
}
foreach (['bg-video', 'bg-video-dark', 'bg-video-light', 'bg-video-brand', 'bg-video-gradient'] as $_bgVariant) {
    $GLOBALS['TL_DCA']['tl_article']['subpalettes']['section_bg_' . $_bgVariant] = 'section_bg_video,section_bg_video_poster';
}
unset($_bgVariant);

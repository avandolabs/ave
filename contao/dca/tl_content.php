<?php

declare(strict_types=1);

use Avando\AveBundle\Dca\ContentElementFields;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;
use Contao\System;

// Several design fields below reference TL_LANG['tl_article'][...] for their
// option labels. That language file is not loaded automatically when editing
// tl_content, so load it explicitly — otherwise the dropdowns show raw CSS
// class names instead of translated labels.
System::loadLanguageFile('tl_article');

/* ── Design fields for element_group — same CSS classes as tl_article ── */

$GLOBALS['TL_DCA']['tl_content']['fields']['section_layout'] = [
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
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(64) NOT NULL default ''",
];

/* Abstand ZWISCHEN den Kindern — nicht zu verwechseln mit section_size, das
   den Innenabstand der Gruppe selbst setzt. Die .gap-*-Klassen gab es im Theme
   laengst, erreichbar waren sie aber nur ueber "Eigene CSS-Klassen".
   Der Studio-Regler „Grid-Abstand" wirkt daneben weiter global; dieses Feld
   uebersteuert ihn fuer eine einzelne Gruppe. */
$GLOBALS['TL_DCA']['tl_content']['fields']['section_gap'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'gap-none', 'gap-sm', 'gap-md', 'gap-lg', 'gap-xl'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['section_gap_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

/* Nur bei der Elementgruppe sinnvoll: die Klasse wirkt auf die KINDER, ein
   einzelnes Inhaltselement hat keine, zwischen denen zu trennen waere. */
$GLOBALS['TL_DCA']['tl_content']['fields']['section_dividers'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'dividers-v'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['section_dividers_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['section_size'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'space-box-sm', 'space-box-md', 'space-box-lg'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['section_size_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['section_height'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'vh-third', 'vh-half', 'vh-large', 'vh-full'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_height_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['extra_style'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 512, 'tl_class' => 'w50'],
    'sql'       => "varchar(512) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['section_bg_image'] = [
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

$GLOBALS['TL_DCA']['tl_content']['fields']['section_bg'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [
        '',
        'bg-none',
        'bg-surface-1',
        'bg-surface-2',
        'bg-primary',
        'bg-secondary',
        'bg-image',
        'bg-image-dark',
        'bg-image-light',
        'bg-image-brand',
        'bg-image-secondary',
        'bg-image-gradient',
        'bg-gradient-subtle',
        'bg-gradient-primary',
        'bg-gradient-secondary',
        'bg-gradient-dark',
        'bg-gradient-animated',
        'style-glass',
        'style-glass-dark',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_bg_options'],
    'eval'      => ['includeBlankOption' => false, 'submitOnChange' => true, 'tl_class' => 'w50'],
    'sql'       => "varchar(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['section_text'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'text-scale--sm', 'text-scale--lg', 'text-scale--display'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_text_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['section_align_x'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'align-text-center', 'align-text-end'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_align_x_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['section_align_y'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'av-y-center', 'av-y-end'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_align_y_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

// Legacy combined field. Out of the palette — kept so data written before the
// split still renders until ave:migrate:align has run.
$GLOBALS['TL_DCA']['tl_content']['fields']['section_align'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [''],
    'eval'      => ['doNotShow' => true],
    'sql'       => "varchar(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['section_color'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'style-light', 'text-on-light', 'color-primary', 'color-secondary'],
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['section_color_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

if (!empty($GLOBALS['TL_DCA']['tl_content']['palettes']['element_group'])) {
    // In Contao 5, :hide legends use v-if — fields are removed from the DOM when collapsed
    // and therefore not submitted. DC_Table then overwrites the DB value with the widget
    // default (empty string). Removing :hide keeps expert_legend always rendered.
    $GLOBALS['TL_DCA']['tl_content']['palettes']['element_group'] = str_replace(
        '{expert_legend:hide}',
        '{expert_legend}',
        $GLOBALS['TL_DCA']['tl_content']['palettes']['element_group'],
    );

    PaletteManipulator::create()
        ->addLegend('design_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE)
        // Order = clean w50 pairs: (Layout, Breite) (Stil, Hintergrund)
        // (Textfarbe, Ausrichtung) (Text, Abstand). clr on each row's first field.
        ->addField('section_layout', 'design_legend',  PaletteManipulator::POSITION_APPEND)
        ->addField('ce_span',        'section_layout', PaletteManipulator::POSITION_AFTER)
        ->addField('ce_style',       'ce_span',        PaletteManipulator::POSITION_AFTER)
        ->addField('section_bg',     'design_legend',  PaletteManipulator::POSITION_APPEND)
        ->addField('section_color',  'design_legend',  PaletteManipulator::POSITION_APPEND)
        ->addField('section_align_x', 'design_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('section_align_y', 'design_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('section_text',   'design_legend',  PaletteManipulator::POSITION_APPEND)
        ->addField('section_size',   'design_legend',  PaletteManipulator::POSITION_APPEND)
        // Die drei Abstands-/Trennungsfragen stehen bewusst beieinander:
        // Innenabstand der Gruppe, Abstand zwischen den Kindern, Trennlinien.
        // Genau dort wird gesucht, wer „keinen Abstand" einstellen will.
        ->addField('section_gap',      'design_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('section_dividers', 'design_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('section_height', 'design_legend',  PaletteManipulator::POSITION_APPEND)
        ->addField('ce_space_below', 'design_legend',  PaletteManipulator::POSITION_APPEND)
        ->addField('extra_style',    'cssID',          PaletteManipulator::POSITION_AFTER)
        ->applyToPalette('element_group', 'tl_content');

    /* section_bg_image only visible when a bg-image* preset is active */
    $GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'section_bg';
    foreach (['bg-image', 'bg-image-dark', 'bg-image-light', 'bg-image-brand', 'bg-image-gradient'] as $_bgVariant) {
        $GLOBALS['TL_DCA']['tl_content']['subpalettes']['section_bg_' . $_bgVariant] = 'section_bg_image';
    }
    unset($_bgVariant);
}

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_bg'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [
        '',
        'bg-none',
        'bg-surface-1',
        'bg-surface-2',
        'bg-primary',
        'bg-secondary',
        'bg-image',
        'bg-image-dark',
        'bg-image-light',
        'bg-image-brand',
        'bg-image-secondary',
        'bg-image-gradient',
        'bg-gradient-subtle',
        'bg-gradient-primary',
        'bg-gradient-secondary',
        'bg-gradient-dark',
        'style-glass',
        'style-glass-dark',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_bg_options'],
    'eval'      => ['includeBlankOption' => false, 'submitOnChange' => true, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_bg_image'] = [
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

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_color'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'style-light', 'text-on-light', 'color-primary', 'color-secondary'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_color_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_span'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'col-span-2', 'col-span-3', 'col-span-4', 'col-span-5', 'col-span-full'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_span_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_align'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'align-text-center', 'align-text-end'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_align_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(32) NOT NULL default ''",
];

// Horizontal self-alignment for content-width elements (e.g. a button /
// hyperlink CE, which is inline-flex and can't be centred via the parent's
// text-align). Shrinks to content and positions L/M/R via auto inline margins.
$GLOBALS['TL_DCA']['tl_content']['fields']['ce_self_align'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'self-start', 'self-center', 'self-end'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_self_align_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(24) NOT NULL default ''",
];

// Image shape + optional border (Image CE). Land on the <img> via image.html.twig.
$GLOBALS['TL_DCA']['tl_content']['fields']['ce_img_shape'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'shape-rounded', 'shape-squircle', 'shape-circle', 'shape-blob'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_img_shape_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(24) NOT NULL default ''",
];
$GLOBALS['TL_DCA']['tl_content']['fields']['ce_img_border'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [
        '',
        'shape-border',
        'shape-border shape-border--secondary',
        'shape-border shape-border--light',
        'shape-border shape-border--dark',
        'shape-shadow',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_img_border_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(48) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_space_below'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'space-below-xs', 'space-below-sm', 'space-below-md', 'space-below-lg', 'space-below-xl', 'space-below-2xl'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_space_below_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(24) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_size'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'display-1', 'display-2', 'display-3', 'text-xl', 'text-lg'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_size_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_tracking'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'tracking-tight', 'tracking-normal', 'tracking-wide', 'tracking-widest'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_tracking_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_btn_icon'] = [
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => [ContentElementFields::class, 'getBtnIconOptions'],
    'eval'             => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'              => "varchar(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_btn_icon_pos'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['', 'icon--before'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon_pos_options'],
    'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w50'],
    'sql'       => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ce_style'] = [
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => static function (DataContainer $dc): array {
        $type = $dc->activeRecord->type ?? '';
        $l    = $GLOBALS['TL_LANG']['tl_content'];

        if ($type === 'hyperlink') {
            $r = $l['ce_btn_styles'] ?? [];
            return [
                ''                      => $r['']                      ?? 'Kein Button-Stil',
                'btn'                   => $r['btn']                   ?? 'Primär',
                'btn btn--secondary'    => $r['btn btn--secondary']    ?? 'Sekundär',
                'btn btn--neutral'      => $r['btn btn--neutral']      ?? 'Neutral',
                'btn btn--outline'      => $r['btn btn--outline']      ?? 'Outline',
                'btn btn--outline-secondary' => $r['btn btn--outline-secondary'] ?? 'Outline Sekundär',
                'btn btn--ghost'        => $r['btn btn--ghost']        ?? 'Ghost',
                'btn btn--soft'         => $r['btn btn--soft']         ?? 'Soft',
                'btn btn--dark'         => $r['btn btn--dark']         ?? 'Dunkel',
                'btn btn--glass'        => $r['btn btn--glass']        ?? 'Glas',
                'btn btn--lg'           => $r['btn btn--lg']           ?? 'Groß',
                'btn btn--sm'           => $r['btn btn--sm']           ?? 'Klein',
                'btn btn--pill'         => $r['btn btn--pill']         ?? 'Pill',
                'btn btn--lg btn--pill' => $r['btn btn--lg btn--pill'] ?? 'Groß + Pill',
            ];
        }

        $r = $l['ce_styles'] ?? [];
        $styles = [
            ''                       => $r['']                       ?? 'Kein Stil',
            'style-card'             => $r['style-card']             ?? 'Karte',
            'style-interactive-card' => $r['style-interactive-card'] ?? 'Interaktive Karte',
            // Die Kachel ist die Flaeche ohne Radius, Schatten und Rahmen —
            // die einzige Kartenform, die mit Trennlinien und mit
            // Spaltenabstand „Keiner" zusammengeht.
            'style-tile'             => $r['style-tile']             ?? 'Kachel',
        ];

        // Nur bei der Elementgruppe. Die Media-Karte lebt davon, dass ein BILD
        // an erster Stelle steht: es laeuft randlos bis an die Kartenkanten,
        // alles danach wird zum gepolsterten Koerper. An einem einzelnen Text-
        // oder Bild-Element gaebe es nichts, was sie unterscheiden koennte —
        // die Option waere dort tot und wuerde nur die Liste verlaengern.
        if ('element_group' === $type) {
            $styles['media-card'] = $r['media-card'] ?? 'Media-Karte (Bild randlos oben, Text darunter)';
        }

        return $styles + [
            'style-shadow'           => $r['style-shadow']           ?? 'Schatten',
            'style-rounded'          => $r['style-rounded']          ?? 'Abgerundet',
            'style-squircle'         => $r['style-squircle']         ?? 'Squircle (experimentell)',
            'style-rounded style-shadow' => $r['style-rounded style-shadow'] ?? 'Abgerundet + Schatten',
        ];
    },
    'eval' => ['includeBlankOption' => false, 'tl_class' => 'w50 clr'],
    'sql'  => "varchar(64) NOT NULL default ''",
];

foreach (['text', 'image', 'hyperlink', 'list', 'table', 'code', 'download', 'downloads'] as $type) {
    if (!empty($GLOBALS['TL_DCA']['tl_content']['palettes'][$type])) {
        // Field order is tuned so the w50 fields pair into tidy rows (clr on the
        // left field of each pair): (Stil|Breite) (Bildform|Rahmen) (Hintergrund|
        // Textfarbe) (Ausrichtung|Abstand).
        $pm = PaletteManipulator::create()
            ->addLegend('style_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE, true)
            ->addField('ce_style',    'style_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('ce_span',     'style_legend', PaletteManipulator::POSITION_APPEND);

        // Image + Text CE: image shape + optional border/shadow, on the attached
        // <img> (the text CE has an optional image).
        if ($type === 'image' || $type === 'text') {
            $pm->addField('ce_img_shape',  'style_legend', PaletteManipulator::POSITION_APPEND)
               ->addField('ce_img_border', 'style_legend', PaletteManipulator::POSITION_APPEND);
        }

        // Background (+ text colour). A hyperlink's colour comes from its button
        // style (ce_style), so the generic text-colour preset is omitted there.
        if ($type !== 'hyperlink') {
            $pm->addField('ce_bg',    'style_legend', PaletteManipulator::POSITION_APPEND)
               ->addField('ce_color', 'style_legend', PaletteManipulator::POSITION_APPEND);
        }

        // Alignment: text = horizontal text alignment (cascades into the RTE);
        // hyperlink (button) = horizontal self-alignment L/M/R (inline-flex can't
        // be centred via the parent's text-align).
        if ($type === 'text') {
            $pm->addField('ce_align', 'style_legend', PaletteManipulator::POSITION_APPEND);
        }
        if ($type === 'image') {
            $pm->addField('ce_self_align', 'style_legend', PaletteManipulator::POSITION_APPEND);
        }
        if ($type === 'hyperlink') {
            $pm->addField('ce_self_align',   'style_legend', PaletteManipulator::POSITION_APPEND)
               ->addField('ce_btn_icon',     'style_legend', PaletteManipulator::POSITION_APPEND)
               ->addField('ce_btn_icon_pos', 'style_legend', PaletteManipulator::POSITION_APPEND);
        }

        $pm->addField('ce_space_below', 'style_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('extra_style', 'cssID', PaletteManipulator::POSITION_AFTER)
            ->applyToPalette($type, 'tl_content');
    }
}

// extra_style for CE types outside the design-preset set above (e.g. accordion),
// so per-element token overrides like --av-accordion-bg work there too.
foreach (['accordion'] as $type) {
    if (!empty($GLOBALS['TL_DCA']['tl_content']['palettes'][$type])) {
        PaletteManipulator::create()
            ->addLegend('style_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE, true)
            ->addField('ce_space_below', 'style_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('extra_style', 'cssID', PaletteManipulator::POSITION_AFTER)
            ->applyToPalette($type, 'tl_content');
    }
}

// Headline CE: font colour + text alignment only (the card/bg presets don't fit
// a lone heading). Classes land on the <h*> itself via _base → _ce_preset.
foreach (['headline'] as $type) {
    if (!empty($GLOBALS['TL_DCA']['tl_content']['palettes'][$type])) {
        PaletteManipulator::create()
            ->addLegend('style_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE, true)
            ->addField('ce_size',     'style_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('ce_tracking', 'style_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('ce_color',    'style_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('ce_align',    'style_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('ce_space_below', 'style_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('extra_style', 'cssID',        PaletteManipulator::POSITION_AFTER)
            ->applyToPalette($type, 'tl_content');
    }
}

/* ce_bg_image only visible when a bg-image* preset is active (background image
   with overlay for a content element, e.g. a text CE). Mirrors section_bg. */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'ce_bg';
foreach (['bg-image', 'bg-image-dark', 'bg-image-light', 'bg-image-brand', 'bg-image-gradient'] as $_ceBgVariant) {
    $GLOBALS['TL_DCA']['tl_content']['subpalettes']['ce_bg_' . $_ceBgVariant] = 'ce_bg_image';
}
unset($_ceBgVariant);


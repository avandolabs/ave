<?php

declare(strict_types=1);

namespace Avando\AveBundle\Dca;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\System;

/**
 * Registers the article "Design" fields (section_*) on another DCA table, so
 * News and Event items get the same section-level design controls as articles.
 *
 * Single source of truth: the field definitions live here and are shared by
 * tl_news / tl_calendar_events; the labels + option references are reused from
 * tl_article's language files (loaded on demand), so translations stay DRY and
 * never drift between the tables.
 *
 * Rendering is handled separately (parseArticles hook + news_full override for
 * news; event_full override for events) — this class only makes the fields
 * available and stored in the back end.
 */
final class SectionDesignFields
{
    /**
     * @param list<string> $palettes Palettes to extend (default: the standard content palette)
     */
    public static function register(string $table, array $palettes = ['default']): void
    {
        // Reuse the article's labels + option references as the single source.
        System::loadLanguageFile('tl_article');
        $lang = &$GLOBALS['TL_LANG']['tl_article'];

        $GLOBALS['TL_LANG'][$table]['design_legend'] = &$lang['design_legend'];

        $fields = [
            'section_layout' => [
                'inputType' => 'select',
                'options'   => ['', 'layout-columns-2', 'layout-columns-3', 'layout-columns-4', 'layout-columns-6', 'layout-columns-asymmetric', 'layout-columns-asymmetric--reverse', 'layout-auto-grid', 'layout-feature', 'layout-sidebar'],
                'reference' => &$lang['section_layout_options'],
                'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33 clr'],
                'sql'       => "varchar(64) NOT NULL default ''",
            ],
            'section_size' => [
                'inputType' => 'select',
                'options'   => ['', 'section--sm', 'section--lg'],
                'reference' => &$lang['section_size_options'],
                'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33'],
                'sql'       => "varchar(16) NOT NULL default ''",
            ],
            'section_height' => [
                'inputType' => 'select',
                'options'   => ['', 'vh-third', 'vh-half', 'vh-large', 'vh-full'],
                'reference' => &$lang['section_height_options'],
                'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33'],
                'sql'       => "varchar(16) NOT NULL default ''",
            ],
            'section_bg' => [
                'inputType' => 'select',
                'options'   => ['', 'bg-surface-1', 'bg-surface-2', 'bg-primary', 'bg-secondary', 'bg-image', 'bg-image-dark', 'bg-image-light', 'bg-image-brand', 'bg-image-gradient', 'bg-gradient-subtle', 'bg-gradient-primary', 'bg-gradient-dark', 'bg-gradient-animated'],
                'reference' => &$lang['section_bg_options'],
                'eval'      => ['includeBlankOption' => false, 'submitOnChange' => true, 'tl_class' => 'w33'],
                'sql'       => "varchar(64) NOT NULL default ''",
            ],
            'section_bg_image' => [
                'inputType' => 'fileTree',
                'eval'      => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png,webp,avif,gif,svg', 'tl_class' => 'clr'],
                'sql'       => "binary(16) NULL",
            ],
            'section_text' => [
                'inputType' => 'select',
                'options'   => ['', 'text-scale--sm', 'text-scale--lg', 'text-scale--display'],
                'reference' => &$lang['section_text_options'],
                'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33 clr'],
                'sql'       => "varchar(32) NOT NULL default ''",
            ],
            'section_align_x' => [
                'inputType' => 'select',
                'options'   => ['', 'align-text-center', 'align-text-end'],
                'reference' => &$lang['section_align_x_options'],
                'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33'],
                'sql'       => "varchar(32) NOT NULL default ''",
            ],
            'section_align_y' => [
                'inputType' => 'select',
                'options'   => ['', 'av-y-center', 'av-y-end'],
                'reference' => &$lang['section_align_y_options'],
                'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33'],
                'sql'       => "varchar(32) NOT NULL default ''",
            ],
            // Legacy combined field. No longer in the palette — kept so data
            // written before the split still renders until ave:migrate:align
            // has run, and so the migration has something to read.
            'section_align' => [
                'inputType' => 'select',
                'options'   => [''],
                'eval'      => ['doNotShow' => true],
                'sql'       => "varchar(64) NOT NULL default ''",
            ],
            'section_color' => [
                'inputType' => 'select',
                'options'   => ['', 'style-light', 'text-on-light', 'color-primary', 'color-secondary'],
                'reference' => &$lang['section_color_options'],
                'eval'      => ['includeBlankOption' => false, 'tl_class' => 'w33'],
                'sql'       => "varchar(16) NOT NULL default ''",
            ],
            'extra_style' => [
                'inputType' => 'text',
                'eval'      => ['maxlength' => 512, 'tl_class' => 'w50'],
                'sql'       => "varchar(512) NOT NULL default ''",
            ],
        ];

        foreach ($fields as $name => $definition) {
            $definition['exclude'] = true;
            $definition['label'] = &$lang[$name];
            $GLOBALS['TL_DCA'][$table]['fields'][$name] = $definition;
            unset($definition);
        }

        $pm = PaletteManipulator::create()
            ->addLegend('design_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE);

        foreach (['section_layout', 'section_size', 'section_height', 'section_bg', 'section_text', 'section_align_x', 'section_align_y', 'section_color', 'extra_style'] as $field) {
            $pm->addField($field, 'design_legend', PaletteManipulator::POSITION_APPEND);
        }

        foreach ($palettes as $palette) {
            $pm->applyToPalette($palette, $table);
        }

        // section_bg_image only shows when a bg-image* preset is active
        $GLOBALS['TL_DCA'][$table]['palettes']['__selector__'][] = 'section_bg';

        foreach (['bg-image', 'bg-image-dark', 'bg-image-light', 'bg-image-brand', 'bg-image-gradient'] as $variant) {
            $GLOBALS['TL_DCA'][$table]['subpalettes']['section_bg_' . $variant] = 'section_bg_image';
        }
    }
}

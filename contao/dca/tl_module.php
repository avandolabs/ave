<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_module']['palettes']['ave_onepage_nav'] =
    '{title_legend},name,headline,type;'
    . '{links_legend},onepage_links;'
    . '{protected_legend:hide},protected;'
    . '{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['onepage_links'] = [
    'inputType' => 'textarea',
    'eval'      => ['style' => 'height:140px; font-family: monospace', 'tl_class' => 'clr'],
    'sql'       => ['type' => 'text', 'notnull' => false],
];

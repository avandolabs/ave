<?php

declare(strict_types=1);

$GLOBALS['TL_LANG']['tl_page']['ave_legend']          = 'Avando AVE';
$GLOBALS['TL_LANG']['tl_page']['ave_license']         = ['Pro token', 'The token from the delivery of your commercial licence (AVE-XXXXX-XXXXX-XXXXX-XXXXX). Once a valid token is entered, the line “Built with the free Avando AVE theme” disappears from the footer of this website. Leave empty if you use AVE under the free licence.'];
$GLOBALS['TL_LANG']['tl_page']['ave_license_invalid'] = 'This pro token is invalid. Please copy it completely from your delivery (it is case-sensitive).';

// AI labelling
$GLOBALS['TL_LANG']['tl_page']['ave_ai_legend']    = 'AI labelling';
$GLOBALS['TL_LANG']['tl_page']['ave_ai_enabled']   = ['Output AI notices', 'AVE labels images that are marked as “AI-generated” in the file manager. Switch off if Contao or an extension takes care of this itself – otherwise the notice appears twice. The checkboxes on the files are kept.'];
$GLOBALS['TL_LANG']['tl_page']['ave_ai_position']  = ['Position', 'Where the notice appears on images that are marked as “AI-generated” in the file manager. Applies to the whole website, including background images; there “Below the image” appears bottom right.'];
$GLOBALS['TL_LANG']['tl_page']['ave_ai_size']      = ['Size', 'Font size of the notice.'];
$GLOBALS['TL_LANG']['tl_page']['ave_ai_style']     = ['Appearance', '“Accent” uses the primary colour of the design. “No background” shows the text only: white with a shadow on the image, in the page’s text colour below it.'];
$GLOBALS['TL_LANG']['tl_page']['ave_ai_text']      = ['Label', 'Leave empty for “AI-generated” (German pages: “KI-generiert”).'];
$GLOBALS['TL_LANG']['tl_page']['ave_ai_positions'] = [
    'bottom-right' => 'On the image, bottom right',
    'bottom-center' => 'On the image, bottom centre',
    'bottom-left'  => 'On the image, bottom left',
    'top-right'    => 'On the image, top right',
    'top-left'     => 'On the image, top left',
    'right-vertical' => 'On the image, right edge, vertical',
    'center'       => 'In the centre of the image',
    'below'        => 'Below the image',
];
$GLOBALS['TL_LANG']['tl_page']['ave_ai_sizes']     = ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'];
$GLOBALS['TL_LANG']['tl_page']['ave_ai_styles']    = ['dark' => 'Dark', 'light' => 'Light', 'accent' => 'Accent', 'none' => 'No background'];

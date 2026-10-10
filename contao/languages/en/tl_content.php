<?php

declare(strict_types=1);

$GLOBALS['TL_LANG']['tl_content']['extra_style'] = ['Inline styles', 'Any CSS declarations, e.g. --av-gutter: 2rem; color: red; padding: 1rem'];

$GLOBALS['TL_LANG']['tl_content']['ce_style'] = [
    'Element Style',
    'Optional visual frame for this content element.',
];

$GLOBALS['TL_LANG']['tl_content']['style_legend']  = 'Element Style';
$GLOBALS['TL_LANG']['tl_content']['design_legend'] = 'Design';

$GLOBALS['TL_LANG']['tl_content']['section_layout'] = ['Layout', 'Column or grid layout for child elements.'];
$GLOBALS['TL_LANG']['tl_content']['section_size']         = ['Spacing', 'Inner padding of this element group.'];
$GLOBALS['TL_LANG']['tl_content']['section_height']       = ['Minimum height', 'Minimum height relative to the viewport – for hero areas. Combine with "Centered (text + content)" to vertically centre the content.'];
$GLOBALS['TL_LANG']['tl_content']['section_gap'] = ['Column gap', 'Spacing between the elements of this group — horizontally as well as vertically once the grid wraps. "Default" follows the theme and the "Grid gap" slider in the Design Studio; any other choice applies to this group only. Not to be confused with "Spacing", which pads the group itself.'];
$GLOBALS['TL_LANG']['tl_content']['section_gap_options'] = [
    ''         => 'Default',
    'gap-none' => 'None',
    'gap-sm'   => 'Small',
    'gap-md'   => 'Medium',
    'gap-lg'   => 'Large',
    'gap-xl'   => 'Extra large',
];
$GLOBALS['TL_LANG']['tl_content']['section_dividers'] = ['Dividers', 'Vertical rules inside the column gaps. They disappear on their own once the grid collapses to a single column. Meant for plain columns — where the elements carry their own background, rounding and shadow they already separate themselves and the hairline is lost in them. Horizontal box padding is removed while they are on, because the rules need the container edge as their clipping edge.'];
$GLOBALS['TL_LANG']['tl_content']['section_dividers_options'] = [
    ''           => 'None',
    'dividers-v' => 'Between columns',
];
$GLOBALS['TL_LANG']['tl_content']['section_size_options'] = [
    ''             => 'Default',
    'space-box-sm' => 'Small',
    'space-box-md' => 'Medium',
    'space-box-lg' => 'Large',
];
$GLOBALS['TL_LANG']['tl_content']['section_bg']       = ['Background', 'Background color or image for this element group.'];
$GLOBALS['TL_LANG']['tl_content']['section_bg_image'] = ['Background image', 'Image for the background. Scaled to 1920 px width and delivered as WebP.'];
$GLOBALS['TL_LANG']['tl_content']['section_text']   = ['Text size', 'Heading size scale for this element group.'];
$GLOBALS['TL_LANG']['tl_content']['section_align']  = ['Alignment', 'Horizontal and vertical alignment of content.'];

$GLOBALS['TL_LANG']['tl_content']['section_align_x'] = ['Horizontal alignment', 'Text alignment and the position of content that shrinks to its own width.'];
$GLOBALS['TL_LANG']['tl_content']['section_align_y'] = ['Vertical alignment', 'Aligns the columns inside the group. Only has an effect when the group is taller than its content — e.g. with columns of differing length, or a set height.'];
$GLOBALS['TL_LANG']['tl_content']['section_color']  = ['Text colour', 'Text colour context for this element group.'];

$GLOBALS['TL_LANG']['tl_content']['ce_bg']    = ['Background', 'Background colour or image for this content element.'];
$GLOBALS['TL_LANG']['tl_content']['ce_bg_image'] = ['Background image', 'Image for the background. Scaled to 1920 px width and delivered as WebP.'];
$GLOBALS['TL_LANG']['tl_content']['ce_color']  = ['Text colour', 'Text colour context for this content element.'];
$GLOBALS['TL_LANG']['tl_content']['ce_span']   = ['Width', 'Spans several columns of the parent grid layout. The resulting share depends on the group\'s column count (e.g. 3 tracks = 50% in "6 columns", 75% in "4 columns"). Higher values (4/5) only take effect in "6 columns".'];

$GLOBALS['TL_LANG']['tl_content']['ce_span_options'] = [
    ''              => 'Auto (1 track)',
    'col-span-2'    => '2 tracks wide',
    'col-span-3'    => '3 tracks wide',
    'col-span-4'    => '4 tracks wide (6 columns only)',
    'col-span-5'    => '5 tracks wide (6 columns only)',
    'col-span-full' => 'Full width',
];

$GLOBALS['TL_LANG']['tl_content']['ce_align'] = ['Text alignment', 'Horizontal text alignment.'];
$GLOBALS['TL_LANG']['tl_content']['ce_align_options'] = [
    ''                  => 'Left (default)',
    'align-text-center' => 'Center',
    'align-text-end'    => 'Right',
];

$GLOBALS['TL_LANG']['tl_content']['ce_self_align'] = ['Self-alignment', 'Horizontal position of the element — shrinks to content width (e.g. a button left/centre/right).'];
$GLOBALS['TL_LANG']['tl_content']['ce_self_align_options'] = [
    ''            => 'Default',
    'self-start'  => 'Left',
    'self-center' => 'Centred',
    'self-end'    => 'Right',
];

$GLOBALS['TL_LANG']['tl_content']['ce_img_shape'] = ['Image shape', 'Shape / mask of the image.'];
$GLOBALS['TL_LANG']['tl_content']['ce_img_shape_options'] = [
    ''               => 'Default (rectangular)',
    'shape-rounded'  => 'Rounded',
    'shape-squircle' => 'Squircle (experimental)',
    'shape-circle'   => 'Circle',
    'shape-blob'     => 'Blob (organic)',
];
$GLOBALS['TL_LANG']['tl_content']['ce_img_border'] = ['Image border', 'Border or shadow — follows the image shape.'];
$GLOBALS['TL_LANG']['tl_content']['ce_img_border_options'] = [
    ''                                     => 'None',
    'shape-border'                         => 'Primary',
    'shape-border shape-border--secondary' => 'Secondary',
    'shape-border shape-border--light'     => 'Light',
    'shape-border shape-border--dark'      => 'Dark',
    'shape-shadow'                         => 'Shadow',
];

$GLOBALS['TL_LANG']['tl_content']['ce_space_below'] = ['Space below', 'Additional bottom margin below this element.'];
$GLOBALS['TL_LANG']['tl_content']['ce_space_below_options'] = [
    ''                => 'Default',
    'space-below-xs'  => 'Extra small',
    'space-below-sm'  => 'Small',
    'space-below-md'  => 'Medium',
    'space-below-lg'  => 'Large',
    'space-below-xl'  => 'Extra large',
    'space-below-2xl' => 'Huge',
];

$GLOBALS['TL_LANG']['tl_content']['ce_size'] = ['Text size', 'Visual size — independent of the heading level (h1–h6).'];
$GLOBALS['TL_LANG']['tl_content']['ce_size_options'] = [
    ''          => 'Default (from level)',
    'display-1' => 'Huge',
    'display-2' => 'Very large',
    'display-3' => 'Large',
    'text-xl'   => 'Medium',
    'text-lg'   => 'Small',
];

$GLOBALS['TL_LANG']['tl_content']['ce_tracking'] = ['Letter spacing', 'Character spacing (tracking).'];
$GLOBALS['TL_LANG']['tl_content']['ce_tracking_options'] = [
    ''                => 'Default',
    'tracking-tight'  => 'Tight',
    'tracking-normal' => 'Normal',
    'tracking-wide'   => 'Wide',
    'tracking-widest' => 'Widest',
];

$GLOBALS['TL_LANG']['tl_content']['ce_bg_options'] = [
    ''                    => 'Default',
    'bg-none'             => 'None (remove surface)',
    'bg-surface-1'        => 'Base surface',
    'bg-surface-2'        => 'Offset surface',
    'bg-primary'          => 'Primary colour',
    'bg-secondary'        => 'Secondary colour',
    'bg-image'            => 'Background image',
    'bg-image-dark'       => 'Background image — dark overlay',
    'bg-image-light'      => 'Background image — light overlay',
    'bg-image-brand'      => 'Background image — primary overlay',
    'bg-image-secondary'  => 'Background image — secondary overlay',
    'bg-image-gradient'   => 'Background image — gradient overlay',
    'bg-gradient-subtle'  => 'Subtle gradient',
    'bg-gradient-primary' => 'Primary gradient',
    'bg-gradient-secondary' => 'Secondary gradient',
    'bg-gradient-dark'    => 'Gradient',
    'style-glass'         => 'Glass',
    'style-glass-dark'    => 'Glass dark',
];

$GLOBALS['TL_LANG']['tl_content']['ce_color_options'] = [
    ''               => 'Default',
    'style-light'    => 'Light (for dark backgrounds)',
    'text-on-light'  => 'Dark (for light backgrounds)',
    'color-primary'  => 'Primary colour',
    'color-secondary'=> 'Secondary colour',
];

$GLOBALS['TL_LANG']['tl_content']['ce_btn_styles'] = [
    ''                      => 'Default (text link)',
    'btn'                   => 'Primary',
    'btn btn--secondary'    => 'Secondary',
    'btn btn--neutral'      => 'Neutral',
    'btn btn--outline'      => 'Outline',
    'btn btn--outline-secondary' => 'Outline secondary',
    'btn btn--ghost'        => 'Ghost (transparent)',
    'btn btn--soft'         => 'Soft (muted primary colour)',
    'btn btn--dark'         => 'Dark',
    'btn btn--glass'        => 'Glass (requires dark background)',
    'btn btn--lg'           => 'Large',
    'btn btn--sm'           => 'Small',
    'btn btn--pill'         => 'Pill (strongly rounded)',
    'btn btn--lg btn--pill' => 'Large + Pill',
];

$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon']     = ['Button icon', 'Icon to the right or left of the button — choose from the list.'];
$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon_none'] = 'No icon';
$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon_pos']  = ['Icon position', 'Where should the icon appear?'];
$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon_pos_options'] = [
    ''             => 'Right (default)',
    'icon--before' => 'Left (before)',
];

$GLOBALS['TL_LANG']['tl_content']['ce_styles'] = [
    ''                       => 'Default',
    'style-card'             => 'Card',
    'style-interactive-card' => 'Card (interactive, lifts on hover)',
    'style-tile'             => 'Tile (surface without rounding or shadow — for grids with dividers)',
    'media-card'             => 'Media card (edge-to-edge image on top, text below)',
    'style-shadow'           => 'Shadow',
    'style-rounded'          => 'Rounded',
    'style-squircle'         => 'Squircle (experimental)',
    'style-rounded style-shadow' => 'Rounded + shadow',
];

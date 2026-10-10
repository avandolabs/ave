<?php

declare(strict_types=1);

$GLOBALS['TL_LANG']['tl_article']['design_legend'] = 'Design';

$GLOBALS['TL_LANG']['tl_article']['extra_style'] = ['Inline styles', 'Any CSS declarations, e.g. --av-gutter: 2rem; color: red; padding: 1rem'];

$GLOBALS['TL_LANG']['tl_article']['section_bg']       = ['Background', 'Background color or image for this section.'];
$GLOBALS['TL_LANG']['tl_article']['section_bg_image'] = ['Background image', 'Image for the background. Scaled to 1920 px width and delivered as WebP.'];
$GLOBALS['TL_LANG']['tl_article']['section_bg_options'] = [
    ''                    => 'Default',
    'bg-none'            => 'None (remove surface)',
    'bg-surface-1'        => 'Base surface',
    'bg-surface-2'        => 'Offset surface',
    'bg-primary'          => 'Primary colour',
    'bg-secondary'        => 'Secondary color',
    'bg-image'            => 'Background image',
    'bg-image-dark'       => 'Background image — dark overlay',
    'bg-image-light'      => 'Background image — light overlay',
    'bg-image-brand'      => 'Background image — primary overlay',
    'bg-image-secondary'  => 'Background image — secondary overlay',
    'bg-image-gradient'   => 'Background image — gradient overlay',
    'bg-video'            => 'Background video',
    'bg-video-dark'       => 'Background video — dark overlay',
    'bg-video-light'      => 'Background video — light overlay',
    'bg-video-brand'      => 'Background video — primary overlay',
    'bg-video-gradient'   => 'Background video — gradient overlay',
    'bg-gradient-subtle'  => 'Gradient — subtle',
    'bg-gradient-primary' => 'Gradient — primary colour',
    'bg-gradient-secondary' => 'Gradient — secondary colour',
    'bg-gradient-dark'      => 'Gradient',
    'bg-gradient-animated'  => 'Gradient — animated',
    'style-glass'           => 'Glass',
    'style-glass-dark'      => 'Glass dark',
];

$GLOBALS['TL_LANG']['tl_article']['section_color'] = ['Text color', 'Color context for this section (Light = white text, Dark = black text).'];
$GLOBALS['TL_LANG']['tl_article']['section_color_options'] = [
    ''               => 'Default (inherit context)',
    'style-light'    => 'White (light text for dark backgrounds)',
    'text-on-light'  => 'Black (dark text for light backgrounds)',
    'color-primary'  => 'Primary colour',
    'color-secondary' => 'Secondary colour',
];

$GLOBALS['TL_LANG']['tl_article']['section_size'] = ['Spacing', 'Vertical padding of this section.'];
$GLOBALS['TL_LANG']['tl_article']['section_size_options'] = [
    ''            => 'Default',
    'section--sm' => 'Small',
    'section--lg' => 'Large',
];

$GLOBALS['TL_LANG']['tl_article']['section_height'] = ['Minimum height', 'Minimum height relative to the viewport – for hero areas. Combine with "Centered (text + content)" to vertically centre the content.'];
$GLOBALS['TL_LANG']['tl_article']['section_height_options'] = [
    ''         => 'Default (content)',
    'vh-third' => 'Third (33%)',
    'vh-half'  => 'Half (50%)',
    'vh-large' => 'Large (75%)',
    'vh-full'  => 'Full (100%)',
];

$GLOBALS['TL_LANG']['tl_article']['section_layout'] = ['Layout', 'Column or grid layout for the content elements.'];
$GLOBALS['TL_LANG']['tl_article']['section_layout_options'] = [
    ''                                   => 'Default (single column)',
    'layout-columns-2'                   => 'Two columns',
    'layout-columns-3'                   => 'Three columns',
    'layout-columns-4'                   => 'Four columns',
    'layout-columns-6'                   => 'Six columns',
    'layout-columns-asymmetric'          => 'Asymmetric 2:1',
    'layout-columns-asymmetric--reverse' => 'Asymmetric 1:2',
    'layout-auto-grid'                   => 'Auto grid',
    'layout-feature'                     => 'Text + image',
    'layout-sidebar'                     => 'Content + sidebar',
];

$GLOBALS['TL_LANG']['tl_article']['section_align'] = ['Alignment', 'Horizontal and vertical alignment of content.'];
$GLOBALS['TL_LANG']['tl_article']['section_align_options'] = [
    ''                                                    => 'Left (default)',
    'align-text-center'                                   => 'Center',
    'align-text-end'                                      => 'Right',
    'align-content-center'                                => 'Left, vertically centered',
    'align-text-center align-content-center'              => 'Center, vertically centered',
    'align-text-end align-content-center align-content-end' => 'Right, vertically centered',
];

$GLOBALS['TL_LANG']['tl_article']['section_align_x'] = ['Horizontal alignment', 'Text alignment and the position of content that shrinks to its own width.'];
$GLOBALS['TL_LANG']['tl_article']['section_align_x_options'] = [
    ''                  => 'Left (default)',
    'align-text-center' => 'Centre',
    'align-text-end'    => 'Right',
];

$GLOBALS['TL_LANG']['tl_article']['section_align_y'] = ['Vertical alignment', 'Only has an effect when the box is taller than its content — e.g. with a viewport height, or with columns of differing length.'];
$GLOBALS['TL_LANG']['tl_article']['section_align_y_options'] = [
    ''            => 'Top (default)',
    'av-y-center' => 'Centre',
    'av-y-end'    => 'Bottom',
];

$GLOBALS['TL_LANG']['tl_article']['section_bg_video'] = ['Background video', 'MP4 or WebM. Plays muted and looped. A pause button is added automatically — without it a moving background fails WCAG 2.2.2.'];
$GLOBALS['TL_LANG']['tl_article']['section_bg_video_poster'] = ['Still image', 'Shown while the video loads, while it is paused, and when it never starts (no JavaScript, reduced motion, unsupported format). Without it the section stays empty in those cases.'];
$GLOBALS['TL_LANG']['tl_article']['section_text'] = ['Text size', 'Heading size scale for this section.'];
$GLOBALS['TL_LANG']['tl_article']['section_text_options'] = [
    'text-scale--sm'      => 'Small',
    ''                    => 'Default',
    'text-scale--lg'      => 'Large',
    'text-scale--display' => 'Hero',
];

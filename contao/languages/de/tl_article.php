<?php

declare(strict_types=1);

$GLOBALS['TL_LANG']['tl_article']['design_legend'] = 'Design';

$GLOBALS['TL_LANG']['tl_article']['extra_style'] = ['Inline-Styles', 'Beliebige CSS-Deklarationen, z.B. --av-gutter: 2rem; color: red; padding: 1rem'];

$GLOBALS['TL_LANG']['tl_article']['section_bg']       = ['Hintergrund', 'Hintergrundfarbe oder -bild dieser Sektion.'];
$GLOBALS['TL_LANG']['tl_article']['section_bg_image'] = ['Hintergrundbild', 'Bild für den Hintergrund. Wird auf 1920 px Breite skaliert und als WebP ausgeliefert.'];
$GLOBALS['TL_LANG']['tl_article']['section_bg_options'] = [
    ''                   => 'Standard',
    'bg-none'            => 'Keiner (Fläche entfernen)',
    'bg-surface-1'       => 'Grundfläche',
    'bg-surface-2'       => 'Abgesetzte Fläche',
    'bg-primary'         => 'Primärfarbe',
    'bg-secondary'       => 'Sekundärfarbe',
    'bg-image'          => 'Hintergrundbild',
    'bg-image-dark'     => 'Hintergrundbild — Overlay dunkel',
    'bg-image-light'    => 'Hintergrundbild — Overlay hell',
    'bg-image-brand'    => 'Hintergrundbild — Overlay Primärfarbe',
    'bg-image-secondary' => 'Hintergrundbild — Overlay Sekundärfarbe',
    'bg-image-gradient' => 'Hintergrundbild — Overlay Verlauf',
    'bg-video'          => 'Hintergrundvideo',
    'bg-video-dark'     => 'Hintergrundvideo — Overlay dunkel',
    'bg-video-light'    => 'Hintergrundvideo — Overlay hell',
    'bg-video-brand'    => 'Hintergrundvideo — Overlay Primärfarbe',
    'bg-video-gradient' => 'Hintergrundvideo — Overlay Verlauf',
    'bg-gradient-subtle' => 'Verlauf — sanft',
    'bg-gradient-primary' => 'Verlauf — Primärfarbe',
    'bg-gradient-secondary' => 'Verlauf — Sekundärfarbe',
    'bg-gradient-dark'     => 'Farbverlauf',
    'bg-gradient-animated' => 'Verlauf — animiert',
    'style-glass'         => 'Glas',
    'style-glass-dark'    => 'Glas dunkel',
];

$GLOBALS['TL_LANG']['tl_article']['section_color'] = ['Textfarbe', 'Textkontext der Sektion (Hell = weiße Texte, Dunkel = schwarze Texte).'];
$GLOBALS['TL_LANG']['tl_article']['section_color_options'] = [
    ''               => 'Standard (erbt Kontext)',
    'style-light'    => 'Weiß (heller Text für dunkle Hintergründe)',
    'text-on-light'  => 'Schwarz (dunkler Text für helle Hintergründe)',
    'color-primary'  => 'Primärfarbe',
    'color-secondary' => 'Sekundärfarbe',
];

$GLOBALS['TL_LANG']['tl_article']['section_size'] = ['Abstand', 'Vertikaler Innenabstand der Sektion.'];
$GLOBALS['TL_LANG']['tl_article']['section_size_options'] = [
    ''           => 'Standard',
    'section--sm' => 'Klein',
    'section--lg' => 'Groß',
];

$GLOBALS['TL_LANG']['tl_article']['section_height'] = ['Mindesthöhe', 'Mindesthöhe relativ zum Viewport – für Hero-Bereiche. Mit „Zentriert (Text + Inhalt)" kombinieren, um den Inhalt vertikal zu zentrieren.'];
$GLOBALS['TL_LANG']['tl_article']['section_height_options'] = [
    ''         => 'Standard (Inhalt)',
    'vh-third' => 'Drittel (33 %)',
    'vh-half'  => 'Halb (50 %)',
    'vh-large' => 'Groß (75 %)',
    'vh-full'  => 'Voll (100 %)',
];

$GLOBALS['TL_LANG']['tl_article']['section_layout'] = ['Layout', 'Spalten- oder Grid-Layout für die Inhaltselemente.'];
$GLOBALS['TL_LANG']['tl_article']['section_layout_options'] = [
    ''                                   => 'Standard (einspaltig)',
    'layout-columns-2'                   => 'Zweispaltig',
    'layout-columns-3'                   => 'Dreispaltig',
    'layout-columns-4'                   => 'Vierspaltig',
    'layout-columns-6'                   => 'Sechsspaltig',
    'layout-columns-asymmetric'          => 'Asymmetrisch 2:1',
    'layout-columns-asymmetric--reverse' => 'Asymmetrisch 1:2',
    'layout-auto-grid'                   => 'Auto-Raster',
    'layout-feature'                     => 'Text + Bild',
    'layout-sidebar'                     => 'Hauptinhalt + Seitenleiste',
];

$GLOBALS['TL_LANG']['tl_article']['section_align'] = ['Ausrichtung', 'Horizontale und vertikale Ausrichtung des Inhalts.'];
$GLOBALS['TL_LANG']['tl_article']['section_align_options'] = [
    ''                                                    => 'Links (Standard)',
    'align-text-center'                                   => 'Mittig',
    'align-text-end'                                      => 'Rechts',
    'align-content-center'                                => 'Links, vertikal zentriert',
    'align-text-center align-content-center'              => 'Mittig, vertikal zentriert',
    'align-text-end align-content-center align-content-end' => 'Rechts, vertikal zentriert',
];

$GLOBALS['TL_LANG']['tl_article']['section_align_x'] = ['Ausrichtung horizontal', 'Textausrichtung und Position von Inhalten, die sich auf ihre Breite verkleinern.'];
$GLOBALS['TL_LANG']['tl_article']['section_align_x_options'] = [
    ''                  => 'Links (Standard)',
    'align-text-center' => 'Mittig',
    'align-text-end'    => 'Rechts',
];

$GLOBALS['TL_LANG']['tl_article']['section_align_y'] = ['Ausrichtung vertikal', 'Wirkt nur, wenn die Box höher ist als ihr Inhalt — etwa bei einer Viewport-Höhe oder bei Spalten unterschiedlicher Länge.'];
$GLOBALS['TL_LANG']['tl_article']['section_align_y_options'] = [
    ''            => 'Oben (Standard)',
    'av-y-center' => 'Mittig',
    'av-y-end'    => 'Unten',
];

$GLOBALS['TL_LANG']['tl_article']['section_bg_video'] = ['Hintergrundvideo', 'MP4 oder WebM. Läuft stumm und in Schleife. Ein Pause-Knopf wird automatisch eingeblendet — ohne ihn verstößt bewegter Hintergrund gegen WCAG 2.2.2.'];
$GLOBALS['TL_LANG']['tl_article']['section_bg_video_poster'] = ['Standbild', 'Wird gezeigt, solange das Video lädt, wenn es angehalten ist und wenn es gar nicht startet (kein JavaScript, reduzierte Bewegung, nicht unterstütztes Format). Ohne Standbild bleibt die Sektion in diesen Fällen leer.'];
$GLOBALS['TL_LANG']['tl_article']['section_text'] = ['Textgröße', 'Schriftgröße der Überschriften in dieser Sektion.'];
$GLOBALS['TL_LANG']['tl_article']['section_text_options'] = [
    'text-scale--sm'   => 'Klein',
    ''                 => 'Standard',
    'text-scale--lg'   => 'Groß',
    'text-scale--display' => 'Hero',
];

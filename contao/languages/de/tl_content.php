<?php

declare(strict_types=1);

$GLOBALS['TL_LANG']['tl_content']['extra_style'] = ['Inline-Styles', 'Beliebige CSS-Deklarationen, z.B. --av-gutter: 2rem; color: red; padding: 1rem'];

$GLOBALS['TL_LANG']['tl_content']['ce_style'] = [
    'Element-Stil',
    'Optionaler visueller Rahmen für dieses Inhaltselement.',
];

$GLOBALS['TL_LANG']['tl_content']['style_legend']  = 'Element-Stil';
$GLOBALS['TL_LANG']['tl_content']['design_legend'] = 'Design';

$GLOBALS['TL_LANG']['tl_content']['section_layout'] = ['Layout', 'Spalten- oder Grid-Layout für die Kind-Elemente.'];
$GLOBALS['TL_LANG']['tl_content']['section_size']         = ['Abstand', 'Innenabstand der Elementgruppe.'];
$GLOBALS['TL_LANG']['tl_content']['section_height']       = ['Mindesthöhe', 'Mindesthöhe relativ zum Viewport – für Hero-Bereiche. Mit „Zentriert (Text + Inhalt)" kombinieren, um den Inhalt vertikal zu zentrieren.'];
$GLOBALS['TL_LANG']['tl_content']['section_gap'] = ['Spaltenabstand', 'Abstand zwischen den Elementen dieser Gruppe — waagerecht wie senkrecht, sobald das Raster umbricht. „Standard" folgt dem Theme und dem Regler „Grid-Abstand" im Design Studio; jede andere Wahl gilt nur für diese Gruppe. Nicht zu verwechseln mit „Abstand", das die Gruppe selbst innen polstert.'];
$GLOBALS['TL_LANG']['tl_content']['section_gap_options'] = [
    ''         => 'Standard',
    'gap-none' => 'Keiner',
    'gap-sm'   => 'Klein',
    'gap-md'   => 'Mittel',
    'gap-lg'   => 'Groß',
    'gap-xl'   => 'Sehr groß',
];
$GLOBALS['TL_LANG']['tl_content']['section_dividers'] = ['Trennlinien', 'Senkrechte Striche in den Spaltenlücken. Bricht das Raster auf eine Spalte um, verschwinden sie von selbst. Gedacht für schlichte Spalten — haben die Elemente eine eigene Hintergrundfläche mit Rundung und Schatten, trennen sie sich bereits selbst und die Linie geht darin unter. Der seitliche Innenabstand der Gruppe wird aufgehoben, weil die Linien die Containerkante als Schnittkante brauchen.'];
$GLOBALS['TL_LANG']['tl_content']['section_dividers_options'] = [
    ''           => 'Keine',
    'dividers-v' => 'Zwischen den Spalten',
];
$GLOBALS['TL_LANG']['tl_content']['section_size_options'] = [
    ''             => 'Standard',
    'space-box-sm' => 'Klein',
    'space-box-md' => 'Mittel',
    'space-box-lg' => 'Groß',
];
$GLOBALS['TL_LANG']['tl_content']['section_bg']       = ['Hintergrund', 'Hintergrundfarbe oder -bild der Elementgruppe.'];
$GLOBALS['TL_LANG']['tl_content']['section_bg_image'] = ['Hintergrundbild', 'Bild für den Hintergrund. Wird auf 1920 px Breite skaliert und als WebP ausgeliefert.'];
$GLOBALS['TL_LANG']['tl_content']['section_text']   = ['Textgröße', 'Schriftgröße der Überschriften in dieser Gruppe.'];
$GLOBALS['TL_LANG']['tl_content']['section_align']  = ['Ausrichtung', 'Horizontale und vertikale Ausrichtung des Inhalts.'];

$GLOBALS['TL_LANG']['tl_content']['section_align_x'] = ['Ausrichtung horizontal', 'Textausrichtung und Position von Inhalten, die sich auf ihre Breite verkleinern.'];
$GLOBALS['TL_LANG']['tl_content']['section_align_y'] = ['Ausrichtung vertikal', 'Richtet die Spalten innerhalb der Gruppe aus. Wirkt nur, wenn die Gruppe höher ist als ihr Inhalt — etwa bei unterschiedlich langen Spalten oder gesetzter Höhe.'];
$GLOBALS['TL_LANG']['tl_content']['section_color']  = ['Textfarbe', 'Textkontext der Elementgruppe.'];

$GLOBALS['TL_LANG']['tl_content']['ce_bg']    = ['Hintergrund', 'Hintergrundfarbe oder -bild des Inhaltselements.'];
$GLOBALS['TL_LANG']['tl_content']['ce_bg_image'] = ['Hintergrundbild', 'Bild für den Hintergrund. Wird auf 1920 px Breite skaliert und als WebP ausgeliefert.'];
$GLOBALS['TL_LANG']['tl_content']['ce_color']  = ['Textfarbe', 'Textfarbenkontext des Inhaltselements.'];
$GLOBALS['TL_LANG']['tl_content']['ce_span']   = ['Breite', 'Überspannt mehrere Spalten des übergeordneten Grid-Layouts. Der resultierende Anteil hängt von der Spaltenzahl der Gruppe ab (z. B. 3 Tracks = 50 % in „6 Spalten", 75 % in „4 Spalten"). Höhere Werte (4/5) wirken nur bei „6 Spalten".'];

$GLOBALS['TL_LANG']['tl_content']['ce_span_options'] = [
    ''              => 'Auto (1 Track)',
    'col-span-2'    => '2 Tracks breit',
    'col-span-3'    => '3 Tracks breit',
    'col-span-4'    => '4 Tracks breit (nur bei 6 Spalten)',
    'col-span-5'    => '5 Tracks breit (nur bei 6 Spalten)',
    'col-span-full' => 'Volle Breite',
];

$GLOBALS['TL_LANG']['tl_content']['ce_align'] = ['Textausrichtung', 'Horizontale Ausrichtung des Textes.'];
$GLOBALS['TL_LANG']['tl_content']['ce_align_options'] = [
    ''                  => 'Links (Standard)',
    'align-text-center' => 'Mittig',
    'align-text-end'    => 'Rechts',
];

$GLOBALS['TL_LANG']['tl_content']['ce_self_align'] = ['Selbst-Ausrichtung', 'Horizontale Position des Elements — schrumpft auf Inhaltsbreite (z. B. Button links/mittig/rechts).'];
$GLOBALS['TL_LANG']['tl_content']['ce_self_align_options'] = [
    ''            => 'Standard',
    'self-start'  => 'Links',
    'self-center' => 'Mittig',
    'self-end'    => 'Rechts',
];

$GLOBALS['TL_LANG']['tl_content']['ce_img_shape'] = ['Bildform', 'Form/Maske des Bildes.'];
$GLOBALS['TL_LANG']['tl_content']['ce_img_shape_options'] = [
    ''               => 'Standard (eckig)',
    'shape-rounded'  => 'Abgerundet',
    'shape-squircle' => 'Squircle (experimentell)',
    'shape-circle'   => 'Rund (Kreis)',
    'shape-blob'     => 'Blob (organisch)',
];
$GLOBALS['TL_LANG']['tl_content']['ce_img_border'] = ['Bild-Rahmen', 'Rahmen oder Schatten — folgt der Bildform.'];
$GLOBALS['TL_LANG']['tl_content']['ce_img_border_options'] = [
    ''                                     => 'Kein',
    'shape-border'                         => 'Primär',
    'shape-border shape-border--secondary' => 'Sekundär',
    'shape-border shape-border--light'     => 'Hell',
    'shape-border shape-border--dark'      => 'Dunkel',
    'shape-shadow'                         => 'Schatten',
];

$GLOBALS['TL_LANG']['tl_content']['ce_space_below'] = ['Abstand nach unten', 'Zusätzlicher Außenabstand unter diesem Element.'];
$GLOBALS['TL_LANG']['tl_content']['ce_space_below_options'] = [
    ''                => 'Standard',
    'space-below-xs'  => 'Sehr klein',
    'space-below-sm'  => 'Klein',
    'space-below-md'  => 'Mittel',
    'space-below-lg'  => 'Groß',
    'space-below-xl'  => 'Sehr groß',
    'space-below-2xl' => 'Riesig',
];

$GLOBALS['TL_LANG']['tl_content']['ce_size'] = ['Textgröße', 'Optische Größe — unabhängig vom Überschriften-Level (h1–h6).'];
$GLOBALS['TL_LANG']['tl_content']['ce_size_options'] = [
    ''          => 'Standard (vom Level)',
    'display-1' => 'Riesig',
    'display-2' => 'Sehr groß',
    'display-3' => 'Groß',
    'text-xl'   => 'Mittel',
    'text-lg'   => 'Klein',
];

$GLOBALS['TL_LANG']['tl_content']['ce_tracking'] = ['Laufweite', 'Zeichenabstand (Tracking).'];
$GLOBALS['TL_LANG']['tl_content']['ce_tracking_options'] = [
    ''                => 'Standard',
    'tracking-tight'  => 'Eng',
    'tracking-normal' => 'Normal',
    'tracking-wide'   => 'Weit',
    'tracking-widest' => 'Sehr weit',
];

$GLOBALS['TL_LANG']['tl_content']['ce_bg_options'] = [
    ''                    => 'Standard',
    'bg-none'             => 'Keiner (Fläche entfernen)',
    'bg-surface-1'        => 'Grundfläche',
    'bg-surface-2'        => 'Abgesetzte Fläche',
    'bg-primary'          => 'Primärfarbe',
    'bg-secondary'        => 'Sekundärfarbe',
    'bg-image'            => 'Hintergrundbild',
    'bg-image-dark'       => 'Hintergrundbild — Overlay dunkel',
    'bg-image-light'      => 'Hintergrundbild — Overlay hell',
    'bg-image-brand'      => 'Hintergrundbild — Overlay Primärfarbe',
    'bg-image-secondary'  => 'Hintergrundbild — Overlay Sekundärfarbe',
    'bg-image-gradient'   => 'Hintergrundbild — Overlay Verlauf',
    'bg-gradient-subtle'  => 'Sanfter Verlauf',
    'bg-gradient-primary' => 'Primärfarbe-Verlauf',
    'bg-gradient-secondary' => 'Sekundärfarbe-Verlauf',
    'bg-gradient-dark'    => 'Farbverlauf',
    'style-glass'         => 'Glas',
    'style-glass-dark'    => 'Glas dunkel',
];

$GLOBALS['TL_LANG']['tl_content']['ce_color_options'] = [
    ''               => 'Standard',
    'style-light'    => 'Hell (für dunkle Hintergründe)',
    'text-on-light'  => 'Dunkel (für helle Hintergründe)',
    'color-primary'  => 'Primärfarbe',
    'color-secondary'=> 'Sekundärfarbe',
];

$GLOBALS['TL_LANG']['tl_content']['ce_btn_styles'] = [
    ''                      => 'Standard (Textlink)',
    'btn'                   => 'Primär-Button',
    'btn btn--secondary'    => 'Sekundär-Button',
    'btn btn--neutral'      => 'Neutral-Button',
    'btn btn--outline'      => 'Outline-Button',
    'btn btn--outline-secondary' => 'Outline Sekundär',
    'btn btn--ghost'        => 'Ghost (transparent)',
    'btn btn--soft'         => 'Soft (gedämpfte Primärfarbe)',
    'btn btn--dark'         => 'Dunkel',
    'btn btn--glass'        => 'Glas (benötigt dunklen Hintergrund)',
    'btn btn--lg'           => 'Groß',
    'btn btn--sm'           => 'Klein',
    'btn btn--pill'         => 'Pill (stark abgerundet)',
    'btn btn--lg btn--pill' => 'Groß + Pill',
];

$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon']     = ['Button-Icon', 'Icon rechts oder links am Button — wähle ein Icon aus der Liste.'];
$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon_none'] = 'Kein Icon';
$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon_pos']  = ['Icon-Position', 'Wo soll das Icon erscheinen?'];
$GLOBALS['TL_LANG']['tl_content']['ce_btn_icon_pos_options'] = [
    ''             => 'Rechts (Standard)',
    'icon--before' => 'Links (davor)',
];

$GLOBALS['TL_LANG']['tl_content']['ce_styles'] = [
    ''                       => 'Standard',
    'style-card'             => 'Karte',
    'style-interactive-card' => 'Karte (interaktiv, hebt sich beim Hover)',
    'style-tile'             => 'Kachel (Fläche ohne Rundung und Schatten — für Raster mit Trennlinien)',
    'media-card'             => 'Media-Karte (Bild randlos oben, Text darunter)',
    'style-shadow'           => 'Schatten',
    'style-rounded'          => 'Abgerundet',
    'style-squircle'         => 'Squircle (experimentell)',
    'style-rounded style-shadow' => 'Abgerundet + Schatten',
];

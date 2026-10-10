<?php

declare(strict_types=1);

use Avando\AveBundle\Dca\SectionDesignFields;

/*
 * Give Event items the same section-level design fields as articles
 * (section_bg, section_layout, section_color, …). Rendering is applied via the
 * event_full.html.twig override (the calendar bundle has no parseArticles-style
 * hook, so the template reads the fields from the event model directly).
 */
SectionDesignFields::register('tl_calendar_events');

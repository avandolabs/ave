<?php

declare(strict_types=1);

use Avando\AveBundle\Dca\SectionDesignFields;

/*
 * Give News items the same section-level design fields as articles
 * (section_bg, section_layout, section_color, …). Rendering is applied via the
 * parseArticles hook + the news_full.html.twig override.
 */
SectionDesignFields::register('tl_news');

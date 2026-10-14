# Avando AVE — AI Development Instructions

## Project Identity
**Avando AVE** is a performance-optimized, premium B2B theme framework for Contao.
Package: `avando/ave` · Bundle namespace: `Avando\AveBundle`
PHP minimum: 8.3 · Contao: 5.7 and 6.0 (`^5.7 || ^6.0`, one code base for both) ·
Templates: 100% native Twig (no legacy `.html5`)

The theme runs on its own. The Design Studio (`avando/ave-editor`) is an optional,
paid add-on — never make the bundle depend on a file, table or service that only
the Studio creates (`tests/Consistency/FreeThemeStandsAloneTest.php` guards this).

---

## Core Rules (Non-Negotiable)

### 1. Data Integrity — Contao Models, never raw SQL
Never write raw SQL to create or modify pages, articles, or content elements.
Always use Contao's native model layer:
- `Contao\PageModel`
- `Contao\ArticleModel`
- `Contao\ContentModel`
- Domain-specific models via `\Contao\Model::getClassFromTable()`

Raw SQL bypasses DCA hooks and causes data structure corruption.
Records the theme needs on a fresh install (theme, page layout, navigation
module) come from `src/Migration/StarterThemeMigration.php`, never from a demo
database dump.

### 2. Design Tokens — `--av-*` first, Open Props underneath
The theme's public interface is its set of **`--av-*` custom properties**
(`public/theme/tokens.css`, every one listed with its default in
`cheat-sheet.md`). Change the look by setting these, not by writing new rules.
Where a raw scale value is needed, use **Open Props** variables:
```css
/* CORRECT */
gap: var(--av-gutter);
color: var(--av-color-text-muted);
box-shadow: var(--shadow-2);

/* WRONG — never hardcode values or use Tailwind utilities */
gap: 12px;
```
Colour tokens use `light-dark()`; dark mode follows `color-scheme` and the
classes `html.light` / `html.dark` (set by the footer switch,
`public/theme/theme-switch.js`). A colour override with a single fixed value
switches dark mode off for that token — write `light-dark(<light>, <dark>)`.

Do not install Tailwind, Bootstrap, or any utility-first framework.
CSS budget: the whole theme is **one** stylesheet, `dist/ave.min.css` —
Open Props, the layered reset, the nine theme sources and the starter files,
187 KB raw, **34 KB gzipped** (measured 14.10.2026, `ave:css:build` prints it
on every run). If a project replaces `theme.css` or `view-transitions.css`
in files/, the layout falls back to `dist/theme.min.css` (theme only) plus
the single files. `theme-vars.css` is inlined as `<style id="ave-theme-vars">`
when small and safe (`ave_theme_vars_inline()`).
There is no separate critical path, so measure the one file instead of
budgeting a thing that does not exist.

### 3. Markup — Native HTML5 Semantics
Use the semantically correct HTML5 element for every pattern:

| Pattern    | Required element(s)            |
|------------|-------------------------------|
| Accordion  | `<details>` + `<summary>`     |
| Navigation | `<nav>` + `<ul>` / `<a>`      |
| Dialog     | `<dialog>`                    |
| Progress   | `<progress>`                  |
| Tabs       | `<role=“tablist“>` (ARIA)     |

Never implement accordions with `<div>`+JS click toggles.

### 4. Performance & DSGVO
- 100% local assets in the frontend — no external CDNs, no Google Fonts request
  from a visitor's browser. The Design Studio *downloads* a chosen family from
  Google once and self-hosts it under `files/theme/fonts/<slug>/`, licence file
  alongside; the visitor never contacts Google.
- Zero render-blocking JavaScript (defer/async or `<script type=“module“>`).
  One documented exception: the four-line inline script in
  `page/layout.html.twig` that applies the stored light/dark choice before
  first paint — any other solution flashes the wrong scheme.
- Responsive images exclusively via Contao's image manager. The bundle ships
  its image sizes in `config/image_sizes.yaml` (prepended by
  `AvandoAveExtension`; WebP plus the original format, labels in
  `translations/image_sizes.*.yaml`).
- No third-party tracking scripts

---

## Where Customisations Go

Order in which the layout loads the theme (later wins):

| File | Owner | Notes |
|------|-------|-------|
| `bundles/avandoave/open-props/*` | bundle | values only, plus the layered reset |
| `bundles/avandoave/dist/ave.min.css` | bundle | everything below in one file (built by `ave:css:build`) |
| `bundles/avandoave/dist/theme.min.css` | bundle | all theme CSS inside `@layer av.*` |
| `files/theme/CSS/theme.css`, `view-transitions.css` | project | optional; fall back to `bundles/avandoave/starter/` |
| `files/theme/CSS/theme-vars.css` | Design Studio | generated — **never edit by hand**, the Studio overwrites it |
| `files/theme/CSS/custom.css` | project | **unlayered** — beats every theme rule without `!important`, survives updates |

Layer order (`tokens.css`):
`av.normalize, av.tokens, av.base, av.typography, av.components, av.forms, av.theme, av.layout, av.animations`.

For an AI agent restyling a site, the smallest correct change is a `:root { … }`
block of `--av-*` tokens in `files/theme/CSS/custom.css`. Most dependent values
(secondary colour, hover tones, muted text, dark variants, radius and spacing
ramps) are derived in `tokens.css` — set the inputs, not the outputs.

`files/theme/` must be public; `ThemeFolderMigration` / `src/Theme/ThemeFolder.php`
take care of the `.public` marker.

---

## CSS Utility Class Schema

When generating layout components, use only the documented classes —
`cheat-sheet.md` lists every one (`CheatSheetTest` keeps it complete). Main
prefixes:

| Prefix    | Examples                                                  |
|-----------|----------------------------------------------------------|
| `layout-` | `layout-grid`, `layout-columns-3`, `layout-columns-asymmetric`, `layout-auto-grid` |
| `align-`  | `align-text-center`, `align-content-center`, `align-all-center` |
| `space-`  | `space-box-md`, `space-block-lg`, `space-below-lg`, `space-none-mobile` |
| `style-`  | `style-glass`, `style-interactive-card`, `style-shadow`, `style-rounded` |
| `bg-`     | `bg-surface-2`, `bg-primary`, `bg-gradient-subtle`, `bg-image-dark`, `bg-video-dark` |
| `text-` / `color-` | `text-scale--lg`, `text-on-light`, `color-primary` |
| `vh-`     | `vh-half`, `vh-full` (hero heights)                       |

### Inline Styles — `extra_style` Field

The expert legend (next to ID/Class) exposes an **`extra_style`** text field
(label: “Inline-Styles“) on `tl_article` and `tl_content`.
Enter any semicolon-separated CSS declarations:

```
--av-gutter: 2rem; --av-color-primary: oklch(55% 0.2 250)
```

These are injected as the element's `style` attribute by the template layer (`mod_article.html.twig`,
`content_element/_base.html.twig`, `element_group.html.twig`) via `HtmlAttributes::addStyle()`.

This replaces the former pipe syntax (`class | --var: value`) which has been removed.

---

## Section & Element Design Fields

AVE adds design fields to `tl_article` (sections) and `tl_content` (element
groups + individual CEs). They are part of the theme bundle, not of the Design
Studio. All values are plain CSS class strings applied to the wrapper element
by the template; field definitions are shared via `src/Dca/SectionDesignFields.php`
and `src/Dca/ContentElementFields.php`.

### Section fields (`tl_article`)

| Field                     | Type            | Effect                                      |
|---------------------------|-----------------|---------------------------------------------|
| `section_bg`              | select          | Background class (`bg-surface-2`, `bg-primary`, `bg-image-dark`, `bg-video-dark`, …) |
| `section_bg_image`        | fileTree (UUID) | Background image — only used when `section_bg` starts with `bg-image` |
| `section_bg_video`        | fileTree (UUID) | MP4/WebM, muted and looped — only used when `section_bg` starts with `bg-video` |
| `section_bg_video_poster` | fileTree (UUID) | Still image for the video (loading, paused, no JS) |
| `section_color`           | select          | Text colour context (`style-light`, `text-on-light`, `color-primary`, …) |
| `section_size`            | select          | Vertical padding (`section--sm`, `section--lg`) |
| `section_height`          | select          | Minimum height (`vh-third`, `vh-half`, `vh-large`, `vh-full`) |
| `section_layout`          | select          | Column/grid layout (`layout-columns-2`, `layout-auto-grid`, `layout-sidebar`, …) |
| `section_align_x`         | select          | Horizontal alignment (`align-text-center`, `align-text-end`) |
| `section_align_y`         | select          | Vertical alignment (`av-y-center`, `av-y-end`) |
| `section_text`            | select          | Heading size scale (`text-scale--sm`, `text-scale--lg`, `text-scale--display`) |

`section_align` is a legacy combined field, hidden from the palette and kept
only until `ave:migrate:align` has split old data into `_x` / `_y`.

### Element group fields (`tl_content`, type `element_group`)

The section fields above (without the video fields), plus:

| Field              | Type   | Effect                                               |
|--------------------|--------|------------------------------------------------------|
| `section_gap`      | select | Spacing between the elements of the group            |
| `section_dividers` | select | Vertical rules in the column gaps (vanish when the grid collapses) |

### Individual CE fields (`tl_content`, types: text, image, hyperlink, …)

| Field             | Type   | Effect                                              |
|-------------------|--------|-----------------------------------------------------|
| `ce_style`        | select | Visual style (`style-card`, `style-shadow`, button variants for `hyperlink`) |
| `ce_bg`           | select | Background (`bg-surface-2`, `bg-gradient-subtle`, `style-glass`, …) |
| `ce_bg_image`     | fileTree | Background image (1920 px, WebP)                  |
| `ce_color`        | select | Text colour context                                 |
| `ce_align`        | select | Text alignment                                      |
| `ce_self_align`   | select | Horizontal position; the element shrinks to content width |
| `ce_size`         | select | Visual text size, independent of the heading level  |
| `ce_tracking`     | select | Letter spacing                                      |
| `ce_space_below`  | select | Extra bottom margin                                 |
| `ce_span`         | select | Spans several columns of the parent grid            |
| `ce_img_shape`    | select | Image shape / mask                                  |
| `ce_img_border`   | select | Border or shadow following the image shape          |
| `ce_btn_icon`, `ce_btn_icon_pos` | select | Button icon and its position (`hyperlink`) |

### Background image mechanics

When `section_bg` starts with `bg-image` (`bg-image`, `bg-image-dark`, `bg-image-light`, `bg-image-brand`, `bg-image-gradient`), the template:

1. Calls Contao's native `figure(uuid, size)` Twig function three times (600 / 1200 / 1920 px, proportional)
2. Sets three inline CSS custom properties: `--section-bg-sm`, `--section-bg-md`, `--section-bg-lg`
3. Adds class `has-bg-image` to the wrapper

CSS `.has-bg-image` uses media queries to route the viewport-appropriate variable into `--section-bg-image`, which all `bg-image*` classes consume via `background-image: var(--section-bg-image)`.

Images are rendered **deferred** by Contao's `ImagesController` (route `/assets/images/{path}`) — the file is generated on first browser request, then cached.

To set a background image programmatically, store the binary UUID of the `tl_files` entry in `section_bg_image` and set `section_bg` to one of the `bg-image*` values.

### Background video mechanics

When `section_bg` starts with `bg-video`, `mod_article.html.twig` renders a
`<video class=“section-video“ muted loop playsinline preload=“none“>` (no
`autoplay` — `public/theme/video-bg.js` starts it) plus a pause button
(`data-ave-video-toggle`), and adds `has-bg-video`. The poster is a separate
`<picture class="section-video__poster">` underneath (image size
`_section_video_poster`, WebP, lazy unless it is the first section), not a
`poster` attribute — that one loads eagerly even far down the page. The video
starts only when the section scrolls into view, never with Save-Data.

The first article in the main column preloads its background image
(`<link rel="preload" fetchpriority="high">`, one per breakpoint), because a
CSS-variable background is otherwise discovered only after all stylesheets.

### Where to look up valid preset values

```
contao/dca/tl_article.php   → section_* options
contao/dca/tl_content.php   → section_* (element_group) + ce_* options
public/theme/layout.css     → CSS implementation of each class
contao/languages/de|en/tl_article.php, tl_content.php → human labels
```

---

## Bundle Structure

```
[bundle-root]/                             ← getPath() points here
├── composer.json                          ← avando/ave, type: contao-bundle
├── LICENSE.md                             ← dual licence (commercial / free non-commercial)
├── THIRD-PARTY.md                         ← bundled and downloaded third-party components
├── README.md                              ← installation, Contao 5.7 → 6 upgrade path
├── ai-instructions.md                     ← this file
├── cheat-sheet.md                         ← the class and token reference, guarded by CheatSheetTest
├── bin/build-css.sh
├── config/
│   ├── services.php
│   └── image_sizes.yaml                   ← contao.image.sizes, prepended by the extension
├── translations/image_sizes.de|en.yaml    ← labels of the image sizes
├── contao/
│   ├── config/config.php
│   ├── dca/                               ← tl_article, tl_content, tl_module, tl_page …
│   ├── languages/de|en/                   ← PHP language files
│   └── templates/                         ← ALL Twig lives here, not in templates/
│       ├── modules/                       ← mod_navigation_accessible
│       └── twig/
│           ├── page/layout.html.twig      ← the page layout override
│           ├── mod_article.html.twig
│           ├── news_av_*, event_av_*      ← card/list/upcoming templates for News & Calendar
│           ├── news_full, event_full, mod_newsreader, mod_eventreader, pagination
│           ├── component/
│           ├── content_element/
│           └── frontend_module/
├── public/                                ← assets:install → public/bundles/avandoave/
│   ├── dist/ave.min.css                   ← what the frontend actually loads
│   ├── theme/                             ← the 9 CSS sources + 6 JS files
│   ├── starter/                           ← fallback theme.css + view-transitions.css
│   ├── open-props/                        ← vendored Open Props 1.7.23
│   │   ├── open-props.min.css
│   │   ├── normalize.min.css              ← upstream, unused
│   │   └── normalize-layer.css            ← the layered variant the theme loads
│   ├── glightbox/
│   └── icons/
├── src/
│   ├── AvandoAveBundle.php
│   ├── Command/                           ← ave:css:build, ave:migrate:align, ave:migrate:cssid
│   ├── ContaoManager/Plugin.php
│   ├── Controller/FrontendModule/         ← OnepageNavController (the only controller)
│   ├── Dca/                               ← shared field definitions
│   ├── DependencyInjection/
│   ├── EventListener/                     ← CSP for fonts, HTML minify, DataContainer callbacks
│   ├── License/ProToken.php
│   ├── Migration/                         ← StarterThemeMigration, ThemeFolderMigration
│   ├── Theme/ThemeFolder.php
│   └── Twig/                              ← ave_asset, ave_show_attribution, ave_file_url, inline SVG, theme vars
└── tests/                                 ← export-ignore: never ships to customers
```

The 9 CSS sources, in build order (`BuildCssCommand`): `tokens`, `base`,
`layout`, `typography`, `forms`, `components`, `navigation`, `icons`,
`animations`. The JS files: `animations`, `header`, `navigation`, `onepage`,
`theme-switch`, `video-bg`.

Note the empty leftover `src/Controller/ContentElement/`. Content elements are
Twig-only — there is no CE controller in this bundle.

### Asset paths in dev vs. packaged
In both environments assets are served from the same URL — `/bundles/avandoave/`.

- **Dev project** (bundle root = project root): files live at
  `public/bundles/avandoave/` directly and are web-accessible immediately.
- **Packaged** (`vendor/avando/ave/`): files live at
  `public/` within the package; `php bin/console assets:install --symlink`
  creates `public/bundles/avandoave/ → vendor/avando/ave/public/`.

Always reference assets via `{{ ave_asset('bundles/avandoave/...') }}` — never
hardcode paths. `ave_asset()` adds a cache-busting `?v=` and leaves URLs alone
that are already versioned (the Managed Edition stamps its own).

---

## Attribution & Pro Token

The free tier shows one line of markup at the end of
`contao/templates/twig/page/layout.html.twig`:

```twig
{% if ave_show_attribution() %}
    <div class=“ave-credit“>Built with the free <a href=“…“ rel=“nofollow“ …>…</a></div>
{% endif %}
```

`ave_show_attribution()` (`src/Twig/AttributionExtension.php`) hides it only
when a valid **Pro token** is present:

1. in the website root page, field „Pro-Token“ (`tl_page.ave_license`), or
2. as a fallback for all root pages in the config:
   ```yaml
   avando_ave:
       license: 'AVE-XXXXX-XXXXX-XXXXX-XXXXX'
   ```

`src/License/ProToken.php` checks the token against a SHA-256 hash — the plain
token never appears in the source. When in doubt (typo, empty field, no root
page) the line is shown. This is a convenience for honest customers, not copy
protection: the licence is enforced by `LICENSE.md`, not by code. There is no
domain check and no phone-home.

Styled by `.ave-credit` in `public/theme/base.css`. The link takes
`--av-color-primary-text`, not the raw brand — the raw colour fails AA on
`surface-2` under six of the sixteen presets, and an attribution nobody can read
is not one.

`tests/Consistency/AttributionTest.php` guards all of it: that the block exists,
that it carries a link, that `rel=“nofollow“` stays, and that the theme's own
stylesheet does not hide it. Removing the block is a licence matter, not a
technical one — see `LICENSE.md` § 5.

---

## Contao 5.7 and 6

One code base serves both. Keep it that way:
- `insert_tag_raw` (used in `mod_article`, `news_full`, `event_full`) is
  deprecated in Contao 6, but 5.7 does not know `insert_tag_html`. Switch only
  when 5.7 support ends.
- Contao 6 puts a form field's CSS class on the `div.widget`, not on the
  `button`. Button modifiers in `forms.css` therefore match both
  (`:not(a,button).btn--x > button`).
- Customers must upgrade via the latest 5.7 (see README), otherwise `&` gets
  double-encoded.

---

## Conventions
- Twig templates are found by Contao's own convention from
  `contao/templates/twig/…`; the only explicit namespace in the bundle is
  `@!AvandoAveBundle` in `page/layout.html.twig` (next-in-chain = Contao core)
- Content elements are **templates, not services** — there is no
  `contao.content_element` tag and no `#[AsContentElement]` anywhere in this
  bundle. A new element is a Twig file plus its DCA options
- DCA extensions live in `contao/dca/` (config array style, no SQL in install)
- All strings translatable: DCA labels via the PHP files in
  `contao/languages/de|en/`, image size labels via `translations/` (domain
  `image_sizes`)
- Run the suite (`tests/`, incl. `tests/Consistency/`) and `ave:css:build --check`
  after every change; the consistency tests catch drift between DCA, CSS,
  cheat sheet and tokens

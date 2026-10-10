# Avando AVE — Cheat Sheet

All available CSS classes and custom properties. Reference for theme work, AI agents, and backend users.

---

## Design Tokens (`--av-*`)

### Colour

| Token | Default |
|---|---|
| `--av-color-primary` | blue-7 |
| `--av-color-primary-light` | blue-3 |
| `--av-color-primary-dark` | blue-9 |
| `--av-color-text-on-primary` | white |
| `--av-color-text-on-secondary` | = text-on-primary |
| `--av-sec-hue-shift` | -60 (Farbdrehung der Sekundärfarbe, °) — Design Studio: „Farbdrehung" |
| `--av-sec-chroma` | 0.8 (Sättigung der Sekundärfarbe) — Design Studio: „Sättigung" |
| `--av-color-secondary` | **Modus „Abgeleitet" (Default):** aus Primary über die zwei Dials berechnet — das Studio schreibt dann nur die Dials, nicht die Farbe. **Modus „Eigene Farbe":** frei gewählt, überschreibt die Ableitung |
| `--av-color-secondary-light` | derived: primary-light, same rotation |
| `--av-color-secondary-dark` | derived: primary-dark, same rotation |
| `--av-color-surface` | gray-0 (page bg) |
| `--av-color-surface-2` | gray-1 |
| `--av-color-surface-3` | gray-2 |
| `--av-color-border` | gray-3 |
| `--av-color-text` | gray-9 |
| `--av-color-text-muted` | aus `text` ↦ `surface` abgeleitet (Default 55% Textfarbe); Editor (Typo-Palette): „Gedämpfter Text" — höher = kräftiger |
| `--av-color-success` | green-6 |
| `--av-color-warning` | yellow-5 |
| `--av-color-error` | red-6 |
| `--av-color-info` | blue-5 |
| `--av-card-bg` | contextual (set by bg-* classes) |
| `--av-card-bg-1` … `--av-card-bg-4` | blue-1 … blue-7 |

Dark mode: all surface/text/border tokens shift automatically via `@media (prefers-color-scheme: dark)` or `html.dark` (set by the footer switch).

### Spacing

| Token | Desktop | Mobile (≤768px) |
|---|---|---|
| `--av-space-2xs` | size-1 (0.25rem) | — |
| `--av-space-xs` | size-2 (0.5rem) | — |
| `--av-space-sm` | size-3 (1rem) | — |
| `--av-space-md` | size-5 (1.5rem) | size-4 (1.25rem) |
| `--av-space-lg` | size-7 (2rem) | size-5 (1.5rem) |
| `--av-space-xl` | size-9 (4rem) | size-7 (2rem) |
| `--av-space-2xl` | size-11 (7.5rem) | size-9 (4rem) |
| `--av-space-3xl` | size-13 (15rem) | size-11 (7.5rem) |

### Typography

**Families**
- `--av-font-sans` — system UI stack
- `--av-font-serif` — Georgia stack
- `--av-font-mono` — monospace stack

**Display sizes (fluid)**
- `--av-text-display-1` — clamp(3rem, 5vw+1rem, 5.5rem)
- `--av-text-display-2` — clamp(2.25rem, 4vw+.75rem, 4rem)
- `--av-text-display-3` — clamp(1.75rem, 3vw+.5rem, 3rem)

**Text sizes**
- `--av-text-xs` 0.75rem · `--av-text-sm` 0.875rem · `--av-text-base` ≈1rem–1.125rem (fluid)
- `--av-text-md` · `--av-text-lg` · `--av-text-xl` · `--av-text-2xl` · `--av-text-3xl` · `--av-text-4xl`

**Line-height**
- `--av-leading-none` 1 · `--av-leading-display` 1.05 · `--av-leading-tight` 1.2
- `--av-leading-snug` 1.35 · `--av-leading-body` 1.6 · `--av-leading-loose` 1.8

**Weight**
- `--av-weight-light` 300 · `--av-weight-normal` 400 · `--av-weight-medium` 500 · `--av-weight-semi` 600 · `--av-weight-bold` 700 · `--av-weight-extra` 800 · `--av-weight-black` 900

**Other**
- `--av-tracking-tight` / `--av-tracking-normal` / `--av-tracking-wide` / `--av-tracking-widest`
- `--av-prose-width` 68ch

### Layout

- `--av-container` — max-width (default: `--av-container-xl`)
- `--av-container-sm` 640px · `--av-container-md` 768px · `--av-container-lg` 1024px
- `--av-container-xl` 1280px · `--av-container-2xl` 1440px
- `--av-gutter` — horizontal padding of `.container` (= `--av-space-md`)
- `--av-layout-gap` — overrides `gap` on grid layouts (`layout-columns-*`, `layout-feature`, `layout-auto-grid`, `layout-sidebar`)
- `--av-layout-gap-scale` — Multiplikator auf **alle** Grid-/Spalten-Abstände (proportional, erhält die je-Layout-Defaults). Theme-Editor: „Grid-Abstand". Default `1`
- `--av-row-gap` — overrides `gap` on `layout-row` only
- `--av-stack-gap` — overrides `gap` on `layout-stack` only

### Borders & Radius

- `--av-border` — 1px solid `--av-color-border`
- `--av-radius-scale` — **ein Faktor für die ganze Skala** (Default `1`). Skaliert die Rampe, statt sie zu ersetzen; bei `1` bleibt jeder Wert unverändert. Nur auf `:root` wirksam (z. B. `custom.css`); pro Element/Sektion stattdessen direkt `--av-radius-lg` o. ä. setzen. Design Studio: Regler „Eckenradius" (0–2×)
- `--av-radius-sm` (5px) · `--av-radius-md` (16px) · `--av-radius-lg` (32px) · `--av-radius-xl` (64px) · `--av-radius-full` — geometrische Rampe (`--radius-2..5`), jede Stufe ≈ doppelt so groß

> Das Design Studio schreibt **nur** `--av-radius-scale`, nie die vier Stufen. Sein Regler zeigt px (= resultierendes `--av-radius-md`, Default 16) und speichert intern den Faktor. Dadurch ist ein Speichern mit Defaults pixelgenau wirkungslos — verifiziert per Pixel-Diff.

### Header / Navigation / Footer

- `--av-header-position` `sticky` · `--av-header-inset` `auto` · `--av-header-height` `100px`
- `--av-header-bg` · `--av-header-color` · `--av-header-accent` · `--av-header-border` · `--av-header-backdrop` (`blur(16px)`)
- `--av-header-btn-bg` · `--av-header-btn-color` · `--av-header-nav` · `--av-header-nav-active`
- `--av-nav-font-family` · `--av-nav-font-size` · `--av-nav-font-weight`
- **Zeile:** `--av-nav-gap` · `--av-nav-item-divider-x` · `--av-nav-divider` · `--av-nav-divider-space`
- **Markierung** (aktiver/überfahrener Punkt): `--av-nav-marker-line` (Unterstrich) · `--av-nav-marker-bg` · `--av-nav-marker-radius` · `--av-nav-marker-pad` · `--av-nav-marker-rest` · `--av-nav-marker-ease`
- **Umschalter:** `--av-nav-toggle-size` · `--av-nav-toggle-mask` · `--av-nav-toggle-mask-open` · `--av-nav-toggle-open-rotate`
- **Dropdown:** `--av-nav-dropdown-bg` · `-color` · `-active-color` · `-radius` · `-padding` · `-shadow` · `-border` · `--av-nav-item-gap` · `--av-nav-item-divider` · `--av-nav-item-hover-bg`
- `--av-footer-bg` · `--av-footer-color` · `--av-footer-muted` · `--av-footer-accent` · `--av-footer-border`

**Im Design Studio:** Header-Farben über „Header & Navigation" → **Header-Stil** (`transparent` · `surface` · `inverted` · **`Eigene Farben`**), Footer über „Brand & Farben" → **Footer-Fläche** (8 Presets + **`Eigene Farben`**). Im Custom-Modus gibt es je drei Picker — Fläche, Schrift, Akzent:

| | Fläche | Schrift | Akzent |
|---|---|---|---|
| Header | `--av-header-bg` | `--av-header-color` **+ Navigation** | Logo-Icon, aktiver Link, Button-Fläche |
| Footer | `--av-footer-bg` | `--av-footer-color` **+ gedämpfter Text (65 %) + Trennlinie (20 %)** | Links-Hover, Icons |

Die Button-Schrift im Header wird automatisch gegen den Akzent kontrastiert (OKLCH-Schwelle wie bei `--av-color-text-on-primary`), bleibt also bei jeder Akzentfarbe lesbar.

> Konsumiert werden diese Tokens von `components.css` (Bundle, zweiter Block `@layer av.theme`) — Header-Shell, Logo, Footer-Shell und Footer-Navigation. Markup-spezifische Demo-Klassen (`.footer-contact`, `.footer-legal`, `.footer-tagline`, …) bleiben im Site-Stylesheet.

### Sonstige

- `--av-link` · `--av-link-hover` · `--av-color-heading` (`currentColor`)
- `--av-link-underline` (Ruhe) · `--av-link-underline-hover` — `underline` oder
  `none`. Utilities dazu, an Absatz, Element oder Sektion: `.links-plain`
  (nie) · `.links-hover-underline` (erst beim Überfahren) · `.links-underline`
  (immer). Sie stellen im Bereich auch den Hover-Effekt auf schlicht zurück.
- **Hover-Effekt der Textlinks** — Studio: „Beim Überfahren" (Aus · Unterstrich
  · Strich wächst · Marker · Strich wird kräftig · Welle · Invertiert) und
  „Auch in Ruhe unterstrichen" (nicht bei Strich wächst / Marker, die haben
  eine eigene Ruheform). Token: `--av-link-underline-style-hover` ·
  `--av-link-thickness-hover` · `--av-link-mark` (Streifenfarbe) ·
  `--av-link-mark-size` / `-hover` · `--av-link-hover-ink` · `--av-link-pad` ·
  `--av-link-block` (Blockfarbe bei Invertiert). Gilt nur für klassenlose
  Links ausserhalb von Überschriften, Navigation, Buttons, Bildern, Kopf und
  Fuss — also Fliesstext, „Weiterlesen", „Zurück", „Alle →"
- `--av-layout-gap-scale` `1` · `--av-bg-overlay-scale` `1` · `--av-btn-radius` (= `--av-input-radius`)
- `--av-gradient-angle` `135deg` — Winkel **aller diagonalen Verläufe**:
  `.bg-gradient-primary` · `.bg-gradient-secondary` · `.bg-gradient-dark` und die
  drei Verlaufs-Vorlagen des Fußes. `.bg-gradient-subtle` bleibt senkrecht
- `--av-gradient-dark-from` / `-to` — die beiden Enden des frei wählbaren
  Verlaufs. Die Token heißen weiter `dark` (gespeicherte Zustände kennen sie so),
  im Studio heißt der Abschnitt „Farbverlauf": wählbar ist jede Farbe

### Persona per URL (Demo-Betrieb, Pro: Design Studio)

`?persona=<id>` färbt **eine Seite für genau diesen Request** ein, ohne etwas zu speichern — gedacht für Demos, bei denen mehrere Markenlooks in einer Installation liegen:

```
/leistungen?persona=ngo      /gastro?persona=gastro      /?persona=midnight
```

IDs: `basic` · `corporate` · `brutalist` · `vibrant` · `refined` · `cool` · `midnight` · `soft` · `bold` · `kids` · `ngo` · `health` · `poster` · `finance` · `gastro` · `studio`

| | |
|---|---|
| Aktivierung | `avando_ave_editor: persona_query: true` (Default **aus**) |
| Ausgabe | ein `<style>:root{…}</style>` am Ende von `<head>` — nur die **Deltas** der Persona, alles Übrige kommt weiter aus `tokens.css` |
| DOM | `<html data-ave-persona="ngo">` — für Screenshots, Debugging und demo-spezifisches CSS |
| Caching | Antwort wird `private, no-store` — eine Persona-Variante landet nie im geteilten Cache |
| Sicherheit | Der Query-Wert wird **nur** zum Nachschlagen einer bekannten ID benutzt; unbekannte IDs bleiben wirkungslos, nichts aus der URL erreicht das CSS |

**Im Design Studio:** Ist ein Backend-User angemeldet, **übernimmt der Drawer die Persona beim Laden** — die Regler zeigen ihre Werte, die Kachel ist markiert, und Speichern schreibt sie als Theme. Sobald du einen Regler bewegst, verschwindet die Markierung (der Look entspricht dann nicht mehr exakt der Persona). **Beim Speichern wird `?persona=` aus der Adresszeile entfernt** — die Persona ist dann das Theme, ein Reload zeigt den gespeicherten Stand. Bis dahin bleibt der Parameter erhalten, damit der Look beim Durchklicken der Demo mitwandert. `?persona=basic` ist damit ein **„zurück auf Theme-Standard"-Link**: leerer State, alle Regler auf die `tokens.css`-Werte.

> Auf Kundenseiten weglassen — sonst kann jeder Besucher die Seite über die Adresszeile umfärben. Personas mit `colorScheme: dark` (z. B. `midnight`) brauchen dunkeltaugliche Inhalte; ein helles Hero-Bild bleibt hell, während die Schrift auf Weiß umschaltet.

### Karten

Ein Vokabular für alle fünf Kartenarten — `.style-card`, `.style-interactive-card`
und `.media-card` (Bundle) sowie `.news--card` und `.event--upcoming`
(Starter-Theme). Die Listendarstellungen `.news--list` und `.event--list` (beide
in `theme.css`) sind Zeilen, keine Karten — sie lesen das Vokabular nicht.

- `--av-card-bg` · `--av-card-radius` · `--av-card-padding`
- `--av-card-border-width` (vererbbar, vom Studio gesetzt) · `--av-card-border`
  (`var(--av-card-border-width) solid var(--av-color-border)`) — dazu
  `--av-tile-border-width` / `--av-tile-border` für die Kachel
- `--av-card-shadow` (Ruhe) · `--av-card-shadow-hover` · `--av-card-lift` (Hub in px)
- `--av-tile-border` — die **Kachel** (`.style-interactive-card`) hat von Haus aus
  keinen Rahmen, die Karte schon. Zwei Token, weil sich das nicht als ein
  Standard schreiben lässt; der Studio-Regler setzt beide gemeinsam
- `--av-card-align` (`inherit`) · `--av-event-align` (`center`) — die Event-Karte
  zentriert von sich aus, die anderen nicht
- `--av-dl-term-width` `minmax(8rem, 14rem)` — Begriffsspalte der
  **Beschreibungsliste** (`description_list`). Sie steht zweispaltig mit einer
  Linie über jedem Paar und stapelt unter 600px; Modifier siehe Style-Klassen
- `--av-media-zoom` `1.04` — **in `theme.css`**, nicht im Bundle: das Bild der
  `.media-card` zoomt beim Überfahren. Auf `1` setzen schaltet es ab (an der
  Karte, der Sektion oder global). Entfällt unter `prefers-reduced-motion`

> **Zusammengesetzte Token neu auflösen — aber nur die Farbe.** `--av-card-border`
> und `--av-card-bg` werden dort aufgelöst, wo sie DEKLARIERT sind. Wer eine
> Sektion baut, die `--av-color-border` oder `--av-color-surface-2` umstellt, muss
> das Karten-Token im selben Block mitziehen, sonst bekommt die Karte darin
> weiter den Wurzelwert. Dabei **die Breite nicht mitschreiben**: `var(--av-border)`
> an dieser Stelle überstimmt den Studio-Regler, der ebenfalls an der Wurzel
> sitzt. Richtig ist `var(--av-card-border-width) solid <farbe>` — die Breite
> vererbt sich in die Sektion hinein. `BorderTokenTest` bewacht beide Hälften.

### Shadows

- `--av-shadow-scale` — Intensität aller Schatten (Default `1`). Skaliert die Deckkraft-Leiter von Open Props; `0` = unsichtbar, `1` = exakt Open Props, `2` = doppelt. Die **Mehrschichtigkeit bleibt erhalten** (`--shadow-3` sind fünf Layer) — anders als eine synthetisierte Einzelschatten-Formel. Geometrie (Versatz/Weichzeichnung) ist davon unberührt
- `--av-shadow-sm` · `--av-shadow-md` · `--av-shadow-lg`
- `--av-text-shadow-sm` · `--av-text-shadow-md` · `--av-text-shadow-lg`
- `--av-btn-shadow-rest` · `--av-btn-shadow-hover` — was ein Knopf in Ruhe und beim Überfahren trägt (beide `none`). Die Schatten-Klassen setzen **beide**; eine Referenz `-hover: var(--av-btn-shadow-rest)` an `:root` wäre dort einmal zu `none` eingefroren
- `--av-btn-lift-sm` `1px` · `--av-btn-lift-md` `2px` · `--av-btn-lift-lg` `3px` · `--av-btn-lift` `0` (aktiver Hub) · `--av-btn-lift-diag` `0`/`1` (FAKTOR, keine Länge: waagerechter Anteil). Beim **harten** Schatten ist der Versatz die Höhe — wächst nur er, liest das Auge „Licht wandert" statt „Knopf steigt". Die Gegenbewegung hält die knopfnahe Schattenkante an ihrem Platz. Weich hebt nur nach oben (`-diag: 0`), hart diagonal (`1`, gesetzt von „Schatten-Art")
- `--av-btn-shadow-sm` · `--av-btn-shadow-md` · `--av-btn-shadow-lg` — **eigene Leiter für Knöpfe**, am selben Regler (`--av-shadow-scale`). Die Flächen-Leiter von Open Props ist für grosse helle Karten entworfen (Deckkraft 0,03–0,10, kaum Versatz); unter einem kleinen gesättigten Knopf ist sie angesehen nicht von „kein Schatten" zu unterscheiden. Der Knopf bekommt deshalb engeren Radius, deutlichen Versatz nach unten und mehr Deckkraft

### Glow

Ein Schatten wirft Dunkles nach unten, ein Leuchten legt Farbiges rundum — gleiche Staffel, kein Versatz, wählbare Farbe.

- `--av-glow-color` — Farbe des Leuchtens (Default `#ffffff`; weiß bleibt auf dunklem Grund neutral)
- `--av-glow-scale` — Intensität (Default `1`). `0` = aus, `2` = doppelt. Wirkt auf alle Stufen, die Geometrie bleibt unberührt — dasselbe Muster wie `--av-shadow-scale`
- `--av-glow-sm` · `--av-glow-md` · `--av-glow-lg` — je **zwei Schichten**: ein enger heller Kern und ein weiter weicher Hof. Ein einzelner grosser Weichzeichner sieht nach Nebel aus, nicht nach Licht. Beide Schichten haben eine **Spreizung**, sonst liegt der Weichzeichner zur Hälfte hinter dem Element und das Leuchten wirkt matt. Die Spreizung ist Geometrie und wird von `--av-glow-scale` bewusst nicht angefasst
- `--av-text-glow-sm` · `--av-text-glow-md` · `--av-text-glow-lg` — enger gefasst, sonst verwischt die Buchstabenform

**Utility-Klassen:** `.glow` (= md) · `.glow-sm` · `.glow-md` · `.glow-lg` · `.glow-none` — und für Schrift `.text-glow` (= md) · `.text-glow-sm` · `.text-glow-md` · `.text-glow-lg`.

Ein Element trägt nur EINEN `box-shadow`: `.glow-*` und `.shadow-*` sind Alternativen, keine Ergänzungen. Dasselbe gilt für `.text-glow-*` gegen `.text-shadow-*`.

Im Design Studio unter **Effekte → Leuchten** (Farbe + Stärke).

**Farbe und Stärke wirken auch je Abschnitt.** `--av-glow-color` bzw. `--av-glow-scale` auf einer Sektion setzen genügt:

```css
.hero { --av-glow-color: #22d3ee; }   /* alles Leuchtende darin wird türkis */
```

Das ist nicht selbstverständlich: eine Custom Property wird dort aufgelöst, wo sie deklariert ist. Stünden `--av-glow-sm/md/lg` nur auf `:root`, erbten Nachfahren die fertige Zeichenkette und ein Abschnitts-`--av-glow-color` bliebe wirkungslos. Sie werden deshalb zusätzlich auf den Leucht-Klassen selbst deklariert — dort wird neu aufgelöst. Gemessen kostet das nichts (volle Stil-Neuberechnung auf der Prüfseite mit 1319 Elementen: 13,4 ms gegen 13,5 ms), weil nur zahlt, wer leuchtet; dieselbe Deklaration auf `*` kostete 16,4 ms und läge damit über dem Budget eines Bildes.

**Die Schatten können das nicht** — `--av-shadow-scale` auf einer Sektion wirkt nicht. Dort ist die Kette länger, weil Open Props' `--shadow-N` ihrerseits aus `--shadow-strength-*` zusammengesetzt sind.

### Motion

- `--av-duration-fast` 100ms · `--av-duration-base` 200ms · `--av-duration-slow` 350ms
- `--av-ease-out` · `--av-ease-spring`
- Page cross-fade (view transitions): **standardmäßig aus**. `--av-page-transition-duration` (Wert = an, `0s` = aus) · `--av-page-transition-ease` · `--av-page-transition-scale` (1 = reiner Fade). Site-weit an: Duration in custom.css setzen.
- Site-weit an: `--av-page-transition-duration` in `custom.css` setzen (dort bereits aktiv). Ausnahmen pro Seite: `.no-page-transition`.
- Pro Seite an (wenn global aus): `.page-transition` · `.page-transition-slow` (600ms) · `.page-transition-plain` (nur Fade) — CSS-Klasse der Seite, wirkt beim **Ankommen**

### Z-Index

- `--av-z-base` 1 · `--av-z-dropdown` 100 · `--av-z-sticky` 200
- `--av-z-modal` 9000 · `--av-z-toast` 9500 · `--av-z-skip` 9999

### Cascade Bridge (set on a CE wrapper, cascade to children)

- `--av-el-font-size` — overrides h1–h6 and p font-size
- `--av-el-color` — overrides h1–h6, p, li, td color
- `--av-el-line-height` — overrides heading line-height
- `--av-section-color` — set by color-* classes, cascades to headings/p

### Background Image (injected inline by template)

- `--section-bg-sm` / `--section-bg-md` / `--section-bg-lg` — URL per breakpoint
- `--section-bg-image` — computed by `.has-bg-image`, consumed by `bg-image*` classes

### Animated Gradient (override to change colours)

- `--bg-anim-color-1` orange-6 · `--bg-anim-color-2` pink-6
- `--bg-anim-color-3` blue-6 · `--bg-anim-color-4` teal-5

### Animation (override per element)

- `--animate-duration` 0.75s · `--animate-delay` 0.1s
- `--animate-distance` 2.5rem · `--animate-ease`
- Ihre Rückfallwerte sind selbst Token und hängen am Regler „Übergangsdauer"
  (`--av-duration-scale`): `--av-animate-duration` · `--av-animate-delay` ·
  `--av-animate-stagger` (Stufe der Staffel) · `--av-animate-ease`. Eine
  Übersteuerung je Element ist absolut und ignoriert den Regler.

---

## Layout Classes

### Container

| Class | Effect |
|---|---|
| `.container` | Centered, max-width, horizontal padding |
| `.container--sm` … `.container--2xl` | Override max-width to sm/md/lg/xl/2xl |
| `.container--full` | No max-width |

### Section (on article/element group wrappers)

| Class | Effect |
|---|---|
| `.section` | `padding-block: --av-space-xl` |
| `.section--sm` | `padding-block: --av-space-lg` |
| `.section--lg` | `padding-block: --av-space-2xl` |
| `.section--flush` | `padding-block: 0` |
| `.section--bleed` | volle Fensterbreite (`100vw`, ausgebrochen aus dem Container) — nur zusammen mit `.section` und ohne Seitenspalte |
| `no-container` | Suppress inner `.container` (article only) |
| `space-mobile-collapse` | Zero `padding-block` + `margin-block` on mobile ≤768px |

### layout-*

| Class | Columns / Effect |
|---|---|
| `.layout-grid` | Basic grid |
| `.layout-columns-2` | 2 equal columns → 1 col at ≤768px |
| `.layout-columns-3` | 3 cols → 2 at ≤900px → 1 at ≤640px |
| `.layout-columns-4` | 4 cols → 2 at ≤1024px → 1 at ≤560px |
| `.layout-columns-6` | Ratio-Basis: 6 Tracks, v. a. für Spans (50/50, 66/33, 83/17, Drittel, Sechstel) |
| `.layout-columns-asymmetric` | 2fr 1fr → 1 col at ≤768px |
| `.layout-columns-asymmetric--reverse` | 1fr 2fr → 1 col at ≤768px |
| `.layout-sidebar` | 1fr 280px → 1 col at ≤900px |
| `.layout-sidebar--left` | 280px 1fr → 1 col at ≤900px |
| `.layout-feature` | 1fr 1fr, xl gap, centered → 1 col at ≤768px |
| `.no-stack` | Modifier: verhindert Stacking auf mobil (kombinieren mit `layout-columns-2`, `-feature`, `-asymmetric`) |
| `.layout-auto-grid` | auto-fit, Spaltenbreite ≥ `--av-auto-min` (Default 280px) — Spaltenzahl folgt dem **Container**, nicht der Kind-Anzahl |
| `.layout-stack` | flex column |
| `.layout-row` | flex row, wrap — Modifier: `.row-spread` (justify-content: space-between) |
| `.layout-sticky` | Element klebt beim Scrollen — **nur ab Desktop** (≥769px); auf Mobil static (kein Overlap). Offset via `--av-sticky-top`. Typisch: Sidebar in `layout-sidebar`. |

**Element-Spans** (Ausnahme-Layouts — auf einem einzelnen Kind im `layout-columns-*`):

| Class (aufs **Kind**) | Gültig in | Effect |
|---|---|---|
| `.col-span-2` | `columns-3/4/6` | Kind überspannt 2 Tracks |
| `.col-span-3` | `columns-4/6` | Kind überspannt 3 Tracks |
| `.col-span-4` | `columns-6` | Kind überspannt 4 Tracks |
| `.col-span-5` | `columns-6` | Kind überspannt 5 Tracks |
| `.col-span-full` | alle | Kind über die volle Breite |

> Spans wirken **nicht** in `layout-auto-grid` — die Regeln greifen nur bei `layout-columns-*` und `.layout-grid`.

**Spaltenzahl bei `layout-auto-grid`** — `n ≤ (Innenbreite + Gap) / (--av-auto-min + Gap)`. Mit den Defaults (Container 1280px, Gutter/Gap je `--av-space-md` = 24px, min 280px) sind das **4 Spalten**; das 5. Element bricht um und streckt sich nicht (`auto-fit` klappt nur Tracks zusammen, die im ganzen Grid leer bleiben). Mehr Spalten ⇒ `--av-auto-min` senken:

| `--av-auto-min` | Spalten @ Container-xl |
|---|---|
| 280px (Default) | 4 |
| 240px | 4 |
| 200px | 5 |
| 160px | 6 |

Pro Element via „Inline-Styles" (`--av-auto-min: 200px`), site-weit in `custom.css`. Für eine **feste** Spaltenzahl ist `layout-columns-4` / `-6` der richtige Griff.

Gängige Ratios mit `layout-columns-6`:

| Aufteilung | Kinder |
|---|---|
| 50/50 | `col-span-3` + `col-span-3` |
| 66/33 | `col-span-4` + `col-span-2` |
| 83/17 | `col-span-5` + (1 Track) |
| Drittel | `col-span-2` ×3 |

> Enthält ein `layout-columns-*` einen Span, wird es via `:has()` auf feste Tracks umgestellt (sonst bleibt es intrinsisch). Ungültige Kombinationen (z. B. `col-span-3` in `layout-columns-2`) sind bewusst **nicht** definiert → das Kind bleibt eine Spalte, bricht nie aus. Auf ≤768px kollabiert alles auf volle Breite.

### align-*

| Class | Effect |
|---|---|
| `.align-content-center` | `place-content: center` |
| `.align-content-start` | `place-content: start` |
| `.align-content-end` | `place-content: end` |
| `.align-content-between` | `place-content: space-between` |
| `.av-y-start` / `.av-y-center` / `.av-y-end` | senkrechte Ausrichtung des Inhalts **in** der Box — auch in Grid- und Spalten-Layouts, wo `align-content` mehrdeutig wäre |
| `.align-items-start` / `-center` / `-end` / `-stretch` | reine Modifier (`align-items: …`) auf dem Container. `-stretch` = Kinder füllen die Zeilenhöhe (überstimmt z. B. `layout-sidebar`s `start`). |
| `.h-full` | `block-size: 100%` — eine **Box** füllt die volle Zellen-/Elternhöhe (Bento: an höchsten Nachbarn angleichen; oder verschachtelte Karte). Grid-Items strecken sich ohnehin per Default. |
| `.grow` | `flex: 1 1 0` — ein **Flex-Kind** wächst in den freien Platz. Auf die Boxen *innerhalb* einer `layout-stack`-Spalte setzen, damit sie die (gestreckte) Spaltenhöhe füllen/angleichen. Bento: rechte Stat-Boxen neben einer hohen Box. |
| **`.fill-stack`** | **One-shot** für den Bento-Fall: auf **eine** Spalte (Elementgruppe) setzen → **ab >768px** vollhohe Flex-Spalte, Boxen füllen den Platz **content-bewusst** (mehr Inhalt → größere Box) **und zentrieren ihren Inhalt vertikal**. **Auf Mobil** normaler kompakter Stapel (Inhalt oben, natürliche Höhe). Fasst `align-items-stretch` + `h-full` + `grow` + vertikale Zentrierung zusammen. |
| `.v-center` | zentriert den Inhalt einer Box **nur vertikal** (horizontale Textausrichtung bleibt) — für gestreckte Text-Boxen, ohne den Fließtext mittig zu setzen. |
| `.fill-stack--even` | Modifier zu `.fill-stack`: erzwingt **gleich hohe** Boxen (statt content-bewusst). |
| `.justify-center` / `.justify-between` | reine Modifier (`justify-content: …`) — in einer Flex-**Spalte** (`layout-stack`) zentriert `-center` vertikal |

**Navigations-Modul ausrichten** (Onepage-Navi, Footer-Navi): Die Leiste `.mod_navigation > .level_1` ist eine Flex-Zeile — `text-align` bewegt sie nicht. Deshalb übersetzt `navigation.css` die Ausrichtung auf die Flex-Achse:

| Weg | Wirkung |
|---|---|
| Sektions-Preset **„Mittig"** (`align-text-center`) bzw. `align-all-center` | Navigation mittig |
| Sektions-Preset **„Rechts"** (`align-text-end`) | Navigation rechts |
| CSS-Klasse **`justify-center`** direkt am Modul | Navigation mittig — Contao setzt die Modul-Klasse auf den `<nav>`-Wrapper, die Regel greift von dort auf die `ul` |
| CSS-Klasse `justify-between` am Modul | Punkte auf volle Breite verteilt |

Die Header-Navigation ist davon **nicht** betroffen (dort steht keine dieser Klassen).
| `.align-all-center` | One-shot: `display:grid; place-items:center` — **beide** Achsen (für Button/Icon/kleine Inhalte). **Nicht für Fließtext:** `place-items` zentriert auch horizontal und schrumpft den Textblock → dafür `.v-center` nehmen. |
| `.self-center` / `.self-end` | positioniert **das Element selbst** horizontal (fit-content + `margin-inline`-auto) — mittig bzw. rechts. Für inline-flex-Elemente **ohne Container-Breite**, z. B. **Button / Hyperlink-CE**, das `text-align` am Parent nicht erreicht. Wirkt in Block-, Flex-Spalten- und Grid-Kontext. BE-Feld beim Hyperlink-CE: **Selbst-Ausrichtung**. |
| `.align-text-center` | text-align: center + auto margins |
| `.align-text-end` | text-align: end |

### Viewport-Höhen (Hero)

| Class | Effect |
|---|---|
| `.vh-full` | `min-block-size: 100dvh` (minus `--av-hero-offset`, default `0px`) — Desktop & Mobil gleich |
| `.vh-large` | `75dvh` · **≤768px: 80dvh** |
| `.vh-half` | `50dvh` · **≤768px: 60dvh** |
| `.vh-third` | `33dvh` · **≤768px: 50dvh** |

> `min-*`, Inhalt kann die Box also immer höher schieben. `dvh` mit `vh`-Fallback. Auf Hochkant-Phones (≤768px) sind die Bruchteil-Höhen angehoben, damit Heros nicht flach/leer wirken; `.vh-full` bleibt voll. Für vertikal zentrierten Hero mit **„Zentriert (Text + Inhalt)"** kombinieren. Fixer/Overlay-Header: `--av-hero-offset` in custom.css setzen (wirkt nur auf `.vh-full`). BE-Feld: **Mindesthöhe** (Element-Gruppe + Sektion).

> Ausrichtungs-Utilities erzeugen **keinen** Flex-Kontext — den machen `layout-row` / `layout-stack`. So kein `flex-wrap`-Nebeneffekt.

### space-*

**Padding (all sides):** `.space-box-2xs` / `.space-box-xs` / `.space-box-sm` / `.space-box-md` / `.space-box-lg` / `.space-box-xl`

**Padding (block / vertical):** `.space-block-xs` / `.space-block-sm` / `.space-block-md` / `.space-block-lg` / `.space-block-xl` / `.space-block-2xl`

**Padding auf Mobil aufheben (nur ≤768px):** `.space-none-mobile` (alle Seiten) / `.space-inline-none-mobile` (horizontal) / `.space-block-none-mobile` (vertikal). Frei im CSS-Klassenfeld nutzbar. `.space-inline-none-mobile` wird zusätzlich von den Layout-Presets automatisch mitgesetzt, sobald eine `section_size` (space-box) gewählt ist — so verdoppelt der Box-Inset auf dem Handy nicht den Section-Gutter. Ausnahme Elementgruppe: Hat sie eine eigene Fläche (Hintergrund ≠ `bg-none` oder Stil Karte/Kachel), bleibt der seitliche Innenabstand erhalten. Auf Mobil skalieren `space-box-lg`/`-xl` außerdem eine Stufe runter.

**Margin-bottom:** `.space-below-xs` / `.space-below-sm` / `.space-below-md` / `.space-below-lg` / `.space-below-xl` / `.space-below-2xl` / `.no-space-below` (entfernt Abstand komplett)

**Responsive Sichtbarkeit:** `.desktop-only` (versteckt auf ≤768px) / `.mobile-only` (versteckt auf ≥769px). Im **Kopf** folgt `.desktop-only` zusätzlich der Navigation: schaltet die Leiste auf den Burger, verschwindet auch der Knopf daneben — sonst nähme er genau den Platz weg, der die Leiste eng gemacht hat. Im Seiteninhalt bleibt es bei der Fensterbreite.

**Hell/Dunkel-Schalter:** Das Theme gibt im Fuß einen Knopf aus (`.ave-theme-switch`). Er ist **aus**; eingeschaltet wird er in `custom.css`:

```css
:root { --av-theme-switch-display: inline-flex; }
```

Bewusst *nicht* im Design Studio: das stellt ein, wie die Seite aussieht — der Schalter dagegen, was ein Besucher daran ändern darf. Zwei Fragen, die getrennt bleiben sollen. Wer sein Farbschema fest auf hell oder dunkel stellt, schaltet ihn nicht ein: dann gäbe es nichts zu schalten.

Wo er steht, entscheidet die Seite: Das erste Element mit der Klasse **`theme-switch-here`** nimmt ihn auf. Eine Klasse und kein `data-`Attribut, weil Contao an jedem Inhaltselement ein Feld *CSS-Klasse* hat, aber keines für Attribute — so ist das Ziel auch ohne HTML-Element setzbar.

Am besten ein **eigenes Element neben der Navigation**, nicht darin: ein Schalter ist keine Navigation, und zwischen Impressum und Datenschutz wäre er für jeden, der die Seite vorgelesen bekommt, ein Linkziel, das keines ist.

```html
<div class="footer-bottom">
  <p class="footer-copy">© {{date::Y}} Firma GmbH</p>
  <nav class="footer-legal">
    <a href="{{link_url::93|urlattr}}">Impressum</a>
    <a href="{{link_url::94|urlattr}}">Datenschutz</a>
  </nav>
  <div class="theme-switch-here"></div>
</div>
```

Das Theme richtet `.theme-switch-here` selbst mittig aus — in einem Flex-Container wie außerhalb.

Fehlt die Klasse, bleibt der Knopf am Ende des Fußbereichs. Er zeigt **beide Symbole** und hebt das aktive hervor; welcher Zustand gilt, steht in `aria-pressed` — daraus liest auch das CSS, welches Symbol es betonen muss. Die Wahl des Besuchers hält in `localStorage`; ohne Wahl folgt die Seite dem Betriebssystem.

**Gap (Reihen- **und** Spaltenabstand, auf den Layout-Wrapper):** `.gap-none` / `.gap-xs` / `.gap-sm` / `.gap-md` / `.gap-lg` / `.gap-xl` / `.gap-2xl` — überschreibt `--av-layout-gap`/`--av-stack-gap`/`--av-row-gap` gleichzeitig, wirkt also auf Grid, `layout-stack` und `layout-row`.

**Gap nur Reihen (vertikal):** `.gap-row-none…2xl` · **Gap nur Spalten (horizontal):** `.gap-col-none…2xl` — setzen `row-gap`/`column-gap` direkt (überschreiben das `gap`-Shorthand). Kombinierbar mit `.gap-*` als Basis, dann eine Achse überschreiben.

### Tabellen

Klasse ins **CSS-Klasse-Feld des Tabellen-CE** (Wrapper `.content-table`):

| Class | Effect |
|---|---|
| `.table-compact` | engeres Zell-Padding (`space-2xs`/`space-xs`) |
| `.table-comfortable` | mehr Zell-Padding (`space-sm`/`space-md`) |
| `.table-spacious` | luftiges Zell-Padding (`space-md`/`space-lg`) |
| `.table-nowrap` | Zellen brechen nicht um → Spalten in natürlicher Breite, saubere horizontale Scrollung auf Mobil |

`.content-table` scrollt auf schmalen Viewports automatisch horizontal (kein Layout-Bruch). Zell-Padding auch per `extra_style` feinjustierbar: `--av-table-px` / `--av-table-py`. Einzelne Spalten ausrichten: `align-text-end` / `align-text-center` am `<td>`/`<th>` (Default: linksbündig).

### order-*

| Class | Effect |
|---|---|
| `.order-first` / `.order-last` | order: -1 / 999 |
| `.order-1` … `.order-4` | order: 1–4 |
| `.order-first--mobile` / `.order-last--mobile` | same, mobile only (≤768px) |
| `.order-1--mobile` / `.order-2--mobile` | mobile only |

### ratio-*

`.ratio-16-9` / `.ratio-4-3` / `.ratio-3-2` / `.ratio-1-1` — children fill with `object-fit: cover`

### btn--hover-shadow-* / btn--hover-flat

`.btn--hover-shadow-sm` / `-md` / `-lg` — der Knopf bekommt den Schatten **nur
beim Überfahren**; im Ruhezustand behält er, was er hat. `.btn--hover-flat`
nimmt das Abheben wieder weg, auch wenn es über den Regler „Knopf-Schatten bei
Hover" für die ganze Website eingeschaltet ist.

Beide setzen ein Token, keinen `box-shadow` — sie dürfen deshalb auch an einem
Vorfahren stehen und gelten dann für alle Knöpfe darin. Die Grundhaltung ohne
Design Studio steht in `theme.css` (dort ein Kommentar mit der einen Zeile).

### logo-tint

Das Logo nimmt seine Farbe aus der Umgebung, statt sie aus der Datei
mitzubringen: der Schriftzug die Textfarbe, das Zeichen den Akzent dessen,
worauf es steht — im Kopf die Kopf-Farben, im Fuss die Fuss-Farben, im Inhalt
Text- und Markenfarbe. Damit ist es in jeder Persona und beiden Farbschemata
lesbar, aus demselben Grund wie die Navigation daneben.

Klasse `logo-tint` ins Feld **CSS-Klasse** des Elements, das das Logo zeigt
(Hyperlink, Bild oder Text mit Bild).

- **SVG** wird als Code eingebettet (bereinigt, ohne Skripte). Gefärbt wird
  nur, was benannt ist: `<g class="logo-mark">` für das Zeichen (Akzent),
  `<g class="logo-word">` für den Schriftzug (Textfarbe). Ein einfarbiges Logo
  braucht nur `logo-word`. **Eine SVG ohne diese Namen behält ihre Farben** —
  ein buntes Logo wird also nicht versehentlich einfarbig. Die `fill`-Werte der
  benannten Teile gelten nur dort, wo die Klasse fehlt; sie sind die
  Originalfarben für E-Mail, PDF und Backend.
- **PNG / WebP / GIF / AVIF** mit durchsichtigem Hintergrund wird zur
  einfarbigen Schablone in der Textfarbe — auch ein buntes; dort die Klasse
  nur bei einfarbigen Logos setzen.
- **JPG** wird nie eingefärbt, auch mit der Klasse nicht: ohne durchsichtige
  Stellen ergäbe die Schablone ein volles Rechteck. Es erscheint als normales
  Bild.

Der Alternativtext des Bildes wird zum Namen der Grafik; leer heisst wie beim
Bild „Schmuck", für Screenreader unsichtbar.

### image--original

Nimmt Bilder vom globalen **Bild-Filter** aus (Design Studio → Effekte →
Inhaltsbilder: Graustufen, Sepia, Verblasst, Kontrastreich, Aufgehellt).
Der Filter gilt für alle Bilder im Inhaltsbereich (`#main`) — Inhaltselemente,
News- und Event-Teaser, Galerien, SVG-Icons eingeschlossen. Logos in Kopf und
Fuss bleiben aussen vor, ebenso die Hintergrundbilder von Sektionen.

Die Klasse wirkt über Vererbung — sie darf am Bild-Element, am Inhaltselement,
an einer Elementgruppe oder an der ganzen Sektion stehen und schaltet den Filter
für alles darunter ab.

### image--cover · image-left · image-right

- `.image--cover` — Bild füllt seine Box formatfüllend (Zuschnitt via `overflow: hidden`
  + `object-fit`). Ohne die Klasse (und ohne `ratio-*`) behält das Bild sein Format.
- `.image-left` / `.image-right` — **am Text-CE**: das Bild schrumpft auf seine
  Inhaltsgrösse und rückt an den Rand. Ohne die Klasse bleibt es blockbreit.
  Kein Float — der Text läuft nicht darum herum.
- `.media--wrap-left` / `.media--wrap-right` — **mit** Float: der Text läuft um
  das Bild herum. Auf Mobil (≤768px) fällt der Float weg und das Bild wird
  blockbreit.

---

## Background Classes

### Solid & Gradient

| Class | Effect |
|---|---|
| `.bg-surface-1` | Grundfläche (Token `--av-color-surface`, = Seiten-Hintergrund) |
| `.bg-surface-2` | Abgesetzte Fläche (Token `--av-color-surface-2`, default gray-1) |
| `.bg-surface-3` | Stärker abgesetzt (Token `--av-color-surface-3`, default gray-2) |
| `.bg-primary` | Brand color |
| `.bg-primary-light` | Light brand tint |
| `.bg-secondary` | Secondary accent (derived from primary) |
| `.bg-secondary-light` | Light secondary tint |
| `.bg-gradient-primary` | Brand diagonal gradient |
| `.bg-gradient-secondary` | Verlauf aus der Sekundärfarbe |
| `.bg-gradient-dark` | Dark navy gradient |
| `.bg-gradient-subtle` | Subtle vertical gradient |
| `.bg-gradient-animated` | Animated 4-color gradient |

### Background Image (requires `section_bg_image` field)

| Class | Overlay |
|---|---|
| `.bg-image` | No overlay |
| `.bg-image-dark` | Dark overlay (white text) |
| `.bg-image-light` | Light overlay |
| `.bg-image-brand` | Brand color overlay |
| `.bg-image-secondary` | Overlay in der Sekundärfarbe |
| `.bg-image-gradient` | Brand-to-dark gradient overlay |

Template adds `.has-bg-image` and injects `--section-bg-sm/md/lg` automatically.

**Modifier:** add `.bg-blur` to any `bg-image*` class to blur the background image (default 8 px, override with `--av-bg-blur`). Gradient overlay is preserved.

---

## Text Color Classes

| Class | Effect |
|---|---|
| `.style-light` | White text (for dark backgrounds) |
| `.text-on-light` | Dark text (for light backgrounds) |
| `.color-primary` | Primary brand color text |
| `.color-secondary` | Secondary color text |

---

## Style Classes

| Class | Effect |
|---|---|
| `.style-card` | Background, border, radius, padding. **Die Polsterung ist ein Standard, keine Ansage**: sie steht in `:where(.style-card, .style-interactive-card, .style-tile)` und hat damit Spezifität null — ein ausdrücklich gewähltes `space-box-*` gewinnt. Fläche, Rahmen, Radius und Schatten bleiben Ansage. (`.media-card` ist nicht dabei: deren Innenpolsterung ist strukturell.) |
| `.bg-video`, `.bg-video-dark/-light/-brand/-gradient` | **Hintergrundvideo am Artikel.** Ein `<video class="section-video">` hinter dem Inhalt; die Overlays sind dieselben wie beim Bild (`--av-bg-overlay`). Kein `autoplay` im Markup — `video-bg.js` startet, und nur bei `prefers-reduced-motion: no-preference`. Pause-Knopf ist Pflichtteil (WCAG 2.2.2), Standbild trägt Ladezeit, Pause und JS-losen Fall. BE: Artikel → Hintergrund |
| `.bg-none` | Nimmt eine Fläche **weg**, statt keine zu setzen — der Unterschied zu „Standard" (= keine `bg-`Klasse), das stehen lässt, was ein Element-Stil selbst mitbringt (`.style-card`, `.style-tile`). Räumt als Kurzform auch `background-image` weg, also Verläufe und Hintergrundbilder. Lässt `--av-card-bg` bewusst unangetastet, damit die Klasse auf einer Gruppe nicht deren Karten-Kinder mit durchsichtig macht. BE: Hintergrund → „Keiner (Fläche entfernen)" |
| `.style-tile` | **Kachel** (Bilder darin sind auf `--av-tile-media-max` (8rem) Höhe gedeckelt — sonst deckelt nur die Spaltenbreite, und die *wächst* beim Umbruch; am Element per Inline-Style heraufsetzen. Raster mit Kacheln bekommen ab 769px `grid-auto-rows: 1fr`, also gleich hohe Zeilen auch über den Umbruch hinweg): Fläche + Polsterung, Inhalt senkrecht mittig (`align-content: center`, ohne Wechsel des Layoutmodus — die Klasse kann auch auf einer Elementgruppe stehen). Nachlaufender Absatz-Außenabstand wird abgeräumt, sonst zentriert es rechnerisch richtig und optisch schief. aus dem Karten-Vokabular (`--av-card-bg`/`--av-card-padding`, folgt also den Studio-Reglern unter „Karten"), aber **ohne** Radius, Schatten und Rahmen. Gedacht für Raster mit `.dividers-v` oder Spaltenabstand „Keiner" — genau die drei weggelassenen Eigenschaften vertragen sich damit nicht (Radius biegt die Naht auf, Schatten schluckt die Haarlinie, Rahmen verdoppelt sich an der Stoßstelle). BE: Element-Stil „Kachel" |
| `.style-interactive-card` | Card that lifts on hover (translateY) |
| `.style-pop` | Federnder Hover: dezentes `scale` (elastic rein, squish raus) + Shadow. Nutzt `scale:` → **mit `.animate` kombinierbar** (kein transform/transition-Konflikt). Komponierbar auf Karte/Bild/Button. Tune: `--av-pop-scale` (1.03), `--av-pop-return` (500ms). Reduced-motion-safe. Definiert in `animations.css` |
| `.style-shadow` | Drop shadow only (default `--av-shadow-lg`) |
| `.style-rounded` | Large border-radius + overflow hidden |
| `.style-squircle` | Stark abgerundet, leitet sich aus `--av-radius-xl` ab (`clamp(1rem, xl × 0.75, 4rem)`) und folgt damit der Marke — Default 48px wie bisher, bei „Radius 0" 16px, gedeckelt bei 64px. Tunbar via `--av-squircle-card`. BE: Element-Stil „Squircle". **Chromium 139+: echtes Squircle** via `corner-shape` (`@supports`-gated); Safari/Firefox behalten den Kreisbogen-Radius — identisch zum bisherigen Aussehen, kein Bruch. |
| `.border-primary` / `.border-secondary` | Coloured border on a card/element (token-driven) |
| `.border-strong` | Modifier: border width 2px (combine with `.border-*`) |
| `.border-none` | Entfernt den Rand (auch von `style-card` / `media-card` / Event-Card) |
| `.style-glass` | Frosted glass (use on dark background) |
| `.style-glass-dark` | Dunkles Pendant zu `.style-glass`: gleiche Milchglas-Unschärfe (`--av-glass-blur`), aber die Fläche zieht ins Dunkle (Schwarz, 42 %) statt ins Helle. Für **helle** Sektionen und helle Hero-Bilder, auf denen ein heller Glas-Block zu wenig Kontrast zum Text bringt. Bringt den Textkontext selbst mit — dunkle Fläche → helle Schrift, gleiches Token-Set wie `.style-light` / `.bg-gradient-dark`, also ziehen verschachtelte Karten, gedämpfte Texte und Ränder mit. Der Rand bleibt der helle Glas-Saum (liest auf Dunkel als Kante). BE: Element-Stil bzw. Hintergrund „Glas dunkel" |
| `.cards-solid` | **Auf die Sektion/Gruppe** setzen: macht Card-Flächen (event/news/media/`style-card`) deckend statt Glas — für dunkle Sektionen mit **Hintergrundbild**, wo das Glas sonst durchscheint. Farbe **aus dem Theme abgeleitet** (getönte dunkle Fläche, kein fixes Grau) |
| `.cards-primary` / `.cards-secondary` | Wie `.cards-solid`, aber Card-Flächen aus **Primär- bzw. Sekundärfarbe** getönt (dunkel genug, dass weißer Sektionstext lesbar bleibt) |
| `.style-dark` | Dark background context (tokens adjusted) |
| `.style-primary` | Primary color background |
| `.style-divider` | Horizontal rule via border-top |
| `.dividers-v` | **Auf der Elementgruppe** (nicht auf den Kacheln): senkrechte Trennlinien in den Spaltenlücken. Bricht das Raster auf eine Spalte um, verschwinden sie restlos — die erste Spalte wird per `overflow-x: clip` weggeschnitten, statt per `:not(:first-child)` (das trüge in der zweiten Zeile einen Strich an der Außenkante). Hebt den seitlichen Innenabstand auf, weil an der Padding-Box geschnitten wird. **Für schlichte Spalten gedacht:** zwischen Kacheln mit eigener Fläche, Rundung und Schatten (`bg-*` + `style-rounded` + `style-shadow`) trennen die Karten schon sich selbst, und die Haarlinie geht unter. BE: Elementgruppe → „Trennlinien" |
| `.slider--overlay` | **Am Slider-Element**: Steuerung liegt über den Folien statt darunter (Bild-Slider). Ohne die Klasse steht sie als eigene Zeile darunter — sicher für Text. Gilt für **beide** Slider: Swiper (`content-swiper`, Contao 5.3+) und den älteren Swipe (`slider-control`) |
| `.description-list--plain` | Beschreibungsliste ohne Trennlinien (kurze Faktenblöcke) |
| `.description-list--stacked` | Beschreibungsliste immer gestapelt, auch auf breiten Schirmen |
| `.media-card` | **Auf einer Elementgruppe**, die mit einem Bild-CE beginnt: das Bild läuft randlos bis an die Kartenkanten, alles danach wird zum gepolsterten Körper. Im Backend als Stil wählbar (nur bei der Elementgruppe). Liest dasselbe Karten-Vokabular wie `.style-card` |
| `.border-top` / `.border-bottom` / `.border-left` / `.border-right` | `--av-border` an EINER Seite, frei kombinierbar |
| `.shadow` / `.shadow-sm` / `.shadow-md` / `.shadow-lg` / `.shadow-none` | Schatten-Leiter als Utility; `-none` nimmt einen geerbten wieder weg |

**Bildformen** (auf ein `<img>` — via Image/Text-CE-Feld „Bildform" oder manuell im RTE):

| Klasse | Form / Rahmen |
|---|---|
| `.shape-rounded` · `.shape-squircle` · `.shape-circle` · `.shape-blob` | Form (Radius/Maske); `circle` croppt quadratisch |
| `.shape-border` (+ `--secondary`/`--light`/`--dark`) | Rahmen, folgt der Form; Farbe via `--av-img-border-color` |
| `.shape-shadow` | weicher Schatten statt Rahmen (folgt dem Radius) |

**Accordion-Varianten** — **in `theme.css`**, nicht im Bundle (CSS-Klasse am **Accordion-CE** setzen — nicht am Body, der landet nur im `content__inner`):

> Sie sind bewusst dort: handorgel liefert unlayered aus und schlägt damit `@layer av.components`, und die Varianten sind Geschmack, kein Gerüst. Das Bundle stylt `.content-accordion` selbst; diese drei Varianten und ihre `--av-accordion-*`-Tokens kommen aus dem Starter-Theme.

| Klasse (in `theme.css`) | Look | Tokens |
|---|---|---|
| `.accordion--cards` | jedes Item eine gerundete Box (Header+Content) | `--av-accordion-bg` · `--av-accordion-radius` |
| `.accordion--filled` | farbige Balken je Header, Content eingerückt | `--av-accordion-bg` · `--av-accordion-radius` |
| `.accordion--accent` | Farbstreifen an der Startkante jedes Items | `--av-accordion-accent` (default primary) |

---

## Typography Classes

### Display & Headings

| Class | Effect |
|---|---|
| `.display-1` / `.display-2` / `.display-3` | Hero-scale headings |
| `.display-hero` | Überdimensionierter Hero-Titel — eine Stufe über `.display-1` (×1.5), token-getrieben (skaliert mit dem Design Studio); `.is-accent` färbt ein Wort in der Sekundärfarbe (in `theme.css`) |
| `.lead` | Large muted intro paragraph |
| `.eyebrow` | Small caps mono label above a heading |
| `.section-index` | Großer Display-Kicker über einer Überschrift — lautere Alternative zu `.eyebrow` (in `theme.css`) |
| `.pull-quote` | Übergroßes Aussage-/Testimonial-Zitat, mittig (in `theme.css`) |
| `.badge` | Inline chip/tag |

### Kleine Auszeichnungen

- `.eyebrow` — kleine Versalzeile ÜBER einer Überschrift, mit weiter Laufweite.
  Bewusst ohne eigene Schriftfamilie: die Auszeichnung liegt in Versalien und
  Laufweite, nicht in einem Wechsel auf Mono.
- `.section-label` — kleine Versal-Überschrift für Spalten und Bereiche (Fuss,
  Seitenspalte), in der gedämpften Textfarbe.
- `.badge` — Chip/Etikett in der Markenfarbe.

### Text Size (sets `--av-el-font-size`, cascades to children)

`.text-xs` · `.text-sm` · `.text-base` · `.text-md` · `.text-lg` · `.text-xl` · `.text-2xl` · `.text-3xl`

### Section-Level Scale (applied to section wrapper, bypasses CE boundary)

| Class | Effect |
|---|---|
| `.text-scale--sm` | h1 → 2xl, h2 → xl, h3 → lg (kleinere Stufe für Nebenbereiche) |
| `.text-scale--lg` | h1/h2 → display-1, h3 → display-2 |
| `.text-scale--display` | h1/h2 → display-1 weight 900, h3 → display-2 |

### Text Color

`.text-muted` · `.text-base` · `.text-primary` · `.text-secondary`

(Setzen die `--av-el-color`-Bridge — wirken auf Überschriften/Fließtext im CE. Für den Sektions-Textkontext gibt es zusätzlich `.color-primary`/`.color-secondary`, siehe Style Classes / DCA `ce_color`.)

### Weight

`.font-light` · `.font-normal` · `.font-medium` · `.font-semi` · `.font-bold` · `.font-extrabold` · `.font-black`

### Tracking

`.tracking-tight` · `.tracking-wide` · `.tracking-widest`

### Line-height (Leading)

`.leading-none` (1) · `.leading-display` (1.05) · `.leading-tight` (1.2) · `.leading-snug` (1.35) · `.leading-body` (1.6) · `.leading-loose` (1.8)

Auf den CE-Wrapper (CSS-Klasse-Feld) setzen — wirkt auf Überschriften **und** Fließtext im Element. Hinweis: Überschriften sind per Default schon `tight` (1.2), zum Verkleinern also `.leading-none`.

### Wrap & Truncation

`.text-balance` · `.text-pretty` · `.text-truncate`

### Überschriften-Maß (Zeilenlänge)

Überschriften sind **standardmäßig unbeschränkt** (Open Props' enges `max-inline-size` je h1–h6 ist neutralisiert). Steuerung per Token/Utility:

- `--av-heading-measure` — global setzen (z. B. in custom.css) für eine site-weite Grenze; Default `none`.
- `.measure` — Opt-in: setzt die Grenze auf `--av-heading-measure-on` (Default `40ch`); wirkt auf die Überschrift selbst **oder** einen Wrapper/Section (Token vererbt).
- `.measure-none` — erzwingt „aus" (falls der Token global gesetzt wurde).

**Allgemeine Zeilenlänge (jedes Element):** `.measure-45` · `.measure-50` · `.measure-60` · `.measure-70` — setzen `max-inline-size` in `ch` **direkt** auf das Element (z. B. `.lead`, `p`, `div`). Anders als das nackte `.measure` (das nur Descendant-Überschriften über den Token kappt). Z. B. `<p class="lead measure-50">` → max. ~50 Zeichen/Zeile.

> **Zentrierung:** Die Klasse kappt nur die Breite — die schmalere Box bleibt für sich genommen linksbündig. In einer Section mit `align-text-center` (bzw. `align-text-end`) wird sie automatisch mittig (bzw. rechts) gesetzt; `align-all-center` zentriert ohnehin. Ohne eine dieser Klassen bleibt sie bewusst links, damit linksbündiger Fließtext nicht ungewollt zentriert wird.

### Prose Container

| Class | Effect |
|---|---|
| `.prose` | Max-width 68ch, readable rhythm for CMS content |
| `.prose--sm` | Smaller text + 60ch |
| `.prose--lg` | Larger text + 72ch |

---

## Animation Classes

Add `.animate` to any element. IntersectionObserver adds `.is-visible` when it enters the viewport.

| Class | Effect |
|---|---|
| `.animate` | Fade + slide up (default) |
| `.animate.animate--fade` | Fade only (no movement) |
| `.animate.animate--left` | Slide from left |
| `.animate.animate--right` | Slide from right |
| `.animate--stagger` | On wrapper: children stagger in sequence |
| `.animate--hero` | On wrapper: children animate on page load (no scroll trigger) |
| `.animate--count-up` | First number in heading counts up on enter |

Override timing per element via inline `extra_style`:
```
--animate-duration: 1s; --animate-delay: 0.3s
```

---

## Button Classes (on `<a>` or `<button>`)

Base: `.btn`

Farb-Varianten: `.btn` (Primär, gefüllt) · `.btn--secondary` (Sekundärfarbe, gefüllt) · `.btn--neutral` (Grau/Surface) · `.btn--outline` (Primär-Umriss) · `.btn--outline-secondary` (Sekundär-Umriss)

Weitere: `.btn--ghost` · `.btn--soft` (Primär-Tint) · `.btn--dark` · `.btn--glass` · `.btn--full` · `.btn--sm`/`--lg` · `.btn--pill`

> Auf farbigen/dunklen Sektionen werden gefüllte Buttons weiß + Primärtext, Outline-Varianten weißer Umriss (automatisch).

Sizes: `.btn--sm` · `.btn--lg`

Shape: `.btn--pill` · `.btn--icon` (quadratisch, nur Symbol)

Effekt: `.btn--shadow-sm` · `.btn--shadow` (= md) · `.btn--shadow-md` · `.btn--shadow-lg` · `.btn--glow-sm` · `.btn--glow` (= md) · `.btn--glow-md` · `.btn--glow-lg`

> **Warum eigene Klassen und nicht `.shadow` / `.glow`?** Beim Hyperlink-CE landet die Klasse am **Wrapper**, nicht am `<a>`: Contao baut `<div class="content-hyperlink btn btn--pill"><a>…</a></div>`. Ein `.glow-lg` im CSS-Feld ergäbe dort ein 900 px breites, **eckiges** Leuchten um eine unsichtbare Kiste (gemessen: Wrapper 900 px/Radius 0, sichtbarer Knopf 202 px/Radius 99). Die `btn--*`-Varianten dürfen am Wrapper stehen — das CSS holt sie ans `<a>`, wie bei `.btn--pill` auch.
>
> Sie hängen an den **globalen Token**: „Schatten-Tiefe" und „Leuchten" (Farbe/Stärke) aus den Effekten wirken auch hier, und eine Sektion mit eigenem `--av-glow-color` färbt den Knopf mit. Der Fokusring gewinnt bewusst — ein `box-shadow` lässt sich nicht aus zwei Regeln addieren, und sichtbarer Fokus geht vor Zierde.

Combinations: `.btn.btn--lg.btn--pill` · `.btn.btn--pill.btn--glow-lg`

On dark/brand backgrounds, buttons automatically switch to white fill.

---

## Component Classes

| Class | Purpose |
|---|---|
| `.media--left` / `.media--right` | Text CE with float image |
| `.alert--info` / `--success` / `--warning` / `--error` | Alert boxes |
| `.skip-link` | Accessibility: skip navigation |
| `.sr-only` / `.invisible` | Screen reader only |

---

## Design Studio — Contao Backend Fields

### tl_article (sections)

| Field | Type | CSS classes applied |
|---|---|---|
| `section_bg` | select | See bg-* classes above |
| `section_bg_image` | fileTree | Background image (UUID) |
| `section_color` | select | `style-light` / `text-on-light` / `color-primary` / `color-secondary` |
| `section_size` | select | `section--sm` / `section--lg` |
| `section_height` | select | `vh-third` / `vh-half` / `vh-large` / `vh-full` — Mindesthöhe (Hero); mit „Zentriert (Text + Inhalt)" vertikal zentrieren |
| `section_layout` | select | `layout-columns-*` / `layout-auto-grid` / `layout-feature` / `layout-sidebar` |
| `section_align` | select | Mittig (`align-text-center`) · Rechts (`align-text-end`) · Links vertikal (`align-content-center`) · Mittig vertikal (`align-text-center align-content-center`) · Rechts vertikal (`align-text-end align-content-center align-content-end`) |
| `section_text` | select | `text-scale--lg` / `text-scale--display` |
| `extra_style` | text | Inline CSS declarations (style attribute) |

### tl_content — element_group

Same fields as tl_article. Data available as `data.section_*`.

### tl_content — individual CEs (text, image, hyperlink, …)

| Field | Type | CSS classes applied |
|---|---|---|
| `ce_style` | select | `style-card` / `style-shadow` / `style-rounded` / `style-rounded style-shadow` / `style-interactive-card` / btn variants (hyperlink) |
| `ce_span` | select | `col-span-2/3/4/5/full` — überspannt N Tracks des Eltern-Grids (`columns-3/4/6`); Anteil hängt von der Spaltenzahl ab, 4/5 nur in `columns-6` |
| `ce_bg` | select | `bg-surface-1/2` / `bg-primary` / `bg-secondary` / `bg-image*` (mit Overlay) / `bg-gradient-*` / `style-glass` / `style-glass-dark` |
| `ce_bg_image` | fileTree | Hintergrundbild (UUID) — nur sichtbar wenn `ce_bg` = `bg-image*`; setzt `--section-bg-*` + `has-bg-image` |
| `ce_color` | select | `style-light` / `text-on-light` / `color-primary` / `color-secondary` (nicht bei hyperlink — Farbe kommt aus `ce_style`) |
| `ce_align` | select | `align-text-center` / `align-text-end` — nur beim **Text-CE**; landet auf `.content-text` und greift bis in die RTE-Absätze |
| `ce_self_align` | select | `self-center` / `self-end` — nur beim **Hyperlink-CE** (Button); positioniert das Element selbst horizontal (L/M/R) |
| `ce_btn_icon` | select (auto-glob) | `icon--[name]` — nur beim **Hyperlink-CE**; Auto-Glob aus `public/icons/*.svg`; Chosen-Select (durchsuchbar) |
| `ce_btn_icon_pos` | select | `icon--before` (links) / leer (rechts, Standard) — nur beim **Hyperlink-CE** |
| `ce_img_shape` | select | **Image- & Text-CE** (angehängtes Bild): Bildform `shape-rounded` / `-squircle` / `-circle` / `-blob` — auf dem `<img>` |
| `ce_img_border` | select | **Bild-Rahmen**: Kein · Primär (`shape-border`) · Sekundär · Hell · Dunkel · Schatten (`shape-shadow`). Rahmen folgt der Form; tunbar via `--av-img-border-width`/`-color`. RTE-Inline-Bilder: Klasse manuell setzen. |
| `ce_space_below` | select | `space-below-xs…2xl` — Abstand nach unten (`margin-block-end`) am CE-Wrapper; bei **allen** CEs |
| `extra_style` | text | Inline CSS declarations (style attribute) |

**Icon über einer Überschrift** — `field-icon` + ein `icon--*` ins **CSS-Klasse-Feld** eines Text-CEs oder einer Elementgruppe (z. B. `field-icon icon--globe`). Malt das Icon als Blockmarke über den Inhalt. **Kein Markup im RTE nötig — und keins möglich:** ein leeres `<span class="icon icon--globe">` entfernt TinyMCE beim Speichern.

| Class / Token | Effect |
|---|---|
| `.field-icon` | Icon als `::before` über dem Inhalt — Default 2em, Primärfarbe |
| **Größe** | `.field-icon--xs` 1.25em · `--sm` 1.6em · `--md` 2em (Default) · `--lg` 2.75em · `--xl` 3.5em — der Abstand nach unten skaliert mit |
| **Skalierung** | in `em`, wie im ganzen Icon-System: folgt einem `.text-*` **direkt am CE** und dem Typo-Regler des Design Studios (`--av-text-base`). Ein `.text-*` an der *Sektion* wirkt nicht — genau wie beim Text |
| **Farbe** | `.field-icon--primary` (Default) · `--secondary` · `--text` · `--muted` · `--current` (currentColor) · `--success` · `--warning` · `--error` |
| Kombination | Größe + Farbe frei mischbar: `field-icon field-icon--lg field-icon--secondary icon--heart` |
| Ausrichtung | folgt automatisch `align-text-center` / `align-text-end` / `align-all-center` — egal ob die Klasse auf einem Vorfahren (`section_align`, Elementgruppe) oder auf dem CE selbst (`ce_align`) sitzt |
| auf `.style-light` | Icon übernimmt automatisch die helle Sektionsschrift; ein expliziter Farb-Modifier gewinnt weiterhin |
| `--field-icon-size` · `--field-icon-color` · `--field-icon-gap` | freie Werte per „Inline-Styles" |
| `--icon-src` | eigene Grafik statt Theme-Icon: `url('/files/…/mein-icon.svg')` — **absoluter** Pfad |

> Definiert in `icons.css` — **nicht** in `theme.css`/`custom.css`: `icon--*` legt in `--icon-src` nur das Token `url(../icons/x.svg)` ab, und relative URLs aus Custom-Properties werden am **Ort der Verwendung** aufgelöst. Aus `/files/theme/CSS/` heraus zeigt der Pfad auf `/files/theme/icons/…` (404) — und eine fehlgeschlagene Maske blendet das Element komplett aus (kein sichtbarer Fehler, nur nichts).

Note: on the **image CE**, `ce_style/ce_bg/ce_color` **and `extra_style`** are applied to `<figure>`, not the outer wrapper — so `box-shadow`, `border-radius` etc. frame the image tightly.

Note: the **HTML CE** has **no** design/preset fields (`ce_*`, `extra_style`) — style it directly in the entered HTML.

---

## Demo-Patterns (`custom.css`, nicht Teil des Kern-Themes)

Token-basierte Pattern-Klassen für die Demo-Seiten. Leben in `files/theme/CSS/custom.css`, nicht im Bundle — nur laden, wo die Demos sie brauchen. Design-Studio-kompatibel (nur `--av-*`-Tokens).

### Nummerierte Liste (Leistungen / Schritte)

Eine Zeile = ein `ce_text` mit CSS-Klasse `cap-row`, Inhalt flach als `cap-no` + `cap-name` + `cap-desc`. Contao wrappt den Text in `<div class="rte">`; `.cap-row > .rte { display: contents }` hebt die Kinder ins Zeilen-Grid, daher **kein `ce_html` nötig**.

| Class | Effect |
|---|---|
| `.cap-row` | Grid `auto 1fr`; Nummer links (über 2 Zeilen), Titel + Text rechts; Trennlinie oben, Hover färbt den Titel primär |
| `.cap-no` | Ordnungszahl (01, 02 …) — Display-Font, `--av-text-lg`, Primärfarbe |
| `.cap-name` | Titel — Display-Font, uppercase, `--av-text-display-3` |
| `.cap-desc` | Beschreibung — gedämpft, max. 60ch |

### Spendenfortschritt (Fundraising)

Ein **einziges Text-CE** trägt Balken **und** Zahlen — kein HTML-CE nötig:
CSS-Klasse `fund-progress` · Inline-Styles `--fund-pct: 62%` · Inhalt (RTE) „**84.200 €** von 135.000 € · noch 41 Tage".

| Class / Token | Effect |
|---|---|
| `.fund-progress` | Balken als `::before` des CE-Wrappers, Füllung per Hard-Stop-Gradient auf `--fund-pct` (rein dekorativ — die Zahlen stehen als Text daneben) |
| `--fund-pct` | Füllstand in % (Feld „Inline-Styles") |
| `--fund-fill` / `--fund-track` | Füll- bzw. Spurfarbe überschreiben |
| auf `.style-light` | automatisch Sekundärfarbe auf hellem Track, Zahlen erben die helle Sektionsschrift |

### Betrags-Chips (Fundraising)

CSS-Klasse `donate-amounts` auf ein Contao-Formularfeld **„Radio-Button-Menü"** — kein eigenes Markup. Der Radio liegt unsichtbar über seinem Label, das Label ist der Chip; die aktive Option füllt sich mit der Primärfarbe.

---

## CSS Layer Order

```
av.normalize → av.tokens → av.base → av.typography → av.components
→ av.forms → av.theme → av.layout → av.animations
```

Customer overrides in `files/theme/CSS/custom.css` are **un-layered** and always win.

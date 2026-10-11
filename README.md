# Avando AVE

Ein Theme-Framework für **Contao 5.7 LTS und Contao 6**. Gestaltung entsteht hier nicht durch
Templates, die man kopiert und anpasst, sondern durch **Auswahllisten im
Backend**: eine Sektion bekommt ihren Hintergrund, ihr Raster und ihre
Textfarbe über Felder am Artikel, ein Inhaltselement seinen Stil über Felder am
Element. Was dabei herauskommt, sind CSS-Klassen — keine Inline-Styles, kein
Baukasten-Markup, kein Build-Schritt.

## Installation

> **Öffentlich heißt nicht frei.** Das Paket liegt auf Packagist, damit
> Installation und Updates über Composer laufen. Benutzt werden darf es nur
> nach [`LICENSE.md`](LICENSE.md) — kommerziell nur mit gekaufter Lizenz.

Dieses Paket ist der Kern. Das Theme-Paket (Beispielinhalte, Schriften,
Hilfe-Seiten) und das Design Studio gibt es im
[Contao Theme Store](https://themes.contao.org) — dort als Free- oder
Pro-Version.

**Mit dem Contao Manager:** Das Theme-Paket aus dem Theme Store unter
„Pakete“ → „Pakete hochladen“ auswählen, dann „Änderungen anwenden“. Den Kern
(`avando/ave`) holt der Manager dabei selbst von Packagist. Danach die
Datenbank aktualisieren.

**Mit Composer:**

```bash
composer require avando/ave
```

Updates des Kerns kommen wie bei jeder Erweiterung: „Pakete aktualisieren“ im
Contao Manager bzw. `composer update avando/ave`.

## Was es mitbringt

- **Sektionen am Artikel** — Hintergrund (Fläche, Verlauf, Bild, Video),
  Spalten­raster, Ausrichtung, vertikale Größe, Textskala. Hintergrundbilder
  laufen über Contaos eigene Bildverarbeitung, in drei Größen, verzögert erzeugt.
- **Element-Stile am Inhaltselement** — Karte, Glas, Schatten, Rahmen,
  Button-Varianten, Bildfilter, Zoom.
- **Ein Stylesheet.** `dist/theme.min.css`, 19 KB gzip. Kein Tailwind, kein
  Bootstrap, keine Utility-Wolke — die Klassen heißen `layout-columns-3` und
  `space-box-md`, nicht `md:grid-cols-3 py-8`.
- **Native Bausteine.** Akkordeon ist `<details>`, Dialog ist `<dialog>`,
  Navigation ist `<nav><ul>`. Kein JavaScript für Dinge, die HTML kann.
- **Datenschutz ohne Nacharbeit.** Alle Dateien liegen auf dem eigenen Server;
  der Besucher kontaktiert kein CDN und keine Schriftenbibliothek.
- **Dunkelmodus** über `light-dark()`, ohne zweites Stylesheet.
- **KI-Kennzeichnung.** Haken „KI-generiert“ an der Datei, sichtbarer Hinweis
  an jeder Stelle, an der das Bild erscheint, auch als Hintergrundbild.
  Position, Größe und Darstellung legt der Startpunkt fest.

## Anforderungen

| | |
|---|---|
| PHP | 8.3 oder neuer (Contao 6 verlangt selbst 8.4) |
| Contao | 5.7 LTS oder 6.x — dasselbe Paket für beide |
| Templates | Twig (die alten `.html5`-Templates werden nicht unterstützt) |

## Nach der Installation

```bash
php bin/console assets:install --symlink
php bin/console contao:migrate
```

Danach steht das Theme unter `/bundles/avandoave/` bereit. Die Migration legt
bei einer frischen Installation ein Theme „Avando AVE“ an, mit Seitenlayout und
Navigationsmodul. Dieses Layout der Startseite zuweisen, fertig. Außerdem macht
sie den Ordner `files/theme/` öffentlich (Datei `.public` plus Verweis unter
`public/files/theme`), damit `custom.css` und die Ausgabe des Design Studios
beim Besucher ankommen. Bildgrößen,
Templates und das Starter-Stylesheet bringt das Bundle selbst mit; eine
Demo-Datenbank oder Projektkonfiguration ist nicht nötig.

`theme.css` (Starter-Bausteine) und `view-transitions.css` liegen unter
`/bundles/avandoave/starter/`. Eine Datei gleichen Namens in `files/theme/CSS/`
ersetzt die mitgelieferte.

Eigene Anpassungen gehören in `files/theme/CSS/custom.css`. Diese Datei liegt
außerhalb aller CSS-Layer und gewinnt deshalb immer gegen das Theme — auch ohne
`!important`, und auch nach einem Update.

## Von Contao 5.7 auf Contao 6

AVE braucht dafür keinen eigenen Schritt, wohl aber die Reihenfolge, die
Contao vorgibt:

1. **Zuerst auf das neueste Contao 5.7 aktualisieren** (mindestens 5.7.13) und
   die Datenbank migrieren (`contao:migrate` bzw. „Datenbank aktualisieren“ im
   Contao Manager). Dieser Schritt merkt sich, welche Felder Contao 6 anders
   kodiert — auch die Felder von AVE.
2. **Dann auf Contao 6 aktualisieren** und erneut migrieren.

Wer Schritt 1 überspringt, sieht danach doppelt kodierte Zeichen, etwa
`Daten &amp; BI` statt `Daten & BI` in der Navigation. Abhilfe: zurück auf
den Stand vor dem Update und den Weg über 5.7 gehen.

Ein Datenbank-Backup vor jedem der beiden Schritte ist ohnehin Pflicht.

## Wo was steht

| Frage | Datei |
|---|---|
| Welche Klassen gibt es? | [`cheat-sheet.md`](cheat-sheet.md) |
| Welche Werte hat ein Backend-Feld? | `contao/dca/tl_article.php`, `tl_content.php` |
| Wie ist eine Klasse umgesetzt? | `public/theme/layout.css` |
| Welche Tokens kann ich überschreiben? | `public/theme/tokens.css` |
| Anleitung für Redakteure | `files/theme/help/` (im Theme-Paket) |
| Regeln für die Weiterentwicklung | [`ai-instructions.md`](ai-instructions.md) |

## Das Design Studio

Farben, Schriften, Radien, Schatten und Kopfzeile lassen sich mit dem
kostenpflichtigen Zusatz **[Avando AVE Design Studio](https://ave-theme.avando-labs.de)**
(`avando/ave-editor`) im Backend einstellen, mit Vorschau, sechzehn Vorlagen,
Kontrastprüfung und Versionsverlauf.

Das Theme braucht es nicht. Ohne das Studio steht jeder Wert als CSS-Token in
`tokens.css` und lässt sich in `custom.css` überschreiben — ein eigener Test
(`FreeThemeStandsAloneTest`) hält fest, dass kein Stylesheet auf eine Datei
angewiesen ist, die erst das Studio erzeugt.

## Lizenz

Zwei Wege, ausführlich in [`LICENSE.md`](LICENSE.md) (English: [`LICENSE.en.md`](LICENSE.en.md)):

- **Kommerziell** — einmalig je Domain, beliebiger Zweck, ohne Namensnennung.
- **Frei** — unbegrenzt viele Webseiten, aber nur nichtkommerzielle, bei denen
  niemand für den Bau bezahlt wird, und die Zeile im Seitenfuß bleibt stehen.

Sobald jemand Geld für die Erstellung erhält, gilt der freie Weg nicht mehr —
auch dann nicht, wenn die Webseite selbst nichtkommerziell ist.

Im freien Weg steht eine Zeile im Seitenfuß. Mit einer kommerziellen Lizenz
entfällt sie über das Pro-Token aus der Lieferung — nicht durch Ändern des
Templates, denn das fällt beim nächsten Update zurück.

Eingetragen wird das Token im Backend: **Seitenstruktur → Startpunkt der
Website bearbeiten → Avando AVE → Pro-Token**, dann speichern. Ein falsches
Token meldet das Formular sofort; ein Cache-Leeren ist nicht nötig.

Wer die Konfiguration als Code ausrollt, kann es stattdessen in der
`config/config.yaml` setzen — dann gilt es für alle Startpunkte der
Installation (Cache danach leeren):

```yaml
avando_ave:
    license: 'AVE-XXXXX-XXXXX-XXXXX-XXXXX'
```

Fremdbestandteile und Schriftlizenzen: [`THIRD-PARTY.md`](THIRD-PARTY.md).

## Entwicklung

```bash
composer install
vendor/bin/phpunit          # Tests laufen gegen das umgebende Projekt
php bin/console ave:css:build   # theme/*.css → dist/theme.min.css
```

Der Testbestand ist zum größeren Teil **Konsistenzprüfung**, nicht
Verhaltenstest: dass die Klassen im Spickzettel den Klassen im Stylesheet
entsprechen, dass jedes Token einen Rückfall hat, dass die Attributionszeile
noch da ist. Diese Tests fangen die Fehlerart, die dieses Projekt wirklich
bedroht — stille Abweichung zwischen zwei Orten, die dasselbe behaupten.

`tests/` wird per `export-ignore` nicht mit ausgeliefert. Entwickelt wird in
einem eigenen Repository; das öffentliche Repository enthält nur die
veröffentlichten Stände, ohne Tests.

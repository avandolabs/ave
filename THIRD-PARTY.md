# Fremdbestandteile

Avando AVE enthält, lädt oder erzeugt Bestandteile Dritter. Deren Lizenzen gehen
für diese Bestandteile der Lizenz in `LICENSE.md` vor.

## Mitgeliefert im Paket

| Bestandteil | Ort | Lizenz | Rechteinhaber |
|---|---|---|---|
| Open Props | `public/open-props/` | MIT | Adam Argyle |
| GLightbox | `public/glightbox/` | MIT | Biati Digital |
| matthiasmullie/minify | via Composer | MIT | Matthias Mullie |
| Contao Core Bundle | via Composer | LGPL-3.0-or-later | Leo Feyer / Contao Association |

Die Symbole unter `public/icons/` sind Eigenentwicklung des Lizenzgebers und
unterliegen `LICENSE.md`.

## Nicht mitgeliefert, sondern zur Laufzeit geladen

**Schriften.** Das Design Studio lädt die gewählte Schriftfamilie einmalig von
Google Fonts und legt sie unter `files/theme/fonts/<familie>/` ab
(Selbst-Hosting, keine Verbindung des Besuchers zu Google). Jede Familie
unterliegt ihrer eigenen Lizenz — in aller Regel der **SIL Open Font License
1.1**, einzelne Familien der **Apache License 2.0**.

Beim Herunterladen wird die Lizenzdatei der Familie als
`files/theme/fonts/<familie>/LICENSE.txt` mit abgelegt. **Diese Datei muss dort
verbleiben**: die OFL verlangt, dass Urhebervermerk und Lizenztext bei jeder
Weitergabe der Schriftdatei mitgeführt werden — und eine Webseite gibt die
Schriftdatei an jeden Besucher weiter.

Ist der Lizenztext nicht abrufbar, wird die Schrift trotzdem installiert — aber
`LICENSE.txt` bleibt nicht leer, sondern benennt die fehlende Lizenz und die
beiden Adressen, unter denen sie zu holen ist. Eine fehlende Datei würde
niemandem auffallen; eine Datei, die ihr eigenes Fehlen ausspricht, schon.

## Demo-Inhalte

Die Beispielinhalte unter `files/demo/` (Texte, Bilder, PDF-Dateien) sind
**nicht** Bestandteil der lizenzierten Software. Sie dienen ausschließlich der
Vorführung und dürfen nicht in eine veröffentlichte Webseite übernommen werden.
Für Bilder aus fremden Quellen gelten die Bedingungen der jeweiligen Quelle.

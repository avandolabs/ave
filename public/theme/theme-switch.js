/*
 * Avando AVE — Hell/Dunkel-Schalter
 * ------------------------------------------------------------------
 * Ergaenzt den Knopf, den page/layout.html.twig im Fuss ausgibt:
 *  - setzt html.light / html.dark (die Haken in tokens.css) — auf <html>, nicht
 *    auf <body>: dort schlug die Besucherwahl das Farbschema des Design Studios
 *  - merkt die Wahl in localStorage, bis der Besucher sie zurueckstellt
 *  - verschiebt den Knopf nach [data-ave-theme-switch], wenn die Seite so ein
 *    Element hat — so steht er in der letzten Fusszeile neben Impressum und
 *    Datenschutz, ohne dass das Bundle das Markup des Kunden kennen muss
 *
 * Drei Zustaende, nicht zwei: hell, dunkel und „wie das System". Der dritte ist
 * der Standard und muss erreichbar bleiben — wer einmal umgeschaltet hat, soll
 * nicht fuer immer von seiner Systemeinstellung abgeschnitten sein.
 *
 * Gegen das Aufblitzen des falschen Themas sorgt ein kurzes Inline-Skript im
 * <head> (siehe layout.html.twig); hier ist es dafuer zu spaet.
 */
(function () {
  'use strict';

  var KEY = 'ave-theme';
  var FOLGEN = ['auto', 'light', 'dark'];

  function gespeichert() {
    try {
      var v = localStorage.getItem(KEY);

      return FOLGEN.indexOf(v) === -1 ? 'auto' : v;
    } catch (e) {
      /* Privater Modus, blockierte Site-Daten: dann eben ohne Gedaechtnis. */
      return 'auto';
    }
  }

  function merken(wert) {
    try {
      if ('auto' === wert) {
        localStorage.removeItem(KEY);
      } else {
        localStorage.setItem(KEY, wert);
      }
    } catch (e) { /* siehe oben */ }
  }

  function anwenden(wert) {
    /* Auf <html>, wie das Boot-Skript im <head>. Frueher <body> — und eine
       Deklaration dort ueberstimmte fuer den ganzen Inhalt, was das Studio auf
       <html> setzt. Die Rangfolge steht in tokens.css. */
    var c = document.documentElement.classList;
    c.toggle('light', 'light' === wert);
    c.toggle('dark', 'dark' === wert);
  }

  /** Was der Besucher gerade SIEHT — nicht, was er gewaehlt hat.

      Gelesen wird das berechnete color-scheme von <html>, nicht die eigene
      Wahl: ueber der Wahl stehen ein fest eingestelltes Schema und das
      Studio, und dann zeigte der Knopf sonst einen Zustand, den niemand sieht.
      Nur bei „light dark" entscheidet das System. */
  function istDunkel() {
    var cs = getComputedStyle(document.documentElement).colorScheme || '';

    if (/\bdark\b/.test(cs) && !/\blight\b/.test(cs)) return true;
    if (/\blight\b/.test(cs) && !/\bdark\b/.test(cs)) return false;

    return window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  function beschriften(knopf, wert) {
    var dunkel = istDunkel();
    var ziel = dunkel ? knopf.dataset.toLight : knopf.dataset.toDark;

    knopf.setAttribute('aria-label', ziel);
    knopf.setAttribute('title', ziel);

    /* Der Zustand gehoert in aria-pressed, nicht in die Beschriftung: ein
       Screenreader liest sonst „zu Dunkel wechseln" und weiss trotzdem nicht,
       wo er gerade steht. */
    knopf.setAttribute('aria-pressed', dunkel ? 'true' : 'false');

    /* Keine sichtbare Beschriftung: der Knopf zeigt beide Symbole und hebt das
       aktive hervor. Was er tut, steht in aria-label; wo man steht, in
       aria-pressed — das liest auch das CSS, um das richtige Symbol zu
       betonen. Ein Wort daneben waere eine dritte Aussage ueber denselben
       Zustand gewesen, und zwei davon widersprachen sich schon. */
  }

  function einrichten(knopf) {
    /* Erst jetzt sichtbar: ohne JS taete der Knopf nichts, und ein Knopf, der
       nichts tut, ist schlechter als keiner. Das CSS entscheidet danach, ob er
       ueberhaupt gezeigt wird (--av-theme-switch-display). */
    knopf.removeAttribute('hidden');

    var wert = gespeichert();
    anwenden(wert);
    beschriften(knopf, wert);

    knopf.addEventListener('click', function () {
      /* Umgeschaltet wird gegen das, was man SIEHT. Steht das System auf dunkel
         und der Besucher klickt, will er hell — nicht „auto", das ihn dort
         liesse, wo er gerade ist. */
      wert = istDunkel() ? 'light' : 'dark';
      merken(wert);
      anwenden(wert);
      beschriften(knopf, wert);
    });

    /* Zurueck zu „wie das System": langes Druecken bzw. Kontextmenue waere
       versteckte Bedienung. Stattdessen folgt der Knopf dem System weiter,
       solange nichts gespeichert ist. */
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () {
      if ('auto' === wert) beschriften(knopf, wert);
    });
  }

  /* Das Ziel wird ueber eine KLASSE bestimmt, nicht ueber ein data-Attribut.
     Grund ist das Backend: Contao gibt jedem Inhaltselement ein Feld
     „CSS-Klasse", aber keines fuer Attribute. Mit einem data-Attribut haette
     ein Redakteur das Ziel nur setzen koennen, indem er ein HTML-Element baut —
     fuer eine Fusszeile eine unverhaeltnismaessige Huerde. So traegt er
     `theme-switch-here` ins Klassenfeld der letzten Zeile, und fertig. */
  function platzieren(knopf) {
    var ziel = document.querySelector('.theme-switch-here');

    if (ziel && !ziel.contains(knopf)) ziel.appendChild(knopf);
  }

  function init() {
    var knopf = document.querySelector('[data-ave-theme-switch-button]');
    if (!knopf) return;

    platzieren(knopf);
    einrichten(knopf);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
}());

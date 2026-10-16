/*
 * Avando AVE — Farben für Browser ohne light-dark() oder relative Farben
 * ------------------------------------------------------------------
 * Wird nur geladen, wenn der Browser eins von beidem nicht kann (Boot-Skript
 * in page/layout.html.twig). Den Ersatz selbst trägt das CSS (Ende von
 * tokens.css); dieses Skript beantwortet zwei Fragen, die CSS dort nicht
 * stellen kann, und meldet die Antwort als Attribut an <html>:
 *
 *  - data-av-ld="hell" | "dunkel": welches Schema gilt FEST. Das Design Studio
 *    setzt dafür nur color-scheme (Regler „Farbschema", Persona, Vorschau
 *    „Dunkel"), und welchen Zweig color-scheme wählt, sagt dem Ersatz nichts —
 *    er schaltet über eigene Variablen. Gelesen wird das BERECHNETE
 *    color-scheme, also das, was am Ende gilt, egal wer es gesetzt hat. Bei
 *    „light dark" entscheidet weiter das System (Media Query im CSS).
 *
 *  - data-av-auf-primaer / data-av-auf-sekundaer="schwarz" | "weiss": welche
 *    Schrift auf der Markenfarbe lesbar ist. tokens.css rechnet das mit einer
 *    relativen Farbe aus der echten Leuchtdichte; ohne die stand im Ersatz
 *    pauschal Weiss — gemessen am 16.10.2026 in Chrome 115 bei Cool und Poster
 *    1,5:1 auf Badges und Knöpfen, bei Midnight auf allen Knöpfen. Hier
 *    dieselbe Rechnung, derselbe Umschlag (L = 0,1791).
 *
 * Das Studio weiss von alldem nichts und muss es auch nicht: beobachtet wird
 * <html>, und was das Studio dort schreibt (Inline-Werte, Vorschau-Attribut),
 * löst eine neue Messung aus.
 */
(function () {
  'use strict';

  var root = document.documentElement;
  var probe = document.createElement('span');
  probe.hidden = true;
  (document.body || root).appendChild(probe);

  function schema() {
    var cs = (getComputedStyle(root).colorScheme || '').trim();
    if (cs === 'dark') return 'dunkel';
    if (cs === 'light') return 'hell';
    return null;
  }

  /* Die Farbe kommt über die Probe, nicht über getPropertyValue(): an <html>
     steht der Wert, wie er geschrieben wurde (etwa `var(--indigo-8)`), an der
     Probe ist er aufgelöst. */
  function leuchtdichte(token) {
    probe.style.color = '';
    probe.style.color = 'var(' + token + ')';
    var m = getComputedStyle(probe).color.match(/rgba?\(\s*([\d.]+)[,\s]+([\d.]+)[,\s]+([\d.]+)/);
    if (!m) return null;
    var lin = function (v) {
      v /= 255;
      return v <= 0.04045 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
    };
    return 0.2126 * lin(+m[1]) + 0.7152 * lin(+m[2]) + 0.0722 * lin(+m[3]);
  }

  function schrift(token) {
    var l = leuchtdichte(token);
    return l === null ? null : (l > 0.1791 ? 'schwarz' : 'weiss');
  }

  /* Nur schreiben, was sich ändert: jede Änderung an <html> weckt den
     Beobachter unten wieder. */
  function setze(name, wert) {
    if (wert === null) {
      if (root.hasAttribute(name)) root.removeAttribute(name);
    } else if (root.getAttribute(name) !== wert) {
      root.setAttribute(name, wert);
    }
  }

  function messen() {
    setze('data-av-ld', schema());
    setze('data-av-auf-primaer', schrift('--av-color-primary'));
    setze('data-av-auf-sekundaer', schrift('--av-color-secondary'));
  }

  messen();

  new MutationObserver(messen).observe(root, {
    attributes: true,
    attributeFilter: ['style', 'class', 'data-ave-preview-scheme'],
  });
  /* Eine Persona per ?persona= steht als <style> im Kopf; das Studio tauscht
     solche Blöcke aus, ohne <html> anzufassen. */
  new MutationObserver(messen).observe(document.head, { childList: true, subtree: true, characterData: true });
}());

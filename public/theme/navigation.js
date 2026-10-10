/*
 * Avando AVE — Navigation (standard mod_navigation) — accessible enhancement
 * ------------------------------------------------------------------
 * Progressive enhancement for Contao's plain mod_navigation:
 *  - injects a burger + per-submenu chevron toggle buttons (markup untouched)
 *  - desktop: disclosure with aria-expanded, hover OR keyboard, Escape closes,
 *    click-outside / focus-leave closes
 *  - mobile: non-modal panel (no focus-trap / no scroll-lock — kept simple and
 *    easy to customise), Escape + click-outside close
 * The accessible variant (list inside .navigation-main) is skipped.
 */
(function () {
  'use strict';

  /* Untergrenze: darunter ist die Leiste nie sinnvoll, egal wie kurz die
     Eintraege sind. Darueber entscheidet die Messung, nicht die Breite. */
  var MQ = window.matchMedia('(max-width: 768px)');

  /* „Mobil" heisst jetzt: das Panel ist aktiv — nicht „Fenster ist schmal".
     Beides faellt bei schmalen Fenstern zusammen, aber ein 1440px breites
     Fenster mit einer zu grossen Navigation gehoert eben auch dazu. */
  function isMobile(nav) {
    return nav ? nav.classList.contains('is-compact') : MQ.matches;
  }

  /* ── Passt die Leiste in eine Zeile? ────────────────────────────────
     CSS kann „passt der Inhalt?" nicht fragen; Media Queries kennen nur die
     Fensterbreite. Der Platzbedarf haengt aber an Schriftgroesse, Schriftart
     und Textlaenge — unter „Pop" (1,4rem) lief die Leiste schon bei 1440px
     ueber, waehrend sie unter dem Standard auf 900px bequem passt.

     Gemessen wird am ausgeklappten Zustand: ist .is-compact gesetzt, ist die
     Liste display:none und damit unmessbar. Also Klasse abnehmen, messen,
     Ergebnis anwenden — alles synchron innerhalb eines Frames, der Browser
     zeichnet erst danach. Sichtbar flackert dabei nichts.

     +1px Toleranz gegen Subpixel-Rundung: ohne sie schaltet ein Fenster, das
     auf den Pixel genau passt, je nach Zoomstufe hin und her. */
  function passtNicht(nav, level1) {
    if (MQ.matches) return true;

    /* Gemessen wird die KOPFZEILE, nicht die Liste. Die <nav> ist ein
       Flex-Item, das schrumpfen darf — sie wird also schmaler als ihr Inhalt,
       lange bevor der Kopf voll ist. An der Liste gemessen meldete „Pop" auf
       1440px Ueberlauf, obwohl neben ihr noch 79px frei waren.

       Mit flex-shrink:0 (navigation.css) nimmt die Navigation jetzt den Platz,
       den sie braucht, und erst wenn Logo + Leiste + Knopf zusammen nicht mehr
       in den Container passen, laeuft dieser ueber. Das ist die Frage, die
       wirklich zaehlt. */
    var kopf = nav.closest('.container') || nav.parentNode;

    var war = nav.classList.contains('is-compact');
    if (war) nav.classList.remove('is-compact');

    var ueberlauf = kopf.scrollWidth > kopf.clientWidth + 1;

    if (war && ueberlauf) nav.classList.add('is-compact');
    return ueberlauf;
  }

  /* Beobachtet den Kopfbereich statt des Fensters: die Leiste kann auch dann
     zu breit werden, wenn das Fenster gleich bleibt — ein laengerer Menuepunkt
     im Backend, eine neu geladene Schrift, ein Vorlagenwechsel im Studio. */
  function beobachte(nav, level1) {
    var anwenden = function () {
      nav.classList.toggle('is-compact', passtNicht(nav, level1));
    };

    anwenden();

    if (window.ResizeObserver) {
      new ResizeObserver(anwenden).observe(nav.parentNode || nav);
    } else {
      window.addEventListener('resize', anwenden);
    }

    /* Webfonts aendern die Breite, nachdem die erste Messung gelaufen ist. */
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(anwenden);
    }

    return anwenden;
  }

  function enhance(nav) {
    var level1 = nav.querySelector(':scope > .level_1');
    if (!level1) return;                                       // not the standard nav
    if (nav.querySelector(':scope > .av-nav-burger')) return;  // already enhanced

    var parents = [];

    /* ── Burger ─────────────────────────────────────────────────── */
    if (!level1.id) level1.id = 'av-nav-menu-' + Math.random().toString(36).slice(2, 7);
    var mobileMenu = document.getElementById('av-mobile-menu');
    var mobileMenuHeader = mobileMenu && mobileMenu.parentNode; // restore target on close
    var burger = document.createElement('button');
    burger.type = 'button';
    burger.className = 'av-nav-burger';
    burger.setAttribute('aria-expanded', 'false');
    burger.setAttribute('aria-controls', level1.id + (mobileMenu ? ' av-mobile-menu' : ''));
    burger.setAttribute('aria-label', 'Menü öffnen');
    burger.innerHTML = '<span></span>';
    nav.insertBefore(burger, nav.firstChild);

    /* ── Submenu toggle buttons ─────────────────────────────────── */
    level1.querySelectorAll('li.submenu').forEach(function (li, i) {
      var link = li.querySelector(':scope > a, :scope > strong');
      // NICHT ':scope > .level_2': querySelectorAll('li.submenu') findet auch
      // verschachtelte Einträge, deren Kindliste .level_3 heisst. Für die war
      // sub === null, also gab es dort weder Umschalter noch is-expanded — auf
      // Mobil war die dritte Ebene damit gar nicht zu öffnen.
      var sub  = li.querySelector(':scope > .level_2, :scope > .level_3, :scope > .level_4, :scope > .level_5');
      if (!link || !sub) return;

      if (!sub.id) sub.id = 'av-nav-sub-' + i + '-' + Math.random().toString(36).slice(2, 7);

      var toggle = document.createElement('button');
      toggle.type = 'button';
      toggle.className = 'av-nav-toggle';
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-controls', sub.id);
      toggle.setAttribute('aria-label', 'Untermenü ' + (link.textContent || '').trim());
      link.insertAdjacentElement('afterend', toggle);

      var entry = { li: li, toggle: toggle };
      parents.push(entry);

      toggle.addEventListener('click', function () {
        setExpanded(entry, !li.classList.contains('is-expanded'));
      });

      // Desktop hover opens/closes (touch ignores hover)
      li.addEventListener('mouseenter', function () {
        if (!isMobile(nav)) { closeAll(entry); setExpanded(entry, true); }
      });
      li.addEventListener('mouseleave', function () {
        if (!isMobile(nav)) setExpanded(entry, false);
      });
    });

    function setExpanded(entry, on) {
      entry.li.classList.toggle('is-expanded', on);
      entry.toggle.setAttribute('aria-expanded', on ? 'true' : 'false');
    }
    // Vorfahren des geöffneten Eintrags müssen offen BLEIBEN, sonst schliesst
    // das Betreten eines Untereintrags das Panel, in dem er liegt.
    function closeAll(except) {
      parents.forEach(function (e) {
        if (e === except) return;
        if (except && e.li.contains(except.li)) return;
        setExpanded(e, false);
      });
    }

    function setMenu(open) {
      nav.classList.toggle('is-open', open);
      if (mobileMenu) {
        if (open) {
          mobileMenu.classList.add('is-open');
          level1.appendChild(mobileMenu);       // move into nav panel
        } else {
          mobileMenu.classList.remove('is-open');
          if (mobileMenuHeader) mobileMenuHeader.appendChild(mobileMenu); // restore
        }
      }
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      burger.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
      if (!open) closeAll();
    }
    burger.addEventListener('click', function () {
      setMenu(!nav.classList.contains('is-open'));
    });

    /* ── Keyboard ───────────────────────────────────────────────── */
    nav.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape') return;
      var open = parents.filter(function (p) { return p.li.classList.contains('is-expanded'); })[0];
      if (open && !isMobile(nav)) {
        setExpanded(open, false);
        open.toggle.focus();
      } else if (nav.classList.contains('is-open')) {
        setMenu(false);
        burger.focus();
      }
    });

    // Desktop: close submenus when focus leaves the nav
    nav.addEventListener('focusout', function (e) {
      if (!isMobile(nav) && !nav.contains(e.relatedTarget)) closeAll();
    });

    // Click outside closes everything
    document.addEventListener('click', function (e) {
      if (nav.contains(e.target)) return;
      closeAll();
      if (nav.classList.contains('is-open')) setMenu(false);
    });

    /* Messen und beobachten. Beim Wechsel zwischen Leiste und Panel bleibt
       sonst ein halb geoeffnetes Untermenue stehen, das im anderen Zustand
       niemand mehr schliessen kann. */
    var war = nav.classList.contains('is-compact');
    var anwenden = beobachte(nav, level1);

    new MutationObserver(function () {
      var jetzt = nav.classList.contains('is-compact');
      if (jetzt === war) return;
      war = jetzt;
      closeAll();
      setMenu(false);
    }).observe(nav, { attributes: true, attributeFilter: ['class'] });

    MQ.addEventListener('change', anwenden);
  }

  function init() {
    document.querySelectorAll('.mod_navigation').forEach(enhance);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
}());

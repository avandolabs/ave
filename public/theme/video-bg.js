(function () {
  'use strict';

  /*
   * Hintergrundvideos: abspielen, anhalten, und beides erreichbar halten.
   *
   * Die Regel dahinter ist WCAG 2.2.2 (Pause, Stop, Hide): Was laenger als
   * fuenf Sekunden von selbst laeuft und neben anderem Inhalt steht, braucht
   * eine Moeglichkeit zum Anhalten. Ein Hintergrundvideo erfuellt beide
   * Bedingungen immer — deshalb ist der Knopf nicht optional, sondern Teil
   * des Bauteils.
   *
   * Das Markup traegt bewusst KEIN autoplay. Der ruhige Zustand ist der
   * Standard: ohne JavaScript bleibt das Standbild stehen. Gestartet wird
   * hier, und nur wenn prefers-reduced-motion nicht widerspricht — eine
   * Media-Query kann ein Video nicht anhalten, das muss der Code tun.
   */

  const SECTIONS = document.querySelectorAll('.has-bg-video');
  if (!SECTIONS.length) return;

  const motionOK = window.matchMedia('(prefers-reduced-motion: no-preference)').matches;

  SECTIONS.forEach((section) => {
    const video = section.querySelector('.section-video');
    const toggle = section.querySelector('[data-ave-video-toggle]');
    if (!video || !toggle) return;

    const labels = {
      play: toggle.getAttribute('data-label-play') || 'Video abspielen',
      pause: toggle.getAttribute('data-label-pause') || 'Video anhalten',
    };

    /* Vom Nutzer angehalten. Getrennt vom blossen "laeuft gerade nicht" —
       nur so weiss der Beobachter unten, dass er beim Zurueckscrollen NICHT
       wieder starten darf. Eine Entscheidung wiegt schwerer als Sichtbarkeit. */
    let pausedByUser = !motionOK;

    function zeige(paused) {
      section.classList.toggle('is-video-paused', paused);
      toggle.setAttribute('aria-label', paused ? labels.play : labels.pause);
    }

    function spiele() {
      /* play() liefert ein Promise, das der Browser ablehnen darf — etwa wenn
         die Datei fehlt oder eine Energiesparregel greift. Unbehandelt waere
         das eine Fehlermeldung in der Konsole und ein Knopf, der etwas
         verspricht, das nicht passiert ist. */
      const p = video.play();
      if (p && typeof p.catch === 'function') {
        p.catch(() => { pausedByUser = true; zeige(true); });
      }
    }

    zeige(pausedByUser);
    if (!pausedByUser) spiele();

    toggle.addEventListener('click', () => {
      pausedByUser = !video.paused;
      if (pausedByUser) { video.pause(); zeige(true); }
      else { zeige(false); spiele(); }
    });

    /* Ausserhalb des Sichtfelds nicht weiterlaufen lassen: das spart Rechen-
       zeit und Akku, und niemand sieht, was dabei verpasst wird. Eine vom
       Nutzer getroffene Pause bleibt davon unberuehrt. */
    if ('IntersectionObserver' in window) {
      new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (pausedByUser) return;
          if (entry.isIntersecting) spiele();
          else video.pause();
        });
      }, { threshold: 0.05 }).observe(section);
    }
  });
})();

/*
 * Avando AVE — Header behaviour (runs on every page, independent of any nav)
 *  - syncs --av-header-measured with the sticky header's actual rendered height
 *    (kept separate from the editor-configured --av-header-height to avoid a
 *    measure→min-height→measure feedback loop)
 *  - hides the header on scroll-down, reveals on scroll-up
 *    (never while a mobile menu is open: body.navigation-open)
 */
(function () {
  'use strict';

  const header = document.getElementById('header');
  if (!header) return;

  // Keep scroll-padding-top / offsets in sync with the sticky header height
  const sync = () =>
    document.documentElement.style.setProperty('--av-header-measured', header.offsetHeight + 'px');
  sync();
  new ResizeObserver(sync).observe(header);

  let lastY = window.scrollY;
  window.addEventListener('scroll', () => {
    const y = window.scrollY;

    // Translucent + blur once scrolled past the top
    header.classList.toggle('is-scrolled', y > 10);

    // Hide on scroll-down, show on scroll-up
    if (y < 10) {
      header.classList.remove('header--hidden');
    } else if (!document.body.classList.contains('navigation-open')) {
      header.classList.toggle('header--hidden', y > lastY);
    }

    lastY = y;
  }, { passive: true });
}());

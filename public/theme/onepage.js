/*
 * Avando AVE — Onepage scroll-spy
 * Marks the in-page nav link whose section is currently in view.
 * Bails out unless the page actually has both sections and hash nav links,
 * so it costs nothing on regular (non-onepage) pages.
 */
(function () {
  'use strict';

  const sections = document.querySelectorAll('[id].mod_article, section[id]');
  const navLinks = document.querySelectorAll(
    '.navigation-main a[href*="#"], .mod_navigation a[href*="#"]'
  );

  if (!sections.length || !navLinks.length) return;

  const spy = new IntersectionObserver(entries => {
    for (const entry of entries) {
      if (!entry.isIntersecting) continue;
      for (const link of navLinks) {
        const active = new URL(link.href, location.href).hash === '#' + entry.target.id;
        link.closest('li')?.classList.toggle('active', active);
        active
          ? link.setAttribute('aria-current', 'page')
          : link.removeAttribute('aria-current');
      }
    }
  }, { rootMargin: '-15% 0px -75% 0px' });

  sections.forEach(s => spy.observe(s));
}());

(function () {
  'use strict';

  const VISIBLE = 'is-visible';
  const opts = { threshold: 0.12, rootMargin: '0px 0px -48px 0px' };

  // Individual .animate elements (not inside a stagger group)
  const solo = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add(VISIBLE);
      solo.unobserve(entry.target);
    });
  }, opts);

  // .animate--stagger groups: fire all children at once → CSS nth-child handles delay
  const stagger = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.querySelectorAll('.animate').forEach((child) => {
        child.classList.add(VISIBLE);
      });
      stagger.unobserve(entry.target);
    });
  }, opts);

  // Small delay so above-fold elements don't fire before the user sees the page
  setTimeout(() => {
    document.querySelectorAll('.animate:not(.animate--stagger .animate)')
      .forEach((el) => solo.observe(el));

    document.querySelectorAll('.animate--stagger')
      .forEach((group) => stagger.observe(group));
  }, 120);

  // ── Counter ──────────────────────────────────────────────────────────────
  function runCounter(el) {
    const numEl = el.querySelector('h1,h2,h3,h4,h5,h6,strong,p') || el;
    const original = numEl.textContent.trim();
    const match = original.match(/^(\D*?)(\d[\d.,]*)(\D*)$/);
    if (!match) return;

    const prefix  = match[1];
    const target  = parseInt(match[2].replace(/[.,]/g, ''), 10);
    const suffix  = match[3];
    const duration = 1400;
    const startTs  = performance.now();

    function tick(now) {
      const progress = Math.min((now - startTs) / duration, 1);
      const eased    = 1 - Math.pow(1 - progress, 3);
      const value    = Math.round(eased * target);
      numEl.textContent = prefix + value.toLocaleString(document.documentElement.lang || undefined) + suffix;
      if (progress < 1) requestAnimationFrame(tick);
    }

    numEl.textContent = prefix + '0' + suffix;
    requestAnimationFrame(tick);
  }

  const counterObs = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      runCounter(entry.target);
      counterObs.unobserve(entry.target);
    });
  }, { threshold: 0.5 });

  document.querySelectorAll('.animate--count-up')
    .forEach((el) => counterObs.observe(el));

  // ── Pause animated gradients + marquees when off-screen ──────────────
  const pauseObs = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      entry.target.classList.toggle('av-anim-paused', !entry.isIntersecting);
    });
  });
  document.querySelectorAll('.bg-gradient-animated, .trust-strip__track').forEach((el) => pauseObs.observe(el));

  // ── Tabellen-Scroll-Nudge ────────────────────────────────────────────────
  // Auf Mobil zeigt ein kurzes Anstupsen (peek nach rechts + zurück), dass eine
  // horizontal überlaufende Tabelle seitlich scrollbar ist. Einmalig je Tabelle,
  // nur bei erlaubter Bewegung, nur wenn wirklich Inhalt verdeckt ist.
  const motionOK = window.matchMedia('(prefers-reduced-motion: no-preference)').matches;
  const isMobile = window.matchMedia('(max-width: 768px)').matches;

  if (motionOK && isMobile) {
    const nudgeObs = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        nudgeObs.unobserve(el);

        const hidden = el.scrollWidth - el.clientWidth;
        if (hidden < 16 || el.scrollLeft > 4) return; // nichts verdeckt / schon gescrollt

        const peek = Math.min(48, hidden);
        el.scrollTo({ left: peek, behavior: 'smooth' });
        setTimeout(() => el.scrollTo({ left: 0, behavior: 'smooth' }), 650);
      });
    }, { threshold: 0.6 });

    document.querySelectorAll('.content-table').forEach((el) => nudgeObs.observe(el));
  }

})();

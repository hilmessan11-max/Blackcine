/* ============================================
   BLACKCINÉ PAGE TRANSITIONS 2026
   View Transitions API + Navigation Animations
   ============================================ */

(function() {
  'use strict';

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // === VIEW TRANSITIONS API ===
  if (!prefersReducedMotion && document.startViewTransition) {
    // Intercept navigation links
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href]');
      if (!link) return;

      const href = link.getAttribute('href');
      if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
      if (link.target === '_blank') return;
      if (e.ctrlKey || e.metaKey || e.shiftKey) return;

      // Skip external links
      if (href.startsWith('http') && !href.includes(window.location.hostname)) return;

      e.preventDefault();

      const destination = new URL(href, window.location.origin).href;
      if (destination === window.location.href) return;

      // Check for same-origin
      if (new URL(destination).origin !== window.location.origin) {
        window.location.href = destination;
        return;
      }

      document.startViewTransition(async () => {
        const response = await fetch(destination);
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Update content
        const newMain = doc.querySelector('main, .main-content, #main-content');
        const oldMain = document.querySelector('main, .main-content, #main-content');
        if (newMain && oldMain) {
          oldMain.innerHTML = newMain.innerHTML;
        }

        // Update title
        document.title = doc.title;

        // Update URL
        window.history.pushState({}, '', destination);

        // Re-initialize scripts
        initPageScripts();
      });
    });

    // Handle back/forward
    window.addEventListener('popstate', () => {
      if (document.startViewTransition) {
        document.startViewTransition(() => {
          window.location.reload();
        });
      }
    });
  }

  // === CSS FOR VIEW TRANSITIONS ===
  const viewTransitionStyles = document.createElement('style');
  viewTransitionStyles.textContent = `
    @view-transition {
      navigation: auto;
    }

    /* Custom transition names */
    ::view-transition-old(root) {
      animation: fade-out-scale 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    ::view-transition-new(root) {
      animation: fade-in-scale 0.3s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    @keyframes fade-out-scale {
      0% { opacity: 1; transform: scale(1); }
      100% { opacity: 0; transform: scale(0.95); }
    }

    @keyframes fade-in-scale {
      0% { opacity: 0; transform: scale(1.05); }
      100% { opacity: 1; transform: scale(1); }
    }

    /* Hero-specific transitions */
    ::view-transition-old(hero-section) {
      animation: slide-out-left 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    ::view-transition-new(hero-section) {
      animation: slide-in-right 0.35s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    @keyframes slide-out-left {
      0% { opacity: 1; transform: translateX(0); }
      100% { opacity: 0; transform: translateX(-30px); }
    }

    @keyframes slide-in-right {
      0% { opacity: 0; transform: translateX(30px); }
      100% { opacity: 1; transform: translateX(0); }
    }

    /* Card transitions */
    ::view-transition-old(card) {
      animation: card-exit 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    ::view-transition-new(card) {
      animation: card-enter 0.3s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    @keyframes card-exit {
      0% { opacity: 1; transform: translateY(0) scale(1); }
      100% { opacity: 0; transform: translateY(20px) scale(0.95); }
    }

    @keyframes card-enter {
      0% { opacity: 0; transform: translateY(-20px) scale(0.95); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }
  `;
  document.head.appendChild(viewTransitionStyles);

  // === PAGE LOAD TRANSITION ===
  const initPageLoad = () => {
    document.body.classList.add('page-loaded');

    // Animate hero on load
    const heroElements = document.querySelectorAll('.hero-animate-in, [data-animate]');
    heroElements.forEach((el, index) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(30px)';
      setTimeout(() => {
        el.style.transition = 'opacity 0.6s cubic-bezier(0.23, 1, 0.32, 1), transform 0.6s cubic-bezier(0.23, 1, 0.32, 1)';
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      }, 100 + (index * 80));
    });
  };

  // === RE-INITIALIZE SCRIPTS AFTER TRANSITION ===
  const initPageScripts = () => {
    // Re-run any initialization needed
    if (typeof window.initApp === 'function') window.initApp();
    if (typeof window.initSearch === 'function') window.initSearch();
  };

  // === INITIALIZE ===
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPageLoad);
  } else {
    initPageLoad();
  }

  window.bcTransitions = { initPageScripts };
})();

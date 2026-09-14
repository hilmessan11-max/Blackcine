/* ============================================
   BLACKCINÉ ACCESSIBILITY 2026
   ARIA Labels, Skip Navigation, Contrast
   ============================================ */

(function() {
  'use strict';

  // === SKIP NAVIGATION ===
  const initSkipNav = () => {
    const skipLink = document.createElement('a');
    skipLink.href = '#main-content';
    skipLink.className = 'skip-nav';
    skipLink.textContent = 'Aller au contenu principal';
    skipLink.setAttribute('aria-label', 'Aller au contenu principal');
    document.body.prepend(skipLink);

    // Add main content ID if not present
    const main = document.querySelector('main, .main-content, #main-content');
    if (main && !main.id) {
      main.id = 'main-content';
    }
  };

  // === ARIA LABELS FOR ICONS ===
  const initAriaLabels = () => {
    // Navigation
    document.querySelectorAll('nav a[href]').forEach(link => {
      if (!link.getAttribute('aria-label') && !link.textContent.trim()) {
        const icon = link.querySelector('i, svg, img');
        if (icon) {
          const label = getIconLabel(icon);
          if (label) link.setAttribute('aria-label', label);
        }
      }
    });

    // Buttons with only icons
    document.querySelectorAll('button').forEach(btn => {
      if (!btn.getAttribute('aria-label') && !btn.textContent.trim()) {
        const icon = btn.querySelector('i, svg, img');
        if (icon) {
          const label = getIconLabel(icon);
          if (label) btn.setAttribute('aria-label', label);
        }
      }
    });

    // Social links
    document.querySelectorAll('a[href*="facebook"], a[href*="twitter"], a[href*="instagram"], a[href*="youtube"]').forEach(link => {
      if (!link.getAttribute('aria-label')) {
        const platform = link.href.includes('facebook') ? 'Facebook' :
                        link.href.includes('twitter') ? 'Twitter' :
                        link.href.includes('instagram') ? 'Instagram' :
                        link.href.includes('youtube') ? 'YouTube' : '';
        if (platform) link.setAttribute('aria-label', `Suivez-nous sur ${platform}`);
      }
    });
  };

  const getIconLabel = (icon) => {
    const classes = icon.className || '';
    if (classes.includes('fa-home') || classes.includes('home')) return 'Accueil';
    if (classes.includes('fa-search') || classes.includes('search')) return 'Rechercher';
    if (classes.includes('fa-user') || classes.includes('user')) return 'Profil';
    if (classes.includes('fa-heart') || classes.includes('heart')) return 'Favoris';
    if (classes.includes('fa-film') || classes.includes('film')) return 'Films';
    if (classes.includes('fa-tv') || classes.includes('tv')) return 'Séries';
    if (classes.includes('fa-play') || classes.includes('play')) return 'Lire';
    if (classes.includes('fa-share') || classes.includes('share')) return 'Partager';
    if (classes.includes('fa-bookmark') || classes.includes('bookmark')) return 'Sauvegarder';
    if (classes.includes('fa-star') || classes.includes('star')) return 'Noter';
    if (classes.includes('fa-comment') || classes.includes('comment')) return 'Commenter';
    if (classes.includes('fa-close') || classes.includes('close') || classes.includes('times')) return 'Fermer';
    if (classes.includes('fa-menu') || classes.includes('menu')) return 'Menu';
    if (classes.includes('fa-arrow') || classes.includes('arrow')) return '';
    if (classes.includes('fa-chevron') || classes.includes('chevron')) return '';
    if (classes.includes('fa-plus') || classes.includes('plus')) return 'Ajouter';
    if (classes.includes('fa-edit') || classes.includes('edit')) return 'Modifier';
    if (classes.includes('fa-trash') || classes.includes('delete')) return 'Supprimer';
    if (classes.includes('fa-settings') || classes.includes('settings')) return 'Paramètres';
    if (classes.includes('fa-bell') || classes.includes('bell')) return 'Notifications';
    if (classes.includes('fa-envelope') || classes.includes('envelope')) return 'Email';
    if (classes.includes('fa-phone') || classes.includes('phone')) return 'Téléphone';
    if (classes.includes('fa-map') || classes.includes('map')) return 'Carte';
    if (classes.includes('fa-calendar') || classes.includes('calendar')) return 'Calendrier';
    if (classes.includes('fa-clock') || classes.includes('clock')) return 'Horaires';
    return '';
  };

  // === FOCUS TRAPPING FOR MODALS ===
  const initFocusTrap = () => {
    let lastFocusedElement = null;

    const FOCUSABLE_SELECTOR = 'button:not([disabled]):not([tabindex="-1"]), ' +
      'a[href]:not([disabled]):not([tabindex="-1"]), ' +
      'input:not([disabled]):not([type="hidden"]):not([tabindex="-1"]), ' +
      'select:not([disabled]):not([tabindex="-1"]), ' +
      'textarea:not([disabled]):not([tabindex="-1"]), ' +
      '[tabindex]:not([tabindex="-1"])';

    const setupAriaAttributes = (modal) => {
      if (!modal.getAttribute('role')) {
        modal.setAttribute('role', 'dialog');
      }
      if (!modal.getAttribute('aria-modal')) {
        modal.setAttribute('aria-modal', 'true');
      }
      const title = modal.querySelector('.modal-title, [id*="ModalLabel"], h2, h3');
      if (title) {
        if (!title.id) {
          title.id = modal.id + '-title';
        }
        if (!modal.getAttribute('aria-labelledby')) {
          modal.setAttribute('aria-labelledby', title.id);
        }
      }
    };

    const handleKeydown = (modal, e) => {
      if (e.key === 'Escape') {
        e.preventDefault();
        const closeBtn = modal.querySelector('[data-bs-dismiss="modal"]');
        if (closeBtn) closeBtn.click();
        return;
      }

      if (e.key !== 'Tab') return;

      const focusableElements = Array.from(modal.querySelectorAll(FOCUSABLE_SELECTOR))
        .filter(el => el.offsetParent !== null && getComputedStyle(el).display !== 'none');

      if (focusableElements.length === 0) return;

      const firstFocusable = focusableElements[0];
      const lastFocusable = focusableElements[focusableElements.length - 1];

      if (e.shiftKey) {
        if (document.activeElement === firstFocusable) {
          e.preventDefault();
          lastFocusable.focus();
        }
      } else {
        if (document.activeElement === lastFocusable) {
          e.preventDefault();
          firstFocusable.focus();
        }
      }
    };

    const setupModal = (modal) => {
      setupAriaAttributes(modal);

      modal.addEventListener('shown.bs.modal', () => {
        lastFocusedElement = document.activeElement;
        document.body.style.overflow = 'hidden';

        const focusableElements = Array.from(modal.querySelectorAll(FOCUSABLE_SELECTOR))
          .filter(el => el.offsetParent !== null && getComputedStyle(el).display !== 'none');

        if (focusableElements.length) {
          const autofocus = modal.querySelector('[autofocus]');
          (autofocus || focusableElements[0]).focus();
        }

        modal._focusTrapHandler = (e) => handleKeydown(modal, e);
        modal.addEventListener('keydown', modal._focusTrapHandler);
      });

      modal.addEventListener('hidden.bs.modal', () => {
        document.body.style.overflow = '';

        if (modal._focusTrapHandler) {
          modal.removeEventListener('keydown', modal._focusTrapHandler);
          delete modal._focusTrapHandler;
        }

        if (lastFocusedElement && lastFocusedElement.isConnected) {
          lastFocusedElement.focus();
          lastFocusedElement = null;
        }
      });
    };

    document.querySelectorAll('.modal, [role="dialog"]').forEach(setupModal);

    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
          if (node.nodeType === 1) {
            if (node.matches && node.matches('.modal, [role="dialog"]')) {
              setupModal(node);
            }
            if (node.querySelectorAll) {
              node.querySelectorAll('.modal, [role="dialog"]').forEach(setupModal);
            }
          }
        });
      });
    });

    observer.observe(document.body, { childList: true, subtree: true });
  };

  // === KEYBOARD NAVIGATION ===
  const initKeyboardNav = () => {
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        const openDropdown = document.querySelector('.dropdown-menu.show');
        if (openDropdown) {
          openDropdown.classList.remove('show');
        }
      }
    });

    // Arrow keys for nav
    document.querySelectorAll('.nav-menu, [role="menubar"]').forEach(menu => {
      const items = menu.querySelectorAll('a, button, [role="menuitem"]');
      items.forEach((item, index) => {
        item.addEventListener('keydown', (e) => {
          if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            const next = items[index + 1] || items[0];
            next.focus();
          }
          if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            const prev = items[index - 1] || items[items.length - 1];
            prev.focus();
          }
        });
      });
    });
  };

  // === REDUCED MOTION ===
  const initReducedMotion = () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    const updateMotion = () => {
      document.documentElement.classList.toggle('reduce-motion', prefersReducedMotion.matches);
    };

    updateMotion();
    prefersReducedMotion.addEventListener('change', updateMotion);
  };

  // === HIGH CONTRAST MODE ===
  const initHighContrast = () => {
    const toggle = document.createElement('button');
    toggle.className = 'contrast-toggle';
    toggle.setAttribute('aria-label', 'Basculer le mode contraste élevé');
    toggle.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20V2z"/></svg>';
    toggle.title = 'Contraste élevé';

    // Only show if user prefers high contrast
    if (window.matchMedia('(prefers-contrast: more)').matches) {
      document.body.appendChild(toggle);
    }

    toggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('high-contrast');
      const isHigh = document.documentElement.classList.contains('high-contrast');
      localStorage.setItem('bc-high-contrast', isHigh);
    });

    // Restore preference
    if (localStorage.getItem('bc-high-contrast') === 'true') {
      document.documentElement.classList.add('high-contrast');
    }
  };

  // === SCREEN READER ANNOUNCEMENTS ===
  const initSRAnnouncements = () => {
    const announcer = document.createElement('div');
    announcer.className = 'sr-only';
    announcer.setAttribute('role', 'status');
    announcer.setAttribute('aria-live', 'polite');
    announcer.setAttribute('aria-atomic', 'true');
    document.body.appendChild(announcer);

    window.bcAnnounce = (message) => {
      announcer.textContent = message;
      setTimeout(() => { announcer.textContent = ''; }, 1000);
    };
  };

  // === ARIA ATTRIBUTES FOR DYNAMIC CONTENT ===
  const initDynamicAria = () => {
    // Loading states
    document.querySelectorAll('[data-loading]').forEach(el => {
      el.setAttribute('aria-busy', 'true');
      el.setAttribute('role', 'status');
    });

    // Expandable content
    document.querySelectorAll('[data-toggle], [data-accordion]').forEach(btn => {
      const target = document.querySelector(btn.dataset.target || btn.getAttribute('href'));
      if (target) {
        btn.setAttribute('aria-expanded', 'false');
        btn.setAttribute('aria-controls', target.id || 'accordion-content');

        btn.addEventListener('click', () => {
          const isExpanded = btn.getAttribute('aria-expanded') === 'true';
          btn.setAttribute('aria-expanded', !isExpanded);
        });
      }
    });

    // Tabs
    document.querySelectorAll('[data-bs-toggle="tab"], [role="tab"]').forEach(tab => {
      tab.setAttribute('role', 'tab');
      tab.setAttribute('aria-selected', tab.classList.contains('active'));
    });
  };

  // === CSS FOR ACCESSIBILITY ===
  const a11yStyles = document.createElement('style');
  a11yStyles.textContent = `
    /* Skip navigation */
    .skip-nav {
      position: absolute;
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--bc-primary);
      color: white;
      padding: 12px 24px;
      border-radius: 0 0 8px 8px;
      font-weight: 600;
      z-index: 99999;
      transition: top 0.3s ease;
      text-decoration: none;
    }

    .skip-nav:focus {
      top: 0;
      outline: 3px solid white;
      outline-offset: 2px;
    }

    /* Screen reader only */
    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    /* Focus visible */
    :focus-visible {
      outline: 2px solid var(--bc-primary);
      outline-offset: 2px;
    }

    :focus:not(:focus-visible) {
      outline: none;
    }

    /* High contrast mode */
    .high-contrast {
      --bc-text-primary: #ffffff;
      --bc-text-secondary: #e0e0e0;
      --bc-border: rgba(255, 255, 255, 0.3);
    }

    .high-contrast * {
      border-color: rgba(255, 255, 255, 0.3) !important;
    }

    .high-contrast a,
    .high-contrast button {
      text-decoration: underline;
    }

    /* Reduced motion */
    .reduce-motion *,
    .reduce-motion *::before,
    .reduce-motion *::after {
      animation-duration: 0.01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.01ms !important;
    }

    /* Contrast toggle button */
    .contrast-toggle {
      position: fixed;
      bottom: 90px;
      right: 30px;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: var(--bc-bg-card);
      border: 1px solid var(--bc-border);
      color: var(--bc-text-secondary);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 998;
      transition: all 0.2s ease;
    }

    .contrast-toggle:hover {
      background: var(--bc-primary);
      color: white;
      border-color: var(--bc-primary);
    }

    /* Ensure touch targets are at least 44x44 */
    @media (pointer: coarse) {
      button, a, input[type="checkbox"], input[type="radio"] {
        min-height: 44px;
        min-width: 44px;
      }
    }

    /* Reduced motion media query */
    @media (prefers-reduced-motion: reduce) {
      .reduce-motion *,
      .reduce-motion *::before,
      .reduce-motion *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
      }
    }
  `;
  document.head.appendChild(a11yStyles);

  // === INITIALIZE ===
  document.addEventListener('DOMContentLoaded', () => {
    initSkipNav();
    initAriaLabels();
    initFocusTrap();
    initKeyboardNav();
    initReducedMotion();
    initHighContrast();
    initSRAnnouncements();
    initDynamicAria();
  });
})();

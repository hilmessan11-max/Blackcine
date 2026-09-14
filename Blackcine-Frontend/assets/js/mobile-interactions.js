/* ============================================
   BLACKCINÉ MOBILE INTERACTIONS 2026
   Swipe Gestures, Touch Feedback, Bottom Sheets
   ============================================ */

(function() {
  'use strict';

  const isMobile = /Android|iPhone|iPad|iPod|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // === TOUCH FEEDBACK ===
  const initTouchFeedback = () => {
    if (!isMobile) return;

    document.addEventListener('touchstart', (e) => {
      const target = e.target.closest('.card, .btn, .nav-link, .list-group-item, [data-touch]');
      if (target) {
        target.classList.add('touch-active');
      }
    }, { passive: true });

    document.addEventListener('touchend', (e) => {
      const target = e.target.closest('.card, .btn, .nav-link, .list-group-item, [data-touch]');
      if (target) {
        setTimeout(() => target.classList.remove('touch-active'), 150);
      }
    }, { passive: true });

    document.addEventListener('touchcancel', () => {
      document.querySelectorAll('.touch-active').forEach(el => el.classList.remove('touch-active'));
    }, { passive: true });
  };

  // === SWIPE GESTURES ===
  const initSwipeGestures = () => {
    // Swipeable carousels
    document.querySelectorAll('[data-swipe], .swiper, .carousel-scroll').forEach(container => {
      let startX = 0;
      let startY = 0;
      let currentX = 0;
      let isDragging = false;
      let startTime = 0;

      container.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        startTime = Date.now();
        isDragging = true;
        container.classList.add('swiping');
      }, { passive: true });

      container.addEventListener('touchmove', (e) => {
        if (!isDragging) return;

        currentX = e.touches[0].clientX;
        const diffX = currentX - startX;
        const diffY = e.touches[0].clientY - startY;

        // Only horizontal swipe
        if (Math.abs(diffX) > Math.abs(diffY)) {
          container.scrollLeft -= diffX * 0.3;
        }
      }, { passive: true });

      container.addEventListener('touchend', (e) => {
        if (!isDragging) return;
        isDragging = false;
        container.classList.remove('swiping');

        const diffX = currentX - startX;
        const elapsed = Date.now() - startTime;
        const velocity = Math.abs(diffX) / elapsed;

        // Quick swipe or long swipe
        if (velocity > 0.3 || Math.abs(diffX) > 80) {
          if (diffX > 0) {
            swipeAction(container, 'left');
          } else {
            swipeAction(container, 'right');
          }
        }
      }, { passive: true });
    });

    // Swipe navigation (back/forward)
    let navStartX = 0;
    let navStartY = 0;
    let navDragging = false;

    document.addEventListener('touchstart', (e) => {
      if (e.touches[0].clientX < 30) {
        navStartX = e.touches[0].clientX;
        navStartY = e.touches[0].clientY;
        navDragging = true;
      }
    }, { passive: true });

    document.addEventListener('touchend', (e) => {
      if (!navDragging) return;
      navDragging = false;

      const diffX = e.changedTouches[0].clientX - navStartX;
      const diffY = e.changedTouches[0].clientY - navStartY;

      if (diffX > 100 && Math.abs(diffY) < 50) {
        window.history.back();
      }
    }, { passive: true });
  };

  const swipeAction = (container, direction) => {
    const scrollAmount = container.clientWidth * 0.8;
    if (direction === 'left') {
      container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
      container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
  };

  // === BOTTOM SHEET ===
  window.bcBottomSheet = {
    show: (content, options = {}) => {
      const sheet = document.createElement('div');
      sheet.className = 'bc-bottom-sheet';
      sheet.innerHTML = `
        <div class="bc-bottom-sheet-backdrop"></div>
        <div class="bc-bottom-sheet-content" role="dialog" aria-modal="true">
          <div class="bc-bottom-sheet-handle"></div>
          <div class="bc-bottom-sheet-body">${content}</div>
        </div>
      `;

      document.body.appendChild(sheet);
      document.body.style.overflow = 'hidden';

      // Animate in
      requestAnimationFrame(() => {
        sheet.classList.add('bc-bottom-sheet-visible');
      });

      // Close on backdrop click
      sheet.querySelector('.bc-bottom-sheet-backdrop').addEventListener('click', () => {
        window.bcBottomSheet.hide(sheet);
      });

      // Close on handle swipe down
      const handle = sheet.querySelector('.bc-bottom-sheet-handle');
      let handleStartY = 0;

      handle.addEventListener('touchstart', (e) => {
        handleStartY = e.touches[0].clientY;
      }, { passive: true });

      handle.addEventListener('touchend', (e) => {
        const diff = e.changedTouches[0].clientY - handleStartY;
        if (diff > 80) {
          window.bcBottomSheet.hide(sheet);
        }
      }, { passive: true });

      // Close button
      const closeBtn = sheet.querySelector('[data-close]');
      if (closeBtn) {
        closeBtn.addEventListener('click', () => window.bcBottomSheet.hide(sheet));
      }

      return sheet;
    },

    hide: (sheet) => {
      if (!sheet) sheet = document.querySelector('.bc-bottom-sheet');
      if (!sheet) return;

      sheet.classList.remove('bc-bottom-sheet-visible');
      sheet.classList.add('bc-bottom-sheet-hiding');

      setTimeout(() => {
        sheet.remove();
        document.body.style.overflow = '';
      }, 300);
    }
  };

  // === PULL TO REFRESH ===
  const initPullToRefresh = () => {
    if (!isMobile) return;

    let startY = 0;
    let pulling = false;
    const threshold = 100;

    const indicator = document.createElement('div');
    indicator.className = 'pull-to-refresh';
    indicator.innerHTML = '<div class="ptr-spinner"></div>';
    document.body.appendChild(indicator);

    document.addEventListener('touchstart', (e) => {
      if (window.pageYOffset === 0) {
        startY = e.touches[0].clientY;
        pulling = true;
      }
    }, { passive: true });

    document.addEventListener('touchmove', (e) => {
      if (!pulling) return;

      const currentY = e.touches[0].clientY;
      const diff = currentY - startY;

      if (diff > 0 && window.pageYOffset === 0) {
        const progress = Math.min(diff / threshold, 1);
        indicator.style.transform = `translateY(${diff * 0.5}px)`;
        indicator.style.opacity = progress;
        indicator.classList.toggle('ptr-active', progress >= 1);
      }
    }, { passive: true });

    document.addEventListener('touchend', () => {
      if (!pulling) return;
      pulling = false;

      if (indicator.classList.contains('ptr-active')) {
        indicator.classList.add('ptr-loading');
        // Trigger refresh
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        indicator.style.transform = '';
        indicator.style.opacity = '';
      }
    }, { passive: true });
  };

  // === SCROLL INDICATOR FOR MOBILE ===
  const initScrollIndicator = () => {
    if (!isMobile) return;

    const indicator = document.createElement('div');
    indicator.className = 'mobile-scroll-indicator';
    indicator.innerHTML = '<div class="msi-progress"></div>';
    document.body.appendChild(indicator);

    const progress = indicator.querySelector('.msi-progress');

    window.addEventListener('scroll', () => {
      const scrollTop = window.pageYOffset;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
      progress.style.width = scrollPercent + '%';
    }, { passive: true });
  };

  // === CSS FOR MOBILE INTERACTIONS ===
  const mobileStyles = document.createElement('style');
  mobileStyles.textContent = `
    /* Touch feedback */
    .touch-active {
      transform: scale(0.97) !important;
      opacity: 0.8;
      transition: transform 0.1s ease, opacity 0.1s ease !important;
    }

    /* Swipe container */
    .swiping {
      cursor: grabbing !important;
      scroll-behavior: auto !important;
    }

    /* Bottom Sheet */
    .bc-bottom-sheet {
      position: fixed;
      inset: 0;
      z-index: 9999;
      pointer-events: none;
    }

    .bc-bottom-sheet-visible {
      pointer-events: auto;
    }

    .bc-bottom-sheet-backdrop {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .bc-bottom-sheet-visible .bc-bottom-sheet-backdrop {
      opacity: 1;
    }

    .bc-bottom-sheet-content {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: var(--bc-bg-card);
      border-radius: 20px 20px 0 0;
      max-height: 80vh;
      overflow-y: auto;
      transform: translateY(100%);
      transition: transform 0.4s cubic-bezier(0.32, 0.72, 0, 1);
    }

    .bc-bottom-sheet-visible .bc-bottom-sheet-content {
      transform: translateY(0);
    }

    .bc-bottom-sheet-hiding .bc-bottom-sheet-content {
      transform: translateY(100%);
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .bc-bottom-sheet-handle {
      width: 40px;
      height: 4px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 2px;
      margin: 12px auto;
      cursor: grab;
    }

    .bc-bottom-sheet-body {
      padding: 0 20px 30px;
    }

    /* Pull to refresh */
    .pull-to-refresh {
      position: fixed;
      top: 0;
      left: 50%;
      transform: translateX(-50%) translateY(-100%);
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9998;
      opacity: 0;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .ptr-spinner {
      width: 24px;
      height: 24px;
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-top-color: var(--bc-primary);
      border-radius: 50%;
      animation: ptr-spin 0.8s linear infinite;
    }

    .ptr-active .ptr-spinner {
      border-color: var(--bc-primary);
    }

    .ptr-loading .ptr-spinner {
      animation: ptr-spin 0.6s linear infinite;
    }

    @keyframes ptr-spin {
      to { transform: rotate(360deg); }
    }

    /* Mobile scroll indicator */
    .mobile-scroll-indicator {
      display: none;
    }

    @media (max-width: 768px) {
      .mobile-scroll-indicator {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.1);
        z-index: 9999;
      }

      .msi-progress {
        height: 100%;
        background: var(--bc-gradient-primary);
        transition: width 100ms linear;
        box-shadow: 0 0 8px rgba(229, 9, 20, 0.5);
      }
    }

    /* Prevent overscroll on iOS */
    @supports (-webkit-touch-callout: none) {
      body {
        -webkit-overflow-scrolling: touch;
      }
    }
  `;
  document.head.appendChild(mobileStyles);

  // === INITIALIZE ===
  document.addEventListener('DOMContentLoaded', () => {
    initTouchFeedback();
    initSwipeGestures();
    initPullToRefresh();
    initScrollIndicator();
  });
})();

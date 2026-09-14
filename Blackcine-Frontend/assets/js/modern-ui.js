/* ============================================
   BLACKCINE MODERN UI 2026
   Advanced Scroll Animations & Micro-Interactions
   ============================================ */

(function() {
  'use strict';

  // === REDUCED MOTION DETECTION ===
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const animationDuration = prefersReducedMotion ? 0.01 : 1;

  // === EASING TOKENS (Emil Kowalski style) ===
  const easings = {
    out: 'cubic-bezier(0.23, 1, 0.32, 1)',
    inOut: 'cubic-bezier(0.77, 0, 0.175, 1)',
    spring: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
    snappy: 'cubic-bezier(0.19, 1, 0.22, 1)',
    drawer: 'cubic-bezier(0.32, 0.72, 0, 1)'
  };

  // === SCROLL PROGRESS INDICATOR ===
  const createScrollProgress = () => {
    const bar = document.createElement('div');
    bar.className = 'scroll-progress-bar';
    bar.setAttribute('aria-hidden', 'true');
    document.body.prepend(bar);

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          const scrollTop = window.pageYOffset;
          const docHeight = document.documentElement.scrollHeight - window.innerHeight;
          const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
          bar.style.width = progress + '%';
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  };

  // === HEADER SCROLL EFFECT ===
  const initHeaderScroll = () => {
    const header = document.querySelector('header');
    if (!header) return;

    let lastScroll = 0;
    let ticking = false;

    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          const currentScroll = window.pageYOffset;
          header.classList.toggle('scrolled', currentScroll > 50);
          lastScroll = currentScroll;
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  };

  // === SCROLL REVEAL ANIMATIONS ===
  const initScrollReveal = () => {
    const revealElements = document.querySelectorAll('[data-animate]');
    if (!revealElements.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const delay = parseInt(el.dataset.delay) || 0;
          const stagger = parseInt(el.dataset.stagger) || 0;
          const parentIndex = el.dataset.parentIndex || 0;

          setTimeout(() => {
            el.classList.add('revealed');
          }, (delay + (parentIndex * stagger)) * animationDuration);

          observer.unobserve(el);
        }
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(el => observer.observe(el));
  };

  // === STAGGER CHILDREN ANIMATIONS ===
  const initStaggerChildren = () => {
    document.querySelectorAll('[data-stagger-parent]').forEach(parent => {
      const children = parent.children;
      Array.from(children).forEach((child, index) => {
        child.dataset.animate = parent.dataset.staggerParent;
        child.dataset.stagger = parent.dataset.staggerDelay || '80';
        child.dataset.parentIndex = index;
      });
    });
  };

  // === SMOOTH SCROLL FOR ANCHORS ===
  const initSmoothScroll = () => {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#' || href.length < 2) return;

        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          const headerHeight = document.querySelector('header')?.offsetHeight || 0;
          const targetPos = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

          window.scrollTo({
            top: targetPos,
            behavior: prefersReducedMotion ? 'auto' : 'smooth'
          });
        }
      });
    });
  };

  // === BACK TO TOP BUTTON ===
  const initBackToTop = () => {
    const btn = document.createElement('button');
    btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>';
    btn.className = 'back-to-top';
    btn.setAttribute('aria-label', 'Retour en haut');
    document.body.appendChild(btn);

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          btn.classList.toggle('visible', window.pageYOffset > 400);
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
    });
  };

  // === RIPPLE EFFECT ON BUTTONS ===
  const initRipple = () => {
    document.querySelectorAll('button, .btn, a.btn').forEach(btn => {
      btn.style.position = 'relative';
      btn.style.overflow = 'hidden';

      btn.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const ripple = document.createElement('span');
        ripple.className = 'bc-ripple';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        this.appendChild(ripple);

        setTimeout(() => ripple.remove(), 700);
      });
    });
  };

  // === PRESS FEEDBACK (scale on :active) ===
  const initPressFeedback = () => {
    document.querySelectorAll('.card, .top-movie-card, .article-card, .ticket-movie-card').forEach(el => {
      el.addEventListener('mousedown', function() {
        this.style.transform = 'scale(0.97)';
        this.style.transition = `transform ${160}ms ${easings.out}`;
      });
      el.addEventListener('mouseup', function() {
        this.style.transform = '';
        this.style.transition = `transform ${300}ms ${easings.out}`;
      });
      el.addEventListener('mouseleave', function() {
        this.style.transform = '';
        this.style.transition = `transform ${300}ms ${easings.out}`;
      });
    });
  };

  // === TILT EFFECT ON CARDS ===
  const initTiltEffect = () => {
    if (prefersReducedMotion) return;

    document.querySelectorAll('.top-movie-card, .article-card, .selection-movie-card').forEach(card => {
      let rafId = null;

      card.addEventListener('mousemove', function(e) {
        if (rafId) cancelAnimationFrame(rafId);

        rafId = requestAnimationFrame(() => {
          const rect = this.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          const centerX = rect.width / 2;
          const centerY = rect.height / 2;
          const rotateX = ((y - centerY) / centerY) * -5;
          const rotateY = ((x - centerX) / centerX) * 5;

          this.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
          this.style.transition = 'transform 100ms ease-out';
        });
      });

      card.addEventListener('mouseleave', function() {
        if (rafId) cancelAnimationFrame(rafId);
        this.style.transform = '';
        this.style.transition = `transform ${400}ms ${easings.spring}`;
      });
    });
  };

  // === PARALLAX HERO ===
  const initParallax = () => {
    if (prefersReducedMotion) return;

    const hero = document.querySelector('.hero-section, .hero, [data-parallax]');
    if (!hero) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          const scrolled = window.pageYOffset;
          const heroBg = hero.querySelector('.hero-bg, .hero-background, [data-parallax-bg]');
          if (heroBg && scrolled < window.innerHeight * 1.5) {
            heroBg.style.transform = `translateY(${scrolled * 0.4}px)`;
          }
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  };

  // === COUNTER ANIMATION ===
  const initCounters = () => {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const animateCounter = (el) => {
      const target = parseInt(el.dataset.count);
      const suffix = el.dataset.suffix || '';
      const prefix = el.dataset.prefix || '';
      const duration = 2000;
      const startTime = performance.now();

      const easeOutQuart = t => 1 - Math.pow(1 - t, 4);

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = easeOutQuart(progress);
        const current = Math.floor(eased * target);

        el.textContent = prefix + current.toLocaleString() + suffix;

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          el.textContent = prefix + target.toLocaleString() + suffix;
        }
      };

      requestAnimationFrame(update);
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
  };

  // === SKELETON LOADING ===
  const initSkeletons = () => {
    document.querySelectorAll('.skeleton, [data-skeleton]').forEach(el => {
      el.classList.add('bc-skeleton');
    });
  };

  // === SECTION FADE-IN ON SCROLL ===
  const initSectionFade = () => {
    const sections = document.querySelectorAll('section');
    sections.forEach((section, index) => {
      if (index === 0) return;
      section.dataset.animate = 'fade-up';
      section.dataset.delay = '0';
    });
  };

  // === HOVER GLOW EFFECT ===
  const initHoverGlow = () => {
    document.querySelectorAll('.btn-primary, .btn-register').forEach(btn => {
      btn.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        this.style.setProperty('--glow-x', x + 'px');
        this.style.setProperty('--glow-y', y + 'px');
      });
    });
  };

  // === ACCORDION ANIMATION ===
  const initAccordions = () => {
    document.querySelectorAll('.accordion-button, [data-accordion]').forEach(btn => {
      btn.addEventListener('click', function() {
        const target = this.dataset.target || this.getAttribute('href');
        if (!target) return;

        const content = document.querySelector(target);
        if (!content) return;

        const isOpen = content.classList.contains('show');

        if (isOpen) {
          content.style.height = content.scrollHeight + 'px';
          requestAnimationFrame(() => {
            content.style.height = '0';
            content.style.opacity = '0';
          });
          setTimeout(() => {
            content.classList.remove('show');
            content.style.height = '';
            content.style.opacity = '';
          }, 300);
        } else {
          content.classList.add('show');
          content.style.height = '0';
          content.style.opacity = '0';
          requestAnimationFrame(() => {
            content.style.height = content.scrollHeight + 'px';
            content.style.opacity = '1';
          });
          setTimeout(() => {
            content.style.height = '';
          }, 300);
        }
      });
    });
  };

  // === TOAST NOTIFICATIONS ===
  window.bcToast = function(message, type = 'info', duration = 4000) {
    const container = document.getElementById('toast-container') || (() => {
      const c = document.createElement('div');
      c.id = 'toast-container';
      c.className = 'bc-toast-container';
      document.body.appendChild(c);
      return c;
    })();

    const toast = document.createElement('div');
    toast.className = `bc-toast bc-toast-${type}`;
    toast.innerHTML = `
      <div class="bc-toast-content">
        <span class="bc-toast-icon">${getToastIcon(type)}</span>
        <span class="bc-toast-message">${message}</span>
      </div>
      <button class="bc-toast-close" aria-label="Fermer">&times;</button>
    `;

    toast.querySelector('.bc-toast-close').addEventListener('click', () => removeToast(toast));
    container.appendChild(toast);

    requestAnimationFrame(() => {
      toast.classList.add('bc-toast-enter');
    });

    setTimeout(() => removeToast(toast), duration);
  };

  function getToastIcon(type) {
    const icons = {
      success: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
      error: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
      warning: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
      info: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
    };
    return icons[type] || icons.info;
  }

  function removeToast(toast) {
    toast.classList.add('bc-toast-exit');
    setTimeout(() => toast.remove(), 300);
  }

  // === MAGNETIC BUTTONS ===
  const initMagneticButtons = () => {
    if (prefersReducedMotion || window.innerWidth < 768) return;

    document.querySelectorAll('.btn-primary, .btn-register').forEach(btn => {
      btn.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        this.style.transform = `translate(${x * 0.15}px, ${y * 0.15}px)`;
        this.style.transition = 'transform 150ms ease-out';
      });

      btn.addEventListener('mouseleave', function() {
        this.style.transform = '';
        this.style.transition = `transform ${400}ms ${easings.spring}`;
      });
    });
  };

  // === MARQUEE FOR TICKERS ===
  const initMarquees = () => {
    document.querySelectorAll('[data-marquee]').forEach(container => {
      const content = container.innerHTML;
      container.innerHTML = `<div class="bc-marquee-inner">${content}${content}</div>`;
    });
  };

  // === FOCUS VISIBLE STYLES ===
  const initFocusVisible = () => {
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        document.body.classList.add('keyboard-nav');
      }
    });
    document.addEventListener('mousedown', () => {
      document.body.classList.remove('keyboard-nav');
    });
  };

  // === IMAGE LAZY LOADING WITH FADE ===
  const initLazyImages = () => {
    const images = document.querySelectorAll('img[data-src]');
    if (!images.length) return;

    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.add('bc-img-loaded');
          img.removeAttribute('data-src');
          imageObserver.unobserve(img);
        }
      });
    }, { rootMargin: '100px' });

    images.forEach(img => {
      img.classList.add('bc-img-lazy');
      imageObserver.observe(img);
    });
  };

  // === INITIALIZE ===
  document.addEventListener('DOMContentLoaded', () => {
    createScrollProgress();
    initHeaderScroll();
    initStaggerChildren();
    initScrollReveal();
    initSmoothScroll();
    initBackToTop();
    initRipple();
    initPressFeedback();
    initTiltEffect();
    initParallax();
    initCounters();
    initSkeletons();
    initSectionFade();
    initHoverGlow();
    initAccordions();
    initMagneticButtons();
    initMarquees();
    initFocusVisible();
    initLazyImages();

  });

})();

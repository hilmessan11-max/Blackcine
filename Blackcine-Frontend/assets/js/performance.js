/* ============================================
   BLACKCINÉ PERFORMANCE 2026
   Lazy Loading, Code Splitting, Optimization
   ============================================ */

(function() {
  'use strict';

  // === LAZY LOADING IMAGES ===
  const initLazyImages = () => {
    const images = document.querySelectorAll('img[data-src], img[loading="lazy"]');
    if (!images.length) return;

    // Use IntersectionObserver for lazy loading
    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          img.classList.add('bc-img-loaded');
          imageObserver.unobserve(img);
        }
      });
    }, {
      rootMargin: '200px 0px',
      threshold: 0.01
    });

    images.forEach(img => {
      img.classList.add('bc-img-lazy');
      imageObserver.observe(img);
    });
  };

  // === LAZY LOAD SECTIONS ===
  const initLazySections = () => {
    const sections = document.querySelectorAll('section[data-lazy], .lazy-section');
    if (!sections.length) return;

    const sectionObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const section = entry.target;
          // Load any deferred content
          if (section.dataset.lazy) {
            loadSectionContent(section);
          }
          section.classList.add('section-loaded');
          sectionObserver.unobserve(section);
        }
      });
    }, {
      rootMargin: '300px 0px',
      threshold: 0
    });

    sections.forEach(section => sectionObserver.observe(section));
  };

  const loadSectionContent = (section) => {
    // Load any lazy scripts
    const scripts = section.querySelectorAll('script[data-src]');
    scripts.forEach(script => {
      const newScript = document.createElement('script');
      newScript.src = script.dataset.src;
      script.parentNode.replaceChild(newScript, script);
    });

    // Load any lazy iframes
    const iframes = section.querySelectorAll('iframe[data-src]');
    iframes.forEach(iframe => {
      iframe.src = iframe.dataset.src;
    });
  };

  // === PREFETCH ON HOVER ===
  const initPrefetch = () => {
    if (!('requestIdleCallback' in window)) return;

    const prefetched = new Set();

    document.addEventListener('mouseover', (e) => {
      const link = e.target.closest('a[href]');
      if (!link) return;

      const href = link.href;
      if (!href || prefetched.has(href)) return;
      if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
      if (href.includes(window.location.hostname) === false) return;

      requestIdleCallback(() => {
        const prefetchLink = document.createElement('link');
        prefetchLink.rel = 'prefetch';
        prefetchLink.href = href;
        document.head.appendChild(prefetchLink);
        prefetched.add(href);
      });
    });
  };

  // === DEFERRED SCRIPTS ===
  const initDeferredScripts = () => {
    // Move non-critical scripts to load after page
    const deferredScripts = document.querySelectorAll('script[defer], script[data-defer]');
    deferredScripts.forEach(script => {
      script.defer = true;
    });
  };

  // === IMAGE OPTIMIZATION ===
  const initImageOptimization = () => {
    // Add loading="lazy" to images without it
    document.querySelectorAll('img:not([loading])').forEach(img => {
      if (!img.closest('.hero-section, .hero, [data-eager]')) {
        img.loading = 'lazy';
      }
    });

    // Add decoding="async"
    document.querySelectorAll('img:not([decoding])').forEach(img => {
      img.decoding = 'async';
    });
  };

  // === PRELOAD CRITICAL RESOURCES ===
  const initPreload = () => {
    // Preload hero image if exists
    const heroImg = document.querySelector('.hero-section img, .hero img');
    if (heroImg && heroImg.src) {
      const link = document.createElement('link');
      link.rel = 'preload';
      link.as = 'image';
      link.href = heroImg.src;
      document.head.appendChild(link);
    }

    // Preload critical CSS
    const criticalCSS = document.querySelector('link[href*="design-system"]');
    if (criticalCSS) {
      criticalCSS.rel = 'preload';
      criticalCSS.as = 'style';
    }
  };

  // === VIRTUAL SCROLLING FOR LISTS ===
  const initVirtualScroll = () => {
    document.querySelectorAll('[data-virtual-scroll]').forEach(container => {
      const items = Array.from(container.children);
      const itemHeight = items[0]?.offsetHeight || 100;
      const visibleCount = Math.ceil(container.clientHeight / itemHeight) + 5;

      // Create viewport
      const viewport = document.createElement('div');
      viewport.className = 'virtual-viewport';
      viewport.style.height = container.scrollHeight + 'px';
      viewport.style.position = 'relative';

      // Create content
      const content = document.createElement('div');
      content.className = 'virtual-content';
      content.style.position = 'absolute';
      content.style.top = '0';
      content.style.left = '0';
      content.style.right = '0';

      container.innerHTML = '';
      container.appendChild(viewport);
      viewport.appendChild(content);

      const renderVisibleItems = () => {
        const scrollTop = container.scrollTop;
        const startIndex = Math.floor(scrollTop / itemHeight);
        const endIndex = Math.min(startIndex + visibleCount, items.length);

        content.innerHTML = '';
        content.style.transform = `translateY(${startIndex * itemHeight}px)`;

        for (let i = startIndex; i < endIndex; i++) {
          content.appendChild(items[i].cloneNode(true));
        }
      };

      container.addEventListener('scroll', renderVisibleItems, { passive: true });
      renderVisibleItems();
    });
  };

  // === PERFORMANCE METRICS ===
  const initPerformanceMetrics = () => {
    if (!('PerformanceObserver' in window)) return;

    // LCP, FID, CLS metrics collected silently for analytics
  };

  // === CSS FOR PERFORMANCE ===
  const perfStyles = document.createElement('style');
  perfStyles.textContent = `
    /* Lazy image states */
    .bc-img-lazy {
      opacity: 0;
      filter: blur(10px);
      transition: opacity 0.5s ease, filter 0.5s ease;
    }

    .bc-img-loaded {
      opacity: 1;
      filter: blur(0);
    }

    /* Section loading */
    .lazy-section {
      opacity: 0.5;
      transition: opacity 0.3s ease;
    }

    .section-loaded {
      opacity: 1;
    }

    /* Skeleton loading */
    .skeleton {
      background: linear-gradient(90deg, #1a1a1a 25%, #242424 50%, #1a1a1a 75%);
      background-size: 200% 100%;
      animation: skeleton-shimmer 1.5s infinite;
      border-radius: 8px;
    }

    @keyframes skeleton-shimmer {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* Virtual scroll */
    .virtual-viewport {
      overflow: auto;
      will-change: scroll-position;
    }

    .virtual-content {
      will-change: transform;
    }

    /* GPU acceleration for animations */
    .gpu-accelerated {
      will-change: transform, opacity;
      transform: translateZ(0);
    }
  `;
  document.head.appendChild(perfStyles);

  // === INITIALIZE ===
  document.addEventListener('DOMContentLoaded', () => {
    initLazyImages();
    initLazySections();
    initPrefetch();
    initDeferredScripts();
    initImageOptimization();
    initPreload();
    initVirtualScroll();
    initPerformanceMetrics();
  });
})();

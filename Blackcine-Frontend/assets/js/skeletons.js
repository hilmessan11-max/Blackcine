/* ============================================
   BLACKCINÉ SKELETON LOADING 2026
   Animated Placeholders for Dynamic Content
   ============================================ */

(function() {
  'use strict';

  // === SKELETON TEMPLATES ===
  const skeletons = {
    // Movie/TV Card skeleton
    card: `
      <div class="skeleton-card">
        <div class="skeleton skeleton-image"></div>
        <div class="skeleton skeleton-title"></div>
        <div class="skeleton skeleton-text"></div>
        <div class="skeleton skeleton-text short"></div>
      </div>
    `,

    // Article Card skeleton
    article: `
      <div class="skeleton-article">
        <div class="skeleton skeleton-image"></div>
        <div class="skeleton skeleton-badge"></div>
        <div class="skeleton skeleton-title"></div>
        <div class="skeleton skeleton-text"></div>
        <div class="skeleton skeleton-text short"></div>
      </div>
    `,

    // Detail page skeleton
    detail: `
      <div class="skeleton-detail">
        <div class="skeleton-detail-left">
          <div class="skeleton skeleton-poster"></div>
        </div>
        <div class="skeleton-detail-right">
          <div class="skeleton skeleton-title large"></div>
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text short"></div>
          <div class="skeleton-group">
            <div class="skeleton skeleton-tag"></div>
            <div class="skeleton skeleton-tag"></div>
            <div class="skeleton skeleton-tag"></div>
          </div>
        </div>
      </div>
    `,

    // List skeleton
    list: `
      <div class="skeleton-list">
        <div class="skeleton-list-item">
          <div class="skeleton skeleton-avatar"></div>
          <div class="skeleton-list-content">
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text short"></div>
          </div>
        </div>
        <div class="skeleton-list-item">
          <div class="skeleton skeleton-avatar"></div>
          <div class="skeleton-list-content">
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text short"></div>
          </div>
        </div>
        <div class="skeleton-list-item">
          <div class="skeleton skeleton-avatar"></div>
          <div class="skeleton-list-content">
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text short"></div>
          </div>
        </div>
      </div>
    `,

    // Grid skeleton
    grid: `
      <div class="skeleton-grid">
        <div class="skeleton-card"><div class="skeleton skeleton-image"></div><div class="skeleton skeleton-title"></div></div>
        <div class="skeleton-card"><div class="skeleton skeleton-image"></div><div class="skeleton skeleton-title"></div></div>
        <div class="skeleton-card"><div class="skeleton skeleton-image"></div><div class="skeleton skeleton-title"></div></div>
        <div class="skeleton-card"><div class="skeleton skeleton-image"></div><div class="skeleton skeleton-title"></div></div>
      </div>
    `,

    // Comment skeleton
    comment: `
      <div class="skeleton-comment">
        <div class="skeleton skeleton-avatar small"></div>
        <div class="skeleton-comment-content">
          <div class="skeleton skeleton-text tiny"></div>
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text short"></div>
        </div>
      </div>
    `
  };

  // === APPLY SKELETONS ===
  window.bcSkeleton = {
    // Show skeleton in a container
    show: (container, type = 'card', count = 1) => {
      if (typeof container === 'string') {
        container = document.querySelector(container);
      }
      if (!container) return;

      const skeletonHTML = skeletons[type] || skeletons.card;
      let html = '';

      for (let i = 0; i < count; i++) {
        html += `<div class="skeleton-wrapper" style="animation-delay: ${i * 100}ms">${skeletonHTML}</div>`;
      }

      container.innerHTML = html;
      container.classList.add('skeleton-active');
    },

    // Hide skeleton and show content
    hide: (container, content) => {
      if (typeof container === 'string') {
        container = document.querySelector(container);
      }
      if (!container) return;

      container.classList.remove('skeleton-active');
      container.innerHTML = content || '';
    },

    // Create skeleton element
    create: (type = 'card') => {
      const wrapper = document.createElement('div');
      wrapper.className = 'skeleton-wrapper';
      wrapper.innerHTML = skeletons[type] || skeletons.card;
      return wrapper;
    }
  };

  // === AUTO-SKELETON FOR DYNAMIC CONTAINERS ===
  const initAutoSkeleton = () => {
    document.querySelectorAll('[data-skeleton]').forEach(container => {
      const type = container.dataset.skeleton || 'card';
      const count = parseInt(container.dataset.skeletonCount) || 3;

      if (!container.innerHTML.trim()) {
        window.bcSkeleton.show(container, type, count);
      }
    });
  };

  // === CSS FOR SKELETONS ===
  const skeletonStyles = document.createElement('style');
  skeletonStyles.textContent = `
    /* Base skeleton */
    .skeleton {
      background: linear-gradient(90deg, 
        rgba(255, 255, 255, 0.05) 25%, 
        rgba(255, 255, 255, 0.1) 50%, 
        rgba(255, 255, 255, 0.05) 75%);
      background-size: 200% 100%;
      animation: skeleton-shimmer 1.5s ease-in-out infinite;
      border-radius: 8px;
    }

    @keyframes skeleton-shimmer {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* Skeleton types */
    .skeleton-image {
      width: 100%;
      aspect-ratio: 16/9;
      border-radius: 12px;
    }

    .skeleton-poster {
      width: 100%;
      aspect-ratio: 2/3;
      border-radius: 12px;
    }

    .skeleton-title {
      height: 24px;
      width: 70%;
      margin-top: 12px;
    }

    .skeleton-title.large {
      height: 32px;
      width: 80%;
    }

    .skeleton-text {
      height: 14px;
      width: 100%;
      margin-top: 8px;
    }

    .skeleton-text.short {
      width: 60%;
    }

    .skeleton-text.tiny {
      height: 12px;
      width: 40%;
    }

    .skeleton-badge {
      height: 20px;
      width: 80px;
      margin-top: 8px;
      border-radius: 20px;
    }

    .skeleton-tag {
      height: 28px;
      width: 60px;
      border-radius: 20px;
    }

    .skeleton-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    .skeleton-avatar.small {
      width: 32px;
      height: 32px;
    }

    /* Skeleton Card */
    .skeleton-card {
      background: var(--bc-bg-card);
      border: 1px solid var(--bc-border);
      border-radius: 12px;
      padding: 12px;
      overflow: hidden;
    }

    /* Skeleton Article */
    .skeleton-article {
      background: var(--bc-bg-card);
      border: 1px solid var(--bc-border);
      border-radius: 12px;
      padding: 16px;
      overflow: hidden;
    }

    /* Skeleton Detail */
    .skeleton-detail {
      display: flex;
      gap: 24px;
      padding: 20px;
    }

    .skeleton-detail-left {
      flex: 0 0 300px;
    }

    .skeleton-detail-right {
      flex: 1;
    }

    .skeleton-group {
      display: flex;
      gap: 8px;
      margin-top: 16px;
    }

    /* Skeleton List */
    .skeleton-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .skeleton-list-item {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .skeleton-list-content {
      flex: 1;
    }

    /* Skeleton Grid */
    .skeleton-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 16px;
    }

    /* Skeleton Comment */
    .skeleton-comment {
      display: flex;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid var(--bc-border);
    }

    .skeleton-comment-content {
      flex: 1;
    }

    /* Wrapper animation */
    .skeleton-wrapper {
      animation: skeleton-fade-in 0.3s ease forwards;
    }

    @keyframes skeleton-fade-in {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Stagger children */
    .skeleton-wrapper:nth-child(1) { animation-delay: 0ms; }
    .skeleton-wrapper:nth-child(2) { animation-delay: 100ms; }
    .skeleton-wrapper:nth-child(3) { animation-delay: 200ms; }
    .skeleton-wrapper:nth-child(4) { animation-delay: 300ms; }
    .skeleton-wrapper:nth-child(5) { animation-delay: 400ms; }
    .skeleton-wrapper:nth-child(6) { animation-delay: 500ms; }

    /* Responsive */
    @media (max-width: 768px) {
      .skeleton-detail {
        flex-direction: column;
      }

      .skeleton-detail-left {
        flex: none;
        max-width: 200px;
      }

      .skeleton-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 480px) {
      .skeleton-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
      .skeleton {
        animation: none;
        background: rgba(255, 255, 255, 0.05);
      }
    }
  `;
  document.head.appendChild(skeletonStyles);

  // === INITIALIZE ===
  document.addEventListener('DOMContentLoaded', initAutoSkeleton);
})();

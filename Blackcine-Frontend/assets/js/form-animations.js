/* ============================================
   BLACKCINÉ FORM ANIMATIONS 2026
   Validation, Errors, Success States
   ============================================ */

(function() {
  'use strict';

  // === FORM VALIDATION ANIMATIONS ===
  const initFormValidation = () => {
    document.querySelectorAll('form[data-validate], form.needs-validation').forEach(form => {
      const inputs = form.querySelectorAll('input, textarea, select');

      inputs.forEach(input => {
        // Focus animation
        input.addEventListener('focus', function() {
          this.closest('.form-group, .mb-3, .mb-4')?.classList.add('field-focused');
          this.classList.remove('field-error', 'field-success');
        });

        // Blur validation
        input.addEventListener('blur', function() {
          this.closest('.form-group, .mb-3, .mb-4')?.classList.remove('field-focused');
          validateField(this);
        });

        // Input event for real-time validation
        input.addEventListener('input', function() {
          if (this.classList.contains('field-error')) {
            validateField(this);
          }
        });
      });

      // Form submit
      form.addEventListener('submit', function(e) {
        let isValid = true;

        inputs.forEach(input => {
          if (!validateField(input)) {
            isValid = false;
          }
        });

        if (!isValid) {
          e.preventDefault();
          e.stopPropagation();

          // Shake the form
          form.classList.add('form-shake');
          setTimeout(() => form.classList.remove('form-shake'), 600);

          // Show error toast
          if (window.bcToast) {
            window.bcToast('Veuillez corriger les erreurs', 'error');
          }
        } else {
          form.classList.add('form-success');
        }
      });
    });
  };

  // === VALIDATE SINGLE FIELD ===
  const validateField = (field) => {
    const group = field.closest('.form-group, .mb-3, .mb-4');
    if (!group) return true;

    let isValid = true;
    let message = '';

    // Required check
    if (field.required && !field.value.trim()) {
      isValid = false;
      message = 'Ce champ est requis';
    }
    // Email check
    else if (field.type === 'email' && field.value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(field.value)) {
        isValid = false;
        message = 'Email invalide';
      }
    }
    // Password check
    else if (field.type === 'password' && field.value) {
      if (field.value.length < 8) {
        isValid = false;
        message = 'Minimum 8 caractères';
      }
    }
    // Phone check
    else if (field.type === 'tel' && field.value) {
      const phoneRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
      if (!phoneRegex.test(field.value)) {
        isValid = false;
        message = 'Numéro invalide';
      }
    }

    // Update UI
    const errorEl = group.querySelector('.field-error-message');

    if (!isValid) {
      field.classList.add('field-error');
      field.classList.remove('field-success');

      if (!errorEl) {
        const el = document.createElement('span');
        el.className = 'field-error-message';
        el.textContent = message;
        group.appendChild(el);
      } else {
        errorEl.textContent = message;
      }

      // Shake the field
      field.classList.add('field-shake');
      setTimeout(() => field.classList.remove('field-shake'), 500);
    } else if (field.value) {
      field.classList.remove('field-error');
      field.classList.add('field-success');

      if (errorEl) {
        errorEl.remove();
      }
    } else {
      field.classList.remove('field-error', 'field-success');
      if (errorEl) {
        errorEl.remove();
      }
    }

    return isValid;
  };

  // === PASSWORD STRENGTH INDICATOR ===
  const initPasswordStrength = () => {
    document.querySelectorAll('input[type="password"][data-strength]').forEach(input => {
      const container = document.createElement('div');
      container.className = 'password-strength';
      container.innerHTML = `
        <div class="strength-bar">
          <div class="strength-fill"></div>
        </div>
        <span class="strength-text"></span>
      `;
      input.parentNode.appendChild(container);

      const fill = container.querySelector('.strength-fill');
      const text = container.querySelector('.strength-text');

      input.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        fill.style.width = strength.percent + '%';
        fill.className = 'strength-fill strength-' + strength.level;
        text.textContent = strength.label;
      });
    });
  };

  const calculatePasswordStrength = (password) => {
    let score = 0;
    if (password.length >= 8) score++;
    if (password.length >= 12) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    const levels = {
      0: { percent: 0, level: 'weak', label: 'Faible' },
      1: { percent: 25, level: 'weak', label: 'Faible' },
      2: { percent: 50, level: 'medium', label: 'Moyen' },
      3: { percent: 75, level: 'good', label: 'Bon' },
      4: { percent: 90, level: 'strong', label: 'Fort' },
      5: { percent: 100, level: 'strong', label: 'Très fort' }
    };

    return levels[score] || levels[0];
  };

  // === LOADING STATE FOR BUTTONS ===
  const initButtonLoading = () => {
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        if (btn && !btn.disabled) {
          btn.disabled = true;
          btn.dataset.originalText = btn.innerHTML;
          btn.innerHTML = '<span class="btn-loading"></span> Chargement...';
          btn.classList.add('btn-loading-state');
        }
      });
    });
  };

  // === FLOATING LABELS ===
  const initFloatingLabels = () => {
    document.querySelectorAll('.form-floating, [data-floating]').forEach(container => {
      const input = container.querySelector('input, textarea');
      const label = container.querySelector('label');
      if (!input || !label) return;

      input.addEventListener('focus', () => container.classList.add('focused'));
      input.addEventListener('blur', () => container.classList.toggle('has-value', !!input.value));
      input.addEventListener('input', () => container.classList.toggle('has-value', !!input.value));

      // Check initial state
      if (input.value) {
        container.classList.add('has-value');
      }
    });
  };

  // === CSS FOR FORM ANIMATIONS ===
  const formStyles = document.createElement('style');
  formStyles.textContent = `
    /* Field focused state */
    .field-focused .form-control,
    .field-focused .form-select,
    .field-focused textarea {
      border-color: var(--bc-primary) !important;
      box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.15);
      transform: translateY(-1px);
    }

    /* Field error state */
    .field-error .form-control,
    .field-error .form-select,
    .field-error textarea {
      border-color: #ef4444 !important;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }

    .field-error-message {
      display: block;
      color: #ef4444;
      font-size: 0.8rem;
      margin-top: 6px;
      opacity: 0;
      transform: translateY(-10px);
      animation: field-error-in 0.3s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    @keyframes field-error-in {
      to { opacity: 1; transform: translateY(0); }
    }

    /* Field success state */
    .field-success .form-control,
    .field-success .form-select,
    .field-success textarea {
      border-color: var(--bc-success) !important;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .field-success::after {
      content: '✓';
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--bc-success);
      font-weight: bold;
      animation: check-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes check-in {
      0% { transform: translateY(-50%) scale(0); }
      100% { transform: translateY(-50%) scale(1); }
    }

    /* Field shake */
    .field-shake {
      animation: field-shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97);
    }

    @keyframes field-shake {
      10%, 90% { transform: translateX(-2px); }
      20%, 80% { transform: translateX(3px); }
      30%, 50%, 70% { transform: translateX(-4px); }
      40%, 60% { transform: translateX(4px); }
    }

    /* Form shake */
    .form-shake {
      animation: form-shake 0.6s cubic-bezier(0.36, 0.07, 0.19, 0.97);
    }

    @keyframes form-shake {
      10%, 90% { transform: translateX(-3px); }
      20%, 80% { transform: translateX(4px); }
      30%, 50%, 70% { transform: translateX(-5px); }
      40%, 60% { transform: translateX(5px); }
    }

    /* Form success */
    .form-success {
      animation: form-success-pulse 0.5s ease;
    }

    @keyframes form-success-pulse {
      0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
      70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
      100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Button loading */
    .btn-loading-state {
      position: relative;
      pointer-events: none;
    }

    .btn-loading {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: white;
      border-radius: 50%;
      animation: btn-spin 0.8s linear infinite;
      margin-right: 8px;
      vertical-align: middle;
    }

    @keyframes btn-spin {
      to { transform: rotate(360deg); }
    }

    /* Password strength */
    .password-strength {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 8px;
    }

    .strength-bar {
      flex: 1;
      height: 4px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 2px;
      overflow: hidden;
    }

    .strength-fill {
      height: 100%;
      transition: width 0.3s ease, background 0.3s ease;
      border-radius: 2px;
    }

    .strength-weak { background: #ef4444; }
    .strength-medium { background: #f59e0b; }
    .strength-good { background: #3b82f6; }
    .strength-strong { background: #10b981; }

    .strength-text {
      font-size: 0.75rem;
      color: var(--bc-text-muted);
      min-width: 60px;
    }

    /* Floating labels */
    .form-floating {
      position: relative;
    }

    .form-floating label {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      transition: all 0.2s ease;
      pointer-events: none;
      color: var(--bc-text-muted);
      font-size: 0.9rem;
    }

    .form-floating.focused label,
    .form-floating.has-value label {
      top: -8px;
      left: 8px;
      font-size: 0.75rem;
      color: var(--bc-primary);
      background: var(--bc-bg-dark);
      padding: 0 4px;
    }
  `;
  document.head.appendChild(formStyles);

  // === INITIALIZE ===
  document.addEventListener('DOMContentLoaded', () => {
    initFormValidation();
    initPasswordStrength();
    initButtonLoading();
    initFloatingLabels();
  });
})();

/**
 * BlackCiné — Sanitize Helper
 * Protège contre les XSS dans les insertions DOM
 */
const Sanitize = {
    /**
     * Échapper les caractères HTML dangereux
     */
    escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    },

    /**
     * Échapper les attributs
     */
    escapeAttr(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/'/g, '&#39;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    },

    /**
     * Nettoyer une URL (protocoles dangereux)
     */
    sanitizeUrl(url) {
        if (!url) return '#';
        const cleaned = url.trim();
        // Bloquer javascript: et data:
        if (/^(javascript|data|vbscript):/i.test(cleaned)) {
            return '#';
        }
        return cleaned;
    },

    /**
     * Créer un élément DOM de manière sûre
     */
    createElement(tag, attrs = {}, textContent = '') {
        const el = document.createElement(tag);
        for (const [key, value] of Object.entries(attrs)) {
            if (key === 'className') {
                el.className = value;
            } else if (key === 'textContent') {
                el.textContent = value;
            } else if (key.startsWith('on')) {
                // Ignorer les événements inline
                continue;
            } else {
                el.setAttribute(key, value);
            }
        }
        if (textContent) {
            el.textContent = textContent;
        }
        return el;
    },

    /**
     * Injecter du contenu de manière sûre (texte uniquement)
     */
    safeText(element, text) {
        if (element) {
            element.textContent = text || '';
        }
    },

    /**
     * Injecter du HTML de manière sûre (avec nettoyage)
     */
    safeHtml(element, html) {
        if (element) {
            // Autoriser uniquement les balises sûres
            const allowed = ['b', 'i', 'em', 'strong', 'a', 'span', 'br', 'p', 'div'];
            const cleaned = html.replace(/<\/?([a-z][a-z0-9]*)\b[^>]*>/gi, (match, tag) => {
                return allowed.includes(tag.toLowerCase()) ? match : '';
            });
            element.innerHTML = cleaned;
        }
    }
};

// Rendre disponible globalement
window.Sanitize = Sanitize;

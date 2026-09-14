/**
 * Footer - Dynamic data from API
 */
class Footer {
    constructor() {
        this.api = window.blackcineAPI;
        this.init();
    }

    async init() {
        try {
            const response = await this.api.getFooterData();
            const data = response.data || {};
            this.renderPartners(data.partners || []);
            this.renderStats(data.stats || {});
        } catch (error) {
            console.error('Erreur chargement footer:', error);
        }
        this.initNewsletter();
    }

    renderPartners(partners) {
        const container = document.getElementById('footerPartners');
        if (!container || partners.length === 0) return;

        container.innerHTML = partners.map(partner => `
            <a href="${partner.website_url || '#'}" target="_blank" rel="noopener">
                <img src="${partner.logo || 'assets/images/default-partner.png'}" 
                     alt="${partner.name}" 
                     height="40"
                     title="${partner.name}">
            </a>
        `).join('');
    }

    renderStats(stats) {
        const container = document.getElementById('footerStats');
        if (!container) return;

        if (stats.films || stats.series || stats.articles) {
            container.innerHTML = `
                <span class="footer-stat">${stats.films || 0} films</span>
                <span class="footer-stat">${stats.series || 0} séries</span>
                <span class="footer-stat">${stats.articles || 0} articles</span>
            `;
        }
    }

    initNewsletter() {
        document.querySelectorAll('form[role="form"], form.newsletter-form, footer form.d-flex').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = form.querySelector('input[type="email"]')?.value;
                if (email) {
                    this.handleNewsletter(email, form);
                }
            });
        });
    }

    async handleNewsletter(email, form) {
        try {
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = '...';
            btn.disabled = true;

            await this.api.post('/newsletter/subscribe', { email });

            btn.textContent = '✓';
            btn.classList.add('btn-success');
            form.querySelector('input[type="email"]').value = '';

            if (window.bcToast) window.bcToast('Inscription à la newsletter réussie !', 'success');

            setTimeout(() => {
                btn.textContent = originalText;
                btn.classList.remove('btn-success');
                btn.disabled = false;
            }, 3000);
        } catch (error) {
            console.error('Erreur newsletter:', error);
            const btn = form.querySelector('button[type="submit"]');
            btn.textContent = '✗';
            btn.classList.add('btn-error');

            if (window.bcToast) window.bcToast("Erreur lors de l'inscription. Veuillez réessayer.", 'error');

            setTimeout(() => {
                btn.textContent = "S'abonner";
                btn.classList.remove('btn-error');
                btn.disabled = false;
            }, 3000);
        }
    }
}

// Initialize footer on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new Footer();
});

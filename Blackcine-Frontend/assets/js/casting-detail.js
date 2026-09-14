/**
 * Casting Detail page - API integration
 */
class CastingDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.casting = null;
        this.castingId = this.getCastingIdFromUrl();
        this.init();
    }

    getCastingIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.castingId) {
            this.showError('Aucun casting sélectionné');
            return;
        }

        try {
            await this.loadCasting();
        } catch (error) {
            console.error('Erreur chargement casting:', error);
            this.showError('Erreur lors du chargement du casting');
        }
    }

    async loadCasting() {
        const response = await this.api.get(`/castings/${this.castingId}`);
        this.casting = response.data || response;
        this.displayCasting();
    }

    displayCasting() {
        const casting = this.casting;
        if (!casting) return;

        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('castingDetail').style.display = 'block';

        document.title = `BlackCiné - ${casting.title}`;

        document.getElementById('castingTitle').textContent = casting.title;
        document.getElementById('castingType').innerHTML = `<i class="fas fa-film"></i> ${Sanitize.escapeHtml(casting.type || 'N/A')}`;
        document.getElementById('castingLocation').innerHTML = `<i class="fas fa-map-marker-alt"></i> ${Sanitize.escapeHtml(casting.location || 'N/A')}`;

        const deadline = casting.deadline ? new Date(casting.deadline).toLocaleDateString('fr-FR') : 'N/A';
        document.getElementById('castingDeadline').innerHTML = `<i class="fas fa-calendar-alt"></i> ${deadline}`;

        document.getElementById('castingTypeDetail').textContent = casting.type || 'N/A';
        document.getElementById('castingLocationDetail').textContent = casting.location || 'N/A';
        document.getElementById('castingDeadlineDetail').textContent = deadline;

        document.getElementById('castingDescription').textContent = casting.description || 'Description non disponible';

        const roles = casting.roles || [];
        if (roles.length > 0) {
            document.getElementById('rolesContainer').innerHTML = roles.map(role => `
                <div class="role-item">
                    <h5>${Sanitize.escapeHtml(role.title || role.name || 'Rôle')}</h5>
                    <p class="text-muted mb-1">${Sanitize.escapeHtml(role.description || '')}</p>
                    ${role.gender ? `<small class="text-secondary"><i class="fas fa-user me-1"></i>${Sanitize.escapeHtml(role.gender)}</small>` : ''}
                    ${role.age_range ? `<small class="text-secondary ms-3"><i class="fas fa-birthday-cake me-1"></i>${Sanitize.escapeHtml(role.age_range)}</small>` : ''}
                </div>
            `).join('');
        } else {
            document.getElementById('rolesContainer').innerHTML = '<p class="text-muted">Aucun rôle spécifié</p>';
        }

        const requirements = casting.requirements || [];
        if (requirements.length > 0) {
            document.getElementById('requirementsContainer').innerHTML = requirements.map(req =>
                `<li>${Sanitize.escapeHtml(typeof req === 'string' ? req : req.description || JSON.stringify(req))}</li>`
            ).join('');
        } else {
            document.getElementById('requirementsContainer').innerHTML = '<li>Aucun prérequis spécifique</li>';
        }

        const applyBtn = document.getElementById('btnApply');
        if (applyBtn) {
            applyBtn.addEventListener('click', () => {
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3 m-3 rounded shadow-lg text-white';
                toast.style.cssText = 'background-color:#E50914;z-index:9999;transition:opacity 0.5s;';
                toast.textContent = 'Fonctionnalité de candidature bientôt disponible ! Veuillez créer un compte pour postuler.';
                document.body.appendChild(toast);
                setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 500); }, 2500);
            });
        }
    }

    showError(message) {
        document.getElementById('loadingState').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> ${message}
            </div>
            <a href="communaute.html" class="btn btn-outline-dark mt-3">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CastingDetailPage();
});

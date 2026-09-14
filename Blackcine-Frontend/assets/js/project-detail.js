/**
 * Project Detail page - API integration
 */
class ProjectDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.project = null;
        this.projectId = this.getProjectIdFromUrl();
        this.init();
    }

    getProjectIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.projectId) {
            this.showError('Aucun projet sélectionné');
            return;
        }

        try {
            await this.loadProject();
        } catch (error) {
            console.error('Erreur chargement projet:', error);
            this.showError('Erreur lors du chargement du projet');
        }
    }

    async loadProject() {
        const response = await this.api.get(`/projects/${this.projectId}`);
        this.project = response.data || response;
        this.displayProject();
    }

    displayProject() {
        const project = this.project;
        if (!project) return;

        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('projectDetail').style.display = 'block';

        document.title = `BlackCiné - ${project.title}`;

        const thumbnail = project.thumbnail || project.poster || 'assets/images/ceb1.jpg';
        document.getElementById('projectThumbnail').src = thumbnail;
        document.getElementById('projectThumbnail').alt = project.title;
        document.getElementById('projectTitle').textContent = project.title;

        const statusColors = {
            'en cours': '#28a745',
            'en_cours': '#28a745',
            'recherche': '#ffc107',
            'terminé': '#6c757d',
            'termine': '#6c757d'
        };
        const statusText = project.status || 'N/A';
        const statusColor = statusColors[statusText.toLowerCase()] || '#d01313';
        document.getElementById('projectStatus').innerHTML = `<i class="fas fa-circle" style="color: ${statusColor}"></i> ${Sanitize.escapeHtml(statusText)}`;
        document.getElementById('projectStatusLabel').innerHTML = `<i class="fas fa-circle" style="color: ${statusColor}"></i> <span>${Sanitize.escapeHtml(statusText)}</span>`;

        const city = project.city || '';
        const country = project.country || '';
        const location = [city, country].filter(Boolean).join(', ') || 'N/A';
        document.getElementById('projectCity').innerHTML = `<i class="fas fa-map-marker-alt"></i> ${Sanitize.escapeHtml(city || 'N/A')}`;
        document.getElementById('projectCountry').innerHTML = `<i class="fas fa-globe-africa"></i> ${Sanitize.escapeHtml(country || 'N/A')}`;
        document.getElementById('projectTeamSize').innerHTML = `<i class="fas fa-users"></i> ${Sanitize.escapeHtml(String(project.team_size || 'N/A'))} membres`;

        document.getElementById('projectDescription').textContent = project.description || 'Description non disponible';
        document.getElementById('projectFullDescription').textContent = project.description || project.short_description || 'Description complète non disponible';

        document.getElementById('projectStatusDetail').textContent = statusText;
        document.getElementById('projectLocationDetail').textContent = location;
        document.getElementById('projectTeamSizeDetail').textContent = `${project.team_size || 'N/A'} membres`;

        const budget = project.budget ? `${project.budget.toLocaleString('fr-FR')} €` : 'Non spécifié';
        document.getElementById('projectBudgetDetail').textContent = budget;

        const favBtn = document.getElementById('btnFavorite');
        if (favBtn) {
            favBtn.dataset.favoriteType = 'project';
            favBtn.dataset.favoriteId = project.id;
            favBtn.dataset.favoriteName = project.title;
            favBtn.dataset.favoritePoster = thumbnail;
        }

        const shareBtn = document.querySelector('[data-action="share"]');
        if (shareBtn) {
            shareBtn.dataset.shareTitle = project.title;
            shareBtn.dataset.shareUrl = window.location.href;
        }

        const applyHandler = () => {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3 m-3 rounded shadow-lg text-white';
            toast.style.cssText = 'background-color:#E50914;z-index:9999;transition:opacity 0.5s;';
            toast.textContent = 'Fonctionnalité de candidature bientôt disponible ! Veuillez créer un compte pour rejoindre ce projet.';
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 500); }, 2500);
        };

        const applyBtn = document.getElementById('btnApply');
        if (applyBtn) applyBtn.addEventListener('click', applyHandler);

        const applySidebarBtn = document.getElementById('btnApplySidebar');
        if (applySidebarBtn) applySidebarBtn.addEventListener('click', applyHandler);
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
    new ProjectDetailPage();
});

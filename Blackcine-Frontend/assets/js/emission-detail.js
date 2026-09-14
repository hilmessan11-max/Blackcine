/**
 * Emission Detail page - API integration
 */
class EmissionDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.emission = null;
        this.emissionId = this.getEmissionIdFromUrl();
        this.init();
    }

    getEmissionIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.emissionId) {
            this.showError(t('no_emission_selected') || 'Aucune émission sélectionnée');
            return;
        }

        try {
            await this.loadEmission();
        } catch (error) {
            console.error('Error loading emission:', error);
            this.showError(t('error_loading_emission') || 'Erreur lors du chargement de l''émission');
        }
    }

    async loadEmission() {
        const response = await this.api.get(`/emissions/${this.emissionId}`);
        this.emission = response.data || response;
        this.displayEmission();
    }

    displayEmission() {
        const emission = this.emission;
        if (!emission) return;

        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('emissionDetail').style.display = 'block';

        document.title = `BlackCiné - ${emission.title}`;

        const thumbnail = emission.thumbnail || 'assets/images/ceb1.jpg';
        document.getElementById('emissionThumbnail').src = thumbnail;
        document.getElementById('emissionThumbnail').alt = emission.title;
        document.getElementById('videoThumbnail').src = thumbnail;

        document.getElementById('emissionTitle').textContent = emission.title;
        document.getElementById('emissionPresenter').innerHTML = `<i class="fas fa-user"></i> ${Sanitize.escapeHtml(emission.presenter || 'N/A')}`;
        document.getElementById('emissionDuration').innerHTML = `<i class="fas fa-clock"></i> ${this.formatDuration(emission.duration)}`;
        document.getElementById('emissionViews').innerHTML = `<i class="fas fa-eye"></i> ${this.formatViews(emission.views_count || emission.views)} ${t('home_views') || 'vues'}`;

        if (emission.is_live) {
            document.getElementById('emissionLiveBadge').style.display = 'inline-flex';
        }

        const categoryLabels = {
            'interview': t('category_interview') || 'Interview',
            'talkshow': t('category_talkshow') || 'Talk Show',
            'podcast': t('category_podcast') || 'Podcast',
            'makingof': t('category_makingof') || 'Making-of',
            'chronique': t('category_chronique') || 'Chronique'
        };
        document.getElementById('emissionCategory').innerHTML = `<i class="fas fa-tag"></i> ${Sanitize.escapeHtml(categoryLabels[emission.category] || emission.category || t('category_emission') || 'Émission')}`;
        document.getElementById('emissionDescription').textContent = emission.description || t('no_description') || 'Description non disponible';

        document.getElementById('btnPlay').href = '#videoPlayer';

        this.setupShareButtons(emission);
        this.loadUpcomingEmissions();
    }

    setupShareButtons(emission) {
        const shareUrl = window.location.href;
        const shareTitle = emission.title;

        document.getElementById('shareFacebook').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`;
        document.getElementById('shareTwitter').href = `https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent(shareTitle)}`;
        document.getElementById('shareWhatsapp').href = `https://wa.me/?text=${encodeURIComponent(shareTitle + ' ' + shareUrl)}`;
        
        document.getElementById('shareCopy').addEventListener('click', () => {
            navigator.clipboard.writeText(shareUrl).then(() => {
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3 m-3 rounded shadow-lg text-white';
                toast.style.cssText = 'background-color:#E50914;z-index:9999;transition:opacity 0.5s;';
                toast.textContent = t('link_copied') || 'Lien copié dans le presse-papier!';
                document.body.appendChild(toast);
                setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 500); }, 2500);
            });
        });
    }

    async loadUpcomingEmissions() {
        try {
            const response = await this.api.getUpcomingEmissions();
            const upcoming = response.data || [];
            if (upcoming.length > 0) {
                this.displayUpcomingEmissions(upcoming.slice(0, 3));
            }
        } catch (error) {
            console.error('Erreur chargement prochaines émissions:', error);
        }
    }

    displayUpcomingEmissions(emissions) {
        document.getElementById('upcomingSection').style.display = 'block';
        document.getElementById('upcomingContainer').innerHTML = emissions.map(emission => `
            <div class="col-md-4">
                <div class="upcoming-card" onclick="window.location.href='emission-detail.html?id=${emission.id}'">
                    <div class="upcoming-thumb">
                        <img src="${emission.thumbnail || 'assets/images/ceb1.jpg'}" alt="${Sanitize.escapeHtml(emission.title)}">
                        <div class="play-overlay">
                            <i class="fas fa-play-circle"></i>
                        </div>
                    </div>
                    <div class="p-3">
                        <h6 class="mb-1">${Sanitize.escapeHtml(emission.title)}</h6>
                        <small class="text-muted">${Sanitize.escapeHtml(emission.presenter || 'N/A')}</small>
                    </div>
                </div>
            </div>
        `).join('');
    }

    formatDuration(minutes) {
        if (!minutes) return '0:00';
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return hours > 0 ? `${hours}h${mins.toString().padStart(2, '0')}` : `${mins}min`;
    }

    formatViews(views) {
        if (!views) return '0';
        if (views >= 1000000) return (views / 1000000).toFixed(1) + 'M';
        if (views >= 1000) return (views / 1000).toFixed(1) + 'K';
        return views.toString();
    }

    showError(message) {
        document.getElementById('loadingState').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> ${message}
            </div>
            <a href="emissions.html" class="btn btn-outline-dark mt-3">
                <i class="fas fa-arrow-left"></i> Retour aux émissions
            </a>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new EmissionDetailPage();
});
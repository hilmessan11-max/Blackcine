/**
 * Festivals page - API integration
 */
class FestivalsPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.festivals = [];
        this.isLoading = false;
        this.init();
    }

    async init() {
        this.cacheElements();
        await this.loadFestivals();
    }

    cacheElements() {
        this.festivalsGrid = document.getElementById('festivalsGrid');
    }

    async loadFestivals() {
        if (this.isLoading) return;
        this.isLoading = true;
        this.showLoading();

        try {
            const response = await this.api.getFestivals();
            this.festivals = response.data || [];
            this.displayFestivals();
        } catch (error) {
            console.error('Erreur chargement festivals:', error);
            this.showError('Erreur lors du chargement des festivals.');
        } finally {
            this.isLoading = false;
        }
    }

    displayFestivals() {
        if (!this.festivalsGrid) return;

        if (this.festivals.length === 0) {
            this.festivalsGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun festival trouvé</h5>
                    <p class="text-muted">Revenez bientôt pour découvrir les prochains événements.</p>
                </div>
            `;
            return;
        }

        this.festivalsGrid.innerHTML = this.festivals.map(festival => {
            const startDate = festival.start_date ? new Date(festival.start_date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' }) : '';
            const endDate = festival.end_date ? new Date(festival.end_date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : '';
            const dateRange = startDate && endDate ? `${startDate} — ${endDate}` : startDate || endDate || '';
            const location = festival.location || festival.city || '';
            const image = festival.image || festival.poster || 'assets/images/ceb1.jpg';
            const filmsCount = festival.films_count || festival.selections_count || 0;

            return `
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="festival-detail.html?id=${festival.id}" class="text-decoration-none">
                        <div class="card h-100 bg-white/5 border border-white/10 rounded-xl hover:border-primary/30 transition-all duration-300" style="overflow: hidden;">
                            <div style="position: relative;">
                                <img src="${image}" class="card-img-top" alt="${festival.name}" style="height: 200px; object-fit: cover;" loading="lazy">
                                ${festival.is_active ? '<span class="absolute top-3 right-3 badge badge-primary text-white text-xs font-semibold px-3 py-1 rounded-full">EN COURS</span>' : ''}
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-white font-semibold" style="font-size: 1rem;">${festival.name}</h5>
                                <p class="card-text text-white/60 text-sm mb-2">${festival.description ? festival.description.substring(0, 80) + '...' : ''}</p>
                                <div class="d-flex flex-column gap-1">
                                    ${dateRange ? `<small class="text-white/60 text-sm"><i class="fas fa-calendar-alt me-1"></i>${dateRange}</small>` : ''}
                                    ${location ? `<small class="text-white/60 text-sm"><i class="fas fa-map-marker-alt me-1"></i>${location}</small>` : ''}
                                    ${filmsCount ? `<small class="text-white/60 text-sm"><i class="fas fa-film me-1"></i>${filmsCount} films</small>` : ''}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            `;
        }).join('');
    }

    showLoading() {
        if (!this.festivalsGrid) return;
        this.festivalsGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des festivals...</p>
            </div>
        `;
    }

    showError(message) {
        if (!this.festivalsGrid) return;
        this.festivalsGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="bg-white/5 border border-white/10 rounded-xl p-6 max-w-md mx-auto">
                    <i class="fas fa-exclamation-triangle text-3xl text-primary mb-3"></i>
                    <p class="text-white mb-4">${message}</p>
                    <button class="btn btn-ghost btn-sm rounded-full text-white/60" onclick="window.location.reload()">
                        <i class="fas fa-redo"></i> Réessayer
                    </button>
                </div>
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new FestivalsPage();
});

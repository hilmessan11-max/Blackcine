/**
 * Festival Detail page - API integration
 */
class FestivalDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.festival = null;
        this.festivalId = this.getFestivalIdFromUrl();
        this.init();
    }

    getFestivalIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.festivalId) {
            this.showError('Aucun festival sélectionné.');
            return;
        }

        try {
            await this.loadFestival();
        } catch (error) {
            console.error('Erreur chargement festival:', error);
            this.showError('Erreur lors du chargement du festival.');
        }
    }

    async loadFestival() {
        const response = await this.api.get(`/festivals/${this.festivalId}`);
        this.festival = response.data || response;
        this.displayFestival();
    }

    displayFestival() {
        const festival = this.festival;
        if (!festival) return;

        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('festivalDetail').style.display = 'block';

        document.title = `BlackCiné - ${festival.name}`;

        document.getElementById('festivalTitle').textContent = festival.name;

        const startDate = festival.start_date ? new Date(festival.start_date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }) : '';
        const endDate = festival.end_date ? new Date(festival.end_date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }) : '';
        const dateRange = startDate && endDate ? `${startDate} — ${endDate}` : startDate || endDate || 'Dates à confirmer';
        document.getElementById('festivalDates').innerHTML = `<i class="fas fa-calendar-alt"></i> ${dateRange}`;

        document.getElementById('festivalLocation').innerHTML = `<i class="fas fa-map-marker-alt"></i> ${Sanitize.escapeHtml(festival.location || festival.city || 'Lieu à confirmer')}`;

        document.getElementById('festivalDescription').textContent = festival.description || 'Aucune description disponible.';

        // Stats
        const statsContainer = document.getElementById('festivalStats');
        const filmsCount = festival.films_count || festival.selections_count || 0;
        const guestsCount = festival.guests_count || 0;
        const editionsCount = festival.editions_count || 1;

        statsContainer.innerHTML = `
            <div class="festival-stat">
                <div class="stat-number">${filmsCount}</div>
                <div class="stat-label">Films en sélection</div>
            </div>
            <div class="festival-stat">
                <div class="stat-number">${guestsCount}</div>
                <div class="stat-label">Invités d'honneur</div>
            </div>
            <div class="festival-stat">
                <div class="stat-number">${editionsCount}</div>
                <div class="stat-label">${editionsCount > 1 ? 'Éditions' : 'Édition'}</div>
            </div>
        `;

        // Lineup / Selections
        const lineup = festival.selections || festival.films || festival.lineup || [];
        const lineupContainer = document.getElementById('lineupContainer');

        if (lineup.length > 0) {
            lineupContainer.innerHTML = lineup.map(item => {
                const poster = item.poster || item.image || 'assets/images/ceb1.jpg';
                const title = item.name || item.title || 'Film sans titre';
                const year = item.release_date ? new Date(item.release_date).getFullYear() : '';
                const category = item.category || item.genre || '';
                return `
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="lineup-card" onclick="window.location.href='film-detail.html?id=${item.id}'">
                            <img src="${poster}" alt="${Sanitize.escapeHtml(title)}" loading="lazy">
                            <div class="lineup-info">
                                <h6>${Sanitize.escapeHtml(title)}</h6>
                                <div class="lineup-meta">
                                    ${category ? `<span><i class="fas fa-tag"></i> ${Sanitize.escapeHtml(category)}</span>` : ''}
                                    ${year ? `<span><i class="fas fa-calendar"></i> ${year}</span>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    showError(message) {
        document.getElementById('loadingState').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> ${message}
            </div>
            <a href="festivals.html" class="btn btn-outline-dark mt-3">
                <i class="fas fa-arrow-left"></i> Retour aux festivals
            </a>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new FestivalDetailPage();
});

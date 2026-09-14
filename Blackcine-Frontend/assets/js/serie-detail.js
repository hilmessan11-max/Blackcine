/**
 * Série Detail page - API integration
 */
class SerieDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.serie = null;
        this.serieId = this.getSerieIdFromUrl();
        this.init();
    }

    getSerieIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.serieId) {
            this.showError(t('no_serie_selected'));
            return;
        }

        try {
            await this.loadSerie();
        } catch (error) {
            console.error('Erreur chargement série:', error);
            this.showError(t('error_loading_serie'));
        }
    }

    async loadSerie() {
        const response = await this.api.get(`/series/${this.serieId}`);
        this.serie = response.data || response;
        this.displaySerie();
    }

    displaySerie() {
        const serie = this.serie;
        if (!serie) return;

        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('serieDetail').style.display = 'block';

        document.title = `BlackCiné - ${serie.name}`;

        // Poster
        const poster = serie.poster || 'assets/images/ceb1.jpg';
        document.getElementById('seriePoster').src = poster;
        document.getElementById('seriePoster').alt = serie.name;
        document.getElementById('serieTitle').textContent = serie.name;

        // Meta
        const year = serie.release_date ? new Date(serie.release_date).getFullYear() : 'N/A';
        const seasonsCount = serie.seasons ? serie.seasons.length : (serie.seasons_count || 0);
        document.getElementById('serieYear').innerHTML = `<i class="fas fa-calendar"></i> ${year}`;
        document.getElementById('serieSeasons').innerHTML = `<i class="fas fa-layer-group"></i> ${seasonsCount} Saison${seasonsCount > 1 ? 's' : ''}`;
        document.getElementById('serieCountry').innerHTML = `<i class="fas fa-globe-africa"></i> ${Sanitize.escapeHtml(serie.origin_country || 'N/A')}`;
        document.getElementById('serieLanguage').innerHTML = `<i class="fas fa-language"></i> ${Sanitize.escapeHtml(serie.original_language || 'N/A')}`;

        // Genres
        const genres = serie.genres || [];
        document.getElementById('serieGenres').innerHTML = genres.map(g =>
            `<span class="genre-tag">${Sanitize.escapeHtml(typeof g === 'string' ? g : g.name)}</span>`
        ).join('');

        // Rating
        const rating = serie.average_rating || 'N/A';
        document.getElementById('serieRating').innerHTML = `
            <i class="fas fa-star star"></i>
            <span>${rating}</span>
            ${serie.total_reviews ? `<small>/ ${serie.total_reviews} ${t('reviews')}</small>` : ''}
        `;

        // Synopsis
        document.getElementById('serieSynopsis').textContent = serie.synopsis || t('synopsis_unavailable');

        // Trailer button
        const trailers = serie.trailers || [];
        if (trailers.length > 0) {
            document.getElementById('btnTrailer').href = trailers[0].source_url || '#';
            document.getElementById('btnTrailer').target = '_blank';
        }

        // Favorite button
        const favBtn = document.getElementById('btnFavorite');
        if (favBtn) {
            favBtn.dataset.favoriteType = 'serie';
            favBtn.dataset.favoriteId = serie.id;
            favBtn.dataset.favoriteName = serie.name;
            favBtn.dataset.favoritePoster = poster;
        }

        // Share button
        const shareBtn = document.querySelector('[data-action="share"]');
        if (shareBtn) {
            shareBtn.dataset.shareTitle = serie.name;
            shareBtn.dataset.shareUrl = window.location.href;
        }

        // Seasons section
        const seasons = serie.seasons || [];
        if (seasons.length > 0) {
            document.getElementById('seasonsSection').style.display = 'block';
            document.getElementById('seasonsContainer').innerHTML = seasons.map(season => `
                <div class="season-card">
                    <h5><i class="fas fa-layer-group"></i> ${Sanitize.escapeHtml(season.name || `Saison ${season.season_number}`)}</h5>
                    <p class="text-muted mb-3">${season.episodes_count || 0} épisode${season.episodes_count > 1 ? 's' : ''}</p>
                    ${season.episodes && season.episodes.length > 0 ? `
                        <div class="episodes-list">
                            ${season.episodes.map((ep, i) => `
                                <div class="episode-item">
                                    <span class="episode-number">${ep.episode_number || i + 1}</span>
                                    <div>
                                        <div class="episode-title">${Sanitize.escapeHtml(ep.title || `Épisode ${i + 1}`)}</div>
                                        <div class="episode-meta">${ep.duration ? ep.duration + ' min' : ''}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    ` : `<p class="text-muted">${t('no_episodes')}</p>`}
                </div>
            `).join('');
        }

        // Cast section
        const credits = serie.credits || [];
        if (credits.length > 0) {
            document.getElementById('castSection').style.display = 'block';
            document.getElementById('castContainer').innerHTML = credits.map(credit => `
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="cast-card">
                        <img src="assets/images/default-avatar.png" alt="${Sanitize.escapeHtml(credit.person_name)}">
                        <div class="cast-name">${Sanitize.escapeHtml(credit.person_name)}</div>
                        <div class="cast-role">${Sanitize.escapeHtml(credit.role || '')}</div>
                    </div>
                </div>
            `).join('');
        }
    }

    showError(message) {
        document.getElementById('loadingState').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> ${message}
            </div>
            <a href="series.html" class="btn btn-outline-dark mt-3">
                <i class="fas fa-arrow-left"></i> ${t('back_to_series')}
            </a>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new SerieDetailPage();
});

/**
 * Film Detail page - API integration
 */
class FilmDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.film = null;
        this.filmId = this.getFilmIdFromUrl();
        this.init();
    }

    getFilmIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.filmId) {
            this.showError(t('no_film_selected'));
            return;
        }

        try {
            await this.loadFilm();
        } catch (error) {
            console.error('Erreur chargement film:', error);
            this.showError(t('error_loading_film'));
        }
    }

    async loadFilm() {
        const response = await this.api.get(`/films/${this.filmId}`);
        this.film = response.data || response;
        this.displayFilm();
    }

    displayFilm() {
        const film = this.film;
        if (!film) return;

        // Hide loading, show content
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('filmDetail').style.display = 'block';

        // Update page title
        document.title = `BlackCiné - ${film.name}`;

        // Hero section
        const poster = film.poster || 'assets/images/ceb1.jpg';
        document.getElementById('filmPoster').src = poster;
        document.getElementById('filmPoster').alt = film.name;
        document.getElementById('filmTitle').textContent = film.name;

        // Meta info
        const year = film.release_date ? new Date(film.release_date).getFullYear() : 'N/A';
        document.getElementById('filmYear').innerHTML = `<i class="fas fa-calendar"></i> ${year}`;
        document.getElementById('filmDuration').innerHTML = `<i class="fas fa-clock"></i> ${film.runtime_minutes || 'N/A'} min`;
        document.getElementById('filmCountry').innerHTML = `<i class="fas fa-globe-africa"></i> ${Sanitize.escapeHtml(film.origin_country || 'N/A')}`;
        document.getElementById('filmLanguage').innerHTML = `<i class="fas fa-language"></i> ${Sanitize.escapeHtml(film.original_language || 'N/A')}`;

        // Genres
        const genres = film.genres || [];
        document.getElementById('filmGenres').innerHTML = genres.map(g =>
            `<span class="genre-tag">${Sanitize.escapeHtml(typeof g === 'string' ? g : g.name)}</span>`
        ).join('');

        // Rating
        const rating = film.average_rating || 'N/A';
        document.getElementById('filmRating').innerHTML = `
            <i class="fas fa-star star"></i>
            <span>${rating}</span>
            ${film.total_reviews ? `<small>/ ${film.total_reviews} ${t('reviews')}</small>` : ''}
        `;

        // Synopsis
        document.getElementById('filmSynopsis').textContent = film.synopsis || t('synopsis_unavailable');

        // Trailer button
        const trailers = film.trailers || [];
        if (trailers.length > 0) {
            const firstTrailer = trailers[0];
            document.getElementById('btnTrailer').href = firstTrailer.source_url || '#';
            document.getElementById('btnTrailer').target = '_blank';
        }

        // Favorite button
        const favBtn = document.getElementById('btnFavorite');
        if (favBtn) {
            favBtn.dataset.favoriteType = 'film';
            favBtn.dataset.favoriteId = film.id;
            favBtn.dataset.favoriteName = film.name;
            favBtn.dataset.favoritePoster = poster;
        }

        // Share button
        const shareBtn = document.querySelector('[data-action="share"]');
        if (shareBtn) {
            shareBtn.dataset.shareTitle = film.name;
            shareBtn.dataset.shareUrl = window.location.href;
        }

        // Trailers section
        if (trailers.length > 0) {
            document.getElementById('trailersSection').style.display = 'block';
            document.getElementById('trailersContainer').innerHTML = trailers.map(trailer => `
                <div class="col-md-6">
                    <div class="trailer-card-detail" onclick="window.open('${Sanitize.escapeHtml(trailer.source_url || '#')}', '_blank')">
                        <div class="trailer-thumb">
                            <img src="${poster}" alt="${Sanitize.escapeHtml(trailer.title || 'Trailer')}">
                            <div class="play-overlay">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <div class="p-3">
                            <h6 class="mb-1">${Sanitize.escapeHtml(trailer.title || t('detail_trailer'))}</h6>
                            <small class="text-muted">${Sanitize.escapeHtml(trailer.source_type || 'YouTube')}</small>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Cast section
        const credits = film.credits || [];
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
            <a href="films.html" class="btn btn-outline-dark mt-3">
                <i class="fas fa-arrow-left"></i> ${t('back_to_films')}
            </a>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new FilmDetailPage();
});

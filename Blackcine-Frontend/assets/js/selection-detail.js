/**
 * Selection Detail page - API integration
 */
class SelectionDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.selection = null;
        this.selectionId = this.getSelectionIdFromUrl();
        this.init();
    }

    getSelectionIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.selectionId) {
            this.showError(t('no_film_selected'));
            return;
        }

        try {
            await this.loadSelection();
        } catch (error) {
            console.error('Erreur chargement sélection:', error);
            this.showError(t('error_loading_film'));
        }
    }

    async loadSelection() {
        const response = await this.api.get(`/selections/${this.selectionId}`);
        this.selection = response.data || response;
        this.displaySelection();
    }

    displaySelection() {
        const selection = this.selection;
        if (!selection) return;

        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('selectionDetail').style.display = 'block';

        document.title = `BlackCiné - ${selection.title}`;

        const thumbnail = selection.thumbnail || 'assets/images/ceb1.jpg';
        document.getElementById('selectionThumbnail').src = thumbnail;
        document.getElementById('selectionThumbnail').alt = selection.title;
        document.getElementById('selectionTitle').textContent = selection.title;
        document.getElementById('selectionDescription').textContent = selection.description || '';

        const films = selection.films || [];
        const filmsContainer = document.getElementById('filmsContainer');
        if (films.length > 0) {
            filmsContainer.innerHTML = films.map(film => {
                const poster = film.poster || 'assets/images/ceb1.jpg';
                const year = film.release_date ? new Date(film.release_date).getFullYear() : 'N/A';
                const rating = film.average_rating || 'N/A';

                return `
                    <div class="col-film">
                        <a href="film-detail.html?id=${film.id}" class="movie-card position-relative bg-white/5 border border-white/10 rounded-xl hover:border-primary/30 transition-all duration-300 overflow-hidden" style="text-decoration:none; color:inherit; display:block;">
                            <span class="badge-year">${year}</span>
                            <img src="${poster}" alt="${film.name}" loading="lazy">
                            <div class="movie-info p-2">
                                <h6 class="movie-title text-white font-semibold">${film.name}</h6>
                                <div class="rating text-white/60 text-sm">
                                    <i class="fas fa-star text-warning"></i> <span>${rating}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                `;
            }).join('');
        } else {
            filmsContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-film fa-3x text-white/20 mb-3"></i>
                    <p class="text-white/60">Aucun film dans cette sélection.</p>
                </div>
            `;
        }
    }

    showError(message) {
        document.getElementById('loadingState').innerHTML = `
            <div class="bg-white/5 border border-white/10 rounded-xl p-6 max-w-md mx-auto text-center">
                <i class="fas fa-exclamation-triangle text-3xl text-primary mb-3"></i>
                <p class="text-white mb-4">${message}</p>
                <a href="selections.html" class="btn btn-ghost btn-sm rounded-full text-white/60">
                    <i class="fas fa-arrow-left"></i> ${t('back_to_films')}
                </a>
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new SelectionDetailPage();
});

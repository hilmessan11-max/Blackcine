/**
 * Selections page - API integration
 */
class SelectionsPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.selections = [];
        this.init();
    }

    async init() {
        this.cacheElements();
        await this.loadSelections();
    }

    cacheElements() {
        this.selectionsGrid = document.getElementById('selectionsGrid');
    }

    async loadSelections() {
        this.showLoading();

        try {
            const response = await this.api.get('/selections');
            this.selections = response.data || response || [];
            this.displaySelections();
        } catch (error) {
            console.error('Erreur chargement sélections:', error);
            this.showError(t('error_loading_films'));
        }
    }

    displaySelections() {
        if (!this.selectionsGrid) return;

        if (this.selections.length === 0) {
            this.selectionsGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">${t('no_films_found')}</h5>
                </div>
            `;
            return;
        }

        this.selectionsGrid.innerHTML = this.selections.map(selection => {
            const thumbnail = selection.thumbnail || 'assets/images/ceb1.jpg';
            const title = selection.title || 'Sélection';
            const description = selection.description || '';

            return `
                <div class="col-md-4 col-sm-6">
                    <a href="selection-detail.html?id=${selection.id}" class="movie-card position-relative" style="text-decoration:none; color:inherit;">
                        <img src="${thumbnail}" alt="${title}" loading="lazy" style="width:100%; height:220px; object-fit:cover;">
                        <div class="movie-info p-3">
                            <h6 class="movie-title text-dark">${title}</h6>
                            <p class="text-muted small mb-0" style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">${description}</p>
                        </div>
                    </a>
                </div>
            `;
        }).join('');
    }

    showLoading() {
        if (!this.selectionsGrid) return;
        this.selectionsGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">${t('loading')}</span>
                </div>
                <p class="mt-2 text-muted">${t('loading_films')}</p>
            </div>
        `;
    }

    showError(message) {
        if (!this.selectionsGrid) return;
        this.selectionsGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> ${message}
                </div>
                <button class="btn btn-outline-dark mt-3" data-action="retry">
                    <i class="fas fa-redo"></i> ${t('retry')}
                </button>
            </div>
        `;
        this.selectionsGrid.querySelector('[data-action="retry"]')?.addEventListener('click', () => this.loadSelections());
    }
}

let selectionsPage;
document.addEventListener('DOMContentLoaded', () => {
    selectionsPage = new SelectionsPage();
});

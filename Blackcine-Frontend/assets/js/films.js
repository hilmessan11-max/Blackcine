/**
 * Films page - API integration with filters & pagination
 */
class FilmsPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.films = [];
        this.currentPage = 1;
        this.lastPage = 1;
        this.total = 0;
        this.perPage = 20;
        this.isLoading = false;

        this.filters = {
            search: '',
            genre: '',
            country: '',
            year: '',
            sort: 'recent'
        };

        this.init();
    }

    async init() {
        this.cacheElements();
        this.bindEvents();

        const params = new URLSearchParams(window.location.search);
        const search = params.get('search');
        if (search) {
            this.filters.search = search;
            if (this.searchInput) this.searchInput.value = search;
        }

        await Promise.all([this.loadGenres(), this.loadCountries()]);
        await this.loadFilms();
    }

    cacheElements() {
        this.moviesGrid = document.getElementById('moviesGrid');
        this.resultsCount = document.getElementById('resultsCount');
        this.searchInput = document.getElementById('searchInput');
        this.countryFilter = document.getElementById('countryFilter');
        this.countryFilterMobile = document.getElementById('countryFilterMobile');
        this.yearFilter = document.getElementById('yearFilter');
        this.yearFilterMobile = document.getElementById('yearFilterMobile');
        this.sortSelect = document.getElementById('sortSelect');
        this.resetBtn = document.getElementById('resetFilters');
        this.resetMobileBtn = document.getElementById('resetFiltersMobile');
        this.genresList = document.querySelector('.genres-list');
        this.activeFiltersTags = document.getElementById('activeFiltersTags');
        this.paginationContainer = document.getElementById('pagination');
    }

    bindEvents() {
        let searchTimeout;
        this.searchInput?.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.filters.search = e.target.value;
                this.currentPage = 1;
                this.loadFilms();
                this.updateActiveTags();
            }, 500);
        });

        this.countryFilter?.addEventListener('change', (e) => {
            this.filters.country = e.target.value;
            if (this.countryFilterMobile) this.countryFilterMobile.value = e.target.value;
            this.currentPage = 1;
            this.loadFilms();
            this.updateActiveTags();
        });

        this.countryFilterMobile?.addEventListener('change', (e) => {
            this.filters.country = e.target.value;
            if (this.countryFilter) this.countryFilter.value = e.target.value;
            this.currentPage = 1;
            this.loadFilms();
            this.updateActiveTags();
        });

        this.yearFilter?.addEventListener('change', (e) => {
            this.filters.year = e.target.value;
            if (this.yearFilterMobile) this.yearFilterMobile.value = e.target.value;
            this.currentPage = 1;
            this.loadFilms();
            this.updateActiveTags();
        });

        this.yearFilterMobile?.addEventListener('change', (e) => {
            this.filters.year = e.target.value;
            if (this.yearFilter) this.yearFilter.value = e.target.value;
            this.currentPage = 1;
            this.loadFilms();
            this.updateActiveTags();
        });

        this.sortSelect?.addEventListener('change', (e) => {
            this.filters.sort = e.target.value;
            this.currentPage = 1;
            this.loadFilms();
        });

        this.resetBtn?.addEventListener('click', () => this.resetFilters());
        this.resetMobileBtn?.addEventListener('click', () => this.resetFilters());

        document.getElementById('toggleFilters')?.addEventListener('click', () => {
            document.getElementById('filtersCollapse')?.classList.toggle('show');
        });
    }

    resetFilters() {
        this.filters = { search: '', genre: '', country: '', year: '', sort: 'recent' };
        this.currentPage = 1;
        if (this.searchInput) this.searchInput.value = '';
        if (this.countryFilter) this.countryFilter.value = '';
        if (this.countryFilterMobile) this.countryFilterMobile.value = '';
        if (this.yearFilter) this.yearFilter.value = '';
        if (this.yearFilterMobile) this.yearFilterMobile.value = '';
        if (this.sortSelect) this.sortSelect.value = 'recent';
        document.querySelectorAll('.genres-list a').forEach(a => a.classList.remove('active'));
        this.loadFilms();
        this.updateActiveTags();
    }

    async loadGenres() {
        if (!this.genresList) return;
        let genres = [];

        // Try TMDB first
        if (typeof tmdbService !== 'undefined' && API_CONFIG?.tmdb?.apiKey !== 'YOUR_TMDB_API_KEY') {
            try {
                genres = await tmdbService.getMovieGenres();
            } catch (e) {
                console.warn('TMDB genres unavailable:', e);
            }
        }

        // Fallback to API
        if (genres.length === 0) {
            try {
                const response = await this.api.getGenres();
                genres = response.data || response || [];
            } catch (error) {
                console.error('Erreur chargement genres:', error);
            }
        }

        if (Array.isArray(genres) && genres.length > 0) {
            this.genresList.innerHTML = genres.map(genre => `
                <li><a href="#" data-genre="${genre.id || genre.name}">${genre.name}</a></li>
            `).join('');
        }

        document.querySelectorAll('.genres-list a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const genre = link.dataset.genre;
                document.querySelectorAll('.genres-list a').forEach(l => l.classList.remove('active'));
                if (this.filters.genre === genre) {
                    this.filters.genre = '';
                } else {
                    link.classList.add('active');
                    this.filters.genre = genre;
                }
                this.currentPage = 1;
                this.loadFilms();
                this.updateActiveTags();
            });
        });
    }

    async loadCountries() {
        try {
            const response = await this.api.getCountries();
            const countries = response.data || response;
            if (Array.isArray(countries)) {
                [this.countryFilter, this.countryFilterMobile].forEach(select => {
                    if (!select) return;
                    const currentValue = select.value;
                    const defaultOpt = select.querySelector('option[value=""]');
                    select.innerHTML = '';
                    if (defaultOpt) select.appendChild(defaultOpt);
                    countries.forEach(country => {
                        const opt = document.createElement('option');
                        opt.value = country;
                        opt.textContent = country;
                        select.appendChild(opt);
                    });
                    select.value = currentValue;
                });
            }
        } catch (error) {
            console.error('Erreur chargement pays:', error);
        }
    }

    async loadFilms() {
        if (this.isLoading) return;
        this.isLoading = true;
        this.showLoading();

        try {
            const params = { page: this.currentPage };
            if (this.filters.search) params.search = this.filters.search;
            if (this.filters.genre) params.genre = this.filters.genre;
            if (this.filters.country) params.country = this.filters.country;
            if (this.filters.year) params.year = this.filters.year;

            const response = await this.api.getFilms(params);

            // Handle TMDB format: { success: true, data: { items: [], total, page, total_pages } }
            // or mock format: { data: [], current_page, last_page, total }
            if (response.data && response.data.items) {
                this.films = response.data.items;
                this.currentPage = response.data.page || 1;
                this.lastPage = response.data.total_pages || 1;
                this.total = response.data.total || 0;
            } else if (Array.isArray(response.data)) {
                this.films = response.data;
                this.currentPage = response.current_page || 1;
                this.lastPage = response.last_page || 1;
                this.total = response.total || 0;
            } else {
                this.films = [];
                this.currentPage = 1;
                this.lastPage = 1;
                this.total = 0;
            }

            this.displayFilms();
            this.renderPagination();
            this.resultsCount.textContent = this.total;
        } catch (error) {
            console.error('Erreur chargement films:', error);
            this.showError(t('error_loading_films'));
        } finally {
            this.isLoading = false;
        }
    }

    displayFilms() {
        if (!this.moviesGrid) return;

        if (this.films.length === 0) {
            this.moviesGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-film fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">${t('no_films_found')}</h5>
                    <p class="text-muted">${t('try_modify_filters')}</p>
                </div>
            `;
            return;
        }

        this.moviesGrid.innerHTML = this.films.map(film => {
            const year = film.year || (film.release_date ? new Date(film.release_date).getFullYear() : 'N/A');
            const rating = film.rating || film.average_rating || 'N/A';
            const genres = Array.isArray(film.genres) ? film.genres : [];
            const poster = film.poster || 'assets/images/ceb1.jpg';
            const title = film.title || film.name || 'Sans titre';

            return `
                <div class="col-film">
                    <a href="${film.url || `film-detail.html?id=${film.id}`}" class="movie-card position-relative" 
                       data-country="${film.origin_country || film.language || ''}" 
                       data-year="${year}" 
                       data-genre="${genres[0] || ''}" 
                       data-rating="${rating}">
                        <span class="badge-year">${year}</span>
                        <img src="${poster}" alt="${Sanitize.escapeHtml(title)}" loading="lazy">
                        <div class="movie-info p-2">
                            <h6 class="movie-title text-dark">${Sanitize.escapeHtml(title)}</h6>
                            <div class="rating">
                                <i class="fas fa-star text-warning"></i> <span>${rating}</span>
                            </div>
                            <div class="genres-tags">
                                ${genres.slice(0, 2).map(g => `<span class="genre-mini">${Sanitize.escapeHtml(g)}</span>`).join('')}
                            </div>
                        </div>
                    </a>
                </div>
            `;
        }).join('');
    }

    renderPagination() {
        if (!this.paginationContainer) return;

        if (this.lastPage <= 1) {
            this.paginationContainer.innerHTML = '';
            return;
        }

        let html = '<nav aria-label="Navigation des films"><ul class="pagination justify-content-center">';

        // Prev button
        html += `<li class="page-item ${this.currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage - 1}">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>`;

        // Page numbers
        const startPage = Math.max(1, this.currentPage - 2);
        const endPage = Math.min(this.lastPage, this.currentPage + 2);

        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }

        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${i === this.currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        if (endPage < this.lastPage) {
            if (endPage < this.lastPage - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${this.lastPage}">${this.lastPage}</a></li>`;
        }

        // Next button
        html += `<li class="page-item ${this.currentPage === this.lastPage ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage + 1}">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>`;

        html += '</ul></nav>';
        this.paginationContainer.innerHTML = html;

        this.paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(link.dataset.page);
                if (page >= 1 && page <= this.lastPage) {
                    this.currentPage = page;
                    this.loadFilms();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }

    showLoading() {
        if (!this.moviesGrid) return;
        this.moviesGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">${t('loading')}</span>
                </div>
                <p class="mt-2 text-muted">${t('loading_films')}</p>
            </div>
        `;
    }

    showError(message) {
        if (!this.moviesGrid) return;
        this.moviesGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> ${message}
                </div>
                <button class="btn btn-outline-dark mt-3" data-action="retry">
                    <i class="fas fa-redo"></i> ${t('retry')}
                </button>
            </div>
        `;
        this.moviesGrid.querySelector('[data-action="retry"]')?.addEventListener('click', () => this.loadFilms());
    }

    updateActiveTags() {
        if (!this.activeFiltersTags) return;
        const tags = [];
        if (this.filters.search) tags.push({ label: `"${this.filters.search}"`, key: 'search' });
        if (this.filters.country) tags.push({ label: this.filters.country, key: 'country' });
        if (this.filters.year) tags.push({ label: this.filters.year, key: 'year' });
        if (this.filters.genre) tags.push({ label: this.filters.genre, key: 'genre' });

        this.activeFiltersTags.innerHTML = tags.map(tag =>
            `<span class="filter-tag" data-filter="${tag.key}">${tag.label} <i class="fas fa-times"></i></span>`
        ).join('');

        this.activeFiltersTags.querySelectorAll('.filter-tag').forEach(tag => {
            tag.addEventListener('click', () => {
                const key = tag.dataset.filter;
                this.filters[key] = '';
                if (key === 'genre') {
                    document.querySelectorAll('.genres-list a').forEach(a => a.classList.remove('active'));
                }
                if (key === 'country') {
                    if (this.countryFilter) this.countryFilter.value = '';
                    if (this.countryFilterMobile) this.countryFilterMobile.value = '';
                }
                if (key === 'year') {
                    if (this.yearFilter) this.yearFilter.value = '';
                    if (this.yearFilterMobile) this.yearFilterMobile.value = '';
                }
                if (key === 'search' && this.searchInput) {
                    this.searchInput.value = '';
                }
                this.currentPage = 1;
                this.loadFilms();
                this.updateActiveTags();
            });
        });
    }
}

let filmsPage;
document.addEventListener('DOMContentLoaded', () => {
    filmsPage = new FilmsPage();
});

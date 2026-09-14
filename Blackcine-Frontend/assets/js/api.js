/**
 * BlackCiné API Service
 */
class BlackCineAPI {
    constructor() {
        this.baseURL = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
            ? 'http://127.0.0.1:8000/api/v1'
            : `https://${window.location.hostname}/api/v1`;
        this.headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            headers: this.headers,
            ...options
        };

        try {
            const response = await fetch(url, config);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('API Request failed:', error);
            throw error;
        }
    }

    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        return this.request(url, { method: 'GET' });
    }

    async post(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    // API Methods
    async getHomeData() {
        return this.get('/home');
    }

    async getFilms(filters = {}) {
        // Try TMDB via backend proxy (server-side key) or direct fallback
        const hasTmdb = typeof tmdbService !== 'undefined' && (API_CONFIG?.tmdb?.proxyUrl || (API_CONFIG?.tmdb?.apiKey && API_CONFIG.tmdb.apiKey !== 'YOUR_TMDB_API_KEY'));
        if (hasTmdb) {
            try {
                let data;
                if (filters.genre) {
                    data = await tmdbService.getFilmsByGenre(filters.genre, filters.page || 1);
                } else if (filters.country) {
                    data = await tmdbService.getFilmsByCountry(filters.country, filters.page || 1);
                } else if (filters.search) {
                    data = await tmdbService.searchFilms(filters.search, filters.page || 1);
                } else if (filters.trending) {
                    data = await tmdbService.getTrendingFilms();
                } else if (filters.now_playing) {
                    data = await tmdbService.getNowPlayingFilms(filters.page || 1);
                } else {
                    data = await tmdbService.getPopularFilms(filters.page || 1);
                }
                return {
                    success: true,
                    data: {
                        items: (data.results || []).map(f => tmdbService.transformFilmForDisplay(f)),
                        total: data.total_results,
                        page: data.page,
                        total_pages: data.total_pages
                    }
                };
            } catch (e) {
                console.warn('TMDB unavailable, using mock data:', e);
            }
        }
        return this.get('/titles/films', filters);
    }

    async getSeries(filters = {}) {
        // Try TMDB via backend proxy or direct fallback
        const hasTmdb = typeof tmdbService !== 'undefined' && (API_CONFIG?.tmdb?.proxyUrl || (API_CONFIG?.tmdb?.apiKey && API_CONFIG.tmdb.apiKey !== 'YOUR_TMDB_API_KEY'));
        if (hasTmdb) {
            try {
                let data;
                if (filters.genre) {
                    data = await tmdbService.getSeriesByGenre(filters.genre, filters.page || 1);
                } else if (filters.search) {
                    data = await tmdbService.searchSeries(filters.search, filters.page || 1);
                } else if (filters.trending) {
                    data = await tmdbService.getTrendingSeries();
                } else if (filters.african) {
                    data = await tmdbService.getAfricanSeries(filters.page || 1);
                } else {
                    data = await tmdbService.getPopularSeries(filters.page || 1);
                }
                return {
                    success: true,
                    data: {
                        items: (data.results || []).map(s => tmdbService.transformSeriesForDisplay(s)),
                        total: data.total_results,
                        page: data.page,
                        total_pages: data.total_pages
                    }
                };
            } catch (e) {
                console.warn('TMDB unavailable for series, using mock data:', e);
            }
        }
        return this.get('/titles/series', filters);
    }

    async getEmissions(filters = {}) {
        return this.get('/emissions', filters);
    }

    async getUpcomingEmissions() {
        return this.get('/emissions/upcoming');
    }

    async getFeaturedEmissions() {
        return this.get('/emissions/featured');
    }

    async getArticles(filters = {}) {
        return this.get('/articles', filters);
    }

    async getFeaturedArticle() {
        return this.get('/articles/featured');
    }

    async getPopularArticles() {
        return this.get('/articles/popular');
    }

    async getCastings(filters = {}) {
        return this.get('/castings', filters);
    }

    async getCasting(id) {
        return this.get(`/castings/${id}`);
    }

    async getProjects(filters = {}) {
        return this.get('/projects', filters);
    }

    async getProject(id) {
        return this.get(`/projects/${id}`);
    }

    async getContests(filters = {}) {
        return this.get('/contests', filters);
    }

    async getContest(id) {
        return this.get(`/contests/${id}`);
    }

    async getGenres() {
        return this.get('/titles/genres');
    }

    async getCountries() {
        return this.get('/titles/countries');
    }

    // Méthodes pour la page d'accueil
    async getShowtimes(filters = {}) {
        return this.get('/showtimes', filters);
    }

    async getNowShowing() {
        return this.get('/showtimes/now-showing');
    }

    async getFestivals() {
        return this.get('/festivals');
    }

    async getActiveFestivals() {
        return this.get('/festivals/active');
    }

    async getPartners() {
        return this.get('/partners');
    }

    async getSelections() {
        return this.get('/selections');
    }

    async getSelection(id) {
        return this.get(`/selections/${id}`);
    }

    async getFestival(id) {
        return this.get(`/festivals/${id}`);
    }

    async getFooterData() {
        return this.get('/footer');
    }

    async getArticle(id) {
        return this.get(`/articles/${id}`);
    }
}

window.blackcineAPI = new BlackCineAPI();
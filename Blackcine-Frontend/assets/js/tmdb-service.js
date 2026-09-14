/**
 * TMDB Service - The Movie Database API
 * Métadonnées films, séries, tendances, streaming
 */
class TMDBService {
    constructor() {
        this.config = API_CONFIG.tmdb;
        this.cache = new Map();
        this.cacheTTL = 5 * 60 * 1000; // 5 minutes
    }

    getCacheKey(endpoint, params) {
        return `${endpoint}:${JSON.stringify(params)}`;
    }

    getFromCache(key) {
        const item = this.cache.get(key);
        if (item && Date.now() - item.timestamp < this.cacheTTL) {
            return item.data;
        }
        this.cache.delete(key);
        return null;
    }

    setCache(key, data) {
        this.cache.set(key, { data, timestamp: Date.now() });
    }

    async request(endpoint, params = {}) {
        const cacheKey = this.getCacheKey(endpoint, params);
        const cached = this.getFromCache(cacheKey);
        if (cached) return cached;

        const queryString = new URLSearchParams({
            language: this.config.language,
            ...params
        }).toString();

        // Priorité au proxy backend sécurisé (clé côté serveur)
        const useProxy = !!(this.config.proxyUrl);
        let url;
        let headers = { 'Content-Type': 'application/json' };

        if (useProxy) {
            url = `${this.config.proxyUrl}${endpoint}?${queryString}`;
        } else {
            // Fallback direct (dev sans backend) — nécessite apiKey
            if (!this.config.apiKey) {
                throw new Error('TMDB proxy non configuré et aucune clé client disponible.');
            }
            url = `${this.config.baseUrl}${endpoint}?${queryString}`;
            headers['Authorization'] = `Bearer ${this.config.apiKey}`;
        }

        try {
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 8000);
            const response = await fetch(url, { headers, signal: controller.signal });
            clearTimeout(timeout);
            if (!response.ok) {
                if (response.status === 429) {
                    console.warn('TMDB rate limit (429) — retry plus tard');
                }
                throw new Error(`TMDB API error: ${response.status}`);
            }
            const data = await response.json();
            this.setCache(cacheKey, data);
            return data;
        } catch (error) {
            if (error.name === 'AbortError') {
                console.error('TMDB request timeout:', endpoint);
            } else {
                console.error('TMDB request failed:', error);
            }
            throw error;
        }
    }

    // ==================== FILMS ====================
    async getTrendingFilms(timeWindow = 'week') {
        return this.request(`/trending/movie/${timeWindow}`);
    }

    async getPopularFilms(page = 1) {
        return this.request('/movie/popular', { page });
    }

    async getNowPlayingFilms(page = 1) {
        return this.request('/movie/now_playing', { page, region: this.config.region });
    }

    async getUpcomingFilms(page = 1) {
        return this.request('/movie/upcoming', { page, region: this.config.region });
    }

    async getTopRatedFilms(page = 1) {
        return this.request('/movie/top_rated', { page });
    }

    async getFilmDetails(id) {
        return this.request(`/movie/${id}`, { append_to_response: 'credits,videos,similar,recommendations,watch_providers' });
    }

    async searchFilms(query, page = 1) {
        return this.request('/search/movie', { query, page });
    }

    async getFilmsByGenre(genreId, page = 1) {
        return this.request('/discover/movie', {
            with_genres: genreId,
            sort_by: 'popularity.desc',
            page
        });
    }

    async getFilmsByCountry(countryCode, page = 1) {
        return this.request('/discover/movie', {
            with_origin_country: countryCode,
            sort_by: 'popularity.desc',
            page
        });
    }

    async getAfricanFilms(page = 1) {
        const africanCountries = 'NG|SN|KE|GH|CI|BF|ML|CM|DZ|MA|TN|ET|ZA|CD|UG';
        return this.request('/discover/movie', {
            with_origin_country: africanCountries,
            sort_by: 'popularity.desc',
            page
        });
    }

    // ==================== SÉRIES ====================
    async getTrendingSeries(timeWindow = 'week') {
        return this.request(`/trending/tv/${timeWindow}`);
    }

    async getPopularSeries(page = 1) {
        return this.request('/tv/popular', { page });
    }

    async getAiringSeries(page = 1) {
        return this.request('/tv/airing_today', { page });
    }

    async getOnTheAirSeries(page = 1) {
        return this.request('/tv/on_the_air', { page });
    }

    async getTopRatedSeries(page = 1) {
        return this.request('/tv/top_rated', { page });
    }

    async getSeriesDetails(id) {
        return this.request(`/tv/${id}`, { append_to_response: 'credits,videos,similar,recommendations,watch_providers' });
    }

    async searchSeries(query, page = 1) {
        return this.request('/search/tv', { query, page });
    }

    async getSeriesByGenre(genreId, page = 1) {
        return this.request('/discover/tv', {
            with_genres: genreId,
            sort_by: 'popularity.desc',
            page
        });
    }

    async getAfricanSeries(page = 1) {
        const africanCountries = 'NG|SN|KE|GH|CI|BF|ML|CM|DZ|MA|TN|ET|ZA|CD|UG';
        return this.request('/discover/tv', {
            with_origin_country: africanCountries,
            sort_by: 'popularity.desc',
            page
        });
    }

    // ==================== GENRES ====================
    async getMovieGenres() {
        const data = await this.request('/genre/movie/list');
        return data.genres || [];
    }

    async getTVGenres() {
        const data = await this.request('/genre/tv/list');
        return data.genres || [];
    }

    // ==================== RECHERCHE ====================
    async search(query, page = 1) {
        return this.request('/search/multi', { query, page });
    }

    // ==================== WATCH PROVIDERS ====================
    async getWatchProviders(country = 'FR') {
        return this.request('/watch/providers/movie', { watch_region: country });
    }

    async getFilmWatchProviders(filmId) {
        return this.request(`/movie/${filmId}/watch_providers`);
    }

    // ==================== TRANSFORMATION ====================
    transformFilmForDisplay(tmdbFilm) {
        return {
            id: tmdbFilm.id,
            title: tmdbFilm.title,
            original_title: tmdbFilm.original_title,
            overview: tmdbFilm.overview,
            poster: tmdbPosterUrl(tmdbFilm.poster_path),
            backdrop: tmdbBackdropUrl(tmdbFilm.backdrop_path),
            rating: tmdbFilm.vote_average ? tmdbFilm.vote_average.toFixed(1) : 'N/A',
            votes: tmdbFilm.vote_count,
            year: tmdbFilm.release_date ? new Date(tmdbFilm.release_date).getFullYear() : 'N/A',
            release_date: tmdbFilm.release_date,
            genres: tmdbFilm.genres ? tmdbFilm.genres.map(g => g.name) : [],
            genre_ids: tmdbFilm.genre_ids || [],
            runtime: tmdbFilm.runtime,
            language: tmdbFilm.original_language,
            popularity: tmdbFilm.popularity,
            adult: tmdbFilm.adult,
            tmdb_id: tmdbFilm.id,
            imdb_id: tmdbFilm.imdb_id,
            url: `film-detail.html?id=${tmdbFilm.id}`
        };
    }

    transformSeriesForDisplay(tmdbSeries) {
        return {
            id: tmdbSeries.id,
            title: tmdbSeries.name,
            original_title: tmdbSeries.original_name,
            overview: tmdbSeries.overview,
            poster: tmdbPosterUrl(tmdbSeries.poster_path),
            backdrop: tmdbBackdropUrl(tmdbSeries.backdrop_path),
            rating: tmdbSeries.vote_average ? tmdbSeries.vote_average.toFixed(1) : 'N/A',
            votes: tmdbSeries.vote_count,
            year: tmdbSeries.first_air_date ? new Date(tmdbSeries.first_air_date).getFullYear() : 'N/A',
            first_air_date: tmdbSeries.first_air_date,
            last_air_date: tmdbSeries.last_air_date,
            genres: tmdbSeries.genres ? tmdbSeries.genres.map(g => g.name) : [],
            genre_ids: tmdbSeries.genre_ids || [],
            seasons: tmdbSeries.number_of_seasons,
            episodes: tmdbSeries.number_of_episodes,
            status: tmdbSeries.status,
            language: tmdbSeries.original_language,
            popularity: tmdbSeries.popularity,
            tmdb_id: tmdbSeries.id,
            url: `serie-detail.html?id=${tmdbSeries.id}`
        };
    }
}

window.tmdbService = new TMDBService();

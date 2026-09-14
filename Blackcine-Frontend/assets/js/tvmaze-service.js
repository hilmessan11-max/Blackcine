/**
 * TVmaze Service - Séries TV gratuites sans clé API
 * https://www.tvmaze.com/api
 */
class TVmazeService {
    constructor() {
        this.baseUrl = API_CONFIG.tvmaze.baseUrl;
        this.cache = new Map();
        this.cacheTTL = 10 * 60 * 1000; // 10 minutes
    }

    getCacheKey(endpoint) {
        return endpoint;
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

    async request(endpoint) {
        const cacheKey = this.getCacheKey(endpoint);
        const cached = this.getFromCache(cacheKey);
        if (cached) return cached;

        const url = `${this.baseUrl}${endpoint}`;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`TVmaze API error: ${response.status}`);
            }
            const data = await response.json();
            this.setCache(cacheKey, data);
            return data;
        } catch (error) {
            console.error('TVmaze request failed:', error);
            throw error;
        }
    }

    // ==================== SEARCH ====================
    async searchShows(query) {
        return this.request(`/search/shows?q=${encodeURIComponent(query)}`);
    }

    // ==================== SHOWS ====================
    async getShowById(id) {
        return this.request(`/shows/${id}?embed[]=episodes&embed[]=cast&embed[]=crew`);
    }

    async getShowByImdb(imdbId) {
        return this.request(`/lookup/shows?imdb=${imdbId}`);
    }

    async getPopularShows(page = 0) {
        return this.request(`/shows?page=${page}`);
    }

    async getShowSchedule(country = 'US', date = null) {
        const d = date || new Date().toISOString().split('T')[0];
        return this.request(`/schedule?country=${country}&date=${d}`);
    }

    async getShowWebSchedule(date = null) {
        const d = date || new Date().toISOString().split('T')[0];
        return this.request(`/web/schedule?date=${d}`);
    }

    async getSingleSearchShow(query) {
        return this.request(`/singlesearch/shows?q=${encodeURIComponent(query)}`);
    }

    // ==================== EPISODES ====================
    async getShowEpisodes(showId) {
        return this.request(`/shows/${showId}/episodes`);
    }

    async getSeasonEpisodes(showId, season) {
        return this.request(`/shows/${showId}/episodesbyseason/${season}`);
    }

    // ==================== CAST ====================
    async getShowCast(showId) {
        return this.request(`/shows/${showId}/cast`);
    }

    async getPersonById(id) {
        return this.request(`/people/${id}`);
    }

    async getPersonCastCredits(id) {
        return this.request(`/people/${id}/castcredits`);
    }

    // ==================== GENRES ====================
    async getShowByGenre(genre, page = 0) {
        return this.request(`/shows?genre=${genre}&page=${page}`);
    }

    // ==================== TRANSFORMATION ====================
    transformShowForDisplay(tvmazeShow) {
        const show = tvmazeShow.show || tvmazeShow;
        return {
            id: show.id,
            title: show.name,
            overview: show.summary ? show.summary.replace(/<[^>]*>/g, '') : '',
            poster: show.image ? show.image.medium : 'assets/images/placeholder.jpg',
            poster_large: show.image ? show.image.original : 'assets/images/placeholder.jpg',
            rating: show.rating && show.rating.average ? show.rating.average.toFixed(1) : 'N/A',
            year: show.premiered ? new Date(show.premiered).getFullYear() : 'N/A',
            premiered: show.premiered,
            ended: show.ended,
            status: show.status,
            genres: show.genres || [],
            language: show.language,
            network: show.network ? show.network.name : (show.webChannel ? show.webChannel.name : 'N/A'),
            country: show.network && show.network.country ? show.network.country.name : 'N/A',
            runtime: show.runtime || show.averageRuntime,
            officialSite: show.officialSite,
            imdb_id: show.externals ? show.externals.imdb : null,
            tvmaze_id: show.id,
            url: `serie-detail.html?tvmaze=${show.id}`
        };
    }
}

window.tvmazeService = new TVmazeService();

/**
 * Streaming Service - Disponibilité streaming
 * Utilise TMDB Watch Providers (gratuit)
 */
class StreamingService {
    constructor() {
        this.config = API_CONFIG.tmdb;
    }

    async getFilmWatchProviders(filmId) {
        try {
            const data = await tmdbService.getFilmWatchProviders(filmId);
            if (!data.results || !data.results[this.config.region]) {
                return { flatrate: [], rent: [], buy: [], free: [] };
            }
            const regionData = data.results[this.config.region];
            return {
                flatrate: regionData.flatrate || [],
                rent: regionData.rent || [],
                buy: regionData.buy || [],
                free: regionData.free || []
            };
        } catch (error) {
            console.error('Erreur providers streaming:', error);
            return { flatrate: [], rent: [], buy: [], free: [] };
        }
    }

    getProviderLogo(providerId, providerName) {
        const logos = {
            8: 'netflix', 15: 'hulu', 337: 'disney', 10: 'amazon',
            387: 'hbo', 350: 'apple', 531: 'paramount', 283: 'crunchyroll',
            284: 'mubi', 1899: 'max', 546: 'prime', 547: 'apple'
        };
        const slug = logos[providerId] || providerName.toLowerCase().replace(/[^a-z]/g, '');
        return `https://image.tmdb.org/t/p/original/pFzbvZk4q3gMfVpTBSOxPQzYPBp.png`;
    }

    formatProviderInfo(providers) {
        const result = [];
        if (providers.flatrate && providers.flatrate.length > 0) {
            result.push({
                type: 'Abonnement',
                providers: providers.flatrate.map(p => ({
                    name: p.provider_name,
                    logo: p.logo_path ? tmdbImageUrl(p.logo_path, 'w92') : null
                }))
            });
        }
        if (providers.free && providers.free.length > 0) {
            result.push({
                type: 'Gratuit',
                providers: providers.free.map(p => ({
                    name: p.provider_name,
                    logo: p.logo_path ? tmdbImageUrl(p.logo_path, 'w92') : null
                }))
            });
        }
        if (providers.rent && providers.rent.length > 0) {
            result.push({
                type: 'Location',
                providers: providers.rent.map(p => ({
                    name: p.provider_name,
                    logo: p.logo_path ? tmdbImageUrl(p.logo_path, 'w92') : null
                }))
            });
        }
        if (providers.buy && providers.buy.length > 0) {
            result.push({
                type: 'Achat',
                providers: providers.buy.map(p => ({
                    name: p.provider_name,
                    logo: p.logo_path ? tmdbImageUrl(p.logo_path, 'w92') : null
                }))
            });
        }
        return result;
    }
}

window.streamingService = new StreamingService();

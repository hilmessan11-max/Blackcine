/**
 * BlackCiné API Configuration
 * La clé TMDB est désormais côté serveur (config/services.php + /api/v1/tmdb/* proxy).
 * Aucun secret ne doit être en clair côté client.
 */
const API_CONFIG = {
    tmdb: {
        // Plus de clé en clair — le backend proxy gère l'auth
        apiKey: null,
        // Proxy sécurisé via Laravel (cache + whitelist + throttle)
        proxyUrl: '/api/v1/tmdb',
        // Fallback direct (dev uniquement) — laisser null en prod
        baseUrl: 'https://api.themoviedb.org/3',
        imageBaseUrl: 'https://image.tmdb.org/t/p',
        posterSize: 'w500',
        backdropSize: 'w1280',
        profileSize: 'w185',
        language: 'fr-FR',
        region: 'FR'
    },
    tvmaze: {
        baseUrl: 'https://api.tvmaze.com',
        language: 'fr'
    },
    reeldb: {
        apiKey: 'YOUR_REELDB_API_KEY', // Obtenir sur https://reeldb.io
        baseUrl: 'https://api.reeldb.io'
    },
    streaming: {
        apiKey: 'YOUR_STREAMING_API_KEY', // Obtenir sur https://www.movieofthenight.com/about/api
        baseUrl: 'https://streaming-availability.p.rapidapi.com'
    }
};

// Helper pour construire les URLs d'images TMDB
function tmdbImageUrl(path, size = 'w500') {
    if (!path) return 'assets/images/placeholder.jpg';
    return `${API_CONFIG.tmdb.imageBaseUrl}/${size}${path}`;
}

function tmdbPosterUrl(path) {
    return tmdbImageUrl(path, API_CONFIG.tmdb.posterSize);
}

function tmdbBackdropUrl(path) {
    return tmdbImageUrl(path, API_CONFIG.tmdb.backdropSize);
}

window.API_CONFIG = API_CONFIG;
window.tmdbImageUrl = tmdbImageUrl;
window.tmdbPosterUrl = tmdbPosterUrl;
window.tmdbBackdropUrl = tmdbBackdropUrl;

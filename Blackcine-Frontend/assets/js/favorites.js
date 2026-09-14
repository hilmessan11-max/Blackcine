/**
 * Favorites/Watchlist functionality using localStorage
 */
class Favorites {
    constructor() {
        this.storageKey = 'blackcine_favorites';
        this.init();
    }

    init() {
        document.addEventListener('click', (e) => {
            const favBtn = e.target.closest('[data-action="toggle-favorite"]');
            if (favBtn) {
                e.preventDefault();
                this.toggleFavorite(favBtn);
            }
        });

        this.updateAllFavoriteButtons();
    }

    getFavorites() {
        try {
            return JSON.parse(localStorage.getItem(this.storageKey)) || [];
        } catch {
            return [];
        }
    }

    saveFavorites(favorites) {
        localStorage.setItem(this.storageKey, JSON.stringify(favorites));
    }

    isFavorite(type, id) {
        const favorites = this.getFavorites();
        return favorites.some(f => f.type === type && String(f.id) === String(id));
    }

    addFavorite(type, id, data) {
        const favorites = this.getFavorites();
        if (!this.isFavorite(type, id)) {
            favorites.push({ type, id, ...data, addedAt: Date.now() });
            this.saveFavorites(favorites);
            this.updateAllFavoriteButtons();
        }
    }

    removeFavorite(type, id) {
        let favorites = this.getFavorites();
        favorites = favorites.filter(f => !(f.type === type && String(f.id) === String(id)));
        this.saveFavorites(favorites);
        this.updateAllFavoriteButtons();
    }

    toggleFavorite(btn) {
        const type = btn.dataset.favoriteType;
        const id = btn.dataset.favoriteId;
        const rawName = btn.dataset.favoriteName || 'Ce titre';
        const poster = btn.dataset.favoritePoster || '';
        const name = (typeof Sanitize !== 'undefined' && Sanitize.escapeHtml) ? Sanitize.escapeHtml(rawName) : rawName;

        if (!type || !id) {
            console.warn('Favorites: type ou id manquant', btn);
            return;
        }

        if (this.isFavorite(type, id)) {
            this.removeFavorite(type, id);
            btn.classList.remove('active');
            btn.innerHTML = '<i class="far fa-heart"></i>';
            if (window.bcToast) window.bcToast(`Retiré des favoris : ${name}`, 'info');
        } else {
            this.addFavorite(type, id, { name: rawName, poster });
            btn.classList.add('active');
            btn.innerHTML = '<i class="fas fa-heart"></i>';
            if (window.bcToast) window.bcToast(`Ajouté aux favoris : ${name}`, 'success');
        }

        // Animate button
        btn.style.transform = 'scale(1.3)';
        setTimeout(() => { btn.style.transform = ''; }, 200);
    }

    updateAllFavoriteButtons() {
        document.querySelectorAll('[data-action="toggle-favorite"]').forEach(btn => {
            const type = btn.dataset.favoriteType;
            const id = btn.dataset.favoriteId;
            if (this.isFavorite(type, id)) {
                btn.classList.add('active');
                btn.innerHTML = '<i class="fas fa-heart"></i>';
            } else {
                btn.classList.remove('active');
                btn.innerHTML = '<i class="far fa-heart"></i>';
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Favorites();
});
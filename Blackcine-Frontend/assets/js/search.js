/**
 * Search functionality for BlackCiné
 */
class SearchHandler {
    constructor() {
        this.init();
    }

    init() {
        document.querySelectorAll('.search-container').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSearch(form);
            });
        });
    }

    handleSearch(form) {
        const select = form.querySelector('select');
        const input = form.querySelector('input[type="text"]');
        const category = select?.value || 'films';
        const query = input?.value?.trim();

        if (!query) {
            if (window.bcToast) window.bcToast('Veuillez entrer un terme de recherche', 'warning');
            input?.focus();
            return;
        }

        const searchParams = new URLSearchParams({ search: query });

        switch (category) {
            case 'FILMS':
            case 'films':
                window.location.href = `films.html?${searchParams}`;
                break;
            case 'SÉRIES':
            case 'series':
                window.location.href = `series.html?${searchParams}`;
                break;
            case 'ÉMISSIONS':
            case 'emissions':
                window.location.href = `emissions.html?${searchParams}`;
                break;
            default:
                window.location.href = `films.html?${searchParams}`;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new SearchHandler();
});
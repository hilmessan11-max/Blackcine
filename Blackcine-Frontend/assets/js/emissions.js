/**
 * Emissions page - API integration
 */
class EmissionsPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.emissions = [];
        this.currentCategory = 'all';
        this.isLoading = false;

        this.init();
    }

    async init() {
        this.cacheElements();
        this.bindEvents();

        const params = new URLSearchParams(window.location.search);
        const category = params.get('category');
        if (category) {
            this.currentCategory = category;
            this.categoryTabs.forEach(tab => {
                if (tab.dataset.category === category) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });
        }

        await Promise.all([
            this.loadFeaturedEmissions(),
            this.loadUpcomingEmissions(),
            this.loadEmissions()
        ]);
    }

    cacheElements() {
        this.emissionsGrid = document.getElementById('emissionsGrid');
        this.featuredGrid = document.getElementById('featuredGrid');
        this.upcomingTimeline = document.getElementById('upcomingTimeline');
        this.heroContent = document.getElementById('heroContent');
        this.categoryTabs = document.querySelectorAll('.category-tab');
    }

    bindEvents() {
        this.categoryTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                this.categoryTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                this.currentCategory = tab.dataset.category;
                this.loadEmissions();
            });
        });

        document.querySelector('.btn-load-more')?.addEventListener('click', () => {
            this.loadMore();
        });
    }

    async loadEmissions() {
        if (this.isLoading) return;
        this.isLoading = true;
        this.showLoading();

        try {
            const params = {};
            if (this.currentCategory && this.currentCategory !== 'all') {
                params.category = this.currentCategory;
            }
            const response = await this.api.getEmissions(params);
            this.emissions = response.data || [];
            this.displayEmissions();
        } catch (error) {
            console.error('Erreur chargement émissions:', error);
            this.showError(t('error_loading_emissions'));
        } finally {
            this.isLoading = false;
        }
    }

    displayEmissions() {
        if (!this.emissionsGrid) return;

        if (this.emissions.length === 0) {
            this.emissionsGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-broadcast-tower fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">${t('no_emissions_found')}</h5>
                    <p class="text-muted">${t('try_change_category')}</p>
                </div>
            `;
            return;
        }

        this.emissionsGrid.innerHTML = this.emissions.map(emission => `
            <div class="emission-card" onclick="window.location.href='emission-detail.html?id=${emission.id}'">
                <div class="card-visual">
                    <img src="${emission.thumbnail || 'assets/images/ceb1.jpg'}" alt="${emission.title}" loading="lazy">
                    ${emission.is_live ? '<div class="card-badges"><span class="badge-live">LIVE</span></div>' : ''}
                    <span class="card-duration">${this.formatDuration(emission.duration)}</span>
                    <div class="card-play">
                        <i class="bi bi-play-fill"></i>
                    </div>
                </div>
                <div class="card-body">
                    <span class="card-category-tag ${emission.category}">${this.getCategoryLabel(emission.category)}</span>
                    <h4>${emission.title}</h4>
                    <p class="card-description">${emission.description || ''}</p>
                    <div class="card-stats">
                        <span><i class="bi bi-eye"></i> ${this.formatViews(emission.views_count || emission.views)}</span>
                        <span><i class="bi bi-person"></i> ${emission.presenter || 'N/A'}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    async loadFeaturedEmissions() {
        try {
            const response = await this.api.getFeaturedEmissions();
            const featured = response.data || [];
            this.displayFeaturedEmissions(featured);
        } catch (error) {
            console.error('Erreur émissions à la une:', error);
        }
    }

    displayFeaturedEmissions(emissions) {
        if (!this.featuredGrid || emissions.length === 0) return;

        const mainEmission = emissions[0];
        const secondaryEmissions = emissions.slice(1, 4);

        this.featuredGrid.innerHTML = `
            <div class="featured-main">
                <div class="emission-card-large" onclick="window.location.href='emission-detail.html?id=${mainEmission.id}'">
                    <div class="card-image">
                        <img src="${mainEmission.thumbnail || 'assets/images/image191.jpg'}" alt="${mainEmission.title}">
                        <div class="card-overlay">
                            <span class="card-category">${this.getCategoryLabel(mainEmission.category)}</span>
                            <span class="card-duration"><i class="bi bi-clock"></i> ${this.formatDuration(mainEmission.duration)}</span>
                        </div>
                        <div class="play-button">
                            <i class="bi bi-play-fill"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>${mainEmission.title}</h3>
                        <p>${mainEmission.description || ''}</p>
                        <div class="card-meta">
                            <span class="views"><i class="bi bi-eye"></i> ${this.formatViews(mainEmission.views_count || mainEmission.views)} ${t('home_views')}</span>
                            <span class="presenter"><i class="bi bi-person"></i> ${mainEmission.presenter || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="featured-secondary">
                ${secondaryEmissions.map(emission => `
                    <div class="emission-card-small" onclick="window.location.href='emission-detail.html?id=${emission.id}'">
                        <div class="card-thumbnail">
                            <img src="${emission.thumbnail || 'assets/images/ceb1.jpg'}" alt="${emission.title}">
                            <span class="duration">${this.formatDuration(emission.duration)}</span>
                        </div>
                        <div class="card-info">
                            <span class="category-badge ${emission.category}">${this.getCategoryLabel(emission.category)}</span>
                            <h4>${emission.title}</h4>
                            <p class="meta"><i class="bi bi-eye"></i> ${this.formatViews(emission.views_count || emission.views)} • ${emission.presenter || 'N/A'}</p>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    async loadUpcomingEmissions() {
        try {
            const response = await this.api.getUpcomingEmissions();
            const upcoming = response.data || [];
            this.displayUpcomingEmissions(upcoming);
        } catch (error) {
            console.error('Erreur prochaines diffusions:', error);
        }
    }

    displayUpcomingEmissions(emissions) {
        if (!this.upcomingTimeline) return;

        if (emissions.length === 0) {
            this.upcomingTimeline.innerHTML = `<p class="text-muted text-center">${t('no_broadcast')}</p>`;
            return;
        }

        this.upcomingTimeline.innerHTML = emissions.map(emission => {
            const broadcastDate = new Date(emission.broadcast_date || emission.starts_at);
            const now = new Date();
            const isToday = broadcastDate.toDateString() === now.toDateString();
            const isTomorrow = broadcastDate.toDateString() === new Date(now.getTime() + 86400000).toDateString();

            let dayLabel;
            if (isToday) dayLabel = t('today');
            else if (isTomorrow) dayLabel = t('tomorrow');
            else dayLabel = broadcastDate.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric' });

            const timeLabel = broadcastDate.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

            return `
                <div class="timeline-item" onclick="window.location.href='emission-detail.html?id=${emission.id}'">
                    <div class="time-block">
                        <span class="day">${dayLabel}</span>
                        <span class="hour">${timeLabel}</span>
                    </div>
                    <div class="emission-info">
                        <img src="${emission.thumbnail || 'assets/images/ceb1.jpg'}" alt="${emission.title}">
                        <div class="details">
                            ${emission.is_live ? `<span class="badge-live">${t('en_direct')}</span>` : ''}
                            <h4>${emission.title}</h4>
                            <p>${emission.description || `${t('presented_by')} ${emission.presenter || 'N/A'}`}</p>
                        </div>
                    </div>
                    <button class="btn-reminder" onclick="event.stopPropagation();"><i class="bi bi-bell"></i></button>
                </div>
            `;
        }).join('');
    }

    loadMore() {
        this.loadEmissions();
    }

    formatDuration(minutes) {
        if (!minutes) return '0:00';
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return hours > 0 ? `${hours}h${mins.toString().padStart(2, '0')}` : `${mins}min`;
    }

    formatViews(views) {
        if (!views) return '0';
        if (views >= 1000000) return (views / 1000000).toFixed(1) + 'M';
        if (views >= 1000) return (views / 1000).toFixed(1) + 'K';
        return views.toString();
    }

    getCategoryLabel(category) {
        const labels = {
            'interview': t('category_interview'),
            'talkshow': t('category_talkshow'),
            'podcast': t('category_podcast'),
            'makingof': t('category_makingof'),
            'chronique': t('category_chronique')
        };
        return labels[category] || category || t('category_emission');
    }

    showLoading() {
        if (!this.emissionsGrid) return;
        this.emissionsGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">${t('loading')}</span>
                </div>
                <p class="mt-2 text-muted">${t('loading_emissions')}</p>
            </div>
        `;
    }

    showError(message) {
        if (!this.emissionsGrid) return;
        this.emissionsGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> ${message}
                </div>
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new EmissionsPage();
});

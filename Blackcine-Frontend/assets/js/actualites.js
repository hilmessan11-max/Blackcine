/**
 * Actualités page - API integration
 */
class ActualitesPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.articles = [];
        this.currentPage = 1;
        this.lastPage = 1;
        this.total = 0;
        this.currentCategory = '';
        this.isLoading = false;

        this.init();
    }

    async init() {
        this.cacheElements();
        this.bindEvents();
        await Promise.all([
            this.loadFeaturedArticle(),
            this.loadArticles()
        ]);
    }

    cacheElements() {
        this.articlesGrid = document.querySelector('.row.g-4');
        this.featuredArticle = document.querySelector('.featured-article');
        this.categoryLinks = document.querySelectorAll('.genres-list a');
        this.paginationContainer = document.querySelector('.pagination-container');
    }

    bindEvents() {
        this.categoryLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.categoryLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                this.currentCategory = link.dataset.category || '';
                this.currentPage = 1;
                this.loadArticles();
            });
        });
    }

    async loadFeaturedArticle() {
        try {
            const response = await this.api.getFeaturedArticle();
            const article = response.data;
            if (article) {
                this.updateFeaturedArticle(article);
            }
        } catch (error) {
            console.error('Erreur article à la une:', error);
        }
    }

    updateFeaturedArticle(article) {
        const titleEl = document.querySelector('.featured-title');
        const excerptEl = document.querySelector('.featured-excerpt');
        const authorEl = document.querySelector('.featured-meta span:first-child');
        const dateEl = document.querySelector('.featured-meta span:nth-child(2)');
        const imgEl = document.querySelector('.featured-image img');

        if (titleEl) titleEl.textContent = article.title;
        if (excerptEl) excerptEl.textContent = article.excerpt || '';
        if (authorEl) authorEl.innerHTML = `<i class="bi bi-person"></i> ${Sanitize.escapeHtml(article.author || t('author_default'))}`;
        if (dateEl) {
            const date = new Date(article.published_at || article.created_at);
            dateEl.innerHTML = `<i class="bi bi-calendar"></i> ${date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' })}`;
        }
        if (imgEl && article.thumbnail) {
            imgEl.src = article.thumbnail;
        }
    }

    async loadArticles() {
        if (this.isLoading) return;
        this.isLoading = true;
        this.showLoading();

        try {
            const params = { page: this.currentPage };
            if (this.currentCategory) params.category = this.currentCategory;

            const response = await this.api.getArticles(params);
            this.articles = response.data || [];
            this.currentPage = response.current_page || 1;
            this.lastPage = response.last_page || 1;
            this.total = response.total || 0;

            this.displayArticles();
            this.renderPagination();
        } catch (error) {
            console.error('Erreur chargement articles:', error);
            this.showError(t('error_loading_articles'));
        } finally {
            this.isLoading = false;
        }
    }

    displayArticles() {
        if (!this.articlesGrid) return;

        if (this.articles.length === 0) {
            this.articlesGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">${t('no_articles_found')}</h5>
                </div>
            `;
            return;
        }

        this.articlesGrid.innerHTML = this.articles.map(article => {
            const date = new Date(article.published_at || article.created_at);
            const formattedDate = date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
            const categoryClass = (article.category || 'news').toLowerCase().replace(/\s+/g, '');
            const thumbnail = article.thumbnail || article.image || 'assets/images/ceb1.jpg';

            return `
                <div class="col-article">
                    <div class="article-card position-relative">
                        <span class="article-badge ${categoryClass}">${Sanitize.escapeHtml(article.category || 'News')}</span>
                        <div class="article-image">
                            <img src="${thumbnail}" alt="${Sanitize.escapeHtml(article.title)}" loading="lazy">
                        </div>
                        <div class="article-content">
                            <span class="article-date"><i class="bi bi-calendar"></i> ${formattedDate}</span>
                            <h6 class="article-title">${Sanitize.escapeHtml(article.title)}</h6>
                            <p class="article-excerpt">${Sanitize.escapeHtml(article.excerpt || '')}</p>
                            <div class="article-footer">
                                <span class="article-author"><i class="bi bi-person"></i> ${Sanitize.escapeHtml(article.author || t('author_default'))}</span>
                                <a href="article-detail.html?id=${article.id}" class="article-link">Lire <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    renderPagination() {
        if (!this.paginationContainer || this.lastPage <= 1) {
            if (this.paginationContainer) this.paginationContainer.innerHTML = '';
            return;
        }

        let html = '<nav aria-label="Navigation des articles"><ul class="pagination justify-content-center">';

        html += `<li class="page-item ${this.currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage - 1}"><i class="fas fa-chevron-left"></i></a>
        </li>`;

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

        html += `<li class="page-item ${this.currentPage === this.lastPage ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage + 1}"><i class="fas fa-chevron-right"></i></a>
        </li>`;

        html += '</ul></nav>';
        this.paginationContainer.innerHTML = html;

        this.paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(link.dataset.page);
                if (page >= 1 && page <= this.lastPage) {
                    this.currentPage = page;
                    this.loadArticles();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }

    showLoading() {
        if (!this.articlesGrid) return;
        this.articlesGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">${t('loading')}</span>
                </div>
                <p class="mt-2 text-muted">${t('loading_articles')}</p>
            </div>
        `;
    }

    showError(message) {
        if (!this.articlesGrid) return;
        this.articlesGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> ${message}
                </div>
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ActualitesPage();
});

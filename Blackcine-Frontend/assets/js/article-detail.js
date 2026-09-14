/**
 * Article Detail page - API integration
 */
class ArticleDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.article = null;
        this.articleId = this.getArticleIdFromUrl();
        this.init();
    }

    getArticleIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.articleId) {
            this.showError(t('no_article_selected') || 'Aucun article sélectionné');
            return;
        }

        try {
            await this.loadArticle();
        } catch (error) {
            console.error('Erreur chargement article:', error);
            this.showError(t('error_loading_article') || "Erreur lors du chargement de l'article");
        }
    }

    async loadArticle() {
        const response = await this.api.getArticle(this.articleId);
        this.article = response.data || response;
        this.displayArticle();
        this.updateShareLinks();
    }

    displayArticle() {
        const article = this.article;
        if (!article) return;

        // Hide loading, show content
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('articleDetail').style.display = 'block';

        // Update page title
        document.title = `BlackCiné - ${article.title}`;

        // Category
        document.getElementById('articleCategory').textContent = article.category || 'News';

        // Title
        document.getElementById('articleTitle').textContent = article.title;

        // Author
        document.getElementById('articleAuthor').innerHTML = `<i class="fas fa-user"></i> ${Sanitize.escapeHtml(article.author || t('author_default') || 'Rédaction BlackCiné')}`;

        // Date
        const date = new Date(article.published_at || article.created_at);
        const formattedDate = date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
        document.getElementById('articleDate').innerHTML = `<i class="fas fa-calendar"></i> ${formattedDate}`;

        // Views
        const views = article.views_count || article.views || 0;
        document.getElementById('articleViews').innerHTML = `<i class="fas fa-eye"></i> ${views} ${t('home_views') || 'vues'}`;
        document.getElementById('articleViewsBottom').innerHTML = `<i class="fas fa-eye"></i> <span>${views}</span> ${t('home_views') || 'vues'}`;

        // Thumbnail
        const thumbnail = article.thumbnail || article.image || 'assets/images/ceb1.jpg';
        document.getElementById('articleThumbnail').src = thumbnail;
        document.getElementById('articleThumbnail').alt = article.title;

        // Content / Body
        const content = article.content || article.body || `<p>${t('no_content') || 'Aucun contenu disponible.'}</p>`;
        Sanitize.safeHtml(document.getElementById('articleContent'), content);

        // Load related articles
        this.loadRelatedArticles();
    }

    updateShareLinks() {
        const article = this.article;
        if (!article) return;

        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent(article.title);

        document.getElementById('shareFacebook').href = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
        document.getElementById('shareTwitter').href = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
        document.getElementById('shareLinkedin').href = `https://www.linkedin.com/shareArticle?mini=true&url=${url}&title=${title}`;
        document.getElementById('shareWhatsapp').href = `https://wa.me/?text=${title}%20${url}`;
    }

    async loadRelatedArticles() {
        try {
            const response = await this.api.getArticles({ limit: 3 });
            const articles = (response.data || []).filter(a => a.id !== this.article.id);
            if (articles.length > 0) {
                document.getElementById('relatedSection').style.display = 'block';
                document.getElementById('relatedContainer').innerHTML = articles.map(article => {
                    const date = new Date(article.published_at || article.created_at);
                    const formattedDate = date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
                    const thumbnail = article.thumbnail || article.image || 'assets/images/ceb1.jpg';

                    return `
                        <div class="col-md-4">
                            <a href="article-detail.html?id=${article.id}" class="text-decoration-none">
                                <div class="related-article-card">
                                    <img src="${thumbnail}" alt="${Sanitize.escapeHtml(article.title)}">
                                    <div class="related-info">
                                        <h6>${Sanitize.escapeHtml(article.title)}</h6>
                                        <span class="related-meta"><i class="fas fa-calendar"></i> ${formattedDate}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `;
                }).join('');
            }
        } catch (error) {
            console.error('Erreur chargement articles similaires:', error);
        }
    }

    showError(message) {
        document.getElementById('loadingState').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> ${message}
            </div>
            <a href="actualites.html" class="btn btn-outline-dark mt-3">
                <i class="fas fa-arrow-left"></i> Retour aux actualités
            </a>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ArticleDetailPage();
});

/**
 * Script pour la page d'accueil - Connexion API
 */
class HomePage {
    constructor() {
        this.api = window.blackcineAPI;
        this.allTickets = [];
        this.currentFilter = 'today';
        this.currentSlide = 0;
        this.maxSlides = 0;
        this.autoSlideInterval = null;
        this.init();
    }

    async init() {
        // Load real TMDB data for hero and top sections
        if (typeof tmdbService !== 'undefined' && API_CONFIG?.tmdb?.apiKey !== 'YOUR_TMDB_API_KEY') {
            try {
                // Fetch trending films for hero
                const trending = await tmdbService.getTrendingFilms();
                if (trending.results && trending.results.length > 0) {
                    const heroFilm = trending.results[0];
                    this.renderHeroFromTMDB(heroFilm);
                }

                // Fetch top films
                const topFilms = await tmdbService.getPopularFilms();
                this.renderTopFilmsFromTMDB(topFilms.results || []);

                // Fetch top series
                const topSeries = await tmdbService.getPopularSeries();
                this.renderTopSeriesFromTMDB(topSeries.results || []);
            } catch (e) {
                console.warn('TMDB non disponible, données mock:', e);
            }
        }

        // Load mock data for other sections
        try {
            const homeData = await this.api.getHomeData();
            this.renderContent(homeData.data);
        } catch (error) {
            console.error('Erreur chargement page d\'accueil:', error);
            this.showError();
        }
        this.initTicketFilters();
    }

    renderHeroFromTMDB(film) {
        const heroSection = document.querySelector('.hero-section');
        if (!heroSection) return;

        const heroTitle = heroSection.querySelector('.hero-title');
        const heroImg = heroSection.querySelector('.hero-right img');
        const heroTags = heroSection.querySelector('.tags');

        if (heroTitle) heroTitle.textContent = film.title.toUpperCase();
        if (heroImg) {
            heroImg.src = tmdbBackdropUrl(film.backdrop_path);
            heroImg.alt = film.title;
        }
        if (heroTags) {
            const genres = film.genre_ids ? this.getGenreNames(film.genre_ids) : [];
            heroTags.innerHTML = genres.slice(0, 4).map(g =>
                `<span class="tag">${Sanitize.escapeHtml(g.toUpperCase())}</span>`
            ).join('');
        }
    }

    getGenreNames(ids) {
        const genreMap = {
            28: 'Action', 12: 'Aventure', 16: 'Animation', 35: 'Comédie',
            80: 'Crime', 99: 'Documentaire', 18: 'Drame', 10751: 'Famille',
            14: 'Fantaisie', 36: 'Historique', 27: 'Horreur', 10402: 'Musique',
            9648: 'Mystère', 10749: 'Romance', 878: 'Sci-Fi', 53: 'Thriller',
            10752: 'Guerre', 37: 'Western'
        };
        return ids.map(id => genreMap[id] || 'Autre');
    }

    renderTopFilmsFromTMDB(films) {
        const container = document.querySelector('.top-films-section .row');
        if (!container || !films) return;

        const filmsHtml = films.slice(0, 8).map((film, index) => `
            <div class="col-6 col-md-4 col-lg-3">
                <a href="film-detail.html?id=${film.id}" class="top-movie-card">
                    <div class="top-poster">
                        <img src="${tmdbPosterUrl(film.poster_path)}" alt="${Sanitize.escapeHtml(film.title)}">
                        <div class="top-overlay">
                            <button class="btn-play-top">
                                <i class="fas fa-play"></i>
                            </button>
                            <div class="top-quick-actions">
                                <button class="btn-quick-action" title="Ajouter à ma liste">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn-quick-action" title="Plus d'infos">
                                    <i class="fas fa-info"></i>
                                </button>
                            </div>
                        </div>
                        <span class="top-rank">#${index + 1}</span>
                    </div>
                    <div class="top-info">
                        <h6 class="top-title">${Sanitize.escapeHtml(film.title)}</h6>
                        <div class="top-meta">
                            <div class="top-rating">
                                <i class="fas fa-star"></i>
                                <span>${film.vote_average ? film.vote_average.toFixed(1) : 'N/A'}</span>
                            </div>
                            <span class="top-year">${film.release_date ? new Date(film.release_date).getFullYear() : ''}</span>
                        </div>
                        <div class="top-genres">
                            ${(film.genre_ids || []).slice(0, 2).map(id => {
                                const name = this.getGenreNames([id])[0];
                                return `<span class="genre-tag">${name}</span>`;
                            }).join('')}
                        </div>
                    </div>
                </a>
            </div>
        `).join('');

        container.innerHTML = filmsHtml;
    }

    renderTopSeriesFromTMDB(series) {
        const container = document.querySelector('.top-series-section .row');
        if (!container || !series) return;

        const seriesHtml = series.slice(0, 8).map((show, index) => `
            <div class="col-6 col-md-4 col-lg-3">
                <a href="serie-detail.html?id=${show.id}" class="top-movie-card">
                    <div class="top-poster">
                        <img src="${tmdbPosterUrl(show.poster_path)}" alt="${Sanitize.escapeHtml(show.name)}">
                        <div class="top-overlay">
                            <button class="btn-play-top">
                                <i class="fas fa-play"></i>
                            </button>
                            <div class="top-quick-actions">
                                <button class="btn-quick-action" title="Ajouter à ma liste">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn-quick-action" title="Plus d'infos">
                                    <i class="fas fa-info"></i>
                                </button>
                            </div>
                        </div>
                        <span class="series-badge badge-trending">Série</span>
                        <span class="top-rank">#${index + 1}</span>
                    </div>
                    <div class="top-info">
                        <h6 class="top-title">${Sanitize.escapeHtml(show.name)}</h6>
                        <div class="top-meta">
                            <div class="top-rating">
                                <i class="fas fa-star"></i>
                                <span>${show.vote_average ? show.vote_average.toFixed(1) : 'N/A'}</span>
                            </div>
                            <span class="top-year">${show.first_air_date ? new Date(show.first_air_date).getFullYear() : ''}</span>
                        </div>
                        <div class="top-genres">
                            ${(show.genre_ids || []).slice(0, 2).map(id => {
                                const name = this.getGenreNames([id])[0];
                                return `<span class="genre-tag">${name}</span>`;
                            }).join('')}
                        </div>
                    </div>
                </a>
            </div>
        `).join('');

        container.innerHTML = seriesHtml;
    }

    renderContent(data) {
        this.renderHero(data.slides);
        this.renderArticles(data.featured_articles);
        this.renderTrailers(data.featured_videos);
        this.renderTopFilms(data.top_films);
        this.renderTopSeries(data.top_series);
        this.renderShowtimes(data.now_showing);
        this.renderSelection(data.editorial_selection);
        this.renderClassics(data.classics);
        this.renderOpportunities(data.active_castings, data.active_projects, data.active_contests);
        this.renderPartners(data.partners);
    }

    // ==================== HERO SECTION ====================
    renderHero(slides) {
        const heroSection = document.querySelector('.hero-section');
        if (!heroSection || !slides || slides.length === 0) return;

        const slide = slides[0]; // Use first slide as main hero
        const heroTitle = heroSection.querySelector('.hero-title');
        const heroImg = heroSection.querySelector('.hero-right img');
        const heroTags = heroSection.querySelector('.tags');
        const heroMovieInfo = heroSection.querySelector('.movie-info');

        if (heroTitle && slide.title) {
            heroTitle.textContent = slide.title.toUpperCase();
        }
        if (heroImg && slide.image) {
            heroImg.src = slide.image;
            heroImg.alt = slide.title || 'Film';
        }
        if (heroTags && slide.subtitle) {
            const tags = slide.subtitle.split(',').map(t => t.trim());
            heroTags.innerHTML = tags.map(tag => 
                `<span class="tag">${Sanitize.escapeHtml(tag.toUpperCase())}</span>`
            ).join('');
        }
    }

    // ==================== ARTICLES ====================
    renderArticles(articles) {
        const container = document.querySelector('#articles-container');
        if (!container) return;

        if (!articles || articles.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        ${t('home_no_articles')}
                    </div>
                </div>
            `;
            return;
        }

        container.innerHTML = articles.slice(0, 3).map(article => `
            <div class="col-lg-4 col-md-6">
                <div class="article-card">
                    <div class="article-img-container">
                        <img src="${article.thumbnail || 'assets/images/image191.jpg'}" alt="${Sanitize.escapeHtml(article.title)}" class="article-img">
                        <span class="article-badge">${t('home_badge_new')}</span>
                    </div>
                    <div class="article-card-content">
                        <p class="article-date">${new Date(article.published_at).toLocaleDateString('fr-FR')}</p>
                        <h5 class="article-title">${Sanitize.escapeHtml(article.title)}</h5>
                        <p class="article-text">${Sanitize.escapeHtml(article.excerpt || '')}</p>
                        <a href="#" class="btn-read">
                            ${t('btn_read_more')} <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ==================== TRAILERS ====================
    renderTrailers(videos) {
        const container = document.querySelector('#trailers-container');
        if (!container || !videos || videos.length === 0) return;

        container.innerHTML = videos.map(video => `
            <div class="trailer-card" data-video-id="${video.id}">
                <div class="trailer-thumbnail">
                    <img src="${video.thumbnail || 'assets/images/ceb1.jpg'}" alt="${Sanitize.escapeHtml(video.title)}">
                    <div class="trailer-play-overlay">
                        <button class="btn-trailer-play" data-url="${Sanitize.sanitizeUrl(video.source_url || '#')}">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <span class="trailer-duration">${this.formatDuration(video.duration)}</span>
                </div>
                <div class="trailer-info">
                    <h6 class="trailer-title">${Sanitize.escapeHtml(video.title)}</h6>
                    <span class="trailer-views"><i class="fas fa-eye"></i> ${video.views_count || 0} ${t('home_views')}</span>
                </div>
            </div>
        `).join('');

        this.initTrailerPlayButtons();
    }

    formatDuration(seconds) {
        if (!seconds) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    initTrailerPlayButtons() {
        document.querySelectorAll('.btn-trailer-play').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const url = btn.dataset.url;
                if (url && url !== '#') {
                    window.open(url, '_blank');
                }
            });
        });
    }

    // ==================== TOP FILMS ====================
    renderTopFilms(films) {
        const container = document.querySelector('.top-films-section .row');
        if (!container || !films) return;

        const filmsHtml = films.slice(0, 4).map((film, index) => `
            <div class="col-6 col-md-4 col-lg-3">
                <div class="top-movie-card">
                    <div class="top-poster">
                        <img src="${film.poster || 'assets/images/ceb1.jpg'}" alt="${Sanitize.escapeHtml(film.name)}">
                        <div class="top-overlay">
                            <button class="btn-play-top">
                                <i class="fas fa-play"></i>
                            </button>
                            <div class="top-quick-actions">
                                <button class="btn-quick-action" title="${t('home_add_to_list')}">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn-quick-action" title="${t('home_more_info')}">
                                    <i class="fas fa-info"></i>
                                </button>
                            </div>
                        </div>
                        <span class="top-badge badge-new">Nouveau</span>
                        <span class="top-rank">#${index + 1}</span>
                    </div>
                    <div class="top-info">
                        <h6 class="top-title">${Sanitize.escapeHtml(film.name)}</h6>
                        <div class="top-meta">
                            <div class="top-rating">
                                <i class="fas fa-star"></i>
                                <span>${film.average_rating || '8.' + Math.floor(Math.random() * 9)}</span>
                            </div>
                            <span class="top-year">${new Date(film.release_date).getFullYear()}</span>
                        </div>
                        <div class="top-genres">
                            ${film.genres.slice(0, 2).map(genre => `<span class="genre-tag">${Sanitize.escapeHtml(genre)}</span>`).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        container.innerHTML = filmsHtml;
    }

    // ==================== TOP SERIES ====================
    renderTopSeries(series) {
        const container = document.querySelector('.top-films-section .row');
        if (!container || !series) return;

        const seriesHtml = series.slice(0, 4).map((serie, index) => `
            <div class="col-6 col-md-4 col-lg-3">
                <div class="top-movie-card">
                    <div class="top-poster">
                        <img src="${serie.poster || 'assets/images/ceb1.jpg'}" alt="${Sanitize.escapeHtml(serie.name)}">
                        <div class="top-overlay">
                            <button class="btn-play-top">
                                <i class="fas fa-play"></i>
                            </button>
                            <div class="top-quick-actions">
                                <button class="btn-quick-action" title="${t('home_add_to_list')}">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn-quick-action" title="${t('home_more_info')}">
                                    <i class="fas fa-info"></i>
                                </button>
                            </div>
                        </div>
                        <span class="top-badge badge-trending">${t('home_badge_trending')}</span>
                        <span class="top-rank">#${index + 5}</span>
                        <span class="series-badge">${t('home_badge_series')}</span>
                    </div>
                    <div class="top-info">
                        <h6 class="top-title">${Sanitize.escapeHtml(serie.name)}</h6>
                        <div class="top-meta">
                            <div class="top-rating">
                                <i class="fas fa-star"></i>
                                <span>${serie.average_rating || '7.' + Math.floor(Math.random() * 9)}</span>
                            </div>
                            <span class="top-year">${new Date(serie.release_date).getFullYear()}</span>
                        </div>
                        <div class="top-genres">
                            ${serie.genres.slice(0, 2).map(genre => `<span class="genre-tag">${Sanitize.escapeHtml(genre)}</span>`).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        container.innerHTML += seriesHtml;
    }

    // ==================== SHOWTIMES ====================
    renderShowtimes(showtimes) {
        const container = document.querySelector('.ticket-carousel-track');
        if (!container) return;
        
        if (!showtimes || showtimes.length === 0) {
            this.allTickets = this.getStaticTickets();
        } else {
            this.allTickets = showtimes.map(showtime => ({
                ...showtime,
                date: new Date(showtime.show_date)
            }));
        }
        
        this.filterAndRenderTickets();
    }

    getStaticTickets() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const saturday = new Date(today);
        saturday.setDate(today.getDate() + (6 - today.getDay()));
        const sunday = new Date(today);
        sunday.setDate(today.getDate() + (7 - today.getDay()));

        return [
            {
                title: 'Le Roi de Lagos',
                poster: 'assets/images/cebsingle.png',
                description: "L'ascension d'un jeune homme déterminé à conquérir le monde des affaires à Lagos.",
                cinema: 'Majestic Cotonou',
                city: 'Cotonou',
                show_time: '19h00 • 21h30',
                available_seats: 15,
                date: today
            },
            {
                title: 'Mère Courage',
                poster: 'assets/images/cebsingle.png',
                description: 'Le portrait émouvant d\'une femme qui se bat pour sa famille.',
                cinema: 'Cinéma Palace',
                city: 'Abidjan',
                show_time: '18h30 • 20h45',
                available_seats: 8,
                date: tomorrow
            },
            {
                title: 'Les Enfants du Sahel',
                poster: 'assets/images/cebsingle.png',
                description: 'Une aventure touchante à travers les paysages du Sahel.',
                cinema: 'Ciné Liberté',
                city: 'Dakar',
                show_time: '17h00 • 19h15 • 21h30',
                available_seats: 20,
                date: saturday
            },
            {
                title: 'Rythmes d\'Afrique',
                poster: 'assets/images/cebsingle.png',
                description: 'Un voyage musical à travers les traditions africaines.',
                cinema: 'Grand Écran',
                city: 'Lomé',
                show_time: '16h30 • 19h00',
                available_seats: 12,
                date: sunday
            }
        ];
    }

    filterAndRenderTickets() {
        const container = document.querySelector('.ticket-carousel-track');
        if (!container) return;

        const filteredTickets = this.getFilteredTickets();
        
        this.currentSlide = 0;
        
        if (filteredTickets.length === 0) {
            container.innerHTML = `
                <div class="ticket-empty-state">
                    <div class="empty-state-content">
                        <div class="empty-state-emoji">😞</div>
                        <h4>${t('home_no_events')}</h4>
                        <p>${t('home_no_events_desc')} ${this.getFilterLabel()}.</p>
                        <button class="btn-show-all" data-action="show-all">
                            ${t('home_see_all_dates')}
                        </button>
                    </div>
                </div>
            `;
            return;
        }
        
        const ticketsHtml = filteredTickets.map(ticket => `
            <div class="ticket-carousel-item">
                <div class="ticket-movie-card">
                    <div class="ticket-poster-section">
                        <img src="${ticket.poster || 'assets/images/cebsingle.png'}" alt="${Sanitize.escapeHtml(ticket.title)}">
                        <div class="ticket-poster-overlay">
                            <button class="btn-ticket-play">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <span class="ticket-availability ${ticket.available_seats > 10 ? 'available' : 'few-seats'}">
                            ${ticket.available_seats > 10 ? t('home_available') : t('home_few_seats')}
                        </span>
                    </div>
                    
                    <div class="ticket-details-section">
                        <div class="ticket-header">
                            <h4 class="ticket-movie-title">${Sanitize.escapeHtml(ticket.title)}</h4>
                            <div class="ticket-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="rating-value">4.5/5</span>
                            </div>
                        </div>
                        
                        <p class="ticket-description">
                            ${Sanitize.escapeHtml(ticket.description || 'Un film captivant du cinéma africain.')}
                        </p>
                        
                        <div class="ticket-info-grid">
                            <div class="ticket-info-item">
                                <i class="fas fa-film"></i>
                                <div class="ticket-info-content">
                                    <span class="ticket-info-label">${t('home_cinema')}</span>
                                    <span class="ticket-info-value">${Sanitize.escapeHtml(ticket.cinema)}</span>
                                </div>
                            </div>
                            <div class="ticket-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="ticket-info-content">
                                    <span class="ticket-info-label">${t('home_city')}</span>
                                    <span class="ticket-info-value">${Sanitize.escapeHtml(ticket.city || '')}</span>
                                </div>
                            </div>
                            <div class="ticket-info-item">
                                <i class="fas fa-calendar"></i>
                                <div class="ticket-info-content">
                                    <span class="ticket-info-label">${t('home_date')}</span>
                                    <span class="ticket-info-value">${this.getDateLabel(ticket.date)}</span>
                                </div>
                            </div>
                            <div class="ticket-info-item">
                                <i class="fas fa-clock"></i>
                                <div class="ticket-info-content">
                                    <span class="ticket-info-label">${t('home_schedules')}</span>
                                    <span class="ticket-info-value">${Sanitize.escapeHtml(ticket.show_time)}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="ticket-actions">
                            <a href="#" class="btn-reserve-ticket">
                                <i class="fas fa-ticket-alt"></i> ${t('home_book_now')}
                            </a>
                            <button class="btn-ticket-action" title="${t('detail_share')}">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <button class="btn-ticket-action" title="${t('home_add_to_list')}">
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        const endIndicator = `
            <div class="ticket-carousel-item">
                <div class="ticket-end-indicator">
                    <div class="end-indicator-content">
                        <div class="end-indicator-emoji">🎬</div>
                        <h5>${t('home_end_message')}</h5>
                        <p>${t('home_end_desc')} ${this.getFilterLabel()}.</p>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = ticketsHtml + endIndicator;
        
        this.maxSlides = filteredTickets.length;
        this.currentSlide = 0;
        this.updateCarousel();
        this.initCarousel();
    }

    getFilteredTickets() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        
        const saturday = new Date(today);
        saturday.setDate(today.getDate() + (6 - today.getDay()));
        
        const sunday = new Date(today);
        sunday.setDate(today.getDate() + (7 - today.getDay()));

        switch (this.currentFilter) {
            case 'today':
                return this.allTickets.filter(ticket => {
                    const ticketDate = new Date(ticket.date);
                    ticketDate.setHours(0, 0, 0, 0);
                    return ticketDate.getTime() === today.getTime();
                });
            case 'tomorrow':
                return this.allTickets.filter(ticket => {
                    const ticketDate = new Date(ticket.date);
                    ticketDate.setHours(0, 0, 0, 0);
                    return ticketDate.getTime() === tomorrow.getTime();
                });
            case 'weekend':
                return this.allTickets.filter(ticket => {
                    const ticketDate = new Date(ticket.date);
                    ticketDate.setHours(0, 0, 0, 0);
                    return ticketDate.getTime() === saturday.getTime() || ticketDate.getTime() === sunday.getTime();
                });
            case 'all':
            default:
                return this.allTickets;
        }
    }

    getDateLabel(date) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        const ticketDate = new Date(date);
        ticketDate.setHours(0, 0, 0, 0);
        
        const diffTime = ticketDate.getTime() - today.getTime();
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) return t('today');
        if (diffDays === 1) return t('tomorrow');
        if (diffDays >= 0 && diffDays <= 7) {
            const days = [t('day_sunday'), t('day_monday'), t('day_tuesday'), t('day_wednesday'), t('day_thursday'), t('day_friday'), t('day_saturday')];
            return days[ticketDate.getDay()];
        }
        return ticketDate.toLocaleDateString('fr-FR');
    }

    getFilterLabel() {
        switch (this.currentFilter) {
            case 'today': return t('filter_today');
            case 'tomorrow': return t('filter_tomorrow');
            case 'weekend': return t('filter_weekend');
            case 'all': return t('filter_period');
            default: return t('filter_period');
        }
    }

    initCarousel() {
        const track = document.querySelector('.ticket-carousel-track');
        if (!track) return;
        
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
        }
        
        this.autoSlideInterval = setInterval(() => {
            this.nextSlide();
        }, 5000);
        
        this.addNavigationControls();
    }
    
    nextSlide() {
        if (this.currentSlide < this.maxSlides) {
            this.currentSlide++;
            this.updateCarousel();
        }
        
        if (this.currentSlide >= this.maxSlides) {
            clearInterval(this.autoSlideInterval);
        }
    }
    
    prevSlide() {
        if (this.currentSlide > 0) {
            this.currentSlide--;
            this.updateCarousel();
            
            if (!this.autoSlideInterval && this.currentSlide < this.maxSlides) {
                this.autoSlideInterval = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            }
        }
    }
    
    updateCarousel() {
        const track = document.querySelector('.ticket-carousel-track');
        if (!track) return;
        
        const translateX = -this.currentSlide * 25;
        track.style.transform = `translateX(${translateX}%)`;
    }
    
    addNavigationControls() {
        const wrapper = document.querySelector('.ticket-carousel-wrapper');
        if (!wrapper || wrapper.querySelector('.carousel-nav')) return;
        
        const navHtml = `
            <button class="carousel-nav prev-nav" data-action="prev-slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-nav next-nav" data-action="next-slide">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
        
        wrapper.insertAdjacentHTML('beforeend', navHtml);

        wrapper.querySelector('[data-action="prev-slide"]')?.addEventListener('click', () => this.prevSlide());
        wrapper.querySelector('[data-action="next-slide"]')?.addEventListener('click', () => this.nextSlide());
    }

    initTicketFilters() {
        const filterButtons = document.querySelectorAll('.ticket-filter-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                this.currentFilter = button.dataset.filter;
                this.filterAndRenderTickets();
            });
        });

        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="show-all"]')) {
                const allBtn = document.querySelector('.ticket-filter-btn[data-filter="all"]');
                if (allBtn) allBtn.click();
            }
        });
    }

    // ==================== SELECTION ====================
    renderSelection(selections) {
        const container = document.querySelector('#selection-container');
        if (!container || !selections || selections.length === 0) return;

        container.innerHTML = selections.map(selection => `
            <div class="col-6 col-md-4 col-lg-2">
                <div class="selection-movie-card">
                    <div class="selection-poster">
                        <img src="${selection.poster || 'assets/images/ceb1.jpg'}" alt="${Sanitize.escapeHtml(selection.title)}">
                        <div class="poster-overlay">
                            <button class="btn-play-poster">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        ${selection.is_new ? '<span class="badge-new">Nouveau</span>' : ''}
                        ${selection.rating ? `
                        <div class="rating-badge">
                            <i class="fas fa-star"></i> ${selection.rating}
                        </div>
                        ` : ''}
                    </div>
                    <div class="selection-info">
                        <h6 class="selection-title">${Sanitize.escapeHtml(selection.title)}</h6>
                        <p class="selection-year">${Sanitize.escapeHtml(selection.year || '')}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ==================== CLASSICS ====================
    renderClassics(classics) {
        const container = document.querySelector('#classics-container');
        if (!container || !classics || classics.length === 0) return;

        container.innerHTML = classics.map(classic => `
            <div class="col-6 col-md-4 col-lg-3">
                <div class="classic-movie-card">
                    <div class="classic-poster">
                        <img src="${classic.poster || 'assets/images/cebsingle.png'}" alt="${Sanitize.escapeHtml(classic.name)}">
                        <div class="classic-poster-overlay">
                            <button class="btn-play-classic">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        <div class="classic-year">${new Date(classic.release_date).getFullYear()}</div>
                        ${classic.award ? `
                        <div class="classic-award-badge">
                            <i class="fas fa-trophy"></i> ${Sanitize.escapeHtml(classic.award)}
                        </div>
                        ` : ''}
                    </div>
                    <div class="classic-info">
                        <h5 class="classic-title">${Sanitize.escapeHtml(classic.name)}</h5>
                        <p class="classic-director">${Sanitize.escapeHtml(classic.director || '')}</p>
                        <p class="classic-country">
                            <i class="fas fa-map-marker-alt"></i> ${Sanitize.escapeHtml(classic.origin_country || '')}
                        </p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ==================== OPPORTUNITIES ====================
    renderOpportunities(castings, projects, contests) {
        // Update opportunity cards with counts
        const opportunityCards = document.querySelectorAll('.opportunity-card');
        if (opportunityCards.length === 0) return;

        const counts = {
            castings: castings ? castings.length : 0,
            projects: projects ? projects.length : 0,
            contests: contests ? contests.length : 0
        };

        // Add count badges to the section title
        const totalOpportunities = counts.castings + counts.projects + counts.contests;
        if (totalOpportunities > 0) {
            const titleEl = document.querySelector('.opportunities-section h3');
            if (titleEl) {
                titleEl.innerHTML += ` <span class="badge bg-danger">${totalOpportunities}</span>`;
            }
        }
    }

    // ==================== PARTNERS ====================
    renderPartners(partners) {
        const container = document.querySelector('#partners-container');
        if (!container || !partners || partners.length === 0) return;

        container.innerHTML = partners.map(partner => `
            <div class="partner-card">
                <div class="partner-logo-wrapper">
                    <img src="${partner.logo || 'assets/images/image191.jpg'}" alt="${Sanitize.escapeHtml(partner.name)}" class="partner-logo">
                    <div class="partner-overlay">
                        <div class="partner-info">
                            <h5 class="partner-name">${Sanitize.escapeHtml(partner.name)}</h5>
                            <p class="partner-role">${Sanitize.escapeHtml(partner.partner_type || '')}</p>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ==================== ERROR ====================
    showError() {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-warning text-center';
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            ${t('home_error_static')}
        `;
        document.body.insertBefore(errorDiv, document.body.firstChild);
    }
}

// Initialiser quand le DOM est chargé
let homePage;
document.addEventListener('DOMContentLoaded', () => {
    homePage = new HomePage();
});

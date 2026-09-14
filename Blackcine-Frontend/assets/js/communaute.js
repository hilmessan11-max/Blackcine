/**
 * Communauté page - API integration (Castings, Projets, Concours)
 */
class CommunautePage {
    constructor() {
        this.api = window.blackcineAPI;
        this.currentFilter = 'all';
        this.isLoading = false;
        this.isLoadingProjects = false;

        this.init();
    }

    async init() {
        this.cacheElements();
        this.bindEvents();
        await Promise.all([
            this.loadCastings(),
            this.loadProjects(),
            this.loadContests()
        ]);
    }

    cacheElements() {
        this.castingsGrid = document.querySelector('#casting .row.g-4');
        this.projectsGrid = document.querySelector('#projets .row.g-4');
        this.contestsTimeline = document.querySelector('.contests-timeline');
        this.filterBtns = document.querySelectorAll('.casting-filters .filter-btn');
    }

    bindEvents() {
        this.filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                this.filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                this.currentFilter = btn.dataset.filter;
                this.loadCastings();
            });
        });

        document.addEventListener('click', (e) => {
            const applyBtn = e.target.closest('[data-action="apply-casting"]');
            if (applyBtn) {
                e.preventDefault();
                const castingId = applyBtn.dataset.castingId;
                const toast1 = document.createElement('div');
                toast1.className = 'position-fixed bottom-0 end-0 p-3 m-3 rounded shadow-lg text-white';
                toast1.style.cssText = 'background-color:#E50914;z-index:9999;transition:opacity 0.5s;';
                toast1.textContent = `${t('casting_apply_message') || 'Fonctionnalité d\'inscription au casting'} #${castingId} - ${t('coming_soon') || 'Bientôt disponible !'}`;
                document.body.appendChild(toast1);
                setTimeout(() => { toast1.style.opacity = '0'; setTimeout(() => toast1.remove(), 500); }, 2500);
            }

            const seeProjectBtn = e.target.closest('[data-action="see-project"]');
            if (seeProjectBtn) {
                e.preventDefault();
                const projectId = seeProjectBtn.dataset.projectId;
                const toast2 = document.createElement('div');
                toast2.className = 'position-fixed bottom-0 end-0 p-3 m-3 rounded shadow-lg text-white';
                toast2.style.cssText = 'background-color:#E50914;z-index:9999;transition:opacity 0.5s;';
                toast2.textContent = `${t('project_details_message') || 'Détails du projet'} #${projectId} - ${t('coming_soon') || 'Bientôt disponible !'}`;
                document.body.appendChild(toast2);
                setTimeout(() => { toast2.style.opacity = '0'; setTimeout(() => toast2.remove(), 500); }, 2500);
            }

            const participateBtn = e.target.closest('[data-action="participate-contest"]');
            if (participateBtn) {
                e.preventDefault();
                const contestId = participateBtn.dataset.contestId;
                const toast3 = document.createElement('div');
                toast3.className = 'position-fixed bottom-0 end-0 p-3 m-3 rounded shadow-lg text-white';
                toast3.style.cssText = 'background-color:#E50914;z-index:9999;transition:opacity 0.5s;';
                toast3.textContent = `${t('contest_register_message') || 'Inscription au concours'} #${contestId} - ${t('coming_soon') || 'Bientôt disponible !'}`;
                document.body.appendChild(toast3);
                setTimeout(() => { toast3.style.opacity = '0'; setTimeout(() => toast3.remove(), 500); }, 2500);
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    window.scrollTo({ top: target.offsetTop - 160, behavior: 'smooth' });
                }
            });
        });
    }

    async loadCastings() {
        if (this.isLoading) return;
        this.isLoading = true;
        this.showLoading(this.castingsGrid);

        try {
            const params = { limit: 6 };
            if (this.currentFilter && this.currentFilter !== 'all') {
                if (this.currentFilter === 'urgent') {
                    params.urgent = 'true';
                } else {
                    params.type = this.currentFilter;
                }
            }

            const response = await this.api.getCastings(params);
            const castings = response.data || [];
            this.displayCastings(castings);
        } catch (error) {
            console.error('Erreur castings:', error);
            this.showError(this.castingsGrid, t('error_loading_castings'));
        } finally {
            this.isLoading = false;
        }
    }

    displayCastings(castings) {
        if (!this.castingsGrid) return;

        if (castings.length === 0) {
            this.castingsGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-video fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">${t('no_castings')}</h5>
                </div>
            `;
            return;
        }

        this.castingsGrid.innerHTML = castings.map(casting => {
            const deadline = casting.end_date ? new Date(casting.end_date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Non définie';
            const isUrgent = casting.is_urgent || casting.urgent;
            const type = (casting.casting_type || casting.type || 'film').toLowerCase();

            return `
                <div class="col-lg-6">
                    <a href="casting-detail.html?id=${casting.id}" class="text-decoration-none">
                    <div class="casting-card">
                        <div class="casting-header">
                            <div class="casting-type ${type}">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                            ${isUrgent ? '<div class="casting-urgent"><i class="fas fa-clock"></i> Urgent</div>' : ''}
                        </div>
                        <div class="casting-body">
                            <h3 class="casting-title">${Sanitize.escapeHtml(casting.title)}</h3>
                            <p class="casting-description">${Sanitize.escapeHtml(casting.description || '')}</p>
                            <div class="casting-details">
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><strong>${t('casting_location')}</strong> ${casting.city || ''}${casting.country ? ', ' + casting.country : ''}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><strong>${t('casting_deadline')}</strong> ${deadline}</span>
                                </div>
                            </div>
                            <div class="casting-tags">
                                ${casting.roles_count ? `<span class="tag">${casting.roles_count} ${t('casting_roles')}</span>` : ''}
                                ${casting.tags ? casting.tags.map(tag => `<span class="tag">${tag}</span>`).join('') : ''}
                            </div>
                        </div>
                        <div class="casting-footer">
                            <div class="casting-deadline">
                                <i class="fas fa-hourglass-half"></i>
                                <span>${t('casting_deadline')} <strong>${deadline}</strong></span>
                            </div>
                            <span class="btn-apply">
                                ${t('btn_apply')} <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                    </a>
                </div>
            `;
        }).join('');
    }

    async loadProjects() {
        if (this.isLoadingProjects) return;
        this.isLoadingProjects = true;
        this.showLoading(this.projectsGrid);

        try {
            const response = await this.api.getProjects({ limit: 6 });
            const projects = response.data || [];
            this.displayProjects(projects);
        } catch (error) {
            console.error('Erreur projets:', error);
            this.showError(this.projectsGrid, t('error_loading_projects'));
        } finally {
            this.isLoadingProjects = false;
        }
    }

    displayProjects(projects) {
        if (!this.projectsGrid) return;

        if (projects.length === 0) {
            this.projectsGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-film fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">${t('no_projects')}</h5>
                </div>
            `;
            return;
        }

        this.projectsGrid.innerHTML = projects.map(project => {
            const status = (project.status || 'development').toLowerCase();
            const statusLabels = {
                'development': t('status_development'),
                'recruiting': t('status_recruiting'),
                'preproduction': t('status_preproduction'),
                'production': t('status_production'),
                'completed': t('status_completed')
            };

            return `
                <div class="col-lg-4 col-md-6">
                    <a href="project-detail.html?id=${project.id}" class="text-decoration-none">
                    <div class="project-card">
                        <div class="project-image">
                            <img src="${project.thumbnail || 'assets/images/ceb1.jpg'}" alt="${project.title}" loading="lazy">
                            <div class="project-status ${status}">
                                <i class="fas fa-cog"></i> ${statusLabels[status] || status}
                            </div>
                        </div>
                        <div class="project-content">
                            <div class="project-category">
                                <i class="fas fa-film"></i> ${project.project_type || 'Projet'}
                            </div>
                            <h3 class="project-title">${Sanitize.escapeHtml(project.title)}</h3>
                            <p class="project-pitch">${Sanitize.escapeHtml(project.description || '')}</p>
                            <div class="project-meta">
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>${project.city || ''}${project.country ? ', ' + project.country : ''}</span>
                                </div>
                            </div>
                        </div>
                        <div class="project-footer">
                            <span class="btn-project">
                                ${t('btn_see_project')} <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                    </a>
                </div>
            `;
        }).join('');
    }

    async loadContests() {
        try {
            const response = await this.api.getContests({ limit: 4 });
            const contests = response.data || [];
            this.displayContests(contests);
        } catch (error) {
            console.error('Erreur concours:', error);
        }
    }

    displayContests(contests) {
        if (!this.contestsTimeline) return;

        if (contests.length === 0) {
            this.contestsTimeline.innerHTML = `<p class="text-muted text-center">${t('no_contests')}</p>`;
            return;
        }

        this.contestsTimeline.innerHTML = contests.map(contest => {
            const deadline = contest.registration_deadline ? new Date(contest.registration_deadline) : null;
            const day = deadline ? deadline.getDate() : '--';
            const month = deadline ? deadline.toLocaleDateString('fr-FR', { month: 'short' }) : '--';
            const year = deadline ? deadline.getFullYear() : '--';

            return `
                <div class="contest-item">
                    <div class="contest-date">
                        <div class="date-day">${day}</div>
                        <div class="date-month">${month}</div>
                        <div class="date-year">${year}</div>
                    </div>
                    <a href="contest-detail.html?id=${contest.id}" class="text-decoration-none">
                    <div class="contest-card">
                        <div class="contest-badge winner">
                            <i class="fas fa-trophy"></i> ${contest.contest_type || 'Concours'}
                        </div>
                        <h3 class="contest-title">${Sanitize.escapeHtml(contest.title)}</h3>
                        <p class="contest-description">${Sanitize.escapeHtml(contest.description || '')}</p>
                        <div class="contest-footer">
                            <span class="btn-contest">
                                ${t('btn_participate')} <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                    </a>
                </div>
            `;
        }).join('');
    }

    showLoading(container) {
        if (!container) return;
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">${t('loading')}</span>
                </div>
            </div>
        `;
    }

    showError(container, message) {
        if (!container) return;
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> ${message}
                </div>
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CommunautePage();
});

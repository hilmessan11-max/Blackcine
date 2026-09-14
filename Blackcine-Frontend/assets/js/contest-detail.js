/**
 * Contest Detail page - API integration
 */
class ContestDetailPage {
    constructor() {
        this.api = window.blackcineAPI;
        this.contest = null;
        this.contestId = this.getContestIdFromUrl();
        this.init();
    }

    getContestIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    async init() {
        if (!this.contestId) {
            this.showError('Aucun concours sélectionné');
            return;
        }

        try {
            await this.loadContest();
        } catch (error) {
            console.error('Erreur chargement concours:', error);
            this.showError('Erreur lors du chargement du concours');
        }
    }

    async loadContest() {
        const response = await this.api.get(`/contests/${this.contestId}`);
        this.contest = response.data || response;
        this.displayContest();
    }

    displayContest() {
        const contest = this.contest;
        if (!contest) return;

        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('contestDetail').style.display = 'block';

        document.title = `BlackCiné - ${contest.title}`;

        document.getElementById('contestTitle').textContent = contest.title;
        document.getElementById('contestType').innerHTML = `<i class="fas fa-trophy"></i> ${contest.type || 'N/A'}`;

        const deadline = contest.deadline ? new Date(contest.deadline).toLocaleDateString('fr-FR') : 'N/A';
        document.getElementById('contestDeadline').innerHTML = `<i class="fas fa-calendar-alt"></i> ${deadline}`;
        document.getElementById('contestDeadlineDetail').textContent = deadline;

        const typeBadge = contest.type || 'N/A';
        document.getElementById('contestTypeBadge').innerHTML = `<i class="fas fa-trophy"></i> <span>${typeBadge}</span>`;
        document.getElementById('contestTypeDetail').textContent = typeBadge;

        document.getElementById('contestDescription').textContent = contest.description || 'Description non disponible';

        const prizes = contest.prizes || [];
        if (prizes.length > 0) {
            document.getElementById('prizesContainer').innerHTML = prizes.map((prize, index) => {
                let prizeClass = 'prize-default';
                let icon = 'fa-award';
                if (index === 0) { prizeClass = 'prize-1'; icon = 'fa-trophy'; }
                else if (index === 1) { prizeClass = 'prize-2'; icon = 'fa-medal'; }
                else if (index === 2) { prizeClass = 'prize-3'; icon = 'fa-ribbon'; }

                return `
                    <div class="prize-item">
                        <div class="prize-icon ${prizeClass}">
                            <i class="fas ${icon}"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">${prize.title || prize.name || `Place ${index + 1}`}</h5>
                            <p class="mb-0 text-muted">${prize.description || ''}</p>
                            ${prize.value ? `<strong class="text-danger">${prize.value}</strong>` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            document.getElementById('prizesContainer').innerHTML = '<p class="text-muted">Informations sur les prix non disponibles</p>';
        }

        const requirements = contest.requirements || [];
        if (requirements.length > 0) {
            document.getElementById('requirementsContainer').innerHTML = requirements.map(req =>
                `<li>${typeof req === 'string' ? req : req.description || JSON.stringify(req)}</li>`
            ).join('');
        } else {
            document.getElementById('requirementsContainer').innerHTML = '<li>Aucune condition spécifique</li>';
        }

        const registerHandler = () => {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3 m-3 rounded shadow-lg text-white';
            toast.style.cssText = 'background-color:#E50914;z-index:9999;transition:opacity 0.5s;';
            toast.textContent = 'Fonctionnalité d\'inscription bientôt disponible ! Veuillez créer un compte pour participer.';
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 500); }, 2500);
        };

        const registerBtn = document.getElementById('btnRegister');
        if (registerBtn) {
            registerBtn.addEventListener('click', registerHandler);
        }
    }

    showError(message) {
        document.getElementById('loadingState').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> ${message}
            </div>
            <a href="communaute.html" class="btn btn-outline-dark mt-3">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ContestDetailPage();
});

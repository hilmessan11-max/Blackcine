/**
 * Social sharing functionality
 */
class ShareHandler {
    constructor() {
        this.init();
    }

    init() {
        document.addEventListener('click', (e) => {
            const shareBtn = e.target.closest('[data-action="share"]');
            if (shareBtn) {
                e.preventDefault();
                this.handleShare(shareBtn);
            }
        });
    }

    handleShare(btn) {
        const url = btn.dataset.shareUrl || window.location.href;
        const title = btn.dataset.shareTitle || document.title;
        const text = btn.dataset.shareText || '';

        if (navigator.share) {
            navigator.share({ title, text, url })
                .then(() => {
                    if (window.bcToast) window.bcToast('Lien partagé avec succès', 'success');
                })
                .catch((err) => {
                    if (err.name !== 'AbortError' && window.bcToast) {
                        window.bcToast('Partage annulé', 'info');
                    }
                });
        } else {
            this.showShareModal(url, title, text);
        }
    }

    showShareModal(url, title, text) {
        const encodedUrl = encodeURIComponent(url);
        const encodedTitle = encodeURIComponent(title);
        const encodedText = encodeURIComponent(text);

        const shareLinks = {
            facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
            twitter: `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`,
            linkedin: `https://www.linkedin.com/shareArticle?mini=true&url=${encodedUrl}&title=${encodedTitle}`,
            whatsapp: `https://wa.me/?text=${encodedTitle}%20${encodedUrl}`,
            email: `mailto:?subject=${encodedTitle}&body=${encodedText}%0A%0A${encodedUrl}`
        };

        const modal = document.createElement('div');
        modal.className = 'share-modal';
        modal.innerHTML = `
            <div class="share-modal-content">
                <h4>${t('detail_share') || 'Partager'}</h4>
                <div class="share-links">
                    <a href="${shareLinks.facebook}" target="_blank" class="share-link facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="${shareLinks.twitter}" target="_blank" class="share-link twitter">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="${shareLinks.linkedin}" target="_blank" class="share-link linkedin">
                        <i class="fab fa-linkedin-in"></i> LinkedIn
                    </a>
                    <a href="${shareLinks.whatsapp}" target="_blank" class="share-link whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="${shareLinks.email}" class="share-link email">
                        <i class="fas fa-envelope"></i> Email
                    </a>
                </div>
                <button class="btn btn-outline-dark mt-3" onclick="this.closest('.share-modal').remove()">
                    ${t('btn_close') || 'Fermer'}
                </button>
            </div>
        `;

        modal.style.cssText = `
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); display: flex; align-items: center;
            justify-content: center; z-index: 9999;
        `;
        modal.querySelector('.share-modal-content').style.cssText = `
            background: white; padding: 30px; border-radius: 16px;
            text-align: center; max-width: 400px; width: 90%;
        `;
        modal.querySelectorAll('.share-link').forEach(link => {
            link.style.cssText = `
                display: inline-block; padding: 10px 20px; margin: 5px;
                border-radius: 8px; color: white; text-decoration: none;
                font-weight: 500;
            `;
        });
        modal.querySelector('.facebook').style.background = '#1877F2';
        modal.querySelector('.twitter').style.background = '#1DA1F2';
        modal.querySelector('.linkedin').style.background = '#0A66C2';
        modal.querySelector('.whatsapp').style.background = '#25D366';
        modal.querySelector('.email').style.background = '#666';

        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.remove();
        });

        document.body.appendChild(modal);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ShareHandler();
});
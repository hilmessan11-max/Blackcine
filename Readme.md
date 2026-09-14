# BlackCiné

Plateforme complète de cinéma africain — Films, Séries, Émissions, Actualités, Festivals, Billetterie et Communauté.

## Stack technique

| Composant | Technologie |
|-----------|-------------|
| **Backend** | Laravel 12 (PHP 8.2+) |
| **Frontend** | HTML5 / CSS3 / JavaScript vanilla |
| **Base de données** | SQLite (dev) / MySQL (prod) |
| **Authentification** | Laravel Sanctum (tokens API) |
| **Permissions** | Spatie Laravel Permission |
| **CSS** | Bootstrap 5.3.7 + Tailwind CSS v4 + DaisyUI |
| **Animations** | Custom CSS + JS (View Transitions, Scroll Reveal) |
| **Icons** | Font Awesome 6.5 + Bootstrap Icons |

## Architecture

```
Blackcine/
├── Blackcine-Backend/     # API Laravel 12
│   ├── app/
│   │   ├── Http/Controllers/Api/   # 22 contrôleurs API
│   │   ├── Models/                 # 65 modèles Eloquent
│   │   └── ...
│   ├── database/
│   │   ├── migrations/     # 85+ migrations
│   │   └── seeders/        # 4 seeders
│   └── routes/
│       └── api.php         # 50+ routes API
│
├── Blackcine-Frontend/    # Interface utilisateur
│   ├── assets/
│   │   ├── css/            # 6 fichiers CSS custom
│   │   ├── js/             # 31 fichiers JS
│   │   └── images/         # 33 images
│   └── *.html              # 31 pages HTML
│
└── README.md
```

## Installation rapide

```bash
# 1. Cloner le dépôt
git clone https://github.com/hilmessan11-max/Blackcine.git
cd Blackcine

# 2. Backend
cd Blackcine-Backend
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed
php artisan serve

# 3. Frontend (dans un autre terminal)
cd ../Blackcine-Frontend
# Ouvrir index.html dans un navigateur
# ou utiliser un serveur local :
npx serve .
```

## Compte par défaut

| Champ | Valeur |
|-------|--------|
| Email | `admin@example.com` |
| Mot de passe | `password` |

## Pages Frontend (31)

| Page | Description |
|------|-------------|
| `index.html` | Page d'accueil |
| `films.html` | Catalogue de films |
| `film-detail.html` | Détail d'un film |
| `series.html` | Catalogue de séries |
| `serie-detail.html` | Détail d'une série |
| `emissions.html` | Émissions TV |
| `emission-detail.html` | Détail d'une émission |
| `actualites.html` | Articles et actualités |
| `article-detail.html` | Détail d'un article |
| `selections.html` | Sélections éditoriales |
| `selection-detail.html` | Détail d'une sélection |
| `festivals.html` | Festivals de cinéma |
| `festival-detail.html` | Détail d'un festival |
| `communaute.html` | Castings, projets, concours |
| `casting-detail.html` | Détail d'un casting |
| `project-detail.html` | Détail d'un projet |
| `contest-detail.html` | Détail d'un concours |
| `login.html` | Connexion |
| `register.html` | Inscription |
| `profil.html` | Mon profil |
| `forgot-password.html` | Mot de passe oublié |
| `contact.html` | Contact |
| `faq.html` | Questions fréquentes |
| `about.html` | À propos |
| `partenaires.html` | Partenaires |
| `cgu.html` | Conditions d'utilisation |
| `cgv.html` | Conditions générales de vente |
| `confidentialite.html` | Politique de confidentialité |
| `mentions-legales.html` | Mentions légales |
| `404.html` | Page non trouvée |
| `test-api.html` | Test de l'API |

## API Routes (50+)

### Publiques
- `GET /api/v1/home` — Données accueil
- `GET /api/v1/search` — Recherche multi-types
- `GET /api/v1/articles` — Liste articles
- `GET /api/v1/titles` — Films & séries
- `GET /api/v1/videos` — Vidéos & bandes-annonces
- `GET /api/v1/showtimes` — Séances
- `GET /api/v1/selections` — Sélections éditoriales
- `GET /api/v1/festivals` — Festivals
- `GET /api/v1/castings` — Castings
- `GET /api/v1/projects` — Projets
- `GET /api/v1/contests` — Concours
- `POST /api/v1/newsletter/subscribe` — Inscription newsletter

### Authentifiées
- `GET /api/v1/user` — Profil utilisateur
- `PUT /api/v1/user` — Mise à jour profil
- `PUT /api/v1/user/password` — Changement mot de passe
- `GET /api/v1/favorites` — Liste favoris
- `POST /api/v1/favorites` — Ajouter favori
- `DELETE /api/v1/favorites/{id}` — Supprimer favori

### Admin
- `POST /api/v1/admin/films` — Créer film
- `PUT /api/v1/admin/films/{id}` — Modifier film
- `DELETE /api/v1/admin/films/{id}` — Supprimer film
- `POST /api/v1/admin/series` — Créer série
- `PUT /api/v1/admin/series/{id}` — Modifier série
- `DELETE /api/v1/admin/series/{id}` — Supprimer série
- `POST /api/v1/admin/emissions` — Créer émission
- `PUT /api/v1/admin/emissions/{id}` — Modifier émission
- `DELETE /api/v1/admin/emissions/{id}` — Supprimer émission

## Fonctionnalités

### Frontend
- ✅ Responsive design (mobile, tablette, desktop)
- ✅ Animations de scroll (View Transitions API)
- ✅ Micro-interactions (hover, click, focus)
- ✅ Mode Accessibilité (ARIA, skip-nav, contraste)
- ✅ Touch gestures (swipe, pull-to-refresh)
- ✅ Skeleton loading
- ✅ Toast notifications
- ✅ Système i18n (FR/EN)
- ✅ Recherche multi-types
- ✅ Favoris (localStorage + API)
- ✅ Partage social
- ✅ Lazy loading images
- ✅ Performance (prefetch, LCP/CLS tracking)

### Backend
- ✅ API RESTful complète
- ✅ Authentification par tokens (Sanctum)
- ✅ Système de rôles et permissions
- ✅ 65 modèles Eloquent
- ✅ 85+ migrations
- ✅ CORS sécurisé
- ✅ Validation des données
- ✅ Tests unitaires et fonctionnels

## Développement

### Commandes utiles

```bash
# Backend
php artisan serve              # Démarrer le serveur
php artisan migrate            # Exécuter les migrations
php artisan db:seed            # Peupler la base
php artisan test               # Lancer les tests
php artisan route:list         # Lister les routes

# Frontend
npx serve .                    # Serveur local
npx tailwindcss -i ./input.css -o ./output.css --watch  # Dev Tailwind
```

### Structure des fichiers JS

| Fichier | Rôle |
|---------|------|
| `api.js` | Client API (auto-detect hostname) |
| `i18n.js` | Système de traduction FR/EN |
| `modern-ui.js` | Animations scroll, tilt, parallax |
| `page-transitions.js` | Transitions entre pages |
| `form-animations.js` | Validation formulaires |
| `accessibility.js` | Accessibilité, ARIA |
| `mobile-interactions.js` | Swipe, touch, bottom sheets |
| `performance.js` | Lazy loading, prefetch |
| `skeletons.js` | Placeholders de chargement |
| `favorites.js` | Gestion des favoris |
| `search.js` | Recherche |
| `sharing.js` | Partage social |
| `footer.js` | Données footer dynamiques |

## Licence

MIT

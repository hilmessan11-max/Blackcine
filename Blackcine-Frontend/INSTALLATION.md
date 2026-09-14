# Guide d'installation — BlackCiné

Ce guide explique comment installer et configurer BlackCiné en local.

## Prérequis

| Logiciel | Version minimale | Vérification |
|----------|------------------|--------------|
| PHP | 8.2+ | `php -v` |
| Composer | 2.0+ | `composer -V` |
| Node.js | 18+ (optionnel) | `node -v` |
| SQLite | 3.x | Inclus avec PHP |

## 1. Cloner le dépôt

```bash
git clone https://github.com/hilmessan11-max/Blackcine.git
cd Blackcine
```

## 2. Installer le Backend

### 2.1. Dépendances PHP

```bash
cd Blackcine-Backend
composer install
```

### 2.2. Configuration

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 2.3. Base de données

```bash
# Créer la base SQLite
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate

# Peupler avec des données de test
php artisan db:seed
```

### 2.4. Démarrer le serveur

```bash
php artisan serve
```

Le serveur démarre sur `http://127.0.0.1:8000`.

## 3. Installer le Frontend

### Option A : Ouvrir directement

Ouvrez `Blackcine-Frontend/index.html` dans votre navigateur.

### Option B : Serveur local (recommandé)

```bash
cd ../Blackcine-Frontend

# Avec Node.js
npx serve .

# Ou avec PHP
php -S localhost:8080
```

Le frontend est disponible sur `http://localhost:8080`.

## 4. Configuration API

Le frontend communique avec le backend via l'API. Assurez-vous que :

1. Le backend tourne sur `http://127.0.0.1:8000`
2. Le fichier `assets/js/api.js` pointe vers la bonne URL

Le fichier `api.js` détecte automatiquement l'hostname, donc en local ça fonctionne directement.

## 5. Comptes par défaut

Après le seed, ces comptes sont disponibles :

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | `admin@example.com` | `password` |

## 6. Variables d'environnement importantes

| Variable | Description | Valeur par défaut |
|----------|-------------|-------------------|
| `APP_DEBUG` | Mode debug | `true` (dev) / `false` (prod) |
| `APP_URL` | URL de l'application | `http://localhost` |
| `DB_CONNECTION` | Type de base | `sqlite` |
| `FRONTEND_URL` | URL du frontend (CORS) | `http://localhost:8080` |
| `SESSION_DRIVER` | Stockage des sessions | `database` |

## 7. Résolution de problèmes

### Erreur CORS

Si vous voyez une erreur CORS dans la console :

1. Vérifiez que `FRONTEND_URL` est défini dans `.env`
2. Vérifiez que le frontend tourne sur cette URL

### Erreur 404 sur l'API

1. Vérifiez que le backend tourne (`php artisan serve`)
2. Vérifiez les routes : `php artisan route:list`

### Base de données vide

```bash
php artisan migrate:fresh --seed
```

## 8. Structure des fichiers

```
Blackcine/
├── Blackcine-Backend/
│   ├── app/Http/Controllers/Api/    # Contrôleurs API
│   ├── app/Models/                  # 65 modèles
│   ├── database/migrations/         # 85+ migrations
│   ├── routes/api.php               # Routes API
│   └── .env                         # Configuration
│
├── Blackcine-Frontend/
│   ├── assets/css/                  # Styles
│   ├── assets/js/                   # Scripts (31 fichiers)
│   ├── assets/images/               # Images
│   └── *.html                       # 31 pages
│
└── README.md
```

## 9. Technologies utilisées

### Backend
- Laravel 12
- PHP 8.2+
- SQLite / MySQL
- Laravel Sanctum (auth tokens)
- Spatie Laravel Permission (rôles)

### Frontend
- HTML5 sémantique
- CSS3 (Bootstrap 5.3.7, Tailwind CSS v4, DaisyUI)
- JavaScript vanilla (ES6+)
- View Transitions API
- IntersectionObserver API

## 10. Commandes utiles

```bash
# Backend
php artisan serve                    # Serveur dev
php artisan migrate                  # Migrations
php artisan migrate:fresh --seed     # Reset + seed
php artisan test                     # Tests
php artisan route:list               # Routes
php artisan tinker                   # Console Laravel

# Frontend
npx serve .                          # Serveur local
npx prettier --write "assets/js/*.js"  # Formater JS
```

## Support

En cas de problème, ouvrez une issue sur GitHub :
https://github.com/hilmessan11-max/Blackcine/issues

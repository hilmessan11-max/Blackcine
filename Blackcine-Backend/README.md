# BlackCine - Plateforme de Cinéma Africain

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![License](https://img.shields.io/badge/License-MIT-green)

## 📖 Description

**BlackCine** est une plateforme moderne et complète dédiée au cinéma africain et de la diaspora. Elle offre un système de gestion de contenu (CMS) robuste pour la gestion de films, séries, articles, événements, et bien plus encore.

### Caractéristiques Principales

- 🎬 **Gestion de Contenu** : Films, séries, trailers, critiques
- 🎫 **Billetterie** : Système complet de réservation de tickets
- 📰 **Contenu Éditorial** : Articles, actualités, critiques professionnelles
- 🏆 **Rankings & Classements** : Palmarès, sélections thématiques
- 💰 **Monétisation** : Publicité, contenus sponsorisés, affiliation
- 📧 **Communication** : Newsletter, notifications push, templates d'emails
- 🎯 **Support** : Système de tickets, logs système
- 👥 **Gestion Utilisateurs** : Rôles et permissions avancés

---

## 🚀 Installation

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js & NPM
- Base de données (MySQL, PostgreSQL, ou SQLite)
- Extensions PHP : BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

### Installation Étape par Étape

```bash
# 1. Cloner le projet
git clone https://github.com/Yug-Su/Blackcine_Backend.git
cd blackcine-backend

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JavaScript
npm install

# 4. Copier le fichier d'environnement
cp .env.example .env

# 5. Générer la clé d'application
php artisan key:generate

# 6. Configurer la base de données dans .env
# Modifier DB_CONNECTION, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 7. Exécuter les migrations
php artisan migrate

# 8. (Optionnel) Peupler la base avec des données de test
php artisan db:seed

# 9. Compiler les assets
npm run build

# 10. Lancer le serveur de développement
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

---

## 📚 Documentation

- [Guide d'Installation Complète](docs/INSTALLATION.md)
- [Architecture du Projet](docs/ARCHITECTURE.md)
- [Modules Implémentés](docs/MODULES.md)
- [API Documentation](docs/API.md)
- [Guide de Contribution](docs/CONTRIBUTING.md)

---

## 🏗️ Architecture

### Stack Technique

- **Framework Backend** : Laravel 12.x
- **Base de données** : MySQL / PostgreSQL / SQLite
- **Style** : Tailwind CSS
- **Template Engine** : Blade
- **Authentification** : Laravel Breeze + Spatie Permissions
- **Storage** : Local / S3 (configurable)

### Structure des Dossiers

```
blackcine-backend/
├── app/
│   ├── Http/Controllers/      # Contrôleurs
│   │   ├── Admin/             # Contrôleurs du back-office
│   │   └── Api/               # Contrôleurs API
│   ├── Models/                # Modèles Eloquent
│   └── Services/              # Services métier
├── database/
│   ├── migrations/            # Migrations de base de données
│   └── seeders/               # Seeders
├── resources/
│   ├── views/                 # Vues Blade
│   │   ├── admin/             # Vues back-office
│   │   └── layouts/           # Layouts
│   └── js/                    # Assets JavaScript
├── routes/
│   ├── web.php               # Routes web publiques
│   ├── admin.php             # Routes back-office
│   └── api.php               # Routes API
└── docs/                      # Documentation
```

---

## 🎯 Modules Implémentés (9/18)

### ✅ Modules Complets

1. **Dashboard** - Tableau de bord avec statistiques
2. **Films** - Gestion complète des films + trailers
3. **Séries** - Gestion complète des séries + trailers
4. **Reviews & Rankings** - Critiques et classements
5. **Tickets** - Billetterie et réservations
6. **Publicité** - Campagnes, sponsoring, affiliation
7. **Notifications** - Email templates, push notifications
8. **Newsletter** - Gestion abonnés et campagnes
9. **Support & Logs** - Tickets de support, logs système

### 🚧 Modules en Placeholder

- Contenu éditorial
- Classiques & Patrimoine
- Modération
- Paramètres
- Rapports & Analytics
- SMS/OTP
- Sauvegardes
- Et autres...

Voir [MODULES.md](docs/MODULES.md) pour plus de détails.

---

## 👥 Rôles et Permissions

Le système utilise **Spatie Laravel Permission** avec les rôles suivants :

- **SuperAdmin** : Accès complet
- **Admin** : Gestion générale
- **Rédacteur** : Gestion de contenu
- **TicketMgr** : Gestion billetterie
- **PartnerMgr** : Gestion partenaires
- **CommunityMgr** : Gestion communauté
- **Finance** : Rapports financiers
- **Marketing** : Campagnes marketing
- **SEO** : Optimisation SEO
- **Modérateur** : Modération contenu

---

## 🔐 Sécurité

- Authentification via Laravel Breeze
- Protection CSRF sur tous les formulaires
- Validation des entrées utilisateur
- Middleware de rôles et permissions
- Hashage sécurisé des mots de passe (bcrypt)
- Protection contre les injections SQL (Eloquent ORM)

---

## 🧪 Tests

```bash
# Exécuter les tests
php artisan test

# Tests avec couverture
php artisan test --coverage

# Tests spécifiques
php artisan test --filter=FilmTest
```

---

## 📝 Changelog

### Version 1.0.0 (2025-11-24)

#### Ajoutés
- ✨ Système complet de gestion de films et séries
- ✨ Module de billetterie avec réservations
- ✨ Système de critiques et rankings
- ✨ Module de publicité et monétisation
- ✨ Système de notifications (Email + Push)
- ✨ Newsletter avec gestion d'abonnés
- ✨ Support client avec tickets
- ✨ Visualisation des logs système

#### Améliorés
- 🎨 Interface d'administration moderne avec Tailwind CSS
- ⚡ Optimisation des requêtes base de données
- 📱 Design responsive sur tous les modules

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Veuillez consulter le [Guide de Contribution](docs/CONTRIBUTING.md) pour plus de détails.

### Processus de Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

---

## 📄 Licence

Ce projet est sous licence propriétaire. Tous droits réservés.

---

## 👨‍💻 Équipe

- **Développement Backend** : [Votre Nom]
- **Design UI/UX** : [Designer]
- **Product Owner** : [PO]

---

## 📞 Support

Pour toute question ou problème :

- 📧 Email : support@blackcine.com
- 🌐 Site Web : https://blackcine.com
- 💬 Discord : [Lien Discord]

---

## 🙏 Remerciements

- Laravel Team pour le framework exceptionnel
- Spatie pour les packages de qualité
- La communauté open source

---

**Fait avec ❤️ pour le cinéma africain**

# Guide d'Installation - BlackCine Backend

Ce guide vous accompagne pas à pas dans l'installation et la configuration du back-office BlackCine.

---

## 📋 Prérequis

### Logiciels Requis

- **PHP** : Version 8.2 ou supérieure
- **Composer** : Gestionnaire de dépendances PHP
- **Node.js** : Version 18.x ou supérieure
- **NPM** ou **Yarn** : Gestionnaire de packages JavaScript
- **Base de données** : MySQL 8.0+, PostgreSQL 13+, ou SQLite 3.x
- **Serveur Web** : Apache, Nginx, ou serveur intégré PHP

### Extensions PHP Requises

Vérifiez que ces extensions sont activées :

```bash
php -m | grep -E 'BCMath|Ctype|Fileinfo|JSON|Mbstring|OpenSSL|PDO|Tokenizer|XML'
```

Extensions nécessaires :
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- PDO_MySQL (ou PDO_PGSQL pour PostgreSQL)
- Tokenizer
- XML
- GD ou Imagick (pour manipulation d'images)

---

## 🚀 Installation

### Étape 1 : Récupération du Code

#### Option A : Cloner depuis Git

```bash
git clone https://github.com/Yug-Su/Blackcine_Backend.git
cd blackcine-backend
```

#### Option B : Télécharger l'archive

```bash
# Extraire l'archive
unzip blackcine-backend.zip
cd blackcine-backend
```

---

### Étape 2 : Installation des Dépendances

#### Dépendances PHP (Composer)

```bash
composer install
```

**Note** : Pour un environnement de production, utilisez :
```bash
composer install --no-dev --optimize-autoloader
```

#### Dépendances JavaScript (NPM)

```bash
npm install
```

Ou avec Yarn :
```bash
yarn install
```

---

### Étape 3 : Configuration de l'Environnement

#### Créer le fichier .env

```bash
cp .env.example .env
```

#### Générer la clé d'application

```bash
php artisan key:generate
```

#### Configurer les Variables d'Environnement

Éditez le fichier `.env` :

```env
# Application
APP_NAME="BlackCine"
APP_ENV=local  # local, staging, production
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=true  # false en production
APP_TIMEZONE=Africa/Dakar
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=mysql  # mysql, pgsql, sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blackcine
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# Cache & Session
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue
QUEUE_CONNECTION=database

# Mail (pour notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@blackcine.com
MAIL_FROM_NAME="${APP_NAME}"

# Filesystem
FILESYSTEM_DISK=local  # local, s3

# AWS S3 (si utilisé)
# AWS_ACCESS_KEY_ID=
# AWS_SECRET_ACCESS_KEY=
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=
# AWS_USE_PATH_STYLE_ENDPOINT=false
```

---

### Étape 4 : Configuration de la Base de Données

#### Option A : MySQL

1. Créer la base de données :

```sql
CREATE DATABASE blackcine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Créer un utilisateur (optionnel) :

```sql
CREATE USER 'blackcine_user'@'localhost' IDENTIFIED BY 'mot_de_passe_fort';
GRANT ALL PRIVILEGES ON blackcine.* TO 'blackcine_user'@'localhost';
FLUSH PRIVILEGES;
```

3. Mettre à jour `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blackcine
DB_USERNAME=blackcine_user
DB_PASSWORD=mot_de_passe_fort
```

#### Option B : PostgreSQL

1. Créer la base de données :

```sql
CREATE DATABASE blackcine WITH ENCODING 'UTF8';
```

2. Mettre à jour `.env` :

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=blackcine
DB_USERNAME=postgres
DB_PASSWORD=votre_password
```

#### Option C : SQLite (Développement uniquement)

1. Créer le fichier de base de données :

```bash
touch database/database.sqlite
```

2. Mettre à jour `.env` :

```env
DB_CONNECTION=sqlite
# Pas besoin des autres paramètres DB
```

---

### Étape 5 : Exécution des Migrations

#### Migrer la Structure de Base de Données

```bash
php artisan migrate
```

**Résultat attendu** :
```
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (XX.XX ms)
Migrating: 2024_XX_XX_XXXXXX_create_films_table
Migrated:  2024_XX_XX_XXXXXX_create_films_table (XX.XX ms)
...
```

#### En cas d'erreur

Si vous devez recommencer :
```bash
php artisan migrate:refresh  # Attention: Efface toutes les données!
```

Ou, pour tout réinitialiser :
```bash
php artisan migrate:fresh  # Supprime toutes les tables et recrée
```

---

### Étape 6 : Peuplement de la Base de Données (Optionnel)

#### Créer des Données de Test

```bash
php artisan db:seed
```

Ou, pour un seeder spécifique :
```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=RoleSeeder
```

#### Créer un Super Administrateur

```bash
php artisan tinker
```

Puis dans la console Tinker :

```php
$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@blackcine.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);

$user->assignRole('SuperAdmin');
exit
```

---

### Étape 7 : Installation de Spatie Permissions

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

#### Créer les Rôles de Base

Créer un seeder `RoleSeeder.php` :

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'SuperAdmin',
            'Admin',
            'Rédacteur',
            'TicketMgr',
            'PartnerMgr',
            'CommunityMgr',
            'Finance',
            'Marketing',
            'SEO',
            'Modérateur',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
```

Exécuter le seeder :
```bash
php artisan db:seed --class=RoleSeeder
```

---

### Étape 8 : Compilation des Assets

#### Développement

```bash
npm run dev
```

Ou en mode watch (recompile automatiquement) :
```bash
npm run watch
```

#### Production

```bash
npm run build
```

---

### Étape 9 : Configuration du Storage

#### Créer les Liens Symboliques

```bash
php artisan storage:link
```

Cela crée un lien de `public/storage` vers `storage/app/public`.

#### Vérifier les Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

---

### Étape 10 : Lancement du Serveur

#### Serveur de Développement (Laravel)

```bash
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

#### Avec un Port Spécifique

```bash
php artisan serve --port=8080
```

#### Serveur de Production

Pour la production, configurez Apache ou Nginx.

**Exemple Nginx** :

```nginx
server {
    listen 80;
    server_name blackcine.com www.blackcine.com;
    root /var/www/blackcine-backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## ✅ Vérification de l'Installation

### Checklist Post-Installation

- [ ] L'application charge sur `http://localhost:8000`
- [ ] La page de login est accessible
- [ ] La connexion avec le super admin fonctionne
- [ ] Le dashboard s'affiche correctement
- [ ] Les menus du back-office sont visibles
- [ ] Les modules Films et Séries fonctionnent

### Commandes de Vérification

```bash
# Vérifier la version de Laravel
php artisan --version

# Lister toutes les routes
php artisan route:list

# Vérifier la configuration
php artisan config:show

# Tester la connexion DB
php artisan db:show
```

---

## 🐛 Résolution de Problèmes

### Erreur : "No application encryption key"

```bash
php artisan key:generate
```

### Erreur : "Class 'X' not found"

```bash
composer dump-autoload
php artisan clear-compiled
php artisan cache:clear
```

### Erreur : Permission denied (storage/logs)

```bash
sudo chmod -R 775 storage
sudo chown -R www-data:www-data storage
```

### Erreur : SQLSTATE[HY000] [2002] Connection refused

Vérifier que :
- MySQL/PostgreSQL est en cours d'exécution
- Les identifiants dans `.env` sont corrects
- Le port est correct (3306 pour MySQL, 5432 pour PostgreSQL)

```bash
# Démarrer MySQL
sudo service mysql start

# Ou PostgreSQL
sudo service postgresql start
```

### Assets non compilés

```bash
npm run build
php artisan view:clear
```

---

## 🔐 Sécurité Post-Installation

### Production Checklist

- [ ] `APP_DEBUG=false` dans `.env`
- [ ] `APP_ENV=production` dans `.env`
- [ ] HTTPS activé (certificat SSL)
- [ ] Firewall configuré
- [ ] Clés API sécurisées
- [ ] Backups automatisés
- [ ] Logs de sécurité activés

### Commandes de Cache (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Pour effacer le cache :
```bash
php artisan optimize:clear
```

---

## 📝 Prochaines Étapes

1. **Créer des utilisateurs** : Ajouter les membres de l'équipe
2. **Assigner des rôles** : Définir les permissions
3. **Importer du contenu** : Ajouter films, séries, articles
4. **Configurer les emails** : SMTP pour notifications
5. **Tester les fonctionnalités** : Vérifier chaque module

---

## 📞 Support

En cas de problème d'installation :

- 📧 Email : dev@blackcine.com
- 📚 Documentation : https://docs.blackcine.com
- 🐛 Issues : https://github.com/Yug-Su/Blackcine/issues

---

**Installation réussie !** 🎉

Vous pouvez maintenant accéder au back-office et commencer à gérer votre contenu.

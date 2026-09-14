# BlackCiné — Guide de production

Ce guide explique comment déployer BlackCiné en production.

## Prérequis serveur

| Logiciel | Version |
|----------|---------|
| PHP | 8.2+ |
| MySQL/MariaDB | 8.0+ |
| Nginx ou Apache | Dernière stable |
| Node.js | 18+ (pour le build) |
| Composer | 2.0+ |

## 1. Déploiement Backend

### 1.1. Cloner et installer

```bash
cd /var/www
git clone https://github.com/hilmessan11-max/Blackcine.git blackcine
cd blackcine/Blackcine-Backend

composer install --optimize-autoloader --no-dev
```

### 1.2. Configuration

```bash
cp .env.example .env

# Générer la clé
php artisan key:generate
```

### 1.3. Éditer .env

```ini
APP_NAME=BlackCiné
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.blackcine.com

FRONTEND_URL=https://blackcine.com
FRONTEND_URL_ALT=https://www.blackcine.com
SANCTUM_STATEFUL_DOMAINS=blackcine.com,www.blackcine.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blackcine
DB_USERNAME=blackcine_user
DB_PASSWORD=VotreMotDePasseSecurise

# Sessions sécurisées en HTTPS
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.blackcine.com

# Cache : redis recommandé (supporte les tags du cache home),
# database fonctionne aussi (fallback sans tags, cf. HomeController)
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
QUEUE_CONNECTION=database

# TMDB — UNIQUEMENT côté serveur (jamais exposé au frontend,
# le proxy /api/v1/tmdb signe les appels)
TMDB_BEARER_TOKEN=votre_token_tmdb
# TMDB_API_KEY=alternative_si_pas_de_bearer

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre@gmail.com
MAIL_PASSWORD=votre_mdp_app
MAIL_ENCRYPTION=tls
```

### 1.4. Base de données

```bash
php artisan migrate --force
# La migration 2026_09_13_000002 crée les index FULLTEXT MySQL
# (titles, articles) + index simples — ignorée sur SQLite.
php artisan db:seed --force
# Indispensable : seeders des rôles/permissions (Spatie RBAC).
# Relancer uniquement RolesAndPermissionsSeeder si déjà seedé :
# php artisan db:seed --class=RolesAndPermissionsSeeder --force

# Lien storage (posters, avatars, logos via asset('storage/...'))
php artisan storage:link
```

### 1.5. Cache

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 1.6. Permissions

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 2. Déploiement Frontend

Le frontend est dans le même repo (`Blackcine-Frontend/`), déjà cloné à l'étape 1.

### 2.1. Optimiser

```bash
cd /var/www/blackcine/Blackcine-Frontend
npm install
npm run optimize
```

> ⚠️ `npm run build` **régénère** `assets/css/tailwind.css` (fichier généré,
> non versionné) depuis `assets/css/input.css`. Les surcharges custom
> (DaisyUI, animations, scroll, utilitaires) vivent dans
> `assets/css/modules/tailwind-*.css` : après un rebuild, vérifier que
> le CSS généré les inclut toujours, sinon ré-appliquer.
> Les autres CSS (`responsive`, `design-system`, `sections`, …) sont des
> manifestes `@import` vers `assets/css/modules/` — ne pas les remplacer
> par des builds.

## 3. Configuration Nginx

```nginx
server {
    listen 80;
    server_name blackcine.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name blackcine.com;

    root /var/www/blackcine/Blackcine-Frontend;
    index index.html;

    # SSL
    ssl_certificate /etc/letsencrypt/live/blackcine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/blackcine.com/privkey.pem;

    # Cache browser
    location ~* \.(css|js)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }

    location ~* \.(jpg|jpeg|png|gif|webp|svg|ico|woff|woff2|ttf)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Sécurité (identique à nginx.conf du repo)
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml image/svg+xml;
    gzip_min_length 256;

    location / {
        try_files $uri $uri/ /index.html;
    }
}

server {
    listen 443 ssl http2;
    server_name api.blackcine.com;

    root /var/www/blackcine/Blackcine-Backend/public;
    index index.php;

    # SSL
    ssl_certificate /etc/letsencrypt/live/api.blackcine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.blackcine.com/privkey.pem;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known) {
        deny all;
    }
}
```

## 4. SSL Let's Encrypt

```bash
sudo certbot --nginx -d blackcine.com -d api.blackcine.com
```

## 5. Queue Worker

```bash
# Créer un service systemd
sudo nano /etc/systemd/system/blackcine-worker.service
```

```ini
[Unit]
Description=BlackCiné Queue Worker
After=network.target

[Service]
User=www-data
WorkingDirectory=/var/www/blackcine/Blackcine-Backend
ExecStart=/usr/bin/php artisan queue:work --sleep=3 --tries=3
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable blackcine-worker
sudo systemctl start blackcine-worker
```

## 6. Monitoring

```bash
# Vérifier les logs
tail -f /var/www/blackcine/Blackcine-Backend/storage/logs/laravel.log

# Vérifier les queues
php artisan queue:failed

# Rejeter les jobs échoués
php artisan queue:retry all
```

## 6b. Smoke tests post-déploiement

```bash
API=https://api.blackcine.com/api/v1
curl -sf $API/home | head -c 200; echo
curl -sf $API/footer | head -c 200; echo
curl -sf "$API/search?q=film&type=films" | head -c 200; echo
curl -sf $API/tmdb/image-config; echo
# Proxy TMDB : 403 attendu hors whitelist, jamais de clé exposée
curl -s -o /dev/null -w "%{http_code}\n" $API/tmdb/account/123
```

## 7. Mises à jour

```bash
# Un seul repo (monorepo)
cd /var/www/blackcine
git pull

# Backend
cd Blackcine-Backend
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# Frontend (voir avertissement tailwind §2.1)
cd ../Blackcine-Frontend
npm run optimize

# Redémarrer les services
sudo systemctl restart nginx
sudo systemctl restart blackcine-worker
```

## 8. Sauvegarde

```bash
# Base de données
mysqldump -u blackcine_user -p blackcine | gzip > backup_$(date +%Y%m%d).sql.gz

# Fichiers (storage backend + images frontend)
tar -czf backup_$(date +%Y%m%d).tar.gz /var/www/blackcine/Blackcine-Backend/storage /var/www/blackcine/Blackcine-Frontend/assets/images
```

## 9. Sécurité

- [ ] `APP_DEBUG=false` et `APP_KEY` générée (`php artisan key:generate`)
- [ ] HTTPS forcé + `SESSION_SECURE_COOKIE=true`
- [ ] CORS restreint à `FRONTEND_URL` (+ `SANCTUM_STATEFUL_DOMAINS`)
- [ ] Rate limiting activé (`api`, `auth`, `newsletter`, `tmdb`)
- [ ] Proxy TMDB : clé/bearer uniquement côté serveur, whitelist d'endpoints
- [ ] Recherche : `limit max:50`, LIKE échappés (`whereLike` + `ESCAPE`)
- [ ] Headers de sécurité (cf. §3, identiques à `nginx.conf`)
- [ ] `.env` jamais commité, `storage/` permissions `www-data`
- [ ] Sauvegardes automatiques
- [ ] Logs monitoring

## 10. Performance

- [ ] OPcache activé
- [ ] Redis pour cache (optionnel)
- [ ] CDN pour les images
- [ ] Minification CSS/JS
- [ ] Compression GZIP
- [ ] Cache browser configuré

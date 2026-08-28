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
git clone https://github.com/Yug-Su/Blackcine_Backend.git blackcine-api
cd blackcine-api

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

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blackcine
DB_USERNAME=blackcine_user
DB_PASSWORD=VotreMotDePasseSecurise

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

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
php artisan db:seed --force
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

### 2.1. Cloner

```bash
cd /var/www
git clone https://github.com/Yug-Su/Blackcine_Frontend.git blackcine
cd blackcine
```

### 2.2. Optimiser

```bash
npm install
npm run optimize
```

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

    root /var/www/blackcine;
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

    # Sécurité
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;

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

    root /var/www/blackcine-api/public;
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
WorkingDirectory=/var/www/blackcine-api
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
tail -f /var/www/blackcine-api/storage/logs/laravel.log

# Vérifier les queues
php artisan queue:failed

# Rejeter les jobs échoués
php artisan queue:retry all
```

## 7. Mises à jour

```bash
# Backend
cd /var/www/blackcine-api
git pull
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# Frontend
cd /var/www/blackcine
git pull
npm run optimize

# Redémarrer les services
sudo systemctl restart nginx
sudo systemctl restart blackcine-worker
```

## 8. Sauvegarde

```bash
# Base de données
mysqldump -u blackcine_user -p blackcine | gzip > backup_$(date +%Y%m%d).sql.gz

# Fichiers
tar -czf backup_$(date +%Y%m%d).tar.gz /var/www/blackcine-api/storage /var/www/blackcine/assets/images
```

## 9. Sécurité

- [ ] APP_DEBUG=false
- [ ] HTTPS forcé
- [ ] CORS configuré
- [ ] Rate limiting activé
- [ ] Headers de sécurité
- [ ] Sauvegardes automatiques
- [ ] Logs monitoring

## 10. Performance

- [ ] OPcache activé
- [ ] Redis pour cache (optionnel)
- [ ] CDN pour les images
- [ ] Minification CSS/JS
- [ ] Compression GZIP
- [ ] Cache browser configuré

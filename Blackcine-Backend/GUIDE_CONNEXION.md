# Guide de Connexion - BlackCiné Back-Office

## 🔐 Comment se connecter au Back-Office

### Étape 1 : Préparer la base de données

Assurez-vous que la base de données est configurée et que les migrations sont exécutées :

```bash
# Dans le dossier blackcine-backend
php artisan migrate
```

### Étape 2 : Créer un utilisateur administrateur

Exécutez le seeder pour créer un utilisateur SuperAdmin par défaut :

```bash
php artisan db:seed
```

Cela créera automatiquement :
- **Email** : `admin@example.com`
- **Mot de passe** : `password`
- **Rôle** : SuperAdmin (accès total)

### Étape 3 : Accéder à la page de connexion

1. Démarrez le serveur Laravel :
```bash
php artisan serve
```

2. Ouvrez votre navigateur et allez à :
```
http://localhost:8000/login
```

ou

```
http://127.0.0.1:8000/login
```

### Étape 4 : Se connecter

Utilisez les identifiants par défaut :
- **Email** : `admin@example.com`
- **Mot de passe** : `password`

Après connexion, vous serez redirigé vers le Dashboard (`/admin/dashboard`).

---

## 🔑 Créer un autre utilisateur administrateur

### Option 1 : Via l'interface (si disponible)
Allez sur `/register` pour créer un nouveau compte, puis assignez-lui un rôle via la console.

### Option 2 : Via Tinker (Console Laravel)

```bash
php artisan tinker
```

Puis dans Tinker :
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

// Créer l'utilisateur
$user = User::create([
    'name' => 'Votre Nom',
    'email' => 'votre@email.com',
    'password' => Hash::make('votre_mot_de_passe'),
    'email_verified_at' => now(),
]);

// Assigner le rôle SuperAdmin
$user->assignRole('SuperAdmin');
```

### Option 3 : Créer un Seeder personnalisé

Créez un fichier `database/seeders/AdminUserSeeder.php` :

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Votre Nom',
            'email' => 'votre@email.com',
            'password' => Hash::make('votre_mot_de_passe'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('SuperAdmin');
    }
}
```

Puis exécutez :
```bash
php artisan db:seed --class=AdminUserSeeder
```

---

## 👥 Rôles disponibles

Le système dispose de plusieurs rôles avec des permissions différentes :

- **SuperAdmin** : Accès total à tous les modules
- **Admin** : Gestion utilisateurs, articles, finances
- **Rédacteur** : Édition d'articles
- **Modérateur** : Gestion de la communauté
- **TicketMgr** : Gestion des tickets
- **PartnerMgr** : Gestion des partenaires
- **Finance** : Consultation des finances
- **CommunityMgr** : Gestion complète de la communauté
- **SEO** : Gestion SEO
- **Marketing** : Gestion marketing

---

## 🚨 Problèmes courants

### Erreur "Route [login] not defined"
Assurez-vous que les routes auth sont bien chargées dans `RouteServiceProvider`.

### Erreur "Class 'Spatie\Permission\Models\Role' not found"
Installez le package Spatie Permission :
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Erreur de connexion
Vérifiez que :
1. La base de données est bien configurée dans `.env`
2. Les migrations sont exécutées : `php artisan migrate`
3. Le seeder a été exécuté : `php artisan db:seed`

### Mot de passe oublié
Pour réinitialiser le mot de passe d'un utilisateur via Tinker :
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@example.com')->first();
$user->password = Hash::make('nouveau_mot_de_passe');
$user->save();
```

---

## 📝 Notes importantes

⚠️ **Sécurité** : Changez le mot de passe par défaut après la première connexion !

Le mot de passe par défaut `password` est uniquement pour le développement. En production, utilisez un mot de passe fort.

---

## 🔗 URLs importantes

- **Connexion** : `/login`
- **Inscription** : `/register`
- **Dashboard** : `/admin/dashboard`
- **Déconnexion** : Via le bouton dans la sidebar (en bas)

---

## ✅ Vérification rapide

Pour vérifier que tout fonctionne :

1. ✅ Base de données migrée : `php artisan migrate:status`
2. ✅ Utilisateur créé : `php artisan tinker` puis `User::count()`
3. ✅ Rôles créés : `php artisan tinker` puis `Role::count()`
4. ✅ Serveur démarré : `php artisan serve`
5. ✅ Page accessible : Ouvrir `http://localhost:8000/login`


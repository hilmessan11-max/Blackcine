# Documentation des Modules - BlackCine

Cette documentation détaille tous les modules implémentés dans le back-office BlackCine.

---

## 📊 Vue d'Ensemble

**Progression** : 9/18 modules complets (50%)

### Statut des Modules

- ✅ **Complet** : Module entièrement fonctionnel avec CRUD, vues, et logique métier
- 🚧 **Placeholder** : Route définie mais vue de placeholder

---

## ✅ Modules Complets

### 1. Dashboard (Tableau de Bord)

**Route** : `/dashboard`  
**Contrôleur** : `AdminController`

#### Fonctionnalités
- Vue d'ensemble des statistiques clés
- Graphiques de revenus (7 derniers jours)
- Top 5 des titres vendus
- Articles les plus vus
- Vidéos les plus regardées
- Utilisateurs récents
- Commandes récentes

#### Statistiques Affichées
- Nombre total d'utilisateurs
- Nombre total d'articles
- Nombre total de vidéos
- Revenus du mois en cours
- Tickets vendus
- Articles publiés

---

### 2. Films

**Route** : `/admin/films`  
**Contrôleur** : `FilmController`

#### Fonctionnalités
- ✅ CRUD complet (Create, Read, Update, Delete)
- ✅ Gestion des trailers
- ✅ Upload d'images
- ✅ Gestion des genres (JSON)
- ✅ Recherche et filtres
- ✅ Pagination

#### Champs Principaux
- `title` : Titre du film
- `slug` : URL-friendly identifier
- `synopsis` : Description
- `director` : Réalisateur
- `release_year` : Année de sortie
- `duration` : Durée (en minutes)
- `genres` : Genres (array JSON)
- `category` : Catégorie (film africain, diaspora, etc.)
- `poster_url` : Affiche
- `trailer_url` : Bande-annonce

#### Vues
- `index.blade.php` : Liste des films
- `create.blade.php` : Formulaire de création
- `edit.blade.php` : Formulaire d'édition
- `trailers.blade.php` : Gestion des trailers

---

### 3. Séries

**Route** : `/admin/series-mgmt`  
**Contrôleur** : `SeriesController`

#### Fonctionnalités
- ✅ CRUD complet
- ✅ Gestion des saisons et épisodes
- ✅ Gestion des trailers
- ✅ Système similaire aux films

#### Champs Spécifiques
- Tous les champs des films +
- `seasons` : Nombre de saisons
- `total_episodes` : Nombre total d'épisodes
- `status` : En cours / Terminée

---

### 4. Reviews & Rankings (Critiques & Classements)

**Routes** : `/admin/titles/reviews/*` et `/admin/rankings/*`  
**Contrôleurs** : `ReviewController`, `RankingController`

#### 4.1 Reviews (Critiques)

##### Critiques Utilisateurs
- Modération des avis postés par les utilisateurs
- Approbation / Rejet
- Suppression
- Statistiques (nombre d'avis, note moyenne)

##### Critiques Éditoriales
- Créées par l'équipe
- Note professionnelle (0-10)
- Auteur (membre de l'équipe)
- Publication / Brouillon

**Modèle** : `Review`
```php
- title_id : ID du film/série
- user_id : Auteur
- rating : Note (1-5 pour users, 0-10 pour editorial)
- comment : Texte de la critique
- is_editorial : Boolean
- is_approved : Boolean (pour modération)
```

#### 4.2 Rankings (Classements)

##### Fonctionnalités
- Création de palmarès thématiques
- Ajout/suppression de titres
- Gestion de l'ordre (position)
- Publication / Brouillon
- Visibilité publique

**Modèle** : `Ranking`
```php
- name : Nom du classement
- description : Description
- is_published : Public ou privé
```

**Modèle Pivot** : `RankingTitle`
```php
- ranking_id
- title_id
- position : Ordre dans le classement
```

---

### 5. Tickets (Billetterie)

**Route** : `/admin/boxoffice/tickets`  
**Contrôleur** : `TicketController`

#### Fonctionnalités
- ✅ Liste des tickets vendus
- ✅ Création manuelle de tickets
- ✅ Statistiques (total, en attente, confirmés, annulés)
- ✅ Résolution de tickets (changement de statut)
- ✅ Liaison avec séances (showtimes)

#### Statuts
- `pending` : En attente
- `confirmed` : Confirmé
- `cancelled` : Annulé
- `refunded` : Remboursé

#### Champs Principaux
```php
- order_id : Commande associée
- showtime_id : Séance
- holder_name : Nom du détenteur
- seat_label : Siège (ex: A12)
- price_cents : Prix en centimes
- qr_code : Code QR unique
- status
```

---

### 6. Publicité & Monétisation

**Route** : `/admin/advertising/*`  
**Contrôleurs** : `AdvertisingController`, `SponsoredContentController`, `AffiliateController`

#### 6.1 Campagnes Publicitaires

**Fonctionnalités**
- Création de campagnes pub (bannières, vidéos, etc.)
- Planification (dates début/fin)
- Gestion du budget
- Tracking (impressions, clics)
- Statuts : draft, active, paused, ended

**Types de Pub**
- `banner` : Bannière
- `video` : Vidéo pré-roll
- `sponsored_article` : Article sponsorisé

**Emplacements**
- `home_top` : Haut de page
- `sidebar` : Barre latérale
- `footer` : Pied de page
- `video_preroll` : Avant vidéo
- `article_inline` : Dans l'article

#### 6.2 Contenus Sponsorisés

**Fonctionnalités**
- Gestion des partenariats
- Relation polymorphique avec contenu (films, articles)
- Tracking pixel
- Mention légale obligatoire

#### 6.3 Programmes d'Affiliation

**Fonctionnalités**
- Gestion des programmes d'affiliation
- Génération de liens trackés
- Commission rate
- Statistiques (clics, revenus)

---

### 7. Notifications & Communication

**Route** : `/admin/notifications/*`  
**Contrôleurs** : `EmailTemplateController`, `PushNotificationController`

#### 7.1 Email Templates (Modèles d'E-mails)

**Fonctionnalités**
- Création de templates réutilisables
- Variables dynamiques (ex: `{{ name }}`, `{{ url }}`)
- Clé système unique (ex: `welcome_email`)
- Activation/Désactivation

**Modèle** : `EmailTemplate`
```php
- key : Identifiant système
- name : Nom du template
- subject : Sujet
- body : Contenu HTML
- variables : Array JSON
- is_active : Boolean
```

#### 7.2 Push Notifications

**Fonctionnalités**
- Envoi de notifications push
- Ciblage audience (tous, abonnés, gratuits, spécifiques)
- Planification ou envoi immédiat
- Tracking (ouvertures, clics, bounces)

**Modèle** : `PushNotification`
```php
- title
- message
- image_url
- target_url
- target_audience
- scheduled_at
- sent_at
- status : draft, scheduled, processing, sent, failed
- success_count, failure_count
```

---

### 8. Newsletter

**Route** : `/admin/newsletter/*`  
**Contrôleurs** : `NewsletterSubscriberController`, `NewsletterCampaignController`

#### 8.1 Abonnés

**Fonctionnalités**
- Gestion des abonnés newsletter
- Import/Export (prévu)
- Segmentation (actifs/inactifs)
- Token de désabonnement unique

**Modèle** : `NewsletterSubscriber`
```php
- email
- first_name, last_name
- is_active
- source : website, import, api, manual
- preferences : JSON
- unsubscribe_token
- subscribed_at
- unsubscribed_at
```

#### 8.2 Campagnes

**Fonctionnalités**
- Création de campagnes email
- Éditeur HTML
- Planification
- Envoi immédiat
- Statistiques (taux d'ouverture, clics)

**Modèle** : `NewsletterCampaign`
```php
- name
- subject
- content : HTML
- from_name, from_email
- status : draft, scheduled, sending, sent, failed
- scheduled_at, sent_at
- total_recipients
- sent_count, opened_count, clicked_count
- bounced_count, unsubscribed_count
```

**Accesseurs**
- `open_rate` : Taux d'ouverture (%)
- `click_rate` : Taux de clic (%)

---

### 9. Support & Logs

**Route** : `/admin/support/*`  
**Contrôleurs** : `SupportTicketController`, `SystemLogController`

#### 9.1 Tickets de Support

**Fonctionnalités**
- Gestion des demandes d'aide
- Numéro de ticket unique (TKT-XXXXX)
- Assignment aux admins
- Priorités et catégories
- Notes de résolution

**Modèle** : `SupportTicket`
```php
- ticket_number : Unique (auto-généré)
- user_id : Utilisateur demandeur
- subject
- description
- category : technical, billing, account, content, other
- priority : low, medium, high, urgent
- status : open, in_progress, waiting_user, resolved, closed
- assigned_to : Admin assigné
- resolved_at
- resolution_note
```

**Helpers**
- `priority_color` : Couleur badge priorité
- `status_color` : Couleur badge statut

#### 9.2 Logs Système

**Fonctionnalités**
- Visualisation des fichiers log Laravel
- Affichage des 100 dernières lignes
- Téléchargement du fichier
- Effacement des logs
- Interface style terminal (fond noir, texte vert)

**Route** : `storage/logs/laravel.log`

**Actions**
- `index` : Affichage
- `download` : Télécharger
- `clear` : Effacer

---

## 🚧 Modules Placeholder (9/18)

Ces modules ont des routes définies mais pointent vers `AdminPlaceholderController`.

### Liste des Modules Placeholder

1. **Contenu Éditorial** (`/admin/content/*`)
   - Articles
   - Actualités
   - Critiques professionnelles

2. **Classiques & Patrimoine** (`/admin/heritage/*`)
   - Films classiques
   - Restaurations
   - Archives

3. **Modération** (`/admin/moderation/*`)
   - File de modération
   - Logs d'activité
   - Sécurité

4. **Paramètres** (`/admin/settings/*`)
   - Généraux
   - Paiement
   - Billetterie
   - Rôles & Permissions

5. **Rapports & Analytics** (`/admin/reports/*`)
   - Rapports d'activité
   - Exports de données

6. **SMS/OTP** (`/admin/notifications/sms`)
   - Envoi de SMS
   - Codes OTP

7. **Sauvegardes** (`/admin/support/backups`)
   - Gestion des backups

8. **Catalogue** (certaines sections)
   - Import/Export
   - Gestion avancée

9. **Communauté** (certaines sections)
   - Castings
   - Projets
   - Concours

---

## 📋 Prochaines Implémentations Suggérées

### Priorité Haute
1. **Paramètres Généraux** : Configuration site, paiement, etc.
2. **Contenu Éditorial** : Articles et actualités
3. **Rapports & Analytics** : Tableaux de bord avancés

### Priorité Moyenne
4. **Modération** : Outils de modération avancés
5. **Sauvegardes** : Système de backup automatique

### Priorité Basse
6. **SMS/OTP** : Intégration service SMS
7. **Classiques & Patrimoine** : Module spécialisé

---

## 🔗 Relations entre Modules

```
Users ──────┬── Reviews (Critiques utilisateurs)
            ├── Orders (Commandes)
            ├── Tickets (Tickets achetés)
            ├── SupportTickets (Demandes support)
            └── NewsletterSubscribers (Abonnements)

Films/Series ──┬── Reviews (Critiques)
               ├── Rankings (Classements)
               ├── Trailers (Bandes-annonces)
               └── SponsoredContent (Contenus sponsorisés)

Campaigns ──────┬── AdvertisingCampaigns
                ├── NewsletterCampaigns
                └── PushNotifications
```

---

## 🎨 Conventions de Code

### Nommage
- **Contrôleurs** : `{Nom}Controller` (ex: `FilmController`)
- **Modèles** : Singulier, PascalCase (ex: `Film`, `SupportTicket`)
- **Tables** : Pluriel, snake_case (ex: `films`, `support_tickets`)
- **Routes** : kebab-case (ex: `/admin/support-tickets`)
- **Vues** : kebab-case (ex: `index.blade.php`)

### Structure de Contrôleur Standard
```php
class XxxController extends Controller
{
    public function index()      // Liste
    public function create()     // Formulaire création
    public function store()      // Enregistrement
    public function show($id)    // Détail
    public function edit($id)    // Formulaire édition
    public function update($id)  // Mise à jour
    public function destroy($id) // Suppression
}
```

---

## 📞 Support Développement

Pour toute question sur les modules :
- Consulter le code source dans `app/Http/Controllers/Admin/`
- Vérifier les migrations dans `database/migrations/`
- Examiner les modèles dans `app/Models/`

---

**Dernière mise à jour** : 24 novembre 2025

# Guide de Contribution - BlackCine

Merci de votre intérêt pour contribuer à BlackCine ! Ce guide vous explique comment participer au développement du projet.

---

## 🤝 Comment Contribuer ?

Il existe plusieurs façons de contribuer au projet :

- 🐛 **Signaler des bugs**
- ✨ **Proposer de nouvelles fonctionnalités**
- 📝 **Améliorer la documentation**
- 💻 **Soumettre du code**
- 🧪 **Écrire des tests**
- 🎨 **Améliorer le design**

---

## 📝 Processus de Contribution

### 1. Fork du Projet

1. Forkez le repository sur GitHub
2. Clonez votre fork localement :

```bash
git clone https://github.com/VOTRE-USERNAME/blackcine-backend.git
cd blackcine-backend
```

3. Ajoutez le repository original comme remote :

```bash
git remote add upstream https://github.com/organisation/blackcine-backend.git
```

### 2. Créer une Branche

Créez une branche pour votre contribution :

```bash
git checkout -b feature/ma-nouvelle-fonctionnalite
```

**Convention de nommage des branches** :
- `feature/nom-fonctionnalite` : Nouvelle fonctionnalité
- `bugfix/nom-bug` : Correction de bug
- `hotfix/nom-urgence` : Correction urgente
- `docs/sujet` : Documentation
- `refactor/nom` : Refactoring

### 3. Développer

Faites vos modifications en suivant nos standards de code (voir ci-dessous).

### 4. Tester

Assurez-vous que vos modifications fonctionnent :

```bash
# Tests unitaires
php artisan test

# Tests spécifiques
php artisan test --filter=VotreTest
```

### 5. Commit

Commitez vos changements avec un message clair :

```bash
git add .
git commit -m "feat: ajout de la fonctionnalité X"
```

**Convention de commit** (Conventional Commits) :
- `feat:` : Nouvelle fonctionnalité
- `fix:` : Correction de bug
- `docs:` : Documentation
- `style:` : Formatage, point-virgules manquants, etc.
- `refactor:` : Refactoring de code
- `test:` : Ajout de tests
- `chore:` : Maintenance

### 6. Push

Poussez votre branche vers votre fork :

```bash
git push origin feature/ma-nouvelle-fonctionnalite
```

### 7. Pull Request

1. Allez sur GitHub
2. Créez une Pull Request depuis votre branche vers `main`
3. Remplissez le template de PR
4. Attendez la review

---

## 📋 Standards de Code

### Conventions PHP

#### PSR-12 Coding Standard

Suivez le standard PSR-12 :

```php
<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index()
    {
        $films = Film::paginate(20);
        
        return view('admin.films.index', compact('films'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:films',
        ]);
        
        $film = Film::create($validated);
        
        return redirect()
            ->route('admin.films.index')
            ->with('success', 'Film créé avec succès.');
    }
}
```

#### Nommage

- **Classes** : PascalCase (`FilmController`, `NewsletterSubscriber`)
- **Méthodes** : camelCase (`index()`, `storeFilm()`)
- **Variables** : camelCase (`$filmData`, `$subscriberEmail`)
- **Constantes** : UPPER_SNAKE_CASE (`MAX_UPLOAD_SIZE`)
- **Tables** : snake_case pluriel (`films`, `newsletter_subscribers`)

#### DocBlocks

Documentez vos fonctions complexes :

```php
/**
 * Créer une nouvelle campagne newsletter
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function store(Request $request)
{
    // ...
}
```

### Conventions Blade

#### Structure des Vues

```blade
@extends('layouts.app')

@section('title', 'Titre de la Page')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Titre</h1>
    
    {{-- Commentaire --}}
    <div class="bg-white rounded-lg shadow p-6">
        @forelse($items as $item)
            <div>{{ $item->name }}</div>
        @empty
            <p>Aucun élément</p>
        @endforelse
    </div>
</div>
@endsection
```

#### Indentation

- Utilisez 4 espaces (pas de tabs)
- Indentez les directives Blade

### Conventions JavaScript

```javascript
// Utilisez const/let, pas var
const apiUrl = '/api/films';
let currentPage = 1;

// Arrow functions
const fetchFilms = async () => {
    try {
        const response = await fetch(apiUrl);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Erreur:', error);
    }
};

// Classes
class FilmManager {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
    }
    
    render(films) {
        // ...
    }
}
```

### Tailwind CSS

Utilisez les classes utilitaires Tailwind :

```html
<!-- Bon -->
<div class="bg-white rounded-lg shadow p-6 mb-4">
    <h2 class="text-xl font-bold text-gray-900 mb-2">Titre</h2>
    <p class="text-gray-600">Description</p>
</div>

<!-- Évitez les styles inline -->
<div style="background: white; padding: 20px;">...</div>
```

---

## 🧪 Tests

### Écrire des Tests

Créez des tests pour votre code :

```php
<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Tests\TestCase;

class FilmTest extends TestCase
{
    public function test_admin_can_create_film()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        
        $response = $this->actingAs($admin)->post('/admin/films', [
            'title' => 'Test Film',
            'slug' => 'test-film',
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('films', [
            'title' => 'Test Film',
        ]);
    }
}
```

### Exécuter les Tests

```bash
# Tous les tests
php artisan test

# Tests avec couverture
php artisan test --coverage

# Un fichier spécifique
php artisan test tests/Feature/FilmTest.php

# Une méthode spécifique
php artisan test --filter=test_admin_can_create_film
```

---

## 📖 Documentation

### Documenter votre Code

- Ajoutez des commentaires pour la logique complexe
- Utilisez des DocBlocks pour les fonctions publiques
- Mettez à jour le README si nécessaire
- Créez/mettez à jour la documentation dans `/docs`

### Mettre à Jour MODULES.md

Si vous ajoutez un nouveau module, documentez-le dans `docs/MODULES.md` :

```markdown
### X. Nouveau Module

**Route** : `/admin/nouveau-module`  
**Contrôleur** : `NouveauModuleController`

#### Fonctionnalités
- Description de la fonctionnalité 1
- Description de la fonctionnalité 2

#### Modèle
\`\`\`php
- champ1 : Description
- champ2 : Description
\`\`\`
```

---

## 🔍 Code Review

### Avant de Soumettre une PR

Vérifiez que :

- [ ] Le code suit nos standards
- [ ] Les tests passent
- [ ] La documentation est à jour
- [ ] Il n'y a pas de `console.log()` oubliés
- [ ] Les variables sont nommées clairement
- [ ] Le code est commenté si nécessaire

### Critères de Review

Lors de la review, nous vérifions :

1. **Fonctionnalité** : Le code fait-il ce qu'il doit faire ?
2. **Qualité** : Le code est-il propre et maintenable ?
3. **Performance** : Y a-t-il des optimisations possibles ?
4. **Sécurité** : Y a-t-il des failles de sécurité ?
5. **Tests** : Les tests couvrent-ils suffisamment ?

---

## 🐛 Signaler un Bug

### Template de Bug Report

Utilisez ce template sur GitHub Issues :

```markdown
**Description**
Description claire et concise du bug

**Comment Reproduire**
1. Aller sur '...'
2. Cliquer sur '...'
3. Scroller jusqu'à '...'
4. Voir l'erreur

**Comportement Attendu**
Ce qui devrait se passer

**Screenshots**
Si applicable

**Environnement**
- OS: [ex: Windows 11]
- Navigateur: [ex: Chrome 120]
- Version PHP: [ex: 8.2]
- Version Laravel: [ex: 12.x]

**Logs**
Coller les logs d'erreur pertinents
```

---

## ✨ Proposer une Fonctionnalité

### Template de Feature Request

```markdown
**Problème à Résoudre**
Description du problème que cette fonctionnalité résout

**Solution Proposée**
Description claire de la solution envisagée

**Alternatives Considérées**
Autres approches possibles

**Contexte Additionnel**
Captures d'écran, liens, exemples
```

---

## 📜 Licence

En contribuant à BlackCine, vous acceptez que vos contributions soient sous la même licence que le projet.

---

## 🙏 Remerciements

Nous apprécions énormément vos contributions ! Chaque PR, bug report, ou suggestion nous aide à améliorer BlackCine.

### Hall of Fame

Les contributeurs majeurs seront ajoutés dans la section Contributors du README.

---

## 📞 Questions ?

- 💬 **Discord** : [Lien Discord]
- 📧 **Email** : dev@blackcine.com
- 🐛 **Issues** : GitHub Issues

---

**Bon développement !** 🚀

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\TitleController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\ShowtimeController;
use App\Http\Controllers\Api\CastingController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ContestController;
use App\Http\Controllers\Api\FestivalController;
use App\Http\Controllers\Api\SelectionController;
use App\Http\Controllers\Api\SlideController as ApiSlideController;
use App\Http\Controllers\Api\FilmApiController;
use App\Http\Controllers\Api\SeriesApiController;
use App\Http\Controllers\Api\V1\FavoriteController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\NewsletterController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\TmdbProxyController;

/*
|--------------------------------------------------------------------------
| API Routes - Frontend BlackCiné
|--------------------------------------------------------------------------
|
| Routes publiques et authentifiées pour le frontend
|
*/

Route::prefix('v1')->group(function () {

    // ==================== ROUTES PUBLIQUES ====================

    // Page d'accueil
    Route::get('/home', [HomeController::class, 'index']);

    // Recherche
    Route::get('/search', [SearchController::class, 'index']);

    // Actualités
    Route::get('/articles/featured', [\App\Http\Controllers\Api\ArticlesApiController::class, 'featured']);
    Route::get('/articles/popular', [\App\Http\Controllers\Api\ArticlesApiController::class, 'popular']);
    Route::get('/articles', [\App\Http\Controllers\Api\ArticlesApiController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);

    // Films & Séries
    Route::get('/titles', [TitleController::class, 'index']);
    Route::get('/titles/films', [TitleController::class, 'films']);
    Route::get('/titles/series', [TitleController::class, 'series']);
    Route::get('/titles/classics', [TitleController::class, 'classics']);
    Route::get('/titles/genres', [TitleController::class, 'genres']);
    Route::get('/titles/countries', [TitleController::class, 'countries']);
    Route::get('/titles/{id}', [TitleController::class, 'show']);
    Route::get('/titles/{id}/trailer', [TitleController::class, 'trailer']);

    // Vidéos & Bandes-annonces
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/featured', [VideoController::class, 'featured']);
    Route::get('/videos/{id}', [VideoController::class, 'show']);

    // Séances & Box-Office
    Route::get('/showtimes', [ShowtimeController::class, 'index']);
    Route::get('/showtimes/now-showing', [ShowtimeController::class, 'nowShowing']);
    Route::get('/showtimes/{id}', [ShowtimeController::class, 'show']);
    Route::get('/cinemas', [ShowtimeController::class, 'cinemas']);

    // Slider
    Route::get('/slides', [ApiSlideController::class, 'index']);
    Route::get('/slides/films', [ApiSlideController::class, 'films']);
    Route::get('/slides/series', [ApiSlideController::class, 'series']);

    // Sélections éditoriales
    Route::get('/selections', [SelectionController::class, 'index']);
    Route::get('/selections/{id}', [SelectionController::class, 'show']);

    // Communauté
    Route::get('/castings', [CastingController::class, 'index']);
    Route::get('/castings/{id}', [CastingController::class, 'show']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::get('/contests', [ContestController::class, 'index']);
    Route::get('/contests/{id}', [ContestController::class, 'show']);

    // Festivals
    Route::get('/festivals', [FestivalController::class, 'index']);
    Route::get('/festivals/active', [FestivalController::class, 'active']);
    Route::get('/festivals/{id}', [FestivalController::class, 'show']);

    // Films resource (read-only public)
    Route::get('/films', [FilmApiController::class, 'index']);
    Route::get('/films/{id}', [FilmApiController::class, 'show']);

    // Séries resource (read-only public)
    Route::get('/series', [SeriesApiController::class, 'index']);
    Route::get('/series/{id}', [SeriesApiController::class, 'show']);

    // Émissions
    Route::get('/emissions', [\App\Http\Controllers\Api\EmissionsApiController::class, 'index']);
    Route::get('/emissions/upcoming', [\App\Http\Controllers\Api\EmissionsApiController::class, 'upcoming']);
    Route::get('/emissions/featured', [\App\Http\Controllers\Api\EmissionsApiController::class, 'featured']);
    Route::get('/emissions/live', [\App\Http\Controllers\Api\EmissionsApiController::class, 'live']);
    Route::get('/emissions/{id}', [\App\Http\Controllers\Api\EmissionsApiController::class, 'show']);

    // Genre API
    Route::get('media/genre/{slug}', [\App\Http\Controllers\Api\CatalogApiController::class, 'byGenre']);

    // Footer data
    Route::get('/footer', [\App\Http\Controllers\Api\FooterController::class, 'index']);

    // Partners
    Route::get('/partners', [\App\Http\Controllers\Api\PartnerApiController::class, 'index']);

    // TMDB proxy (sécurisé — clé côté serveur, cache 10min, whitelist)
    Route::get('/tmdb/image-config', [TmdbProxyController::class, 'imageConfig']);
    Route::get('/tmdb/{path}', [TmdbProxyController::class, 'proxy'])->where('path', '.*')->middleware('throttle:60,1');

    // Newsletter (rate limité)
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
        ->middleware('throttle:newsletter');
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);

    // ==================== ROUTES AUTHENTIFIÉES ====================

    Route::middleware(['auth:sanctum'])->group(function () {

        // Utilisateur
        Route::get('/user', [UserController::class, 'show']);
        Route::put('/user', [UserController::class, 'update']);
        Route::put('/user/password', [UserController::class, 'updatePassword']);
        Route::post('/user/avatar', [UserController::class, 'updateAvatar']);
        Route::delete('/user', [UserController::class, 'destroy']);

        // Favoris (check AVANT la route index pour éviter le conflit)
        Route::get('/favorites/check', [FavoriteController::class, 'check']);
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites', [FavoriteController::class, 'store']);
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

        // Actions utilisateur
        Route::post('/castings/{id}/apply', [CastingController::class, 'apply']);
        Route::post('/projects/{id}/apply', [ProjectController::class, 'apply']);
        Route::post('/contests/{id}/register', [ContestController::class, 'register']);
    });

    // ==================== ROUTES ADMIN (protected) ====================

    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
        Route::apiResource('films', FilmApiController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('series', SeriesApiController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('emissions', \App\Http\Controllers\Api\EmissionsApiController::class)->only(['store', 'update', 'destroy']);
    });
});

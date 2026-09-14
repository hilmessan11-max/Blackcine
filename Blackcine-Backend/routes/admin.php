<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\SEOController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TitleDetailController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CastingController;
use App\Http\Controllers\SelectionController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\TalentProfileController;
use App\Http\Controllers\FestivalController;
use App\Http\Controllers\ProAccountController;
use App\Http\Controllers\SettingsController;

// Middleware global pour les routes admin
Route::middleware(['auth'])->group(function () {

    // -----------------------------
    // Dashboard (SuperAdmin + Admin)
    // -----------------------------
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->middleware('role_permission:SuperAdmin|Admin')
        ->name('admin.dashboard');

    // -----------------------------
    // Gestion Articles (Rédacteur + Admin)
    // -----------------------------
    Route::prefix('articles')->name('admin.articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::get('/create', [ArticleController::class, 'create'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('create');
        Route::post('/store', [ArticleController::class, 'store'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('store');
        Route::get('/{id}/edit', [ArticleController::class, 'edit'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('edit');
        Route::put('/{id}', [ArticleController::class, 'update'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('update');
    });

   // -----------------------------
// Gestion Vidéos (Admin + Rédacteur)
// -----------------------------
Route::prefix('videos')->name('admin.videos.')->group(function () {
    Route::get('/', [VideoController::class, 'index'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('index');
    
    Route::get('/create', [VideoController::class, 'create'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('create');
    
    Route::post('/store', [VideoController::class, 'store'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('store');
    
    Route::get('/{id}/edit', [VideoController::class, 'edit'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('edit');
    
    Route::put('/{id}', [VideoController::class, 'update'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('update');
    
    Route::delete('/{id}', [VideoController::class, 'destroy'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('destroy');
    
    Route::post('/upload', [VideoController::class, 'upload'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('upload');
});
    // -----------------------------
    // Gestion Partenaires (PartnerMgr)
    // -----------------------------
    Route::prefix('partners')->name('admin.partners.')->group(function () {
        Route::get('/', [PartnerController::class, 'index'])
            ->middleware('role_permission:PartnerMgr|SuperAdmin,manage partners')
            ->name('index');
        Route::post('/add', [PartnerController::class, 'store'])
            ->middleware('role_permission:PartnerMgr|SuperAdmin,manage partners')
            ->name('store');
    });

    // -----------------------------
    // Partenaires - Festivals (PartnerMgr + Admin)
    // -----------------------------
    Route::prefix('partners/festivals')->name('admin.partners.festivals.')->group(function () {
        Route::get('/', [FestivalController::class, 'index'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('index');
        Route::get('/create', [FestivalController::class, 'create'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('create');
        Route::post('/', [FestivalController::class, 'store'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('store');
        Route::get('/{id}/edit', [FestivalController::class, 'edit'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('edit');
        Route::put('/{id}', [FestivalController::class, 'update'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('update');
        Route::delete('/{id}', [FestivalController::class, 'destroy'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('destroy');
        Route::post('/{id}/titles', [FestivalController::class, 'attachTitle'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('titles.attach');
    });

    // -----------------------------
    // Finance (Finance)
    // -----------------------------
    Route::prefix('finance')->name('admin.finance.')->group(function () {
        Route::get('/reports', [FinanceController::class, 'reports'])
            ->middleware('role_permission:Finance|SuperAdmin,view finances')
            ->name('reports');
        Route::post('/update', [FinanceController::class, 'update'])
            ->middleware('role_permission:Finance|SuperAdmin,view finances')
            ->name('update');
        Route::get('/payouts', [FinanceController::class, 'payouts'])
            ->middleware('role_permission:Finance|SuperAdmin,view finances')
            ->name('payouts');
        Route::get('/transactions', [FinanceController::class, 'transactions'])
            ->middleware('role_permission:Finance|SuperAdmin,view finances')
            ->name('transactions');
    });

    // -----------------------------
    // Communauté (CommunityMgr + Modérateur)
    // -----------------------------
    Route::prefix('community')->name('admin.community.')->group(function () {
        Route::get('/', [CommunityController::class, 'index'])
            ->middleware('role_permission:CommunityMgr|Modérateur,manage community')
            ->name('index');
        Route::post('/ban/{userId}', [CommunityController::class, 'banUser'])
            ->middleware('role_permission:CommunityMgr|Modérateur,manage community')
            ->name('ban');
    });

    // -----------------------------
    // Utilisateurs - Front (Admin)
    // -----------------------------
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UserController::class, 'index'])
            ->middleware('role_permission:Admin|SuperAdmin,manage users')
            ->name('index');
        Route::get('/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])
            ->middleware('role_permission:Admin|SuperAdmin,manage users')
            ->name('show');
        Route::delete('/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])
            ->middleware('role_permission:Admin|SuperAdmin,manage users')
            ->name('destroy');
    });

    // -----------------------------
    // Utilisateurs - Comptes Pro (Admin)
    // -----------------------------
    Route::prefix('users/pro-accounts')->name('admin.users.pro-accounts.')->group(function () {
        Route::get('/', [ProAccountController::class, 'index'])
            ->middleware('role_permission:Admin|SuperAdmin,manage users')
            ->name('index');
        Route::get('/{id}', [ProAccountController::class, 'show'])
            ->middleware('role_permission:Admin|SuperAdmin,manage users')
            ->name('show');
        Route::post('/{id}/verify', [ProAccountController::class, 'verify'])
            ->middleware('role_permission:Admin|SuperAdmin,manage users')
            ->name('verify');
        Route::post('/{id}/reject', [ProAccountController::class, 'reject'])
            ->middleware('role_permission:Admin|SuperAdmin,manage users')
            ->name('reject');
    });

    // -----------------------------
    // SEO (SEO)
    // -----------------------------
    Route::prefix('seo')->name('admin.seo.')->group(function () {
        Route::get('/', [SEOController::class, 'index'])
            ->middleware('role_permission:SEO|SuperAdmin,manage seo')
            ->name('index');
        Route::post('/update', [SEOController::class, 'update'])
            ->middleware('role_permission:SEO|SuperAdmin,manage seo')
            ->name('update');
    });

    // -----------------------------
    // Marketing (Marketing)
    // -----------------------------
    Route::prefix('marketing')->name('admin.marketing.')->group(function () {
        Route::get('/', [MarketingController::class, 'index'])
            ->middleware('role_permission:Marketing|SuperAdmin,manage marketing')
            ->name('index');
        Route::post('/campaign', [MarketingController::class, 'createCampaign'])
            ->middleware('role_permission:Marketing|SuperAdmin,manage marketing')
            ->name('campaign');
    });

    // -----------------------------
    // Catalogue (Admin + Rédacteur)
    // -----------------------------
    Route::prefix('catalog')->name('admin.catalog.')->group(function () {
        Route::get('/', [CatalogController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::get('/create', [CatalogController::class, 'create'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('create');
        Route::post('/store', [CatalogController::class, 'store'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('store');
        Route::get('/{id}/edit', [CatalogController::class, 'edit'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('edit');
        Route::put('/{id}', [CatalogController::class, 'update'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('update');
        Route::delete('/{id}', [CatalogController::class, 'destroy'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('destroy');
    });

    // -----------------------------
    // Catalogue - Personnes (Admin + Rédacteur)
    // -----------------------------
    Route::prefix('persons')->name('admin.persons.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PersonController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::get('/create', [\App\Http\Controllers\PersonController::class, 'create'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('create');
        Route::post('/', [\App\Http\Controllers\PersonController::class, 'store'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\PersonController::class, 'edit'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\PersonController::class, 'update'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\PersonController::class, 'destroy'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('destroy');
    });

    // -----------------------------
    // Séries (Admin + Rédacteur)
    // -----------------------------
    Route::prefix('admin/series-mgmt-catalog')->name('admin.catalog.series.')->group(function () {
        Route::get('/', [SeriesController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::get('/{id}', [SeriesController::class, 'show'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('show');
        Route::post('/{seriesId}/seasons', [SeriesController::class, 'createSeason'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('seasons.store');
        Route::post('/{seriesId}/seasons/{seasonId}/episodes', [SeriesController::class, 'createEpisode'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('episodes.store');
    });

    // -----------------------------
    // Import/Export Catalogue (Admin)
    // -----------------------------
    Route::prefix('catalog/import-export')->name('admin.catalog.import-export.')->group(function () {
        Route::get('/', [ImportExportController::class, 'index'])
            ->middleware('role_permission:Admin|SuperAdmin')
            ->name('index');
        Route::get('/export', [ImportExportController::class, 'export'])
            ->middleware('role_permission:Admin|SuperAdmin')
            ->name('export');
        Route::post('/import', [ImportExportController::class, 'import'])
            ->middleware('role_permission:Admin|SuperAdmin')
            ->name('import');
    });

    // -----------------------------
    // Fiches Titres Centralisées (Admin + Rédacteur)
Route::prefix('admin/titles-mgmt')->name('admin.titles.')->group(function () {
    Route::get('/', [TitleDetailController::class, 'index'])
        ->middleware('role_permission:Admin|Rédacteur|Modérateur,edit articles')
        ->name('index');
    
    Route::get('/create', [TitleDetailController::class, 'create'])  
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('create');
    
    Route::post('/', [TitleDetailController::class, 'store'])  
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('store');
    
    Route::get('/{id}', [TitleDetailController::class, 'show'])
        ->middleware('role_permission:Admin|Rédacteur|Modérateur,edit articles')
        ->name('show');
    
    Route::post('/{id}/feature', [TitleDetailController::class, 'feature'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('feature');
    Route::post('/{id}/selection', [TitleDetailController::class, 'addToSelection'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('selection');
    Route::post('/{id}/now-showing', [TitleDetailController::class, 'markNowShowing'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('now-showing');
        
    Route::post('/{id}/credits', [TitleDetailController::class, 'storeCredit'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('credits.store');
        
    Route::delete('/{id}/credits/{creditId}', [TitleDetailController::class, 'destroyCredit'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('credits.destroy');

    Route::post('/{id}/trailers', [TitleDetailController::class, 'storeTrailer'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('trailers.store');
    Route::delete('/{id}/trailers/{trailerId}', [TitleDetailController::class, 'destroyTrailer'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('trailers.destroy');

    Route::post('/{id}/subtitles', [TitleDetailController::class, 'storeSubtitle'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('subtitles.store');
    Route::delete('/{id}/subtitles/{subtitleId}', [TitleDetailController::class, 'destroySubtitle'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('subtitles.destroy');

    Route::post('/{id}/images', [TitleDetailController::class, 'storeImage'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('images.store');
    Route::delete('/{id}/images/{assetId}', [TitleDetailController::class, 'destroyImage'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('images.destroy');


    // Routes d'édition et suppression
    Route::get('/{id}/edit', [TitleDetailController::class, 'edit'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('edit');
    Route::put('/{id}', [TitleDetailController::class, 'update'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('update');
    Route::delete('/{id}', [TitleDetailController::class, 'destroy'])
        ->middleware('role_permission:Admin|Rédacteur,edit articles')
        ->name('destroy');
});

    // -----------------------------
    // Films (Admin + Rédacteur)
    // -----------------------------
    Route::prefix('admin/films-mgmt')->name('admin.films.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FilmController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\FilmController::class, 'create'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\FilmController::class, 'store'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('store');
        Route::get('/{film}', [\App\Http\Controllers\Admin\FilmController::class, 'show'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('show');
        Route::get('/{film}/edit', [\App\Http\Controllers\Admin\FilmController::class, 'edit'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('edit');
        Route::put('/{film}', [\App\Http\Controllers\Admin\FilmController::class, 'update'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('update');
        Route::delete('/{film}', [\App\Http\Controllers\Admin\FilmController::class, 'destroy'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('destroy');
        
        // Trailers - Vue de gestion
        Route::get('/{id}/trailers', [\App\Http\Controllers\Admin\FilmController::class, 'trailers'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('trailers');
        Route::post('/{id}/trailers', [\App\Http\Controllers\Admin\FilmController::class, 'storeTrailer'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('trailers.store');
        Route::delete('/{id}/trailers/{trailerId}', [\App\Http\Controllers\Admin\FilmController::class, 'destroyTrailer'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('trailers.destroy');
    });

    // -----------------------------
    // Séries (Admin + Rédacteur)
    // -----------------------------
    Route::prefix('series-mgmt')->name('admin.series.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SeriesController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\SeriesController::class, 'create'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\SeriesController::class, 'store'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('store');
        Route::get('/{serie}', [\App\Http\Controllers\Admin\SeriesController::class, 'show'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('show');
        Route::get('/{serie}/edit', [\App\Http\Controllers\Admin\SeriesController::class, 'edit'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('edit');
        Route::put('/{serie}', [\App\Http\Controllers\Admin\SeriesController::class, 'update'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('update');
        Route::delete('/{serie}', [\App\Http\Controllers\Admin\SeriesController::class, 'destroy'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('destroy');
        
        // Trailers - Vue de gestion
        Route::get('/{id}/trailers', [\App\Http\Controllers\Admin\SeriesController::class, 'trailers'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('trailers');
        Route::post('/{id}/trailers', [\App\Http\Controllers\Admin\SeriesController::class, 'storeTrailer'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('trailers.store');
        Route::delete('/{id}/trailers/{trailerId}', [\App\Http\Controllers\Admin\SeriesController::class, 'destroyTrailer'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('trailers.destroy');
    });

    // -----------------------------
    // Box-Office - Dashboard (TicketMgr + Admin)
    // -----------------------------
    Route::get('/boxoffice', [ShowtimeController::class, 'index'])
        ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
        ->name('admin.boxoffice.index');

    // -----------------------------
    // Box-Office - Séances (TicketMgr + Admin)
    // -----------------------------
    Route::prefix('boxoffice/showtimes')->name('admin.boxoffice.showtimes.')->group(function () {
        Route::get('/', [ShowtimeController::class, 'index'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('index');
        Route::get('/create', [ShowtimeController::class, 'create'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('create');
        Route::post('/', [ShowtimeController::class, 'store'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('store');
        Route::get('/{id}/edit', [ShowtimeController::class, 'edit'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('edit');
        Route::put('/{id}', [ShowtimeController::class, 'update'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('update');
        Route::delete('/{id}', [ShowtimeController::class, 'destroy'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('destroy');
    });

    // -----------------------------
    // Box-Office - Cinémas (PartnerMgr + Admin)
    // -----------------------------
    Route::prefix('boxoffice/cinemas')->name('admin.boxoffice.cinemas.')->group(function () {
        Route::get('/', [CinemaController::class, 'index'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('index');
        Route::get('/create', [CinemaController::class, 'create'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('create');
        Route::post('/', [CinemaController::class, 'store'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('store');
        Route::get('/{id}/edit', [CinemaController::class, 'edit'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('edit');
        Route::put('/{id}', [CinemaController::class, 'update'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('update');
        Route::delete('/{id}', [CinemaController::class, 'destroy'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('destroy');
        Route::get('/{id}/rooms', [CinemaController::class, 'rooms'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('rooms');
        Route::post('/{id}/rooms', [CinemaController::class, 'storeRoom'])
            ->middleware('role_permission:PartnerMgr|Admin|SuperAdmin,manage partners')
            ->name('rooms.store');
    });

    // -----------------------------
    // Box-Office - Tickets (TicketMgr + Admin)
    // -----------------------------
    Route::prefix('boxoffice/tickets')->name('admin.tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('index');
        Route::get('/create', [TicketController::class, 'create'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('create');
        Route::post('/', [TicketController::class, 'store'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('store');
        Route::post('/{id}/resolve', [TicketController::class, 'resolve'])
            ->middleware('role_permission:TicketMgr|Admin|SuperAdmin,manage tickets')
            ->name('resolve');
    });


    // -----------------------------
    // Box-Office - Commandes & Paiements (Finance + Admin)
    // -----------------------------
    Route::prefix('boxoffice/orders')->name('admin.boxoffice.orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])
            ->middleware('role_permission:Finance|Admin|SuperAdmin,view finances')
            ->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])
            ->middleware('role_permission:Finance|Admin|SuperAdmin,view finances')
            ->name('show');
        Route::put('/{id}/status', [OrderController::class, 'updateStatus'])
            ->middleware('role_permission:Finance|Admin|SuperAdmin,view finances')
            ->name('status');
        Route::get('/export', [OrderController::class, 'export'])
            ->middleware('role_permission:Finance|Admin|SuperAdmin,view finances')
            ->name('export');
    });

    // -----------------------------
    // Médiathèque (Rédacteur + Admin)
    // -----------------------------
    Route::prefix('media')->name('admin.media.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::post('/', [AssetController::class, 'store'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('store');
        Route::delete('/{id}', [AssetController::class, 'destroy'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('destroy');
    });

    // -----------------------------
    // Communauté - Castings (CommunityMgr + Admin)
    // -----------------------------
    Route::prefix('community/castings')->name('admin.community.castings.')->group(function () {
        Route::get('/', [CastingController::class, 'index'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('index');
        Route::get('/create', [CastingController::class, 'create'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('create');
        Route::post('/', [CastingController::class, 'store'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('store');
        Route::get('/{id}/edit', [CastingController::class, 'edit'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('edit');
        Route::put('/{id}', [CastingController::class, 'update'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('update');
        Route::delete('/{id}', [CastingController::class, 'destroy'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('destroy');
    });

    // -----------------------------
    // Communauté - Projets (CommunityMgr + Admin)
    // -----------------------------
    Route::prefix('community/projects')->name('admin.community.projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('index');
        Route::get('/create', [ProjectController::class, 'create'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('create');
        Route::post('/', [ProjectController::class, 'store'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('store');
        Route::get('/{id}/edit', [ProjectController::class, 'edit'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('edit');
        Route::put('/{id}', [ProjectController::class, 'update'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('update');
        Route::delete('/{id}', [ProjectController::class, 'destroy'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('destroy');
    });

    // -----------------------------
    // Communauté - Concours (CommunityMgr + Admin)
    // -----------------------------
    Route::prefix('community/contests')->name('admin.community.contests.')->group(function () {
        Route::get('/', [ContestController::class, 'index'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('index');
        Route::get('/create', [ContestController::class, 'create'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('create');
        Route::post('/', [ContestController::class, 'store'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('store');
        Route::get('/{id}/edit', [ContestController::class, 'edit'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('edit');
        Route::put('/{id}', [ContestController::class, 'update'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('update');
        Route::delete('/{id}', [ContestController::class, 'destroy'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('destroy');
    });

    // -----------------------------
    // Communauté - Talents & Portfolios (CommunityMgr + Admin)
    // -----------------------------
    Route::prefix('community/talents')->name('admin.community.talents.')->group(function () {
        Route::get('/', [TalentProfileController::class, 'index'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('index');
        Route::get('/create', [TalentProfileController::class, 'create'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('create');
        Route::post('/', [TalentProfileController::class, 'store'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('store');
        Route::get('/{id}/edit', [TalentProfileController::class, 'edit'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('edit');
        Route::put('/{id}', [TalentProfileController::class, 'update'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('update');
        Route::get('/{id}', [TalentProfileController::class, 'show'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('show');
        Route::post('/{id}/verify', [TalentProfileController::class, 'verify'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('verify');
        Route::post('/{id}/reject', [TalentProfileController::class, 'reject'])
            ->middleware('role_permission:CommunityMgr|Admin|SuperAdmin,manage community')
            ->name('reject');
    });

    // -----------------------------
    // Contenu éditorial - Sélections (Rédacteur + Admin)
    // -----------------------------
    Route::prefix('editorial/selections')->name('admin.editorial.selections.')->group(function () {
        Route::get('/', [SelectionController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::get('/create', [SelectionController::class, 'create'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('create');
        Route::post('/', [SelectionController::class, 'store'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('store');
        Route::get('/{id}/edit', [SelectionController::class, 'edit'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('edit');
        Route::put('/{id}', [SelectionController::class, 'update'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('update');
        Route::delete('/{id}', [SelectionController::class, 'destroy'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('destroy');
    });

    // -----------------------------
    // Contenu éditorial - Slider & Bannières (Admin)
    // -----------------------------
    Route::prefix('editorial/slides')->name('admin.editorial.slides.')->group(function () {
        Route::get('/', [SlideController::class, 'index'])
            ->middleware('role_permission:Admin|SuperAdmin,edit articles')
            ->name('index');
        Route::get('/create', [SlideController::class, 'create'])
            ->middleware('role_permission:Admin|SuperAdmin,edit articles')
            ->name('create');
        Route::post('/', [SlideController::class, 'store'])
            ->middleware('role_permission:Admin|SuperAdmin,edit articles')
            ->name('store');
        Route::get('/{id}/edit', [SlideController::class, 'edit'])
            ->middleware('role_permission:Admin|SuperAdmin,edit articles')
            ->name('edit');
        Route::put('/{id}', [SlideController::class, 'update'])
            ->middleware('role_permission:Admin|SuperAdmin,edit articles')
            ->name('update');
        Route::delete('/{id}', [SlideController::class, 'destroy'])
            ->middleware('role_permission:Admin|SuperAdmin,edit articles')
            ->name('destroy');
        Route::post('/order', [SlideController::class, 'updateOrder'])
            ->middleware('role_permission:Admin|SuperAdmin,edit articles')
            ->name('order');
    });

    // -----------------------------
    // Paramètres généraux (SuperAdmin + Admin)
    // -----------------------------
    Route::prefix('settings')->name('admin.settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])
            ->middleware('role_permission:SuperAdmin|Admin')
            ->name('index');
        Route::post('/update', [SettingsController::class, 'update'])
            ->middleware('role_permission:SuperAdmin|Admin')
            ->name('update');
        Route::post('/identity', [SettingsController::class, 'updateIdentity'])
            ->middleware('role_permission:SuperAdmin|Admin')
            ->name('identity');
        Route::post('/languages', [SettingsController::class, 'updateLanguages'])
            ->middleware('role_permission:SuperAdmin|Admin')
            ->name('languages');
        Route::post('/payment', [SettingsController::class, 'updatePayment'])
            ->middleware('role_permission:SuperAdmin|Admin')
            ->name('payment');
        Route::post('/billing', [SettingsController::class, 'updateBilling'])
            ->middleware('role_permission:SuperAdmin|Admin')
            ->name('billing');
        Route::post('/ticket', [SettingsController::class, 'updateTicket'])
            ->middleware('role_permission:SuperAdmin|Admin')
            ->name('ticket');
    });

    // -----------------------------
    // Modération (Modérateur + Admin)
    // -----------------------------
    Route::prefix('moderation')->name('admin.moderation.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ModerationController::class, 'index'])
            ->middleware('role_permission:Modérateur|Admin|SuperAdmin,manage moderation')
            ->name('index');
        
        Route::get('/comments', [\App\Http\Controllers\ModerationController::class, 'comments'])
            ->middleware('role_permission:Modérateur|Admin|SuperAdmin,manage moderation')
            ->name('comments');
        Route::post('/comments/{id}/status', [\App\Http\Controllers\ModerationController::class, 'updateCommentStatus'])
            ->middleware('role_permission:Modérateur|Admin|SuperAdmin,manage moderation')
            ->name('comments.status');
        Route::get('/reports', [\App\Http\Controllers\ModerationController::class, 'reports'])
            ->middleware('role_permission:Modérateur|Admin|SuperAdmin,manage moderation')
            ->name('reports');
        Route::post('/reports/{id}/status', [\App\Http\Controllers\ModerationController::class, 'updateReportStatus'])
            ->middleware('role_permission:Modérateur|Admin|SuperAdmin,manage moderation')
            ->name('reports.status');
            
        Route::get('/logs', [\App\Http\Controllers\ModerationController::class, 'logs'])
            ->middleware('role_permission:SuperAdmin|Admin,view logs')
            ->name('logs');
    });

    // -----------------------------
    // Global Reviews (Sidebar Access)
    // -----------------------------
    Route::prefix('reviews')->name('admin.reviews.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReviewController::class, 'globalIndex'])
            ->middleware('role_permission:Admin|Modérateur|Rédacteur,edit articles')
            ->name('index');
        Route::get('/editorial', [\App\Http\Controllers\ReviewController::class, 'globalEditorialIndex'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('editorial');
    });

    // -----------------------------
    // Reviews & Avis utilisateurs (Admin + Modérateur)
    // -----------------------------
    Route::prefix('titles/{titleId}/reviews')->name('admin.titles.reviews.')->group(function () {
        // User reviews
        Route::get('/', [\App\Http\Controllers\ReviewController::class, 'index'])
            ->middleware('role_permission:Admin|Modérateur|Rédacteur,edit articles')
            ->name('index');
        Route::post('/{reviewId}/approve', [\App\Http\Controllers\ReviewController::class, 'approve'])
            ->middleware('role_permission:Admin|Modérateur,edit articles')
            ->name('approve');
        Route::post('/{reviewId}/reject', [\App\Http\Controllers\ReviewController::class, 'reject'])
            ->middleware('role_permission:Admin|Modérateur,edit articles')
            ->name('reject');
        Route::delete('/{reviewId}', [\App\Http\Controllers\ReviewController::class, 'destroy'])
            ->middleware('role_permission:Admin|Modérateur,edit articles')
            ->name('destroy');
            
        // Editorial reviews
        Route::get('/editorial', [\App\Http\Controllers\ReviewController::class, 'editorialIndex'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('editorial.index');
        Route::get('/editorial/create', [\App\Http\Controllers\ReviewController::class, 'editorialCreate'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('editorial.create');
        Route::post('/editorial', [\App\Http\Controllers\ReviewController::class, 'editorialStore'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('editorial.store');
        Route::get('/editorial/{reviewId}/edit', [\App\Http\Controllers\ReviewController::class, 'editorialEdit'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('editorial.edit');
        Route::put('/editorial/{reviewId}', [\App\Http\Controllers\ReviewController::class, 'editorialUpdate'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('editorial.update');
        Route::delete('/editorial/{reviewId}', [\App\Http\Controllers\ReviewController::class, 'editorialDestroy'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('editorial.destroy');
        Route::post('/editorial/{reviewId}/toggle-featured', [\App\Http\Controllers\ReviewController::class, 'editorialToggleFeatured'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('editorial.toggle-featured');
    });

    // -----------------------------
    // Classements & Rankings (Admin + Rédacteur)
    // -----------------------------
    Route::prefix('rankings')->name('admin.rankings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\RankingController::class, 'index'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('index');
        Route::get('/create', [\App\Http\Controllers\RankingController::class, 'create'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('create');
        Route::post('/', [\App\Http\Controllers\RankingController::class, 'store'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('store');
        Route::get('/{id}', [\App\Http\Controllers\RankingController::class, 'show'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\RankingController::class, 'edit'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\RankingController::class, 'update'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\RankingController::class, 'destroy'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('destroy');
            
        // Gestion des titres dans le classement
        Route::post('/{id}/titles', [\App\Http\Controllers\RankingController::class, 'addTitle'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('titles.add');
        Route::delete('/{id}/titles/{entryId}', [\App\Http\Controllers\RankingController::class, 'removeTitle'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('titles.remove');
        Route::post('/{id}/positions', [\App\Http\Controllers\RankingController::class, 'updatePositions'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('positions.update');
        Route::post('/{id}/recalculate', [\App\Http\Controllers\RankingController::class, 'recalculateScores'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('recalculate');
        Route::post('/{id}/toggle-active', [\App\Http\Controllers\RankingController::class, 'toggleActive'])
            ->middleware('role_permission:Admin|Rédacteur,edit articles')
            ->name('toggle-active');
    });


    // -----------------------------
    // Publicité & Monétisation
    // -----------------------------
    Route::prefix('advertising')->name('admin.advertising.')->middleware('role_permission:Admin|Marketing|SuperAdmin,manage advertising')->group(function () {
        // Campaigns
        Route::get('/campaigns', [\App\Http\Controllers\Admin\AdvertisingController::class, 'index'])->name('campaigns.index');
        Route::get('/campaigns/create', [\App\Http\Controllers\Admin\AdvertisingController::class, 'create'])->name('campaigns.create');
        Route::post('/campaigns', [\App\Http\Controllers\Admin\AdvertisingController::class, 'store'])->name('campaigns.store');
        Route::get('/campaigns/{campaign}/edit', [\App\Http\Controllers\Admin\AdvertisingController::class, 'edit'])->name('campaigns.edit');
        Route::put('/campaigns/{campaign}', [\App\Http\Controllers\Admin\AdvertisingController::class, 'update'])->name('campaigns.update');
        Route::delete('/campaigns/{campaign}', [\App\Http\Controllers\Admin\AdvertisingController::class, 'destroy'])->name('campaigns.destroy');

        // Sponsored Content
        Route::get('/sponsored', [\App\Http\Controllers\Admin\SponsoredContentController::class, 'index'])->name('sponsored.index');
        Route::get('/sponsored/create', [\App\Http\Controllers\Admin\SponsoredContentController::class, 'create'])->name('sponsored.create');
        Route::post('/sponsored', [\App\Http\Controllers\Admin\SponsoredContentController::class, 'store'])->name('sponsored.store');
        Route::get('/sponsored/{sponsoredContent}/edit', [\App\Http\Controllers\Admin\SponsoredContentController::class, 'edit'])->name('sponsored.edit');
        Route::put('/sponsored/{sponsoredContent}', [\App\Http\Controllers\Admin\SponsoredContentController::class, 'update'])->name('sponsored.update');
        Route::delete('/sponsored/{sponsoredContent}', [\App\Http\Controllers\Admin\SponsoredContentController::class, 'destroy'])->name('sponsored.destroy');

        // Affiliate Programs
        Route::get('/affiliate', [\App\Http\Controllers\Admin\AffiliateController::class, 'index'])->name('affiliate.index');
        Route::get('/affiliate/create', [\App\Http\Controllers\Admin\AffiliateController::class, 'create'])->name('affiliate.create');
        Route::post('/affiliate', [\App\Http\Controllers\Admin\AffiliateController::class, 'store'])->name('affiliate.store');
        Route::get('/affiliate/{program}/edit', [\App\Http\Controllers\Admin\AffiliateController::class, 'edit'])->name('affiliate.edit');
        Route::put('/affiliate/{program}', [\App\Http\Controllers\Admin\AffiliateController::class, 'update'])->name('affiliate.update');
        Route::delete('/affiliate/{program}', [\App\Http\Controllers\Admin\AffiliateController::class, 'destroy'])->name('affiliate.destroy');
    });

    // -----------------------------
    // Notifications & Communication
    // -----------------------------
    Route::prefix('notifications')->name('admin.notifications.')->middleware('role_permission:Admin|Marketing|SuperAdmin,manage notifications')->group(function () {
        // Email Templates
        Route::get('/templates', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'index'])->name('templates.index');
        Route::get('/templates/create', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'create'])->name('templates.create');
        Route::post('/templates', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'store'])->name('templates.store');
        Route::get('/templates/{template}/edit', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'edit'])->name('templates.edit');
        Route::put('/templates/{template}', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'update'])->name('templates.update');
        Route::delete('/templates/{template}', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'destroy'])->name('templates.destroy');

        // Push Notifications
        Route::get('/push', [\App\Http\Controllers\Admin\PushNotificationController::class, 'index'])->name('push.index');
        Route::get('/push/create', [\App\Http\Controllers\Admin\PushNotificationController::class, 'create'])->name('push.create');
        Route::post('/push', [\App\Http\Controllers\Admin\PushNotificationController::class, 'store'])->name('push.store');
        Route::get('/push/{push}/edit', [\App\Http\Controllers\Admin\PushNotificationController::class, 'edit'])->name('push.edit');
        Route::put('/push/{push}', [\App\Http\Controllers\Admin\PushNotificationController::class, 'update'])->name('push.update');
        Route::delete('/push/{push}', [\App\Http\Controllers\Admin\PushNotificationController::class, 'destroy'])->name('push.destroy');

        Route::get('/sms', [\App\Http\Controllers\Admin\SMSController::class, 'index'])->name('sms.index');
    });

    // -----------------------------
    // Newsletter & Marketing
    // -----------------------------
    Route::prefix('newsletter')->name('admin.newsletter.')->middleware('role_permission:Admin|Marketing|SuperAdmin,manage newsletter')->group(function () {
        // Subscribers
        Route::get('/subscribers', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'index'])->name('subscribers.index');
        Route::get('/subscribers/create', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'create'])->name('subscribers.create');
        Route::post('/subscribers', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'store'])->name('subscribers.store');
        Route::get('/subscribers/{subscriber}/edit', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'edit'])->name('subscribers.edit');
        Route::put('/subscribers/{subscriber}', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'update'])->name('subscribers.update');
        Route::delete('/subscribers/{subscriber}', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'destroy'])->name('subscribers.destroy');
        Route::post('/subscribers/export', [\App\Http\Controllers\Admin\NewsletterSubscriberController::class, 'export'])->name('subscribers.export');

        // Campaigns
        Route::get('/campaigns', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'index'])->name('campaigns.index');
        Route::get('/campaigns/create', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'create'])->name('campaigns.create');
        Route::post('/campaigns', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'store'])->name('campaigns.store');
        Route::get('/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'show'])->name('campaigns.show');
        Route::get('/campaigns/{campaign}/edit', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'edit'])->name('campaigns.edit');
        Route::put('/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'update'])->name('campaigns.update');
        Route::delete('/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'destroy'])->name('campaigns.destroy');
        Route::post('/campaigns/{campaign}/send', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'send'])->name('campaigns.send');
        Route::post('/campaigns/{campaign}/duplicate', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'duplicate'])->name('campaigns.duplicate');
        
        // Stats
        Route::get('/stats', [\App\Http\Controllers\Admin\NewsletterCampaignController::class, 'stats'])->name('stats.index');
    });

    // -----------------------------
    // Paramètres généraux
    // -----------------------------
    Route::prefix('settings')->name('admin.settings.')->middleware('role_permission:Admin|SuperAdmin,manage settings')->group(function () {
        // Rôles & Permissions

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\RolePermissionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\RolePermissionController::class, 'store'])->name('store');
            Route::get('/{role}/edit', [\App\Http\Controllers\Admin\RolePermissionController::class, 'edit'])->name('edit');
            Route::put('/{role}', [\App\Http\Controllers\Admin\RolePermissionController::class, 'update'])->name('update');
            Route::delete('/{role}', [\App\Http\Controllers\Admin\RolePermissionController::class, 'destroy'])->name('destroy');
        });
    });

    // -----------------------------
    // Rapports & Exports
    // -----------------------------
    Route::prefix('reports')->name('admin.reports.')->middleware('role_permission:Admin|Finance|SuperAdmin,view reports')->group(function () {
        Route::get('/activity', [\App\Http\Controllers\Admin\ReportController::class, 'activity'])->name('activity');
        Route::get('/exports', [\App\Http\Controllers\Admin\ReportController::class, 'exports'])->name('exports');
    });

    // -----------------------------
    // Support & Système
    // -----------------------------
    Route::prefix('support')->name('admin.support.')->middleware('role_permission:Admin|TicketMgr|SuperAdmin,manage support')->group(function () {
        // Support Tickets
        Route::get('/tickets', [\App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [\App\Http\Controllers\Admin\SupportTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [\App\Http\Controllers\Admin\SupportTicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('tickets.show');
        Route::put('/tickets/{ticket}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'update'])->name('tickets.update');
        Route::delete('/tickets/{ticket}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'destroy'])->name('tickets.destroy');

        // System Logs
        Route::get('/logs', [\App\Http\Controllers\Admin\SystemLogController::class, 'index'])->name('logs.index');
        Route::post('/logs/clear', [\App\Http\Controllers\Admin\SystemLogController::class, 'clear'])->name('logs.clear');
        Route::get('/logs/download', [\App\Http\Controllers\Admin\SystemLogController::class, 'download'])->name('logs.download');

        // Backups (placeholder for now)
        Route::get('/backups', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
    });

});

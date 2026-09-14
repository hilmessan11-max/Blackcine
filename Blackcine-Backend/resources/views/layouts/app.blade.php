<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BlackCine Admin')</title>
    <!-- Google Fonts - Inter pour un meilleur rendu des caractères français -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Material Symbols Outlined -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-red': '#DC2626',
                        'dark-red': '#991B1B',
                        'light-red': '#EF4444',
                    }
                }
            }
        }
    </script>
    <style>
        /* Couleurs personnalisées Rouge/Noir */
        :root {
            --primary-red: #DC2626;
            --dark-red: #991B1B;
            --light-red: #EF4444;
            --black-bg: #0F0F0F;
            --dark-gray: #1A1A1A;
            --medium-gray: #2A2A2A;
        }

        /* Sidebar personnalisée */
        .sidebar {
            background: linear-gradient(180deg, var(--black-bg) 0%, var(--dark-gray) 100%);
        }

        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-red) var(--dark-gray);
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: var(--dark-gray);
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: var(--primary-red);
            border-radius: 3px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--light-red);
        }

        /* Menu items avec transitions fluides */
        .menu-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            border-radius: 0.5rem;
            margin: 0.25rem 0.75rem;
        }

        .menu-item:hover {
            background: var(--medium-gray);
            border-left-color: var(--light-red);
            transform: translateX(4px);
        }

        .menu-item.active {
            background: var(--medium-gray);
            border-left-color: var(--primary-red);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            width: 3px;
            height: 100%;
            background: var(--primary-red);
            box-shadow: 0 0 10px var(--primary-red);
            border-radius: 0 3px 3px 0;
        }

        /* Material Icons styling */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            transition: font-variation-settings 0.3s ease;
        }

        .menu-item.active .material-symbols-outlined,
        .menu-item:hover .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24;
        }

        /* Dropdown menu avec animation smooth */
        .dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            opacity: 0;
        }

        .dropdown-menu.open {
            max-height: 500px;
            opacity: 1;
        }

        .dropdown-toggle .material-symbols-outlined {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-toggle.open .material-symbols-outlined {
            transform: rotate(180deg);
        }

        /* Section headers */
        .section-header {
            color: var(--primary-red);
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-size: 0.7rem;
            margin: 1.5rem 0 0.5rem 0;
            padding: 0 1.5rem;
            border-bottom: 1px solid rgba(220, 38, 38, 0.2);
            padding-bottom: 0.5rem;
        }

        /* Forms et inputs améliorés */
        .form-input {
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-red);
            ring: 2px;
            ring-color: rgba(220, 38, 38, 0.2);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Tables améliorées */
        .table-row {
            transition: background-color 0.2s ease;
        }

        .table-row:nth-child(even) {
            background-color: #f9fafb;
        }

        .table-row:hover {
            background-color: #f3f4f6;
        }

        /* Modals avec overlay blur */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            transition: opacity 0.3s ease;
        }

        .modal-content {
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Cartes avec hover et transitions */
        .card {
            border-radius: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Logo effet */
        .logo-text {
            background: linear-gradient(135deg, var(--light-red) 0%, var(--primary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 900;
        }

        /* Badge notification */
        .badge {
            background: var(--primary-red);
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            aside {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 50;
            }
            aside.mobile-open {
                transform: translateX(0);
            }
            main {
                margin-left: 0 !important;
            }
        }

        /* Table improvements */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: var(--primary-red);
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: var(--dark-red);
        }

        /* Sidebar sans scroll horizontal */
        aside {
            overflow-x: hidden;
        }
        
        body {
            overflow-y: auto;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Application de Inter partout pour éviter les problèmes de rendu */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Exception pour Material Symbols qui doit garder sa propre police */
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined' !important;
            vertical-align: middle;
            line-height: 1;
        }

        /* Fix spécifique pour les inputs et boutons */
        input, textarea, select, button {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 sidebar text-white fixed h-full flex flex-col overflow-hidden shadow-2xl">
            <!-- Header -->
            <div class="p-6 border-b border-gray-800 flex-shrink-0 flex flex-col items-center">
                <img src="{{ asset('images/logo-blackcine.png') }}" alt="BlackCiné Logo" class="h-12 w-auto mb-2">
            </div>
            
            <!-- Navigation - Scrollable -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll pb-6">
                <!-- 1. Tableau de bord -->
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-4 py-3 relative">
                    <span class="material-symbols-outlined mr-3 text-xl text-gray-300">dashboard</span>
                    <span class="text-sm font-medium text-gray-200">{{ __('Dashboard') }}</span>
                </a>

                <!-- 2. Contenu éditorial -->
                <div class="section-header">CONTENU ÉDITORIAL</div>
                <div class="relative">
                    <button onclick="toggleDropdown('editorial')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">article</span>
                            <span class="text-sm font-medium text-gray-200">Éditorial</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="editorial-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.articles.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">newspaper</span>
                            Actualités
                        </a>
                        <a href="{{ route('admin.videos.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">play_circle</span>
                            Vidéos
                        </a>
                        <a href="{{ route('admin.editorial.selections.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">collections</span>
                            Sélections
                        </a>
                        <a href="{{ route('admin.editorial.slides.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">view_carousel</span>
                            Slider & Bannières
                        </a>
                    </div>
                </div>

                <!-- 3. Catalogue -->
                <div class="section-header">CATALOGUE</div>
                <div class="relative">
                    <button onclick="toggleDropdown('catalog')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">movie</span>
                            <span class="text-sm font-medium text-gray-200">{{ __('Catalog') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="catalog-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.films.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">movie</span>
                            {{ __('Films') }}
                        </a>
                        <a href="{{ route('admin.series.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">tv</span>
                            {{ __('Series') }}
                        </a>
                        <a href="{{ route('admin.catalog.import-export.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">import_export</span>
                            Import / Export
                        </a>
                    </div>
                </div>

                <!-- 4. Fiches titres -->
                <div class="relative">
                    <button onclick="toggleDropdown('titles')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">description</span>
                            <span class="text-sm font-medium text-gray-200">Fiches titres</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="titles-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.titles.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">list</span>
                            Liste complète
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">rate_review</span>
                            Avis utilisateurs
                        </a>
                        <a href="{{ route('admin.reviews.editorial') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">edit_note</span>
                            Critiques rédaction
                        </a>
                        <a href="{{ route('admin.rankings.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">emoji_events</span>
                            Classements
                        </a>
                    </div>
                </div>

                <!-- 5. Tickets & Box-Office -->
                <div class="section-header">BILLETTERIE</div>
                <div class="relative">
                    <button onclick="toggleDropdown('boxoffice')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">confirmation_number</span>
                            <span class="text-sm font-medium text-gray-200">Box-Office</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="boxoffice-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.boxoffice.showtimes.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">schedule</span>
                            Séances
                        </a>
                        <a href="{{ route('admin.boxoffice.cinemas.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">theaters</span>
                            Cinémas
                        </a>
                        <a href="{{ route('admin.tickets.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">shopping_cart</span>
                            Commandes
                        </a>
                    </div>
                </div>

                <!-- 6. Communauté -->
                <div class="section-header">COMMUNAUTÉ</div>
                <div class="relative">
                    <button onclick="toggleDropdown('community')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">groups</span>
                            <span class="text-sm font-medium text-gray-200">Communauté</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="community-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.community.castings.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">cast</span>
                            Castings
                        </a>
                        <a href="{{ route('admin.community.projects.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">folder</span>
                            Projets
                        </a>
                        <a href="{{ route('admin.community.contests.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">trophy</span>
                            Concours
                        </a>
                        <a href="{{ route('admin.community.talents.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">star</span>
                            Talents
                        </a>
                    </div>
                </div>

                <!-- 7. Partenaires & Festivals -->
                <div class="relative">
                    <button onclick="toggleDropdown('partners')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">festival</span>
                            <span class="text-sm font-medium text-gray-200">{{ __('Partners') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="partners-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.partners.festivals.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">celebration</span>
                            Festivals
                        </a>
                        <a href="{{ route('admin.partners.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">handshake</span>
                            Partenaires
                        </a>
                    </div>
                </div>

                <!-- 8. Publicité & Monétisation -->
                <div class="section-header">MONÉTISATION</div>
                <div class="relative">
                    <button onclick="toggleDropdown('advertising')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">campaign</span>
                            <span class="text-sm font-medium text-gray-200">Publicité</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="advertising-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.advertising.campaigns.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">ads_click</span>
                            Campagnes
                        </a>
                        <a href="{{ route('admin.advertising.sponsored.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">sponsor</span>
                            Contenus Sponsorisés
                        </a>
                        <a href="{{ route('admin.advertising.affiliate.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">link</span>
                            Affiliation
                        </a>
                    </div>
                </div>

                <!-- 9. Transactions & Finance -->
                <div class="relative">
                    <button onclick="toggleDropdown('finance')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">account_balance_wallet</span>
                            <span class="text-sm font-medium text-gray-200">{{ __('Finance') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="finance-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.finance.reports') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">assessment</span>
                            Rapports financiers
                        </a>
                        <a href="{{ route('admin.boxoffice.orders.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">receipt_long</span>
                            Commandes
                        </a>
                    </div>
                </div>

                <!-- 10. Utilisateurs & Rôles -->
                <div class="section-header">UTILISATEURS</div>
                <div class="relative">
                    <button onclick="toggleDropdown('users')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">people</span>
                            <span class="text-sm font-medium text-gray-200">{{ __('Users') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="users-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.users.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">person</span>
                            Utilisateurs Front
                        </a>
                        <a href="{{ route('admin.users.pro-accounts.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">workspace_premium</span>
                            Comptes Pros
                        </a>
                        <a href="{{ route('admin.settings.roles.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">admin_panel_settings</span>
                            Rôles & Permissions
                        </a>
                    </div>
                </div>

                <!-- 11. Modération & Sécurité -->
                <a href="{{ route('admin.moderation.index') }}" class="menu-item {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }} flex items-center px-4 py-3 relative">
                    <span class="material-symbols-outlined mr-3 text-xl text-gray-300">shield</span>
                    <span class="text-sm font-medium text-gray-200">Modération</span>
                </a>

                <!-- 12. Notifications & Communication -->
                <div class="section-header">COMMUNICATION</div>
                <div class="relative">
                    <button onclick="toggleDropdown('notifications')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">notifications</span>
                            <span class="text-sm font-medium text-gray-200">Notifications</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="notifications-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.notifications.templates.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">email</span>
                            Modèles d'e-mails
                        </a>
                        <a href="{{ route('admin.notifications.push.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">notifications_active</span>
                            Push Notifications
                        </a>
                        <a href="{{ route('admin.notifications.sms.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">sms</span>
                            SMS & OTP
                        </a>
                    </div>
                </div>

                <!-- 13. Newsletter & Marketing -->
                <div class="relative">
                    <button onclick="toggleDropdown('newsletter')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">mail</span>
                            <span class="text-sm font-medium text-gray-200">Newsletter</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="newsletter-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.newsletter.subscribers.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">contacts</span>
                            Abonnés
                        </a>
                        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">campaign</span>
                            Campagnes
                        </a>
                        <a href="{{ route('admin.newsletter.stats.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">bar_chart</span>
                            Statistiques
                        </a>
                        <a href="{{ route('admin.marketing.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">trending_up</span>
                            Marketing
                        </a>
                    </div>
                </div>

                <!-- 14. Médiathèque -->
                <a href="{{ route('admin.media.index') }}" class="menu-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }} flex items-center px-4 py-3 relative">
                    <span class="material-symbols-outlined mr-3 text-xl text-gray-300">image</span>
                    <span class="text-sm font-medium text-gray-200">Médiathèque</span>
                </a>

                <!-- 15. SEO & Référencement -->
                <a href="{{ route('admin.seo.index') }}" class="menu-item {{ request()->routeIs('admin.seo.*') ? 'active' : '' }} flex items-center px-4 py-3 relative">
                    <span class="material-symbols-outlined mr-3 text-xl text-gray-300">search</span>
                    <span class="text-sm font-medium text-gray-200">SEO</span>
                </a>

                <!-- 16. Paramètres généraux -->
                <div class="section-header">SYSTÈME</div>
                <div class="relative">
                    <button onclick="toggleDropdown('settings')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">settings</span>
                            <span class="text-sm font-medium text-gray-200">{{ __('Settings') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="settings-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">tune</span>
                            Général
                        </a>
                        <a href="{{ route('admin.settings.index', ['tab' => 'payment']) }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">payment</span>
                            Paiement
                        </a>
                        <a href="{{ route('admin.settings.index', ['tab' => 'ticket']) }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">confirmation_number</span>
                            Billetterie
                        </a>
                    </div>
                </div>

                <!-- 17. Rapports & Exports -->
                <div class="relative">
                    <button onclick="toggleDropdown('reports')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">assessment</span>
                            <span class="text-sm font-medium text-gray-200">{{ __('Reports') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="reports-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.reports.activity') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">activity</span>
                            Activité
                        </a>
                        <a href="{{ route('admin.reports.exports') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">download</span>
                            Exports
                        </a>
                    </div>
                </div>

                <!-- 18. Support & Logs -->
                <div class="relative">
                    <button onclick="toggleDropdown('support')" class="dropdown-toggle w-full menu-item flex items-center justify-between px-4 py-3 relative">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-3 text-xl text-gray-300">support_agent</span>
                            <span class="text-sm font-medium text-gray-200">Support</span>
                        </div>
                        <span class="material-symbols-outlined text-red-500 text-lg">expand_more</span>
                    </button>
                    <div id="support-dropdown" class="dropdown-menu bg-black">
                        <a href="{{ route('admin.support.tickets.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">confirmation_number</span>
                            Tickets
                        </a>
                        <a href="{{ route('admin.support.logs.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">description</span>
                            Logs
                        </a>
                        <a href="{{ route('admin.support.backups.index') }}" class="menu-item flex items-center px-4 py-2.5 pl-12 text-sm text-gray-300 hover:text-white">
                            <span class="material-symbols-outlined mr-2 text-base">backup</span>
                            Sauvegardes
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Footer -->
            <div class="p-4 border-t border-gray-800 flex-shrink-0 space-y-3">
                <!-- Language Switcher -->
                <div class="flex justify-center space-x-2 bg-gray-900 rounded-lg p-1">
                    <a href="{{ route('lang.switch', 'fr') }}" class="flex-1 text-center text-xs py-1 rounded {{ app()->getLocale() == 'fr' ? 'bg-red-600 text-white' : 'text-gray-400 hover:text-white' }}">FR</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="flex-1 text-center text-xs py-1 rounded {{ app()->getLocale() == 'en' ? 'bg-red-600 text-white' : 'text-gray-400 hover:text-white' }}">EN</a>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span>v1.0.0</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center text-red-500 hover:text-red-400 transition-colors duration-200">
                            <span class="material-symbols-outlined mr-1 text-base">logout</span>
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top Bar (Uniquement sur Dashboard) -->
            @if(request()->routeIs('admin.dashboard'))
            <div class="bg-white border-b border-gray-200 px-6 py-4 sticky top-0 z-10 shadow-sm">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">@yield('title', 'BlackCine Admin')</h2>
                        <p class="text-sm text-gray-500">Bienvenue, {{ Auth::user()->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Page Content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-lg shadow-sm flex items-center">
                        <span class="material-symbols-outlined mr-2 text-green-600">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-lg shadow-sm flex items-center">
                        <span class="material-symbols-outlined mr-2 text-red-600">error</span>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- JavaScript pour les dropdowns -->
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id + '-dropdown');
            const button = dropdown.previousElementSibling;
            
            // Fermer tous les autres dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.remove('open');
                    menu.previousElementSibling.classList.remove('open');
                }
            });

            // Toggle le dropdown actuel
            dropdown.classList.toggle('open');
            button.classList.toggle('open');
        }

        // Ouvrir automatiquement le dropdown actif au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const activeLink = document.querySelector('.dropdown-menu a.menu-item.active');
            if (activeLink) {
                const dropdown = activeLink.closest('.dropdown-menu');
                const button = dropdown.previousElementSibling;
                dropdown.classList.add('open');
                button.classList.add('open');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            document.querySelector('aside').classList.toggle('mobile-open');
        }
    </script>
</body>
</html>

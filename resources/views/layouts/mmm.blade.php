<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titre', 'ZeDuc@Space - Administration')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-content: #f7f4ee;
            --sidebar-width: 250px;
            --accent-gold: #cfbd97;
            --accent-gold-hover: #b8a47e;
            --dark-header: #121212;
            --dark-sidebar: #1a1a1a;
        }

        body {
            background-color: var(--bg-content);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            color: #2b2b2b;
        }

        /* Top Header */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 48px;
            background-color: var(--dark-header);
            color: #8a8a8a;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1040;
            font-size: 13px;
        }

        .admin-menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 4px;
            background: transparent;
            color: #fff;
        }

        .admin-header .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            font-weight: 500;
        }

        .admin-header .user-avatar-mini {
            width: 24px;
            height: 24px;
            background-color: #cfbd97;
            color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
        }

        /* Sidebar Navigation - Image mise à jour ici */
        .admin-sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 48px;
            bottom: 0;
            left: 0;
            background: linear-gradient(180deg, rgba(20, 20, 20, 0.92) 0%, rgba(10, 10, 10, 0.96) 100%), 
                        url('{{ asset("images/maquette-employes.png") }}') center/cover no-repeat;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px 15px;
            z-index: 1030;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            background-color: #000;
            border: 1px solid var(--accent-gold);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-gold);
            font-weight: bold;
            font-size: 14px;
        }

        .brand-text h5 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            color: #ffffff;
        }

        .brand-text small {
            font-size: 11px;
            color: var(--accent-gold);
            display: block;
        }

        .sidebar-section-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888888;
            margin-bottom: 12px;
            padding-left: 8px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-nav li {
            margin-bottom: 6px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #d1d1d1;
            text-decoration: none;
            border-radius: 20px;
            font-size: 13.5px;
            transition: all 0.2s ease-in-out;
        }

        .sidebar-nav a:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .sidebar-nav a.active {
            background-color: var(--accent-gold);
            color: #000000;
            font-weight: 600;
        }

        .sidebar-nav a.active i {
            color: #000000;
        }

        .sidebar-footer {
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-footer .settings-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #d1d1d1;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 13.5px;
            margin-bottom: 12px;
        }

        .sidebar-footer .settings-link:hover,
        .sidebar-footer .settings-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--accent-gold);
        }

        .user-profile-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.4);
            padding: 10px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .user-profile-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar-circle {
            width: 34px;
            height: 34px;
            background-color: var(--accent-gold);
            color: #000000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
        }

        .user-name-role {
            line-height: 1.2;
        }

        .user-name-role .name {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
        }

        .user-name-role .role {
            font-size: 10px;
            color: #aaa;
        }

        .btn-logout {
            background: none;
            border: none;
            color: #ff6b6b;
            cursor: pointer;
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 4px;
        }

        .btn-logout:hover {
            background-color: rgba(255, 107, 107, 0.1);
        }

        /* Content Area */
        .admin-main-content {
            margin-left: var(--sidebar-width);
            margin-top: 48px;
            padding: 30px;
            min-height: calc(100vh - 48px);
            min-width: 0;
        }

        @media (max-width: 767.98px) {
            body { overflow-x: hidden; }

            .admin-header {
                padding: 0 12px;
                gap: 10px;
            }

            .admin-menu-toggle { display: inline-flex; flex: 0 0 auto; }

            .admin-header > div:first-of-type {
                margin-right: auto;
                white-space: nowrap;
            }

            .admin-sidebar {
                width: min(var(--sidebar-width), 86vw);
                transform: translateX(-105%);
                transition: transform .2s ease;
                overflow-y: auto;
                overscroll-behavior: contain;
                box-shadow: 4px 0 18px rgba(0, 0, 0, .28);
            }

            .admin-sidebar.is-open { transform: translateX(0); }

            .admin-sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 48px 0 0;
                z-index: 1020;
                border: 0;
                background: rgba(0, 0, 0, .42);
            }

            .admin-sidebar-backdrop.is-open { display: block; }

            .admin-main-content {
                width: 100%;
                margin-left: 0;
                padding: 18px 14px;
                overflow-x: clip;
            }

            .admin-main-content .d-flex.justify-content-between.align-items-start,
            .admin-main-content .d-flex.justify-content-between.align-items-center {
                flex-wrap: wrap;
            }

            .admin-main-content .d-flex.gap-2 {
                flex-wrap: wrap;
            }

            .admin-main-content .table-responsive {
                max-width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .admin-main-content img,
            .admin-main-content input,
            .admin-main-content select,
            .admin-main-content textarea {
                max-width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-sidebar { transition: none; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Barre supérieure fixe -->
    <header class="admin-header">
        <button class="admin-menu-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="admin-sidebar">
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>
        <div>Barre administrateur</div>
        <div class="user-badge">
            <div class="user-avatar-mini">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <span>{{ Auth::user()->name ?? 'Administrateur' }}</span>
        </div>
    </header>

    <!-- Barre latérale (Sidebar) -->
    <button class="admin-sidebar-backdrop" type="button" aria-label="Fermer le menu"></button>
    <aside class="admin-sidebar" id="admin-sidebar">
        <div>
            <!-- Branding Logo -->
            <div class="sidebar-brand">
                <div class="brand-logo">ZeDuc</div>
                <div class="brand-text">
                    <h5>ZeDuc@Space</h5>
                    <small>Mon Miam Miam</small>
                </div>
            </div>

            <div class="sidebar-section-label">Espace Administrateur</div>

            <!-- Navigation Principale -->
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i> Tableau de bord
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.employees.index') }}" class="{{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i> Employés
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.menu.index') }}" class="{{ request()->routeIs('admin.menu*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i> Menu
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i> Catégories
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i> Promotions & événements
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.claims.index') }}" class="{{ request()->routeIs('admin.claims*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i> Réclamations
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i> Paiements
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.stats') ?? '#' }}" class="{{ request()->routeIs('admin.stats*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i> Statistiques
                    </a>
                </li>
            </ul>
        </div>

        <!-- Bas de Sidebar : Paramètres & Profil -->
        <div class="sidebar-footer">
            <a href="{{ route('admin.settings.index') ?? '#' }}" class="settings-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Paramètres
            </a>

            <div class="user-profile-card">
                <div class="user-profile-info">
                    <div class="user-avatar-circle">
                        {{ strtoupper(substr(Auth::user()->name ?? 'AN', 0, 2)) }}
                    </div>
                    <div class="user-name-role">
                        <div class="name">{{ Auth::user()->name ?? 'Aïcha N.' }}</div>
                        <div class="role">ZeDuc@Space</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn-logout" title="Se déconnecter">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Zone de contenu principal -->
    <main class="admin-main-content">
        @yield('contenu')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const toggle = document.querySelector('.admin-menu-toggle');
            const sidebar = document.querySelector('.admin-sidebar');
            const backdrop = document.querySelector('.admin-sidebar-backdrop');

            if (!toggle || !sidebar || !backdrop) return;

            const closeMenu = () => {
                sidebar.classList.remove('is-open');
                backdrop.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('admin-menu-open');
            };
            const openMenu = () => {
                sidebar.classList.add('is-open');
                backdrop.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
                document.body.classList.add('admin-menu-open');
            };

            toggle.addEventListener('click', () =>
                sidebar.classList.contains('is-open') ? closeMenu() : openMenu()
            );
            backdrop.addEventListener('click', closeMenu);
            sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeMenu();
            });
            window.matchMedia('(min-width: 768px)').addEventListener('change', closeMenu);
        })();
    </script>
    @stack('scripts')
</body>
</html>
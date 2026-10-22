<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Accueil') - ZeDuc@Space · Mon Miam Miam</title>

    {{-- Bootstrap local + icônes --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- Polices : Serif (titres) + Sans (texte courant) --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ================= CHARTE GRAPHIQUE ================= */
        :root {
            --miam-bg-cream: #f6f1e7;
            --miam-gold: #cfc096;
            --miam-gold-hover: #bdae82;
            --miam-dark: #0a0a0a;
            --miam-footer: #191612;
            --font-serif: 'Playfair Display', Georgia, serif;
            --font-sans: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--miam-bg-cream);
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main { flex: 1; }

        .font-serif        { font-family: var(--font-serif); }
        .font-serif-italic { font-family: var(--font-serif); font-style: italic; }

        /* ================= NAVBAR ================= */
        .navbar-miam { background-color: var(--miam-dark); padding: 0.85rem 0; }

        .navbar-miam .nav-link {
            color: #d1d1d1 !important;
            font-size: 0.9rem;
            margin: 0 0.75rem;
            padding-bottom: 0.35rem;
            position: relative;
            transition: color 0.2s;
        }

        .navbar-miam .nav-link:hover,
        .navbar-miam .nav-link.active { color: var(--miam-gold) !important; }

        .navbar-miam .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background-color: var(--miam-gold);
        }

        .badge-user {
            background-color: var(--miam-gold);
            color: #111;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 1.1rem;
            border-radius: 50rem;
            text-decoration: none;
        }

        /* ================= BOUTONS ================= */
        .btn-gold {
            background-color: var(--miam-gold);
            color: #111;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 10px;
            padding: 0.65rem 1.4rem;
            border: none;
            transition: background-color 0.2s;
        }

        .btn-gold:hover { background-color: var(--miam-gold-hover); color: #000; }

        .btn-outline-gold {
            background-color: transparent;
            color: #fff;
            border: 1px solid rgba(207, 192, 150, 0.5);
            font-size: 0.85rem;
            border-radius: 10px;
            padding: 0.55rem 1.25rem;
            transition: all 0.2s;
        }

        .btn-outline-gold:hover {
            border-color: var(--miam-gold);
            background-color: rgba(207, 192, 150, 0.1);
            color: #fff;
        }

        .btn-outline-miam {
            background: #fff;
            color: #111;
            border: 1px solid #222;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.65rem 1.25rem;
            transition: all 0.2s;
        }

        .btn-outline-miam:hover { background: #111; color: #fff; }

        /* ================= CARTES ================= */
        .miam-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.25rem;
            border: 1px solid rgba(0, 0, 0, 0.04);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            height: 100%;
        }

        /* ================= TABLEAUX ================= */
        .table-miam th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8c826d;
            font-weight: 600;
            border-bottom: 1px solid #eee8d8;
            padding-bottom: 0.6rem;
        }

        .table-miam td {
            padding: 1rem 0.5rem;
            border-bottom: 1px solid #f2ede2;
            font-size: 0.88rem;
            vertical-align: middle;
        }

        /* ================= BADGES DE STATUT ================= */
        .badge-miam {
            font-weight: 600;
            font-size: 0.73rem;
            padding: 0.35rem 0.85rem;
            border-radius: 50rem;
            display: inline-block;
        }

        .badge-prep    { background-color: #d8ccb2; color: #3d3522; }
        .badge-livree  { background-color: #d8f3dc; color: #1b4332; }
        .badge-soumise { background-color: #faedcd; color: #8c5e0b; }
        .badge-annulee { background-color: #f8d7da; color: #842029; }
        .badge-obtenue { background-color: #d8f3dc; color: #1b4332; }
        .badge-attente { background-color: #faedcd; color: #8c5e0b; }

        /* ================= PILLS DE FILTRE ================= */
        .filter-pill {
            border-radius: 50rem;
            padding: 0.35rem 1.15rem;
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid #333;
            color: #111;
            background: transparent;
            transition: all 0.2s;
        }

        .filter-pill.active,
        .filter-pill:hover { background: #000; color: #fff; border-color: #000; }

        /* ================= FORMULAIRES ================= */
        .form-control-miam, .form-select-miam {
            border: 1px solid #dedad0;
            border-radius: 8px;
            font-size: 0.88rem;
            padding: 0.65rem 0.9rem;
        }

        .form-control-miam:focus, .form-select-miam:focus {
            border-color: var(--miam-gold);
            box-shadow: none;
        }

        /* ================= BANDEAU COOKIES ================= */
        .cookie-banner {
            background-color: #000;
            color: #d1d1d1;
            padding: 1.1rem 0;
            font-size: 0.85rem;
        }

        /* ================= FOOTER ================= */
        .footer-miam {
            background-color: var(--miam-footer);
            color: #8c857b;
            font-size: 0.8rem;
            padding: 1.5rem 0;
            margin-top: auto;
        }
    </style>

    {{-- CSS spécifique à chaque page --}}
    @stack('styles')
</head>
<body>

    {{-- ====================== NAVBAR ====================== --}}
    <nav class="navbar navbar-expand-lg navbar-dark navbar-miam sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('etudiant.accueil') }}">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                     style="width: 42px; height: 42px; border: 1px solid var(--miam-gold); color: var(--miam-gold);">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <div class="font-serif fw-semibold text-white lh-1" style="font-size: 1.2rem;">ZeDuc@Space</div>
                    <div class="text-uppercase" style="font-size: 0.6rem; letter-spacing: 2px; color: var(--miam-gold);">Mon Miam Miam</div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMiam">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarMiam">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('etudiant.menu.*') ? 'active' : '' }}"
                           href="{{ route('etudiant.menu.index') }}">Commander</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('etudiant.loyalty.*') ? 'active' : '' }}"
                           href="{{ route('etudiant.loyalty.index') }}">Fidélité et parrainage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('etudiant.reclamations.*') ? 'active' : '' }}"
                           href="{{ route('etudiant.reclamations.index') }}">Historique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.accueil') }}#promotions">Jeux et événements</a>
                    </li>
                </ul>
            </div>

            <div class="d-none d-lg-flex align-items-center">
                <a href="{{ route('etudiant.loyalty.index') }}" class="badge-user">
                    {{ $user->first_name }} {{ strtoupper(substr($user->last_name, 0, 1)) }}. &middot; {{ $balance }} pts
                </a>
            </div>
        </div>
    </nav>

    {{-- ====================== MESSAGES FLASH ====================== --}}
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    @if(session('show_cart_link'))
                        <a href="{{ route('etudiant.cart.index') }}" class="fw-bold text-success ms-2">Voir mon panier</a>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') ?? $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        {{-- Contenu de chaque page --}}
        @yield('content')
    </main>

    {{-- ====================== FOOTER ====================== --}}
    <footer class="footer-miam">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>&copy; 2026 ZeDuc@Space &mdash; Mon Miam Miam</div>
            <div>Fait avec soin pour les étudiants de l'Institut Ucac-Icam</div>
        </div>
    </footer>

    {{-- ====================== BANDEAU COOKIES ====================== --}}
    @unless(session('cookie_consent_answered'))
        <div class="cookie-banner">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    Nous utilisons des cookies pour améliorer ton expérience et mesurer l'audience. Tu peux accepter ou refuser leur utilisation.
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('cookies.consent') }}" method="POST">
                        @csrf
                        <input type="hidden" name="consented" value="0">
                        <button type="submit" class="btn btn-outline-gold rounded-pill px-4 py-1">Refuser</button>
                    </form>
                    <form action="{{ route('cookies.consent') }}" method="POST">
                        @csrf
                        <input type="hidden" name="consented" value="1">
                        <button type="submit" class="btn btn-gold rounded-pill px-4 py-1">Accepter</button>
                    </form>
                </div>
            </div>
        </div>
    @endunless

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>

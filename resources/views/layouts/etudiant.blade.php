<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Jeton CSRF, utile si tu fais plus tard des requêtes JavaScript (fetch/AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Chaque vue peut définir son titre avec @section('title', 'Commander') --}}
    <title>@yield('title', 'Accueil') - Mon Miam Miam</title>

    {{-- Bootstrap en local (comme ton layout app.blade.php) : fonctionne sans internet --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- Icônes Bootstrap Icons (bi bi-...) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    {{-- Police à empattement pour les titres, comme sur la maquette --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ====== CHARTE GRAPHIQUE (variables réutilisables dans toutes les vues) ====== */
        :root {
            --mm-black: #0e0e0e;   /* barre de navigation, bandeau cookies */
            --mm-cream: #f4efe4;   /* fond des pages */
            --mm-gold:  #c8b48a;   /* boutons principaux, accents */
            --mm-gold-dark: #b39d70;
            --mm-ink:   #1c1a17;   /* texte principal */
            --mm-card:  #ffffff;   /* fond des cartes */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--mm-cream);
            color: var(--mm-ink);
            /* flex + min-height : le pied de page reste en bas même si la page est courte */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main { flex: 1; }

        /* Titres en police à empattement */
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', Georgia, serif; }

        /* ====== BARRE DE NAVIGATION ====== */
        .mm-navbar { background-color: var(--mm-black); }
        .mm-navbar .navbar-brand { color: #fff; font-family: 'Playfair Display', serif; font-weight: 600; }
        .mm-navbar .navbar-brand span { color: var(--mm-gold); }
        .mm-navbar .nav-link { color: #d6d3cc; font-size: .9rem; padding: .5rem 1rem; }
        .mm-navbar .nav-link:hover { color: #fff; }
        /* Lien de la page courante : texte blanc + soulignement or */
        .mm-navbar .nav-link.active { color: #fff; box-shadow: inset 0 -2px 0 var(--mm-gold); }

        /* ====== BOUTONS ET CARTES ====== */
        .btn-gold { background-color: var(--mm-gold); color: var(--mm-black); border: none; font-weight: 600; }
        .btn-gold:hover { background-color: var(--mm-gold-dark); color: var(--mm-black); }
        .btn-outline-ink { border: 1px solid var(--mm-ink); color: var(--mm-ink); background: transparent; }
        .btn-outline-ink:hover { background: var(--mm-ink); color: #fff; }

        .mm-card { background: var(--mm-card); border: 0; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.06); }

        /* ====== BANDEAU COOKIES (fixe en bas de l'écran) ====== */
        .cookie-banner {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 1050;
            background: var(--mm-black); color: #d6d3cc; font-size: .85rem;
        }

        /* ====== PIED DE PAGE ====== */
        .mm-footer { background: var(--mm-black); color: #9b978e; font-size: .8rem; }
    </style>

    {{-- Les vues peuvent ajouter leur propre CSS avec @push('styles') ... @endpush --}}
    @stack('styles')
</head>
<body>

    {{-- ====================== 1. BARRE DE NAVIGATION ====================== --}}
    <nav class="navbar navbar-expand-lg mm-navbar">
        <div class="container">
            {{-- Logo : renvoie vers l'accueil --}}
            <a class="navbar-brand" href="{{ route('etudiant.accueil') }}">
                Mon <span>Miam Miam</span>
            </a>

            {{-- Bouton "burger" visible sur mobile uniquement --}}
            <button class="navbar-toggler border-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#menuEtudiant"
                    aria-controls="menuEtudiant" aria-expanded="false" aria-label="Menu">
                <i class="bi bi-list text-white fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="menuEtudiant">
                {{--
                    routeIs('etudiant.accueil') est vrai quand on est sur cette page :
                    on ajoute alors la classe "active" (soulignement or).
                    Les noms de routes viennent de routes/web.php (prefix "etudiant.").
                --}}
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('etudiant.accueil') ? 'active' : '' }}"
                           href="{{ route('etudiant.accueil') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('etudiant.commander') ? 'active' : '' }}"
                           href="{{ route('etudiant.commander') }}">Commander</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('etudiant.fidelite') ? 'active' : '' }}"
                           href="{{ route('etudiant.fidelite') }}">Fidélité et parrainage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('etudiant.historique') ? 'active' : '' }}"
                           href="{{ route('etudiant.historique') }}">Historique</a>
                    </li>
                </ul>

                {{-- Pill or à droite : prénom + points, avec menu déroulant "Déconnexion" --}}
                <div class="dropdown">
                    <button class="btn btn-gold btn-sm rounded-pill px-3 dropdown-toggle"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->first_name }}
                        {{-- ?-> : si le compte de points n'existe pas, on affiche 0 au lieu de planter --}}
                        · {{ auth()->user()->loyaltyAccount?->balance_points ?? 0 }} pts
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('etudiant.fidelite') }}">
                                <i class="bi bi-star me-2"></i>Mes points
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            {{-- La déconnexion est un POST (protégé par @csrf), pas un simple lien --}}
                            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- ====================== 2. CONTENU PRINCIPAL ====================== --}}
    <main class="container py-4">

        {{-- Message de succès (->with('success', ...) dans les controllers) --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        {{-- Message d'erreur général (->with('error', ...)) --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        {{-- Erreur "restaurant fermé" (clé "hours" ajoutée par StoreOrderRequest) --}}
        @error('hours')
            <div class="alert alert-warning">
                <i class="bi bi-clock me-2"></i>{{ $message }}
            </div>
        @enderror

        {{-- C'est ici que s'insère le contenu de chaque page (accueil, commander...) --}}
        @yield('content')
    </main>

    {{-- ====================== 3. PIED DE PAGE ====================== --}}
    {{-- Marge en bas (pb-5) pour que le bandeau cookies ne cache pas le texte --}}
    <footer class="mm-footer py-3 {{ session('cookie_consent_answered') ? '' : 'pb-5' }}">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-1">
            <span>© {{ date('Y') }} Mon Miam Miam. Tous droits réservés.</span>
            <span>Restaurant universitaire · Commandes en ligne</span>
        </div>
    </footer>

    {{-- ====================== 4. BANDEAU COOKIES ====================== --}}
    {{-- Affiché seulement tant que l'étudiant n'a pas répondu (clé posée par CookieController) --}}
    @unless(session('cookie_consent_answered'))
        <div class="cookie-banner py-3">
            <div class="container d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                <span>Nous utilisons des cookies pour améliorer votre expérience et mémoriser vos préférences.</span>
                <div class="d-flex gap-2">
                    {{-- Deux petits formulaires POST : consented=0 (refuser) ou consented=1 (accepter) --}}
                    <form action="{{ route('cookies.store') }}" method="POST" class="mb-0">
                        @csrf
                        <input type="hidden" name="consented" value="0">
                        <button type="submit" class="btn btn-outline-light btn-sm px-3">Refuser</button>
                    </form>
                    <form action="{{ route('cookies.store') }}" method="POST" class="mb-0">
                        @csrf
                        <input type="hidden" name="consented" value="1">
                        <button type="submit" class="btn btn-gold btn-sm px-3">Accepter</button>
                    </form>
                </div>
            </div>
        </div>
    @endunless

    {{-- Bootstrap JS (menu burger, menu déroulant, alertes fermables) --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    {{-- Les vues peuvent ajouter leur JavaScript avec @push('scripts') ... @endpush --}}
    @stack('scripts')
</body>
</html>
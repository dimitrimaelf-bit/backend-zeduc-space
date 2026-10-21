@extends('layouts.app')

@section('content')
<!-- Import de la police Serif (Playfair Display) pour correspondre à la maquette Figma -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
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
    }

    .font-serif-italic {
        font-family: var(--font-serif);
        font-style: italic;
    }

    .font-serif {
        font-family: var(--font-serif);
    }

    /* Navbar */
    .navbar-miam {
        background-color: var(--miam-dark);
        padding: 0.85rem 0;
    }

    .navbar-miam .nav-link {
        color: #d1d1d1 !important;
        font-size: 0.9rem;
        margin: 0 0.75rem;
        transition: color 0.2s;
    }

    .navbar-miam .nav-link:hover,
    .navbar-miam .nav-link.active {
        color: var(--miam-gold) !important;
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

    /* Hero Section */
    .hero-section {
        position: relative;
        background: linear-gradient(rgba(15, 14, 12, 0.72), rgba(15, 14, 12, 0.72)),
                    url('{{ asset("images/background-miam.jpg") }}') center/cover no-repeat;
        background-color: #2b2620;
        padding: 4.5rem 0;
        color: #fff;
    }

    .loyalty-card {
        background: rgba(20, 19, 17, 0.82);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(207, 192, 150, 0.2);
        border-radius: 16px;
        padding: 1.75rem;
    }

    /* Boutons personnalisés */
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

    .btn-gold:hover {
        background-color: var(--miam-gold-hover);
        color: #000;
    }

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

    /* Cartes Menu du jour */
    .dish-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.04);
        padding: 1.5rem 1.25rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .dish-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    }

    .dish-img {
        width: 145px;
        height: 145px;
        object-fit: cover;
        border-radius: 50%;
        margin: 0 auto 1.1rem;
        display: block;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Blocs Promotions & Top 10 */
    .section-box {
        background: #fff;
        border-radius: 18px;
        padding: 2rem;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .promo-poster {
        background-color: #e6decb;
        border-radius: 12px;
        min-height: 290px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        color: #7a705e;
        font-size: 0.9rem;
    }

    .promo-poster img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
    }

    /* Filtres Top 10 */
    .filter-pill {
        border-radius: 50rem;
        padding: 0.3rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid #333;
        color: #111;
        background: #fff;
        transition: all 0.2s;
    }

    .filter-pill.active,
    .filter-pill:hover {
        background: #000;
        color: #fff;
        border-color: #000;
    }

    /* Bandeau Cookies */
    .cookie-banner {
        background-color: #000;
        color: #d1d1d1;
        padding: 1.1rem 0;
        font-size: 0.85rem;
    }
</style>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-miam sticky-top">
    <div class="container">
        <!-- Logo + Nom -->
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

        <!-- Liens centraux -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarMiam">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.menu.index') }}">Commander</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.loyalty.index') }}">Fidélité et parrainage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.reclamations.index') }}">Historique</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#promotions">Jeux et événements</a>
                </li>
            </ul>
        </div>

        <!-- Badge Utilisateur + Solde -->
        <div class="d-none d-lg-flex align-items-center gap-2">
            <a href="{{ route('etudiant.loyalty.index') }}" class="badge-user">
                {{ $user->first_name }} {{ strtoupper(substr($user->last_name, 0, 1)) }}. &middot; {{ $balance }} pts
            </a>
        </div>
    </div>
</nav>

<!-- Messages Flash (Succès / Erreur lors de l'ajout au panier) -->
@if(session('success') || session('error'))
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <a href="{{ route('etudiant.cart.index') }}" class="fw-bold text-success ms-2">Voir mon panier</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
@endif

<!-- ================= HERO SECTION ================= -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <!-- Texte de bienvenue -->
            <div class="col-lg-7">
                <div class="text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 1.5px; color: var(--miam-gold);">
                    Espace Étudiant
                </div>
                <h1 class="font-serif-italic display-5 mb-4" style="color: #f3e5c8; line-height: 1.2;">
                    Bonjour {{ $user->first_name }}, qu’est-ce<br>qu’on mange aujourd’hui ?
                </h1>
                <a href="#menu-du-jour" class="btn btn-gold rounded-pill px-4">
                    Voir le menu du jour
                </a>
            </div>

            <!-- Carte Points de fidélité -->
            <div class="col-lg-4 offset-lg-1">
                <div class="loyalty-card">
                    <div class="small mb-1" style="color: var(--miam-gold);">Mes points de fidélité</div>
                    <div class="font-serif display-5 text-white mb-2">{{ $balance }} pts</div>
                    @php
                        $pointsManquants = max(0, 15 - ($balance % 15));
                    @endphp
                    <p class="small text-secondary mb-3" style="line-height: 1.4;">
                        15 points = 1 000 F de réduction.
                        @if($balance >= 15)
                            Tu peux déjà bénéficier d'une réduction !
                        @else
                            Il te manque {{ $pointsManquants }} points pour la prochaine.
                        @endif
                    </p>
                    <a href="{{ route('etudiant.loyalty.index') }}" class="btn btn-outline-gold rounded-pill">
                        Voir mon historique
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= MENU DU JOUR ================= -->
<section id="menu-du-jour" class="py-5">
    <div class="container">
        <h2 class="font-serif-italic mb-1" style="font-size: 2rem;">Menu du jour</h2>
        <p class="text-muted small mb-4">Choisis tes plats, ajoute-les au panier et paie en ligne.</p>

        <div class="row g-4">
            @forelse($dailySpecials as $item)
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="dish-card text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <img src="{{ $item->image_url ? asset($item->image_url) : 'https://placehold.co/300x300/2b2620/cfc096?text=' . urlencode($item->name) }}"
                                 alt="{{ $item->name }}"
                                 class="dish-img">
                            <h5 class="font-serif-italic mb-1" style="font-size: 1.15rem;">{{ $item->name }}</h5>
                            <div class="text-muted small mb-3">
                                {{ number_format($item->price_fcfa, 0, ',', ' ') }} F
                            </div>
                        </div>

                        <form action="{{ route('etudiant.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-gold w-100">
                                Ajouter au panier
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border text-center py-4">
                        Aucun plat du jour n'est disponible pour le moment.
                        <a href="{{ route('etudiant.menu.index') }}" class="fw-semibold text-dark ms-1">Voir tout le menu</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ================= PROMOTIONS & TOP 10 CLIENTS ================= -->
<section id="promotions" class="pb-5">
    <div class="container">
        <div class="row g-4">
            
            <!-- Colonne Gauche : Promotions et événements -->
            <div class="col-lg-7">
                <div class="section-box h-100">
                    <h3 class="font-serif-italic mb-4" style="font-size: 1.65rem;">Promotions et événements</h3>

                    <div class="row g-3">
                        @forelse($promotions as $promo)
                            <div class="col-md-6">
                                <div class="promo-poster" title="{{ $promo->description }}">
                                    @if($promo->image_url)
                                        <img src="{{ asset($promo->image_url) }}" alt="{{ $promo->title }}">
                                    @else
                                        <div class="text-center p-3">
                                            <div class="fw-semibold text-dark mb-1">{{ $promo->title }}</div>
                                            @if($promo->description)
                                                <div class="small">{{ $promo->description }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <!-- Placeholders identiques à la maquette Figma si la base est vide -->
                            <div class="col-md-6">
                                <div class="promo-poster">Affiche promotion</div>
                            </div>
                            <div class="col-md-6">
                                <div class="promo-poster">Affiche événement ou mini-jeu</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Colonne Droite : Top 10 des clients -->
            <div class="col-lg-5">
                <div class="section-box h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="font-serif-italic mb-3" style="font-size: 1.65rem;">Top 10 des clients</h3>

                        <!-- Boutons de filtre -->
                        <div class="d-flex gap-2 mb-4">
                            <a href="{{ route('etudiant.accueil', ['periode' => 'tout']) }}"
                               class="filter-pill {{ $periode === 'tout' || $periode === 'jour' ? 'active' : '' }}">
                                Jour
                            </a>
                            <a href="{{ route('etudiant.accueil', ['periode' => 'semaine']) }}"
                               class="filter-pill {{ $periode === 'semaine' ? 'active' : '' }}">
                                Semaine
                            </a>
                            <a href="{{ route('etudiant.accueil', ['periode' => 'mois']) }}"
                               class="filter-pill {{ $periode === 'mois' ? 'active' : '' }}">
                                Mois
                            </a>
                        </div>

                        <!-- Liste du classement -->
                        <div class="top-clients-list">
                            @forelse($topClients->take(5) as $index => $client)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="fw-semibold small">
                                        {{ $index + 1 }}. {{ $client->first_name }} {{ strtoupper(substr($client->last_name, 0, 1)) }}.
                                    </span>
                                    <span class="text-muted small">
                                        {{ $client->commandes_terminees }} {{ $client->commandes_terminees > 1 ? 'commandes' : 'commande' }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-muted small py-3 mb-0">Aucune commande enregistrée sur cette période.</p>
                            @endforelse

                            <!-- Affichage déroulant si plus de 5 clients -->
                            @if($topClients->count() > 5)
                                <div class="collapse" id="moreTopClients">
                                    @foreach($topClients->slice(5) as $index => $client)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <span class="fw-semibold small">
                                                {{ $index + 1 }}. {{ $client->first_name }} {{ strtoupper(substr($client->last_name, 0, 1)) }}.
                                            </span>
                                            <span class="text-muted small">
                                                {{ $client->commandes_terminees }} {{ $client->commandes_terminees > 1 ? 'commandes' : 'commande' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3 pt-2">
                        @if($topClients->count() > 5)
                            <a class="text-dark small fw-medium text-decoration-underline"
                               data-bs-toggle="collapse"
                               href="#moreTopClients"
                               role="button">
                                Voir les 10 premiers
                            </a>
                        @else
                            <span class="text-dark small fw-medium text-decoration-underline">
                                Voir les 10 premiers
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= BANDEAU COOKIES ================= -->
@if(!session('cookie_consent_answered'))
    <div class="cookie-banner">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div>
                Nous utilisons des cookies pour améliorer ton expérience et mesurer l'audience. Tu peux accepter ou refuser leur utilisation.
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('cookies.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="consented" value="0">
                    <button type="submit" class="btn btn-outline-gold rounded-pill px-4 py-1">Refuser</button>
                </form>
                <form action="{{ route('cookies.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="consented" value="1">
                    <button type="submit" class="btn btn-gold rounded-pill px-4 py-1">Accepter</button>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- ================= FOOTER ================= -->
<footer style="background-color: var(--miam-footer); color: #8c857b; font-size: 0.8rem;" class="py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div>&copy; 2026 ZeDuc@Space &mdash; Mon Miam Miam</div>
        <div>Fait avec soin pour les étudiants de l'Institut Ucac-Icam</div>
    </div>
</footer>
@endsection
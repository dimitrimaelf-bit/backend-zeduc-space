@extends('layouts.etudiant')

@section('title', 'Accueil')

@push('styles')
<style>
    /* ================= HERO SECTION ================= */
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

    /* ================= MENU DU JOUR ================= */
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

    /* ================= PROMOTIONS & TOP 10 ================= */
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
</style>
@endpush

@section('content')

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
                    Bonjour {{ $user->first_name }}, qu'est-ce<br>qu'on mange aujourd'hui ?
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
                            <!-- Placeholders si la base est vide -->
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
                            <a href="{{ route('etudiant.accueil', ['periode' => 'jour']) }}"
                               class="filter-pill {{ $periode === 'jour' || $periode === 'tout' ? 'active' : '' }}">
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
                        <div>
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

                            <!-- Clients 6 à 10 (déroulant) -->
                            @if($topClients->count() > 5)
                                <div class="collapse" id="moreTopClients">
                                    @foreach($topClients->slice(5) as $index => $client)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <span class="fw-semibold small">
                                                {{ $index + 6 }}. {{ $client->first_name }} {{ strtoupper(substr($client->last_name, 0, 1)) }}.
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
                            <span class="text-dark small fw-medium text-decoration-underline">Voir les 10 premiers</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

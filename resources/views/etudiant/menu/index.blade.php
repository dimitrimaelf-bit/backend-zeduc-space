@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --miam-bg-cream: #f6f1e7;
        --miam-gold: #cfc096;
        --miam-gold-hover: #bdae82;
        --miam-dark: #0a0a0a;
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
        padding-bottom: 0.35rem;
        position: relative;
    }

    .navbar-miam .nav-link:hover,
    .navbar-miam .nav-link.active {
        color: var(--miam-gold) !important;
    }

    .navbar-miam .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
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

    /* Onglets de filtres (Pills) */
    .category-pill {
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

    .category-pill.active,
    .category-pill:hover {
        background: #000;
        color: #fff;
        border-color: #000;
    }

    /* Lignes des articles du menu */
    .menu-row {
        padding: 1.1rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .menu-thumb {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .menu-icon-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: 1px solid #d5c8a8;
        background-color: #f9f6ef;
        color: #8c7647;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    /* Boutons + et - */
    .qty-btn-minus {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid #222;
        background: transparent;
        color: #111;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        padding: 0;
        transition: all 0.15s;
    }

    .qty-btn-minus:hover:not(:disabled) {
        background: #111;
        color: #fff;
    }

    .qty-btn-minus:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .qty-btn-plus {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background-color: var(--miam-gold);
        color: #111;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        padding: 0;
        transition: background-color 0.15s;
    }

    .qty-btn-plus:hover {
        background-color: var(--miam-gold-hover);
    }

    /* Carte Panier en bas */
    .cart-box {
        background: #fff;
        border-radius: 18px;
        padding: 2.25rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.03);
    }

    /* Sélecteurs Mode de service & Paiement */
    .mode-btn {
        border: 1px solid #222;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        background: #fff;
        color: #111;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-check:checked + .mode-btn {
        background: #000;
        color: #fff;
        border-color: #000;
    }

    .pay-pill {
        border: 1px solid #333;
        border-radius: 50rem;
        padding: 0.35rem 1.1rem;
        font-size: 0.8rem;
        font-weight: 500;
        background: #fff;
        color: #111;
        cursor: pointer;
    }

    .btn-check:checked + .pay-pill {
        background: #000;
        color: #fff;
        border-color: #000;
    }

    .btn-pay-confirm {
        background-color: var(--miam-gold);
        color: #111;
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 10px;
        padding: 0.8rem;
        border: none;
        width: 100%;
        transition: background-color 0.2s;
    }

    .btn-pay-confirm:hover:not(:disabled) {
        background-color: var(--miam-gold-hover);
    }
</style>

<!-- ================= NAVBAR ================= -->
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
                    <a class="nav-link active" href="{{ route('etudiant.menu.index') }}">Commander</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.loyalty.index') }}">Fidélité et parrainage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.reclamations.index') }}">Historique</a>
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

<!-- ================= CONTENU PRINCIPAL ================= -->
<div class="container py-5" style="max-width: 1020px;">

    <!-- Alertes Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error') || $errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            {{ session('error') ?? $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- En-tête -->
    <h1 class="font-serif-italic mb-1" style="font-size: 2.35rem;">Commander</h1>
    <p class="text-muted small mb-4">Compose ta commande, choisis sur place ou en livraison, puis paie.</p>

    <!-- Filtres rapides par ancres -->
    <div class="d-flex flex-wrap gap-2 mb-5">
        <a href="#section-plats-du-jour" class="category-pill active">Plats du jour</a>
        @foreach($categories as $category)
            <a href="#section-cat-{{ $category->id }}" class="category-pill">{{ $category->name }}</a>
        @endforeach
        <a href="#section-promotions" class="category-pill">Promotions</a>
    </div>

    <!-- ================= SECTION : PLATS DU JOUR ================= -->
    <div id="section-plats-du-jour" class="mb-5">
        <h3 class="font-serif-italic mb-3" style="font-size: 1.45rem;">Plats du jour</h3>

        @forelse($dailySpecials as $item)
            @php
                $qty = $cart[$item->id]['quantity'] ?? 0;
            @endphp
            <div class="menu-row d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $item->image_url ? asset($item->image_url) : 'https://placehold.co/120x120/2b2620/cfc096?text=' . urlencode(substr($item->name, 0, 2)) }}"
                         alt="{{ $item->name }}"
                         class="menu-thumb">
                    <div>
                        <div class="font-serif-italic fw-medium" style="font-size: 1.1rem;">{{ $item->name }}</div>
                        <div class="text-muted small">{{ number_format($item->price_fcfa, 0, ',', ' ') }} F</div>
                    </div>
                </div>

                <!-- Contrôles - / Quantité / + -->
                <div class="d-flex align-items-center gap-3">
                    <form action="{{ route('etudiant.cart.remove') }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <button type="submit" class="qty-btn-minus" {{ $qty === 0 ? 'disabled' : '' }}>&minus;</button>
                    </form>

                    <span class="fw-semibold small" style="min-width: 14px; text-align: center;">{{ $qty }}</span>

                    <form action="{{ route('etudiant.cart.add') }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="qty-btn-plus">+</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-muted small">Aucun plat du jour enregistré.</p>
        @endforelse
    </div>

    <!-- ================= SECTIONS : CATÉGORIES (Ex: Boissons) ================= -->
    @foreach($categories as $category)
        @if($category->items->isNotEmpty())
            <div id="section-cat-{{ $category->id }}" class="mb-5">
                <h3 class="font-serif-italic mb-3" style="font-size: 1.45rem;">{{ $category->name }}</h3>

                @foreach($category->items as $item)
                    @php
                        $qty = $cart[$item->id]['quantity'] ?? 0;
                        $isDrink = str_contains(strtolower($category->name), 'boisson');
                    @endphp
                    <div class="menu-row d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            @if($item->image_url)
                                <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}" class="menu-thumb">
                            @elseif($isDrink)
                                <div class="menu-icon-circle">
                                    <i class="bi bi-cup-straw"></i>
                                </div>
                            @else
                                <img src="https://placehold.co/120x120/2b2620/cfc096?text={{ urlencode(substr($item->name, 0, 2)) }}"
                                     alt="{{ $item->name }}" class="menu-thumb">
                            @endif

                            <div>
                                <div class="font-serif-italic fw-medium" style="font-size: 1.1rem;">{{ $item->name }}</div>
                                <div class="text-muted small">{{ number_format($item->price_fcfa, 0, ',', ' ') }} F</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <form action="{{ route('etudiant.cart.remove') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <button type="submit" class="qty-btn-minus" {{ $qty === 0 ? 'disabled' : '' }}>&minus;</button>
                            </form>

                            <span class="fw-semibold small" style="min-width: 14px; text-align: center;">{{ $qty }}</span>

                            <form action="{{ route('etudiant.cart.add') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="qty-btn-plus">+</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    <!-- ================= SECTION : PROMOTIONS ================= -->
    <div id="section-promotions" class="mb-4">
        <h3 class="font-serif-italic mb-3" style="font-size: 1.45rem;">Promotions</h3>

        @forelse($promotions as $promo)
            <div class="menu-row d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    @if($promo->image_url)
                        <img src="{{ asset($promo->image_url) }}" alt="{{ $promo->title }}" class="menu-thumb">
                    @else
                        <div class="menu-icon-circle">
                            <i class="bi bi-stars"></i>
                        </div>
                    @endif
                    <div>
                        <div class="font-serif-italic fw-medium" style="font-size: 1.1rem;">{{ $promo->title }}</div>
                        <div class="text-muted small">{{ $promo->description }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="qty-btn-minus" disabled>&minus;</button>
                    <span class="fw-semibold small" style="min-width: 14px; text-align: center;">0</span>
                    <button type="button" class="qty-btn-plus" disabled>+</button>
                </div>
            </div>
        @empty
            <p class="text-muted small">Aucune promotion active aujourd'hui.</p>
        @endforelse
    </div>

    <!-- ================= CASE À COCHER : UTILISER MES POINTS ================= -->
    @php
        $canUsePoints = $balance >= 15;
        $missingPoints = max(0, 15 - $balance);
        $subtotal = 0;
        foreach ($cart as $cItem) {
            $subtotal += $cItem['price_fcfa'] * $cItem['quantity'];
        }
    @endphp

    <div class="form-check mb-4">
        <input class="form-check-input"
               type="checkbox"
               id="useLoyaltyPointsCheckbox"
               {{ !$canUsePoints ? 'disabled' : '' }}
               onchange="toggleLoyaltyDiscount(this.checked)">
        <label class="form-check-label small text-muted" for="useLoyaltyPointsCheckbox">
            Utiliser mes points (15 pts = 1 000 F)
            @if(!$canUsePoints)
                <br><span style="color: #9e8a60; font-size: 0.78rem;">Seuil non atteint : il te manque {{ $missingPoints }} points.</span>
            @endif
        </label>
    </div>

    <!-- ================= BLOC PANIER & PAIEMENT ================= -->
    <div id="mon-panier" class="cart-box">
        <div class="row g-5">
            
            <!-- Colonne Gauche : Récapitulatif Panier -->
            <div class="col-lg-6 d-flex flex-column justify-content-between">
                <div>
                    <h3 class="font-serif-italic mb-4" style="font-size: 1.5rem;">Mon panier</h3>

                    @forelse($cart as $cItem)
                        <div class="d-flex justify-content-between align-items-center mb-2 small">
                            <span>{{ $cItem['name'] }} &times; {{ $cItem['quantity'] }}</span>
                            <span class="fw-semibold">{{ number_format($cItem['price_fcfa'] * $cItem['quantity'], 0, ',', ' ') }} F</span>
                        </div>
                    @empty
                        <p class="text-muted small my-3">Ton panier est vide. Clique sur le bouton <strong>+</strong> d'un plat pour l'ajouter.</p>
                    @endforelse
                </div>

                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between align-items-center text-muted small mb-2">
                        <span>Sous-total</span>
                        <span class="fw-semibold text-dark">{{ number_format($subtotal, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-muted small mb-3">
                        <span>Réduction points</span>
                        <span id="discountDisplay">0 F</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <span class="fw-bold" style="font-size: 1.05rem;">Total</span>
                        <span class="fw-bold" style="font-size: 1.15rem;" id="totalDisplay">
                            {{ number_format($subtotal, 0, ',', ' ') }} F
                        </span>
                    </div>
                </div>
            </div>

            <!-- Colonne Droite : Formulaire de validation de commande -->
            <div class="col-lg-6">
                <form action="{{ route('etudiant.order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="use_loyalty_points" id="useLoyaltyInput" value="0">

                    <!-- Mode de service -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Mode de service</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="service_mode" id="mode_on_site" value="on_site" checked>
                                <label class="mode-btn w-100" for="mode_on_site">Sur place</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="service_mode" id="mode_delivery" value="delivery">
                                <label class="mode-btn w-100" for="mode_delivery">Livraison</label>
                            </div>
                        </div>
                    </div>

                    <!-- Heure d'arrivée -->
                    <div class="mb-3">
                        <label for="arrival_time" class="form-label small text-muted mb-1">Heure d'arrivée</label>
                        <input type="time" id="arrival_time" name="arrival_time" value="12:45"
                               class="form-control form-control-sm py-2 shadow-none">
                    </div>

                    <!-- Adresse de livraison -->
                    <div class="mb-3">
                        <label for="delivery_address" class="form-label small text-muted mb-1">Adresse de livraison</label>
                        <input type="text" id="delivery_address" name="delivery_address"
                               value="{{ old('delivery_address', $user->delivery_address) }}"
                               placeholder="Ta localisation"
                               class="form-control form-control-sm py-2 shadow-none">
                    </div>

                    <!-- Paiement -->
                    <div class="mb-4">
                        <label class="form-label small text-muted d-block mb-2">Paiement</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="payment_method" id="pay_momo" value="mobile_money" checked>
                            <label class="pay-pill" for="pay_momo">Mobile money</label>

                            <input type="radio" class="btn-check" name="payment_method" id="pay_card" value="card">
                            <label class="pay-pill" for="pay_card">Carte bancaire</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-pay-confirm" {{ empty($cart) ? 'disabled' : '' }}>
                        Payer et confirmer
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
    const subtotalFcfa = {{ $subtotal }};
    function toggleLoyaltyDiscount(usePoints) {
        const discountEl = document.getElementById('discountDisplay');
        const totalEl = document.getElementById('totalDisplay');
        const hiddenInput = document.getElementById('useLoyaltyInput');

        const discount = usePoints ? Math.min(1000, subtotalFcfa) : 0;
        const finalTotal = Math.max(0, subtotalFcfa - discount);

        hiddenInput.value = usePoints ? '1' : '0';
        discountEl.textContent = discount.toLocaleString('fr-FR') + ' F';
        totalEl.textContent = finalTotal.toLocaleString('fr-FR') + ' F';
    }
</script>
@endsection
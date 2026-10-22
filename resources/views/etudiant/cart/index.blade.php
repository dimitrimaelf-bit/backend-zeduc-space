@extends('layouts.etudiant')

@section('title', 'Mon panier')

@push('styles')
<style>
    .cart-page {
        max-width: 1020px;
    }

    .cart-card,
    .summary-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 18px;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.03);
    }

    .cart-card {
        padding: 1.5rem 2rem;
    }

    .summary-card {
        padding: 1.75rem;
        position: sticky;
        top: 6rem;
    }

    .cart-item {
        padding: 1.15rem 0;
        border-bottom: 1px solid #f2ede2;
    }

    .cart-item:first-child {
        padding-top: 0;
    }

    .cart-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .cart-thumb,
    .cart-placeholder {
        width: 72px;
        height: 72px;
        flex: 0 0 72px;
        border-radius: 14px;
    }

    .cart-thumb {
        object-fit: cover;
    }

    .cart-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9f6ef;
        border: 1px solid #e4dac1;
        color: #8c7647;
        font-size: 1.5rem;
    }

    .quantity-control {
        display: inline-flex;
        align-items: center;
        gap: .65rem;
        border: 1px solid #e0dbd0;
        border-radius: 50rem;
        padding: .22rem .35rem;
    }

    .quantity-control button {
        width: 28px;
        height: 28px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        line-height: 1;
    }

    .quantity-control .minus {
        background: transparent;
        color: #111;
    }

    .quantity-control .minus:hover {
        background: #111;
        color: #fff;
    }

    .quantity-control .plus {
        background: var(--miam-gold);
        color: #111;
    }

    .quantity-control .plus:hover {
        background: var(--miam-gold-hover);
    }

    .empty-cart {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-cart-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f9f6ef;
        border: 1px solid #e4dac1;
        color: #8c7647;
        font-size: 2rem;
    }

    .btn-continue {
        color: #111;
        font-size: .88rem;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-continue:hover {
        color: #8c7647;
    }

    @media (max-width: 575.98px) {
        .cart-card {
            padding: 1.25rem;
        }

        .summary-card {
            position: static;
        }

        .cart-thumb,
        .cart-placeholder {
            width: 58px;
            height: 58px;
            flex-basis: 58px;
        }
    }
</style>
@endpush

@section('content')
<div class="container cart-page py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-4">
        <div>
            <div class="text-uppercase text-muted mb-2" style="font-size: .72rem; letter-spacing: 1.5px;">Ta sélection</div>
            <h1 class="font-serif-italic mb-1" style="font-size: 2.35rem;">Mon panier</h1>
            <p class="text-muted small mb-0">Retrouve ici les plats que tu souhaites déguster.</p>
        </div>
        <a href="{{ route('etudiant.menu.index') }}" class="btn-continue">
            <i class="bi bi-arrow-left me-1"></i> Continuer mes achats
        </a>
    </div>

    @if(count($cart) === 0)
        <div class="cart-card empty-cart">
            <div class="empty-cart-icon"><i class="bi bi-basket3"></i></div>
            <h2 class="font-serif-italic mb-2" style="font-size: 1.65rem;">Ton panier est vide</h2>
            <p class="text-muted small mb-4">Ajoute tes plats préférés depuis le menu pour commencer ta commande.</p>
            <a href="{{ route('etudiant.menu.index') }}" class="btn btn-gold px-4">
                Découvrir le menu <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    @else
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="cart-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="font-serif-italic mb-0" style="font-size: 1.5rem;">Articles sélectionnés</h2>
                        <span class="text-muted small">{{ count($cart) }} {{ count($cart) > 1 ? 'articles' : 'article' }}</span>
                    </div>

                    @foreach($cart as $item)
                        <div class="cart-item d-flex align-items-center gap-3">
                            @if(!empty($item['image_url']))
                                <img src="{{ asset($item['image_url']) }}" alt="{{ $item['name'] }}" class="cart-thumb">
                            @else
                                <div class="cart-placeholder"><i class="bi bi-egg-fried"></i></div>
                            @endif

                            <div class="flex-grow-1 min-width-0">
                                <div class="font-serif-italic fw-medium text-truncate" style="font-size: 1.08rem;">{{ $item['name'] }}</div>
                                <div class="text-muted small">{{ number_format($item['price_fcfa'], 0, ',', ' ') }} F l'unité</div>
                                <div class="quantity-control mt-2">
                                    <form action="{{ route('etudiant.cart.remove') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                        <button type="submit" class="minus" aria-label="Retirer une unité">&minus;</button>
                                    </form>
                                    <span class="small fw-semibold" style="min-width: 16px; text-align: center;">{{ $item['quantity'] }}</span>
                                    <form action="{{ route('etudiant.cart.add') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="menu_item_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="plus" aria-label="Ajouter une unité">+</button>
                                    </form>
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="fw-semibold small">{{ number_format($item['price_fcfa'] * $item['quantity'], 0, ',', ' ') }} F</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-5">
                <div class="summary-card">
                    <h2 class="font-serif-italic mb-4" style="font-size: 1.5rem;">Récapitulatif</h2>

                    <div class="d-flex justify-content-between text-muted small mb-3">
                        <span>Sous-total</span>
                        <span class="fw-semibold text-dark">{{ number_format($total, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-3">
                        <span>Frais de service</span>
                        <span class="text-muted">Calculés à l'étape suivante</span>
                    </div>

                    <div class="border-top pt-3 mb-4 d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold" style="font-size: 1.25rem;">{{ number_format($total, 0, ',', ' ') }} F</span>
                    </div>

                    <a href="{{ route('etudiant.menu.index') }}#mon-panier" class="btn btn-gold w-100">
                        Passer la commande <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <p class="text-muted text-center small mt-3 mb-0">
                        <i class="bi bi-shield-check me-1"></i> Tu pourras choisir le mode de service et le paiement.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

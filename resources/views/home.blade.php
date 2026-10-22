@extends('layouts.app')

@section('title', 'Accueil')

@section('body-class', 'home-page')

@section('full-width')
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-dark navbar-zeduc mb-0">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('/images/zeduc-image.jpg') }}" alt="ZEDUC-SP@CE" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link gold active" href="{{ url('/') }}">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ url('/') }}">Terrasse</a></li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-gold">Se connecter</a>
            </div>
        </div>
    </nav>

    {{-- Section Héro --}}
    <section class="hero-section text-center text-white">
        <div class="hero-overlay">
            <div class="container py-5">
                <h1 class="display-5 fw-bold mb-3">Quand les étudiants de<br>Yansoki ont faim,<br>ils savent où commander.</h1>
                <p class="lead mb-4">100+ plats disponibles chaque jour</p>
                <a href="{{ route('login') }}" class="btn btn-gold btn-lg px-4">Voir le menu du jour</a>
                <div class="row mt-5">
                    <div class="col-md-6">
                        <h3 class="gold fw-bold">150+</h3>
                        <p class="small">Plats différents chaque semaine</p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="gold fw-bold">1 plat</h3>
                        <p class="small">Votre repas livré en 15 min</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        {{-- Section Plats Populaires --}}
        <section class="mb-5">
            <div class="text-center mb-4">
                <h2 class="gold fw-bold">À la carte</h2>
                <p class="text-secondary">Découvrez nos plats, préparés avec des ingrédients frais et le savoir-faire de notre cuisine Camerounaise</p>
            </div>
            <div class="row g-4">
                @php
                    $plats = [
                        ['nom' => 'Saucisses Braisées', 'prix' => '1 500 F', 'img' => 'saucisses.jpg'],
                        ['nom' => 'Ndolè Royal', 'prix' => '2 500 F', 'img' => 'ndole.jpg'],
                        ['nom' => 'Poulet Braisé', 'prix' => '3 500 F', 'img' => 'poulet.jpg'],
                        ['nom' => 'Poisson Braisé', 'prix' => '3 000 F', 'img' => 'poisson.jpg'],
                    ];
                @endphp
                @foreach($plats as $plat)
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 bg-dark border-gold">
                            <img src="{{ asset('images/' . $plat['img']) }}" class="card-img-top" alt="{{ $plat['nom'] }}" style="height: 180px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="gold">{{ $plat['nom'] }}</h5>
                                <p class="text-secondary">{{ $plat['prix'] }}</p>
                                <a href="{{ route('login') }}" class="btn btn-gold btn-sm w-100">Ajouter au panier</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Section Expérience --}}
        <section class="mb-5 py-5" style="background-color: #1a1a1a; border-radius: 1rem;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h3 class="gold fw-bold mb-3">Expérience</h3>
                        <p class="text-white">Une terrasse pensée pour rester après le repas.</p>
                        <p class="text-white">Chez nous, la faim a des remises.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5 class="gold">Événements</h5>
                            <p class="text-secondary">Quand ça se joue à Yansoki, ça se vit à table.</p>
                        </div>
                        <div class="mb-4">
                            <h5 class="gold">Fidélité</h5>
                            <p class="text-secondary">Des points à chaque commande. Accumulez des points et utilisez-les pour réduire votre prochaine addition.</p>
                        </div>
                        <div>
                            <h5 class="gold">Parrainage</h5>
                            <p class="text-secondary">Invitez vos camarades. Votre code de parrainage vous rapporte des points dès leur première commande.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section Commander en 3 étapes --}}
        <section class="mb-5">
            <div class="text-center mb-4">
                <h2 class="gold fw-bold">Commander en trois étapes</h2>
                <p class="text-secondary">Depuis votre table, votre chambre ou entre deux cours : le même parcours, à chaque fois.</p>
            </div>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="p-4 border-gold rounded h-100">
                        <h4 class="gold">Étape 1</h4>
                        <h5 class="text-white">Choisissez votre repas</h5>
                        <p class="text-secondary small">Parcourez le menu du jour et les promotions, et composez votre panier.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border-gold rounded h-100">
                        <h4 class="gold">Étape 2</h4>
                        <h5 class="text-white">Sur place ou en livraison</h5>
                        <p class="text-secondary small">Indiquez une heure de retrait ou la livraison, selon votre adresse de livraison.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border-gold rounded h-100">
                        <h4 class="gold">Étape 3</h4>
                        <h5 class="text-white">Payez et cumulez vos points</h5>
                        <p class="text-secondary small">Par carte, mobile money, ou avec vos points de fidélité déjà cumulés.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="text-center py-4 border-top border-secondary">
            <img src="{{ asset('/images/zeduc-image.jpg') }}" alt="ZEDUC-SP@CE" height="60" class="mb-3">
            <div class="row text-secondary small">
                <div class="col-md-3">
                    <h6 class="gold">ZéDuc-Space</h6>
                    <p>Restaurant universitaire<br>Campus de Yansoki<br>Yaoundé, Cameroun</p>
                </div>
                <div class="col-md-3">
                    <h6 class="gold">Explorer</h6>
                    <p>Menu du jour<br>Promotions<br>Événements</p>
                </div>
                <div class="col-md-3">
                    <h6 class="gold">Horaires</h6>
                    <p>Lundi - Samedi<br>11h - 22h<br>Dimanche : 12h - 20h</p>
                </div>
                <div class="col-md-3">
                    <h6 class="gold">Contact</h6>
                    <p>+237 690 00 00 00<br>contact@zeduc-space.cm<br>Suivez-nous : @zeducspace</p>
                </div>
            </div>
            <p class="text-secondary small mt-3">© 2026 ZEDUC-SP@CE - The Climate of Taste & Chill</p>
            <div class="d-flex justify-content-center gap-3 small">
                @if($privacyPolicyUrl)<a href="{{ $privacyPolicyUrl }}" class="text-secondary">Confidentialité</a>@endif
                @if($termsOfServiceUrl)<a href="{{ $termsOfServiceUrl }}" class="text-secondary">Conditions de service</a>@endif
            </div>
        </footer>
    </div>

    @if(session('cookie_consent_version') !== $cookiePolicyVersion)
        <aside class="position-fixed bottom-0 start-0 end-0 bg-white border-top shadow-lg p-3" style="z-index: 1050" aria-label="Consentement aux cookies">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="small text-dark">Nous utilisons des cookies pour le fonctionnement du site. Choisissez si vous consentez à leur utilisation.
                    @if($privacyPolicyUrl)<a href="{{ $privacyPolicyUrl }}">Politique de confidentialité</a>@endif
                    @if($termsOfServiceUrl)<a href="{{ $termsOfServiceUrl }}" class="ms-2">Conditions</a>@endif
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('cookies.consent') }}">@csrf<input type="hidden" name="consented" value="0"><button type="submit" class="btn btn-sm btn-outline-secondary">Refuser</button></form>
                    <form method="POST" action="{{ route('cookies.consent') }}">@csrf<input type="hidden" name="consented" value="1"><button type="submit" class="btn btn-sm btn-dark">Accepter</button></form>
                </div>
            </div>
        </aside>
    @endif
@endsection
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

    /* Cartes personnalisées */
    .miam-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .miam-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    }

    /* Footer */
    .footer-miam {
        background-color: var(--miam-footer);
        color: #8c857b;
        font-size: 0.8rem;
        padding: 1.5rem 0;
    }
</style>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-miam sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('etudiant.accueil') }}">
            <img src="{{ asset('images/Etudiant/logo.png') }}" alt="ZeDuc@Space" style="height: 45px; width: auto; border-radius: 8px;">
            <div class="ms-2">
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
                    <a class="nav-link" href="{{ route('etudiant.menu.index') }}">Commander</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.loyalty.index') }}">Fidélité et parrainage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.reclamations.index') }}">Historique</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('etudiant.evenements.index') }}">Événements et promotions</a>
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
<div class="container py-5" style="max-width: 1120px;">

    <!-- En-tête et filtres -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
        <div>
            <h1 class="font-serif-italic mb-1" style="font-size: 2.5rem;">Événements et promotions</h1>
            <p class="text-muted small mb-0">Découvre les offres du moment et le programme des jours à venir.</p>
        </div>

        <!-- Boutons de filtre par catégorie -->
        <div class="d-flex gap-2">
            <a href="{{ route('etudiant.evenements.index', ['categorie' => 'tout']) }}"
               class="btn btn-sm rounded-pill px-3 {{ $filtre === 'tout' ? 'btn-dark' : 'btn-outline-secondary' }}">
                Tout voir
            </a>
            <a href="{{ route('etudiant.evenements.index', ['categorie' => 'promotion']) }}"
               class="btn btn-sm rounded-pill px-3 {{ $filtre === 'promotion' ? 'btn-dark' : 'btn-outline-secondary' }}">
                Promotions
            </a>
            <a href="{{ route('etudiant.evenements.index', ['categorie' => 'event']) }}"
               class="btn btn-sm rounded-pill px-3 {{ $filtre === 'event' ? 'btn-dark' : 'btn-outline-secondary' }}">
                Événements
            </a>
        </div>
    </div>

    <!-- ================= SECTION 1 : AUJOURD'HUI ================= -->
    <div class="mb-5">
        <h4 class="font-serif-italic mb-3" style="font-size: 1.5rem;">En cours aujourd'hui</h4>

        <div class="row g-4">
            @forelse($offresDuJour as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="miam-card h-100 d-flex flex-column justify-content-between p-4 bg-white rounded-4 shadow-sm">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <!-- Badge de Catégorie : Promotion vs Événement -->
                                @if($item->type === 'event')
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #e0f2fe; color: #0369a1;">
                                        <i class="bi bi-calendar-event me-1"></i> Événement
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #fef3c7; color: #b45309;">
                                        <i class="bi bi-tag me-1"></i> Promotion
                                    </span>
                                @endif

                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">
                                    Aujourd'hui
                                </span>
                            </div>

                            <h5 class="font-serif-italic mb-2" style="font-size: 1.3rem;">{{ $item->title }}</h5>
                            <p class="text-muted small mb-3">{{ $item->description }}</p>
                        </div>

                        <div class="border-top pt-3 mt-2 d-flex justify-content-between align-items-center text-muted small">
                            <span>
                                <i class="bi bi-clock me-1"></i>
                                @if($item->ends_at)
                                    Jusqu'au {{ $item->ends_at->translatedFormat('j M à H:i') }}
                                @else
                                    Toute la journée
                                @endif
                            </span>
                            @if($item->type === 'promotion')
                                <a href="{{ route('etudiant.menu.index') }}" class="text-decoration-none fw-semibold" style="color: var(--miam-gold, #c5a059);">
                                    En profiter &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="miam-card p-4 text-center text-muted bg-white rounded-4 shadow-sm">
                        Aucune promotion ou événement en cours pour aujourd'hui.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ================= SECTION 2 : PLANIFIÉS POUR LES JOURS SUIVANTS ================= -->
    <div>
        <h4 class="font-serif-italic mb-3" style="font-size: 1.5rem;">Prévu pour les jours suivants</h4>

        <div class="row g-4">
            @forelse($offresAVenir as $item)
                <div class="col-md-6">
                    <div class="miam-card h-100 d-flex flex-column justify-content-between p-4 bg-white rounded-4 shadow-sm" style="border-left: 4px solid var(--miam-gold, #c5a059);">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <!-- Badge de Catégorie : Promotion vs Événement -->
                                @if($item->type === 'event')
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #e0f2fe; color: #0369a1;">
                                        <i class="bi bi-controller me-1"></i> Événement
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #fef3c7; color: #b45309;">
                                        <i class="bi bi-percent me-1"></i> Promotion
                                    </span>
                                @endif

                                <!-- Date de début planifiée -->
                                <span class="text-muted small fw-medium">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Dès le {{ $item->starts_at->translatedFormat('l j F') }}
                                </span>
                            </div>

                            <h5 class="font-serif-italic mb-2" style="font-size: 1.35rem;">{{ $item->title }}</h5>
                            <p class="text-muted small mb-3">{{ $item->description }}</p>
                        </div>

                        <div class="border-top pt-3 mt-2 d-flex justify-content-between align-items-center text-muted small">
                            <span>
                                Début : <strong>{{ $item->starts_at->translatedFormat('j M Y à H:i') }}</strong>
                            </span>
                            @if($item->ends_at)
                                <span>
                                    Fin : {{ $item->ends_at->translatedFormat('j M à H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="miam-card p-4 text-center text-muted bg-white rounded-4 shadow-sm">
                        Aucun événement ou promotion planifié pour les prochains jours.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- ================= FOOTER ================= -->
<footer class="footer-miam mt-5">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/Etudiant/logo.png') }}" alt="ZeDuc@Space" style="height: 30px; width: auto; border-radius: 6px;">
            <span>&copy; 2026 ZeDuc@Space &mdash; Mon Miam Miam</span>
        </div>
        <div>Fait avec soin pour les étudiants de l'Institut Ucac-Icam</div>
    </div>
</footer>
@endsection

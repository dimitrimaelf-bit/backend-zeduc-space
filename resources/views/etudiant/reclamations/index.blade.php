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
        min-height: 100vh;
        display: flex;
        flex-direction: column;
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

    /* Cartes Blanches */
    .miam-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(0, 0, 0, 0.04);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    }

    /* Tableaux */
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
        padding: 1.05rem 0.5rem;
        border-bottom: 1px solid #f2ede2;
        font-size: 0.88rem;
        vertical-align: middle;
    }

    /* Badges Statuts Commandes & Réclamations */
    .badge-prep {
        background-color: #d8ccb2;
        color: #3d3522;
        font-weight: 600;
        font-size: 0.73rem;
        padding: 0.35rem 0.8rem;
        border-radius: 50rem;
    }

    .badge-livree {
        background-color: #d8f3dc;
        color: #1b4332;
        font-weight: 600;
        font-size: 0.73rem;
        padding: 0.35rem 0.8rem;
        border-radius: 50rem;
    }

    .badge-soumise {
        background-color: #faedcd;
        color: #8c5e0b;
        font-weight: 600;
        font-size: 0.73rem;
        padding: 0.35rem 0.8rem;
        border-radius: 50rem;
    }

    .badge-annulee {
        background-color: #f8d7da;
        color: #842029;
        font-weight: 600;
        font-size: 0.73rem;
        padding: 0.35rem 0.8rem;
        border-radius: 50rem;
    }

    /* Boutons Actions */
    .btn-action-pill {
        border: 1px solid #333;
        background: #fff;
        color: #111;
        border-radius: 50rem;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.32rem 1rem;
        transition: all 0.15s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-action-pill:hover {
        background: #111;
        color: #fff;
    }

    .btn-submit-claim {
        background-color: var(--miam-gold);
        color: #111;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 10px;
        padding: 0.75rem 1.4rem;
        border: none;
        transition: background 0.2s;
    }

    .btn-submit-claim:hover {
        background-color: var(--miam-gold-hover);
    }

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

    .claim-row {
        padding: 0.9rem 0;
        border-bottom: 1px solid #f2ede2;
        font-size: 0.88rem;
    }

    .claim-row:last-child {
        border-bottom: none;
    }

    .footer-miam {
        background-color: #1c1610;
        color: #8c8275;
        font-size: 0.8rem;
        padding: 1.5rem 0;
        margin-top: auto;
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
                    <a class="nav-link" href="{{ route('etudiant.menu.index') }}">Commander</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.loyalty.index') }}">Fidélité et parrainage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('etudiant.reclamations.index') }}">Historique</a>
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
<div class="container py-5" style="max-width: 1120px;">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Titre -->
    <h1 class="font-serif-italic mb-1" style="font-size: 2.5rem;">Historique et réclamations</h1>
    <p class="text-muted small mb-4">Retrouve tes commandes et signale un problème si besoin.</p>

    <div class="row g-4">
        <!-- ================= COLONNE GAUCHE : MES COMMANDES ================= -->
        <div class="col-lg-7">
            <div class="miam-card">
                <h4 class="font-serif-italic mb-3" style="font-size: 1.4rem;">Mes commandes</h4>

                <div class="table-responsive">
                    <table class="table table-borderless table-miam mb-0">
                        <thead>
                            <tr>
                                <th class="ps-0">N°</th>
                                <th>DATE</th>
                                <th>MONTANT</th>
                                <th>STATUT</th>
                                <th class="text-end pe-0">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-0 fw-medium">#{{ $order->order_number }}</td>
                                    <td class="text-muted">
                                        {{ $order->created_at ? $order->created_at->translatedFormat('j M.') : '-' }}
                                    </td>
                                    <td>{{ number_format($order->total_fcfa, 0, ',', ' ') }} F</td>
                                    <td>
                                        @switch($order->status)
                                            @case('delivered')
                                            @case('completed')
                                                <span class="badge-livree">Livrée</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge-annulee">Annulée</span>
                                                @break
                                            @default
                                                <span class="badge-prep">En préparation</span>
                                        @endswitch
                                    </td>
                                    <td class="text-end pe-0">
                                        @if(in_array($order->status, ['delivered', 'completed']))
                                            <button type="button" class="btn-action-pill"
                                                    onclick="selectOrderForClaim('{{ $order->id }}')">
                                                Réclamer
                                            </button>
                                        @else
                                            <button type="button" class="btn-action-pill"
                                                    onclick="alert('Commande #{{ $order->order_number }} — Total : {{ number_format($order->total_fcfa, 0, ',', ' ') }} F')">
                                                Détails
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Tu n'as pas encore passé de commande.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= COLONNE DROITE : NOUVELLE RÉCLAMATION & SUIVI ================= -->
        <div class="col-lg-5 d-flex flex-column gap-4">

            <!-- Carte : Nouvelle réclamation -->
<div class="miam-card" id="formClaimBox">
    <h4 class="font-serif-italic mb-3" style="font-size: 1.35rem;">Nouvelle réclamation</h4>

    <form action="{{ route('etudiant.reclamations.store') }}" method="POST">
        @csrf
        
        <!-- Sélection de la commande -->
        <div class="mb-3">
            <label for="order_id" class="form-label text-muted small mb-1">Commande concernée</label>
            <select name="order_id" id="order_id" class="form-select form-select-miam" required>
                @forelse($orders as $order)
                    <option value="{{ $order->id }}">
                        Commande #{{ $order->order_number }} ({{ number_format($order->total_fcfa, 0, ',', ' ') }} F)
                    </option>
                @empty
                    <option value="" disabled selected>Aucune commande disponible</option>
                @endforelse
            </select>
        </div>

        <!-- AJOUT DU CHAMP SUJET ICI -->
        <div class="mb-3">
            <label for="subject" class="form-label text-muted small mb-1">Sujet</label>
            <input type="text" name="subject" id="subject" 
                   class="form-control form-control-miam" 
                   placeholder="Ex: Retard de livraison, Plat froid..." 
                   value="{{ old('subject') }}" required>
        </div>

        <!-- Description du problème -->
        <div class="mb-3">
            <label for="description" class="form-label text-muted small mb-1">Décris le problème</label>
            <textarea name="description" id="description" rows="3"
                      class="form-control form-control-miam"
                      placeholder="Plat reçu froid après 40 minutes d'attente." required>{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn-submit-claim" {{ $orders->isEmpty() ? 'disabled' : '' }}>
            Envoyer la réclamation
        </button>
    </form>
</div>

            <!-- Carte : Suivi de mes réclamations -->
            <div class="miam-card">
                <h4 class="font-serif-italic mb-3" style="font-size: 1.35rem;">Suivi de mes réclamations</h4>

                @forelse($claims as $claim)
                    <div class="claim-row d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-medium">#R-{{ 200 + $claim->id }}</span>
                            &middot;
                            Commande #{{ $claim->order ? $claim->order->order_number : $claim->order_id }}
                        </div>
                        <div>
                            @switch($claim->status)
                                @case('open')
                                    <span class="badge-soumise">Soumise</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge-prep">En cours</span>
                                    @break
                                @case('resolved')
                                    <span class="badge-livree">Résolue</span>
                                    @break
                                @case('rejected')
                                    <span class="badge-annulee">Rejetée</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Aucune réclamation soumise pour le moment.</p>
                @endforelse
            </div>

        </div>
    </div>
</div>

<!-- ================= FOOTER ================= -->
<footer class="footer-miam">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div>&copy; 2026 ZeDuc@Space &mdash; Mon Miam Miam</div>
        <div>Fait avec soin pour les étudiants de l'Institut Ucac-Icam</div>
    </div>
</footer>

<script>
    function selectOrderForClaim(orderId) {
        const select = document.getElementById('order_id');
        if (select) {
            select.value = orderId;
            document.getElementById('description').focus();
        }
    }
</script>
@endsection
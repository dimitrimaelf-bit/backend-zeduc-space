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
        padding: 2.25rem;
        border: 1px solid rgba(0, 0, 0, 0.04);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        height: 100%;
    }

    /* Barre de progression */
    .loyalty-progress {
        height: 10px;
        background-color: #e9e2d0;
        border-radius: 50rem;
        overflow: hidden;
    }

    .loyalty-progress-bar {
        background-color: #111111;
        height: 100%;
        border-radius: 50rem;
        transition: width 0.4s ease;
    }

    /* Boutons */
    .btn-use-points {
        background-color: #e6dfd1;
        color: #7a7365;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.65rem 1.4rem;
        border-radius: 10px;
        border: none;
    }

    .btn-use-points.active-points {
        background-color: var(--miam-gold);
        color: #111;
        cursor: pointer;
    }

    .referral-box {
        border: 1.5px dashed #7a7365;
        border-radius: 10px;
        padding: 0.85rem 1.25rem;
        font-family: var(--font-serif);
        font-size: 1.55rem;
        letter-spacing: 3px;
        text-align: center;
        color: #111;
        background-color: #fdfcf9;
        flex-grow: 1;
    }

    .btn-copy {
        background-color: var(--miam-gold);
        color: #111;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 10px;
        padding: 0.85rem 1.35rem;
        border: none;
        transition: background 0.2s;
    }

    .btn-copy:hover {
        background-color: var(--miam-gold-hover);
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

    .btn-outline-miam:hover {
        background: #111;
        color: #fff;
    }

    /* Tableaux épurés */
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

    /* Badges Statut */
    .badge-obtenue {
        background-color: #d8f3dc;
        color: #1b4332;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.35rem 0.85rem;
        border-radius: 50rem;
    }

    .badge-attente {
        background-color: #faedcd;
        color: #8c5e0b;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.35rem 0.85rem;
        border-radius: 50rem;
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
                    <a class="nav-link active" href="{{ route('etudiant.loyalty.index') }}">Fidélité et parrainage</a>
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

    <!-- Titre de page -->
    <h1 class="font-serif-italic mb-1" style="font-size: 2.5rem;">Fidélité et parrainage</h1>
    <p class="text-muted small mb-4">Gagne des points à chaque commande et invite tes camarades.</p>

    @php
        $targetPoints = 5;
        $pointsRemaining = max(0, $targetPoints - ($balance % $targetPoints));
        if ($balance >= $targetPoints && $balance % $targetPoints === 0) {
            $pointsRemaining = 0;
        }
        $progressPercent = min(100, round((($balance % $targetPoints) / $targetPoints) * 100));
        if ($balance >= $targetPoints) {
            $progressPercent = 100;
        }
    @endphp

    <div class="row g-4">
        <!-- ================= COLONNE GAUCHE : SOLDE & HISTORIQUE DES POINTS ================= -->
        <div class="col-lg-6">
            <div class="miam-card">
                <div class="text-muted small mb-1">Mon solde</div>
                <div class="font-serif mb-3" style="font-size: 3.4rem; line-height: 1.1;">
                    {{ $balance }} pts
                </div>

                <!-- Barre de progression -->
                <div class="loyalty-progress mb-3">
                    <div class="loyalty-progress-bar" style="width: {{ $progressPercent }}%;"></div>
                </div>

                <p class="text-muted small mb-4">
                    @if($balance >= 5)
                        Tu as assez de points pour obtenir 1 000 F de réduction ! 1 000 F dépensés = 1 point.
                    @else
                        Plus que {{ $pointsRemaining }} points avant ta prochaine réduction de 1 000 F. 1 000 F dépensés = 1 point.
                    @endif
                </p>

                <div class="mb-5">
                    @if($balance >= 5)
                        <a href="{{ route('etudiant.menu.index') }}" class="btn-use-points active-points text-decoration-none d-inline-block">
                            Utiliser mes points
                        </a>
                    @else
                        <button type="button" class="btn-use-points" disabled>
                            Utiliser mes points
                        </button>
                    @endif
                </div>

                <!-- Historique des points -->
                <h4 class="font-serif-italic mb-3" style="font-size: 1.35rem;">Historique des points</h4>

                <div class="table-responsive">
                    <table class="table table-borderless table-miam mb-0">
                        <thead>
                            <tr>
                                <th class="ps-0">DATE</th>
                                <th>COMMANDE</th>
                                <th class="text-end pe-0">POINTS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                                <tr>
                                    <td class="ps-0 text-muted">
                                        {{ $tx->created_at ? $tx->created_at->translatedFormat('j M.') : '-' }}
                                    </td>
                                    <td>
                                        @if($tx->order)
                                            #{{ $tx->order->order_number }} &middot; {{ number_format($tx->order->total_fcfa, 0, ',', ' ') }} F
                                        @else
                                            {{ $tx->description }}
                                        @endif
                                    </td>
                                    <td class="text-end pe-0 fw-medium">
                                        {{ $tx->points > 0 ? '+ ' . $tx->points : $tx->points }} pts
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Aucune transaction de fidélité pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= COLONNE DROITE : PARRAINAGE & FILLEULS ================= -->
        <div class="col-lg-6">
            <div class="miam-card">
                <div class="text-muted small mb-2">Mon code de parrainage</div>

                <!-- Encadré Code + Bouton Copier -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="referral-box" id="referralCodeText">
                        {{ $referralCode ? $referralCode->code : 'AUCUN CODE' }}
                    </div>
                    <button type="button" class="btn-copy" onclick="copyReferralCode()">
                        Copier
                    </button>
                </div>

                <!-- Bouton Générer un nouveau code -->
                <form action="{{ route('etudiant.parrainage.generer') }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn-outline-miam">
                        Générer un nouveau code
                    </button>
                </form>

                <p class="text-muted small mb-4">
                    Quand un camarade passe sa première commande avec ton code, tu gagnes des points supplémentaires.
                </p>

                <!-- Liste des filleuls -->
                <h4 class="font-serif-italic mb-3" style="font-size: 1.35rem;">Mes filleuls</h4>

                <div class="table-responsive">
                    <table class="table table-borderless table-miam mb-0">
                        <thead>
                            <tr>
                                <th class="ps-0">FILLEUL</th>
                                <th>1RE COMMANDE</th>
                                <th class="text-end pe-0">RÉCOMPENSE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($referrals as $ref)
                                @php
                                    $filleul = $ref->referred;
                                    $hasCompletedOrder = $filleul && $filleul->orders->whereIn('status', ['delivered', 'completed'])->isNotEmpty();
                                @endphp
                                <tr>
                                    <td class="ps-0">
                                        {{ $filleul ? $filleul->first_name . ' ' . strtoupper(substr($filleul->last_name, 0, 1)) . '.' : 'Étudiant' }}
                                    </td>
                                    <td>
                                        {{ ($ref->status === 'rewarded' || $hasCompletedOrder) ? 'Passée' : 'En attente' }}
                                    </td>
                                    <td class="text-end pe-0">
                                        @if($ref->status === 'rewarded')
                                            <span class="badge-obtenue">Obtenue</span>
                                        @else
                                            <span class="badge-attente">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Tu n'as pas encore de filleul enregistré. Partage ton code !
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
    function copyReferralCode() {
        const code = document.getElementById('referralCodeText').innerText.trim();
        if (code && code !== 'AUCUN CODE') {
            navigator.clipboard.writeText(code);
            alert('Code ' + code + ' copié dans le presse-papier !');
        }
    }
</script>
@endsection
@extends('layouts.etudiant')

@section('title', 'Fidélité et parrainage')

@push('styles')
<style>
    /* ================= BARRE DE PROGRESSION ================= */
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

    /* ================= BOUTON UTILISER MES POINTS ================= */
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

    /* ================= ENCADRÉ CODE DE PARRAINAGE ================= */
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

    .btn-copy:hover { background-color: var(--miam-gold-hover); }
</style>
@endpush

@section('content')

<div class="container py-5" style="max-width: 1120px;">

    {{-- En-tête --}}
    <h1 class="font-serif-italic mb-1" style="font-size: 2.5rem;">Fidélité et parrainage</h1>
    <p class="text-muted small mb-4">Gagne des points à chaque commande et invite tes camarades.</p>

    {{-- Calculs de progression --}}
    @php
        $targetPoints = 5;
        $pointsRemaining = max(0, $targetPoints - ($balance % $targetPoints));
        $progressPercent = $balance >= $targetPoints
            ? 100
            : min(100, round((($balance % $targetPoints) / $targetPoints) * 100));
    @endphp

    <div class="row g-4">

        {{-- ================= COLONNE GAUCHE : SOLDE & HISTORIQUE ================= --}}
        <div class="col-lg-6">
            <div class="miam-card">
                <div class="text-muted small mb-1">Mon solde</div>
                <div class="font-serif mb-3" style="font-size: 3.4rem; line-height: 1.1;">
                    {{ $balance }} pts
                </div>

                {{-- Barre de progression --}}
                <div class="loyalty-progress mb-3">
                    <div class="loyalty-progress-bar" style="width: {{ $progressPercent }}%;"></div>
                </div>

                <p class="text-muted small mb-4">
                    @if($balance >= $targetPoints)
                        Tu as assez de points pour obtenir 1 000 F de réduction ! 1 000 F dépensés = 1 point.
                    @else
                        Plus que {{ $pointsRemaining }} points avant ta prochaine réduction de 1 000 F. 1 000 F dépensés = 1 point.
                    @endif
                </p>

                <div class="mb-5">
                    @if($balance >= $targetPoints)
                        <a href="{{ route('etudiant.menu.index') }}" class="btn-use-points active-points text-decoration-none d-inline-block">
                            Utiliser mes points
                        </a>
                    @else
                        <button type="button" class="btn-use-points" disabled>
                            Utiliser mes points
                        </button>
                    @endif
                </div>

                {{-- Historique des points --}}
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

        {{-- ================= COLONNE DROITE : PARRAINAGE & FILLEULS ================= --}}
        <div class="col-lg-6">
            <div class="miam-card">
                <div class="text-muted small mb-2">Mon code de parrainage</div>

                {{-- Encadré Code + Bouton Copier --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="referral-box" id="referralCodeText">
                        {{ $referralCode ? $referralCode->code : 'AUCUN CODE' }}
                    </div>
                    <button type="button" class="btn-copy" onclick="copyReferralCode()">
                        Copier
                    </button>
                </div>

                {{-- Générer un nouveau code --}}
                <form action="{{ route('etudiant.parrainage.generer') }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn-outline-miam">
                        Générer un nouveau code
                    </button>
                </form>

                <p class="text-muted small mb-4">
                    Quand un camarade passe sa première commande avec ton code, tu gagnes des points supplémentaires.
                </p>

                {{-- Liste des filleuls --}}
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
                                    $hasCompletedOrder = $filleul
                                        && $filleul->orders->whereIn('status', ['delivered', 'completed'])->isNotEmpty();
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
                                            <span class="badge-miam badge-obtenue">Obtenue</span>
                                        @else
                                            <span class="badge-miam badge-attente">En attente</span>
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

@endsection

@push('scripts')
<script>
    function copyReferralCode() {
        const code = document.getElementById('referralCodeText').innerText.trim();
        if (code && code !== 'AUCUN CODE') {
            navigator.clipboard.writeText(code);
            alert('Code ' + code + ' copié dans le presse-papier !');
        }
    }
</script>
@endpush

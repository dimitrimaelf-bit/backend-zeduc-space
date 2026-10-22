@extends('layouts.etudiant')

@section('title', 'Historique et réclamations')

@push('styles')
<style>
    /* ================= BOUTONS D'ACTION ================= */
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

    .btn-action-pill:hover { background: #111; color: #fff; }

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

    .btn-submit-claim:hover { background-color: var(--miam-gold-hover); }

    /* ================= LIGNES DE RÉCLAMATIONS ================= */
    .claim-row {
        padding: 0.9rem 0;
        border-bottom: 1px solid #f2ede2;
        font-size: 0.88rem;
    }

    .claim-row:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

<div class="container py-5" style="max-width: 1120px;">

    {{-- En-tête --}}
    <h1 class="font-serif-italic mb-1" style="font-size: 2.5rem;">Historique et réclamations</h1>
    <p class="text-muted small mb-4">Retrouve tes commandes et signale un problème si besoin.</p>

    <div class="row g-4">

        {{-- ================= COLONNE GAUCHE : MES COMMANDES ================= --}}
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
                                                <span class="badge-miam badge-livree">Livrée</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge-miam badge-annulee">Annulée</span>
                                                @break
                                            @default
                                                <span class="badge-miam badge-prep">En préparation</span>
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

        {{-- ================= COLONNE DROITE : RÉCLAMATIONS ================= --}}
        <div class="col-lg-5 d-flex flex-column gap-4">

            {{-- Carte : Nouvelle réclamation --}}
            <div class="miam-card" id="formClaimBox">
                <h4 class="font-serif-italic mb-3" style="font-size: 1.35rem;">Nouvelle réclamation</h4>

                <form action="{{ route('etudiant.reclamations.store') }}" method="POST">
                    @csrf
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

            {{-- Carte : Suivi de mes réclamations --}}
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
                                    <span class="badge-miam badge-soumise">Soumise</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge-miam badge-prep">En cours</span>
                                    @break
                                @case('resolved')
                                    <span class="badge-miam badge-livree">Résolue</span>
                                    @break
                                @case('rejected')
                                    <span class="badge-miam badge-annulee">Rejetée</span>
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

@endsection

@push('scripts')
<script>
    {{-- Pré-sélectionne la commande dans le formulaire de réclamation --}}
    function selectOrderForClaim(orderId) {
        const select = document.getElementById('order_id');
        if (select) {
            select.value = orderId;
            document.getElementById('description').focus();
        }
    }
</script>
@endpush

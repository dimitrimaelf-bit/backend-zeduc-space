@extends('layouts.Gerant')
@section('title', 'Réclamations & Arbitrage')

@section('content')
<div class="container-fluid px-0">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    {{-- En-tête principal --}}
    <div class="mb-4">
        <div class="mb-3">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-dark text-white text-uppercase px-2 py-1" style="font-size: 0.7rem;">
                    <i class="fa-solid fa-shield-halved text-warning me-1"></i> ARBITRAGE EN DIRECT
                </span>
                <span class="text-muted small">• Décisions managériales requises</span>
            </div>
            <h1 class="h2 fw-bold text-dark m-0">Réclamations & Arbitrage Litiges</h1>
        </div>

        {{-- Uniquement les 3 boutons de filtres --}}
        <div class="d-flex align-items-center gap-3 flex-wrap pt-2">
            <button class="btn btn-dark fw-semibold px-3 py-2 rounded-3 d-flex flex-column align-items-center shadow-sm" style="background-color: #1a1a1a;">
                <span>À traiter</span>
                <span class="badge bg-danger rounded-circle mt-1 px-2 py-1" style="font-size: 0.65rem;">
                    {{ $summary['open'] + $summary['in_progress'] }}
                </span>
            </button>

            <button class="btn btn-light bg-white border-0 fw-medium px-3 py-3 rounded-3 shadow-sm text-secondary">
                Traitées
            </button>

            <button class="btn btn-light bg-white border-0 fw-medium px-3 py-3 rounded-3 shadow-sm text-secondary">
                Remboursées
            </button>
        </div>
    </div>

    {{-- Master-Detail --}}
    <div class="row g-4">
        
        {{-- COLONNE GAUCHE --}}
        <div class="col-12 col-lg-5">
            <div class="d-flex flex-column gap-3">
                @forelse($claims as $claim)
                    <x-claim-card :claim="$claim" :active="$loop->first" />
                @empty
                    {{-- Vide quand aucun litige n'est présent --}}
                @endforelse
            </div>
        </div>

        {{-- COLONNE DROITE --}}
        <div class="col-12 col-lg-7">
            @if($selectedClaim)
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">
                                {{ $selectedClaim->user?->first_name }} {{ $selectedClaim->user?->last_name }} 
                                <span class="text-muted fw-normal fs-6">• Table {{ $selectedClaim->order?->table_number ?? 'N/A' }}</span>
                            </h4>
                            <p class="text-muted small mb-0">
                                Litige #{{ $selectedClaim->code ?? 'REC-'.$selectedClaim->id }} • {{ $selectedClaim->order?->item_name ?? $selectedClaim->subject }}
                            </p>
                        </div>
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 fw-bold rounded-3">Action requise</span>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <span class="fw-bold text-uppercase text-muted extra-small d-block mb-2">PREUVE VISUELLE (PRISE EN SALLE)</span>
                            <div class="rounded-4 overflow-hidden border bg-light p-2 text-center d-flex align-items-center justify-content-center" style="min-height: 200px;">
                                @if($selectedClaim->image_url)
                                    <img src="{{ asset($selectedClaim->image_url) }}" alt="Preuve" class="img-fluid rounded-3 object-fit-cover w-100 h-100">
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center py-4 text-muted">
                                        <i class="fa-solid fa-image fs-2 mb-2 text-secondary"></i>
                                        <span class="small px-2">{{ $selectedClaim->description }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <span class="fw-bold text-uppercase text-muted extra-small d-block mb-2">GESTE COMMERCIAL PROPOSÉ</span>
                            <div class="p-3 rounded-4 bg-light border">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <span class="fw-bold text-dark">Total compensation</span>
                                    <span class="h5 fw-bold text-dark mb-0">{{ number_format($selectedClaim->compensation_amount ?? 2000, 0, ',', ' ') }} F CFA</span>
                                </div>
                                <ul class="list-unstyled mb-0 small text-muted">
                                    <li class="mb-2 d-flex align-items-start gap-2">
                                        <i class="fa-solid fa-circle-minus text-secondary mt-1"></i>
                                        <span>Déduction immédiate sur l'addition</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="fa-solid fa-utensils text-secondary mt-1"></i>
                                        <span>Geste commercial attribué au client</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('gerant.claims.review', $selectedClaim) }}" method="POST" class="mt-auto pt-3 border-top">
                        @csrf
                        @method('PATCH')
                        <div class="row g-2">
                            <div class="col-12 col-sm-7">
                                <button type="submit" name="decision" value="approved" class="btn w-100 py-3 fw-bold rounded-3 text-dark shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #cfbd97;">
                                    <i class="fa-regular fa-circle-check fs-5"></i> Valider la solution ({{ number_format($selectedClaim->compensation_amount ?? 2000, 0, ',', ' ') }} F)
                                </button>
                            </div>
                            <div class="col-12 col-sm-5">
                                <button type="submit" name="decision" value="rejected" class="btn btn-outline-secondary w-100 py-3 fw-bold rounded-3 bg-light border-0 text-muted d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-link-slash"></i> Rejeter / Ajuster
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 bg-white h-100 d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="rounded-circle bg-light p-4 mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-shield-cat fs-1 text-success"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Aucun litige en attente d'arbitrage</h5>
                    <p class="text-muted small mb-3" style="max-width: 360px;">
                        Toutes les commandes se déroulent parfaitement. Les nouvelles réclamations ou demandes d'arbitrage apparaîtront ici dès qu'un client ou serveur en signale une.
                    </p>
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold">
                        <i class="fa-solid fa-check me-1"></i> Service sous contrôle
                    </span>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
@extends('layouts.mmm')

@section('titre', 'Promotions & Événements - ZeDuc@Space')

@section('contenu')
<div class="promo-events-wrapper">

    <!-- En-tête : Titre + Description + Boutons d'action -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="font-serif fw-bold text-dark mb-1" style="font-size: 28px; font-family: Georgia, serif;">Promotions et événements</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Créer, gérer et suivre vos offres promotionnelles et événements.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.promotions.create') }}" class="btn btn-gold px-3 py-2 text-white fw-medium shadow-sm" style="background-color: #a68a56; border: none; border-radius: 6px; font-size: 13px;">
                + Créer une nouvelle promotion
            </a>
            <a href="{{ route('admin.evenements.create') ?? '#' }}" class="btn btn-gold px-3 py-2 text-white fw-medium shadow-sm" style="background-color: #a68a56; border: none; border-radius: 6px; font-size: 13px;">
                <i class="bi bi-calendar-event me-1"></i> Créer un nouvel événement
            </a>
        </div>
    </div>

    <!-- Top KPI Cards avec arrière-plan sombre (Comme sur Figma) -->
    <div class="row g-3 mb-4">
        <!-- Carte 1 : Total Promotions Actives -->
        <div class="col-md-6">
            <div class="card border-0 rounded-3 text-white p-3 shadow-sm" style="background-color: #0d0d0d; position: relative; overflow: hidden; min-height: 130px;">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Promotions Actives</span>
                    <i class="bi bi-graph-up text-muted fs-5"></i>
                </div>
                <div class="d-flex align-items-baseline gap-2 my-2">
                    <i class="bi bi-bar-chart-line-fill text-muted fs-4"></i>
                    <span class="fw-bold" style="font-size: 38px; line-height: 1;">{{ $totalPromotionsActives ?? 5 }}</span>
                </div>
                <div class="mt-auto">
                    <span class="badge bg-transparent text-success p-0" style="font-size: 12px; font-weight: 500;">
                        <i class="bi bi-arrow-up-right me-1"></i>+3 ce mois
                    </span>
                </div>
            </div>
        </div>

        <!-- Carte 2 : Événements à Venir -->
        <div class="col-md-6">
            <div class="card border-0 rounded-3 text-white p-3 shadow-sm" style="background-color: #0d0d0d; position: relative; overflow: hidden; min-height: 130px;">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Événements à Venir</span>
                    <i class="bi bi-calendar-check text-muted fs-5"></i>
                </div>
                <div class="d-flex align-items-baseline gap-2 my-2">
                    <i class="bi bi-calendar3 text-muted fs-4"></i>
                    <span class="fw-bold" style="font-size: 38px; line-height: 1;">{{ $evenementsAVenirCount ?? 2 }}</span>
                </div>
                <div class="mt-auto">
                    <span class="badge bg-transparent text-success p-0" style="font-size: 12px; font-weight: 500;">
                        <i class="bi bi-calendar-event me-1"></i>Prochain : {{ isset($prochainEvenement) ? \Carbon\Carbon::parse($prochainEvenement->date_debut)->format('d/m/Y') : '15/10/2025' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau 1 : Liste des promotions en cours -->
    <div class="mb-5">
        <h2 class="h5 fw-bold text-dark mb-3">Liste des promotions en cours</h2>
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead style="background-color: #1a1a1a; color: #ffffff;">
                        <tr class="text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">
                            <th class="py-3 px-3">Nom de la promotion</th>
                            <th class="py-3 px-3">Date de début</th>
                            <th class="py-3 px-3">Date de fin</th>
                            <th class="py-3 px-3">Statut</th>
                            <th class="py-3 px-3">Utilisations</th>
                            <th class="py-3 px-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions ?? [] as $promo)
                            <tr class="border-bottom">
                                <td class="py-3 px-3 fw-bold text-dark">{{ $promo->nom }}</td>
                                <td class="py-3 px-3 text-muted">{{ \Carbon\Carbon::parse($promo->date_debut)->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 text-muted">{{ \Carbon\Carbon::parse($promo->date_fin)->format('d/m/Y') }}</td>
                                <td class="py-3 px-3">
                                    <span class="badge rounded-pill bg-success-subtle text-success px-2 py-1" style="font-weight: 500;">
                                        ● {{ ucfirst($promo->statut ?? 'Actif') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 fw-medium">{{ number_format($promo->utilisations_count ?? 0) }}</td>
                                <td class="py-3 px-3 text-end">
                                    <a href="{{ route('admin.promotions.edit', $promo->id ?? 1) }}" class="btn btn-sm text-white px-3" style="background-color: #a68a56; border-radius: 4px; font-size: 12px;">
                                        Modifier
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucune promotion en cours.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tableau 2 : Liste des événements à venir -->
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold text-dark mb-0">Liste des événements à venir</h2>
            <a href="{{ route('admin.evenements.create') ?? '#' }}" class="btn btn-sm btn-light border px-3 py-1 shadow-sm" style="font-size: 12px; font-weight: 500;">
                + Ajouter un événement
            </a>
        </div>
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead style="background-color: #1a1a1a; color: #ffffff;">
                        <tr class="text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">
                            <th class="py-3 px-3">Nom de l'événement</th>
                            <th class="py-3 px-3">Type</th>
                            <th class="py-3 px-3">Date de début</th>
                            <th class="py-3 px-3">Date de fin</th>
                            <th class="py-3 px-3">Statut</th>
                            <th class="py-3 px-3">Participations / Inscrits</th>
                            <th class="py-3 px-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evenements ?? [] as $evt)
                            <tr class="border-bottom">
                                <td class="py-3 px-3 fw-bold text-dark">{{ $evt->nom }}</td>
                                <td class="py-3 px-3 text-muted">{{ $evt->type ?? 'Général' }}</td>
                                <td class="py-3 px-3 text-muted">{{ \Carbon\Carbon::parse($evt->date_debut)->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 text-muted">{{ \Carbon\Carbon::parse($evt->date_fin)->format('d/m/Y') }}</td>
                                <td class="py-3 px-3">
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-2 py-1" style="font-weight: 500;">
                                        ● {{ ucfirst($evt->statut ?? 'Planifié') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 fw-medium">{{ $evt->inscrits_count ?? 0 }}</td>
                                <td class="py-3 px-3 text-end">
                                    <a href="{{ route('admin.evenements.edit', $evt->id ?? 1) ?? '#' }}" class="btn btn-sm text-white px-3" style="background-color: #a68a56; border-radius: 4px; font-size: 12px;">
                                        Modifier
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucun événement à venir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
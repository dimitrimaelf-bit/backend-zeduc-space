@extends('layouts.Gerant')
@section('title', 'Supervision du Service')

@section('content')
{{-- En-tête de la page --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <p class="text-uppercase fw-bold small mb-1" style="color: var(--zeduc-gold, #cfbd97);">• EN DIRECT • SERVICE EN COURS</p>
        <h1 class="h2 fw-bold text-dark mb-0">Supervision des Commandes</h1>
    </div>

    {{-- Bouton d'action ou rafraîchissement rapide --}}
    <div>
        <button class="btn btn-outline-dark btn-sm rounded-3 px-3 fw-bold" onclick="window.location.reload();">
            <i class="bi bi-arrow-clockwise me-1"></i> Actualiser
        </button>
    </div>
</div>

{{-- KPI Temps Réel du Service --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card
            title="EN PRÉPARATION"
            value="{{ $commandesEnAttente }}"
            unit="commandes"
            subtitle="En cuisine / bar"
        />
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card
            title="PRÊTES AU PASSE"
            value="{{ $commandesPretes }}"
            unit="bons"
            subtitle="À servir ou livrer"
        />
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card
            title="LIVRÉES AUJOURD'HUI"
            value="{{ $commandesLivrees }}"
            unit="servies"
            subtitle="Service actuel"
        />
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card
            title="ALERTES RETARD"
            value="{{ $commandesEnRetardCount ?? 0 }}"
            unit="alertes"
            
        />
    </div>
</div>

{{-- Système d'onglets de navigation --}}
<x-order-tabs :active="$currentTab" :counts="[
    'encours' => $commandesEnAttente,
    'pretes' => $commandesPretes,
    'livrees' => $commandesLivrees,
]" />

@if (session('status'))
    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
@endif

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Commande</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Modifier le statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user?->first_name }} {{ $order->user?->last_name }}</td>
                        <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($order->status)) }}</td>
                        <td>
                            <form method="POST" action="{{ route('gerant.orders.status.update', $order) }}" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" aria-label="Nouveau statut">
                                    @foreach (['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'completed', 'cancelled'] as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-dark">Enregistrer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucune commande à afficher.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
</div>
@endsection
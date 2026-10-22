@extends('employe.layouts.app')
@section('title', 'Commandes')
@section('page-heading', 'Gestion des commandes')
@section('content')
<div class="section-title-row"><div><h2>Suivi des commandes</h2><p>Consultez, préparez et validez les commandes des étudiants.</p></div><span class="date-chip">Mise à jour en direct</span></div>
<form method="GET" action="{{ route('employe.commandes.index') }}" class="filter-bar">
    <input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Rechercher par numéro ou client…">
    <select class="form-select" name="statut"><option value="">Tous les statuts</option>@foreach(['En attente','En préparation','Prête','Terminée','Annulée'] as $s)<option value="{{ $s }}" @selected(request('statut')===$s)>{{ $s }}</option>@endforeach</select>
    <button class="btn btn-dark-gold">Filtrer</button>
</form>
<div class="panel-card"><div class="table-responsive"><table class="table custom-table align-middle"><thead><tr><th>N° commande</th><th>Client</th><th>Type</th><th>Date</th><th>Montant</th><th>Statut</th><th>Action</th></tr></thead><tbody>
@forelse($commandes ?? [] as $commande)
<tr><td><strong>#{{ $commande->id }}</strong></td><td>{{ $commande->user->name ?? $commande->client_nom ?? 'Client' }}</td><td>{{ $commande->type_commande ?? 'Sur place' }}</td><td>{{ optional($commande->created_at)->format('d/m/Y H:i') }}</td><td>{{ number_format($commande->montant_total ?? 0,0,',',' ') }} FCFA</td><td><span class="status status-pending">{{ $commande->statut ?? 'En attente' }}</span></td><td><a class="table-action" href="{{ route('employe.commandes.show', $commande) }}">Détails →</a></td></tr>
@empty
<tr><td colspan="7"><div class="empty-state"><span>▤</span><strong>Aucune commande à afficher</strong><p>Les commandes apparaîtront ici dès qu'elles seront enregistrées.</p></div></td></tr>
@endforelse
</tbody></table></div>
@if(isset($commandes) && method_exists($commandes,'links'))<div class="p-3">{{ $commandes->links() }}</div>@endif
</div>
@endsection

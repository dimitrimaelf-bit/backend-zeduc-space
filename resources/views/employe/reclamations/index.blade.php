@extends('employe.layouts.app')
@section('title', 'Réclamations')
@section('page-heading', 'Réclamations des clients')
@section('content')
<div class="section-title-row"><div><h2>Centre de réclamations</h2><p>Suivez les problèmes signalés et répondez aux étudiants.</p></div></div>
<form method="GET" action="{{ route('employe.reclamations.index') }}" class="filter-bar"><input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Rechercher une réclamation…"><select class="form-select" name="statut"><option value="">Tous les statuts</option><option>Ouverte</option><option>En cours</option><option>Résolue</option></select><button class="btn btn-dark-gold">Filtrer</button></form>
<div class="panel-card"><div class="table-responsive"><table class="table custom-table align-middle"><thead><tr><th>N°</th><th>Client</th><th>Objet</th><th>Date</th><th>Statut</th><th></th></tr></thead><tbody>
@forelse($reclamations ?? [] as $reclamation)
<tr><td>#{{ $reclamation->id }}</td><td>{{ $reclamation->user->name ?? 'Étudiant' }}</td><td>{{ $reclamation->objet ?? $reclamation->sujet ?? 'Réclamation' }}</td><td>{{ optional($reclamation->created_at)->format('d/m/Y') }}</td><td><span class="status status-pending">{{ $reclamation->statut ?? 'Ouverte' }}</span></td><td><a class="table-action" href="{{ route('employe.reclamations.show', $reclamation) }}">Traiter →</a></td></tr>
@empty<tr><td colspan="6"><div class="empty-state"><span>✉</span><strong>Aucune réclamation à afficher</strong><p>Les nouvelles réclamations seront listées ici.</p></div></td></tr>@endforelse
</tbody></table></div>@if(isset($reclamations) && method_exists($reclamations,'links'))<div class="p-3">{{ $reclamations->links() }}</div>@endif</div>
@endsection

@extends('employe.layouts.app')
@section('title', 'Détail commande')
@section('page-heading', 'Détail de la commande')
@section('content')
<a class="back-link" href="{{ route('employe.commandes.index') }}">← Retour aux commandes</a>
<div class="detail-grid"><section class="panel-card"><div class="panel-heading"><div><h3>Commande #{{ $commande->id }}</h3><p>Informations et articles commandés</p></div><span class="status status-pending">{{ $commande->statut ?? 'En attente' }}</span></div>
<div class="detail-info"><div><small>Client</small><strong>{{ $commande->user->name ?? $commande->client_nom ?? 'Client' }}</strong></div><div><small>Date</small><strong>{{ optional($commande->created_at)->format('d/m/Y à H:i') }}</strong></div><div><small>Mode</small><strong>{{ $commande->type_commande ?? 'Sur place' }}</strong></div><div><small>Téléphone</small><strong>{{ $commande->user->telephone ?? $commande->telephone ?? 'Non renseigné' }}</strong></div></div>
<h4 class="subheading">Articles</h4><div class="table-responsive"><table class="table custom-table"><thead><tr><th>Article</th><th>Quantité</th><th>Sous-total</th></tr></thead><tbody>
@forelse(($commande->items ?? $commande->details ?? []) as $item)<tr><td>{{ $item->plat->nom ?? $item->nom ?? 'Article' }}</td><td>{{ $item->quantite ?? 1 }}</td><td>{{ number_format($item->sous_total ?? 0,0,',',' ') }} FCFA</td></tr>@empty<tr><td colspan="3">Le détail des articles n'est pas disponible.</td></tr>@endforelse
</tbody></table></div><div class="total-line"><span>Total commande</span><strong>{{ number_format($commande->montant_total ?? 0,0,',',' ') }} FCFA</strong></div></section>
<section class="panel-card"><h3>Mettre à jour le statut</h3><p class="muted">Confirmez l'avancement de la commande.</p><form method="POST" action="{{ route('employe.commandes.update', $commande) }}">@csrf @method('PATCH')
<label class="form-label" for="statut">Statut de la commande</label><select class="form-select mb-3" id="statut" name="statut" required>@foreach(['En attente','En préparation','Prête','Terminée','Annulée'] as $s)<option value="{{ $s }}" @selected(($commande->statut ?? '')===$s)>{{ $s }}</option>@endforeach</select><button class="btn btn-gold w-100">Enregistrer le statut</button></form></section></div>
@endsection

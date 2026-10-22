@extends('employe.layouts.app')
@section('title', 'Traiter une réclamation')
@section('page-heading', 'Traitement d’une réclamation')
@section('content')
<a class="back-link" href="{{ route('employe.reclamations.index') }}">← Retour aux réclamations</a>
<div class="panel-card complaint-card"><span class="status status-pending">{{ $reclamation->statut ?? 'Ouverte' }}</span><h2 class="mt-3">{{ $reclamation->objet ?? $reclamation->sujet ?? 'Réclamation #'.$reclamation->id }}</h2><p class="muted">Soumise par {{ $reclamation->user->name ?? 'un étudiant' }} · {{ optional($reclamation->created_at)->format('d/m/Y à H:i') }}</p><div class="complaint-message">{{ $reclamation->message ?? $reclamation->description ?? 'Aucun détail fourni.' }}</div>
<form method="POST" action="{{ route('employe.reclamations.update', $reclamation) }}">@csrf @method('PATCH')
<div class="mb-3"><label class="form-label" for="reponse">Votre réponse</label><textarea class="form-control" id="reponse" name="reponse" rows="5" required>{{ old('reponse', $reclamation->reponse ?? '') }}</textarea></div>
<div class="mb-3"><label class="form-label" for="statut">État</label><select class="form-select" name="statut" id="statut"><option value="Ouverte">Ouverte</option><option value="En cours">En cours</option><option value="Résolue">Résolue</option></select></div><button class="btn btn-gold">Enregistrer la réponse</button></form></div>
@endsection

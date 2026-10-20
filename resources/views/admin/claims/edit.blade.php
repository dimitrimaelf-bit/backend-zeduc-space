@extends('layouts.admin')

@section('title', 'Traiter la Réclamation - Mon Miam Miam')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Traiter la réclamation #{{ $claim->id }}</h3>
    <a href="{{ route('admin.claims.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Retour</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-2">Message du Client ({{ $claim->user->name ?? 'Anonyme' }}) :</h5>
        <p class="text-muted p-3 bg-light rounded">{{ $claim->message }}</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.claims.update', $claim) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="status" class="form-label fw-semibold">Changer le Statut</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="en_attente" {{ $claim->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="résolu" {{ $claim->status == 'résolu' ? 'selected' : '' }}>Résolu</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="admin_response" class="form-label fw-semibold">Réponse de l'administration</label>
                <textarea class="form-control" id="admin_response" name="admin_response" rows="4" placeholder="Tapez votre réponse ici...">{{ old('admin_response', $claim->admin_response) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.claims.index') }}" class="btn btn-light">Annuler</a>
                <button type="submit" class="btn btn-brand"><i class="fa-solid fa-paper-plane me-1"></i> Valider et Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
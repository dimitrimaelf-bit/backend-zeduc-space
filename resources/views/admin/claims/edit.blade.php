@extends('layouts.mmm')

@section('titre', 'Traiter une réclamation')

@section('contenu')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start mb-4"><div><h1 class="h3 fw-bold">Réclamation #{{ $claim->id }}</h1><p class="text-muted">Commande {{ $claim->order?->order_number }} · {{ $claim->user?->first_name }} {{ $claim->user?->last_name }}</p></div><a href="{{ route('admin.claims.index') }}" class="btn btn-outline-secondary">Retour</a></div>
    <div class="bg-white border rounded-2 p-4 mb-3"><h2 class="h5">{{ $claim->subject }}</h2><p class="mb-0">{{ $claim->description ?: 'Aucun détail fourni.' }}</p></div>
    <div class="bg-white border rounded-2 p-4">
        <form method="POST" action="{{ route('admin.claims.update', $claim) }}">
            @csrf
            @method('PUT')
            <div class="mb-3"><label for="status" class="form-label">Décision</label><select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>@foreach(['open' => 'Ouverte', 'in_progress' => 'En cours', 'resolved' => 'Résolue', 'rejected' => 'Rejetée'] as $value => $label)<option value="{{ $value }}" @selected(old('status', $claim->status) === $value)>{{ $label }}</option>@endforeach</select>@error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label for="response" class="form-label">Réponse à l’étudiant</label><textarea id="response" name="response" rows="5" class="form-control @error('response') is-invalid @enderror">{{ old('response', $claim->response) }}</textarea>@error('response')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="d-flex justify-content-end"><button type="submit" class="btn btn-dark">Enregistrer la décision</button></div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.mmm')

@section('titre', 'Modifier un événement')

@section('contenu')
<div class="container-fluid px-0"><div class="mb-4"><h1 class="h3 fw-bold">Modifier l’événement</h1><p class="text-muted">{{ $event->title }}</p></div><div class="bg-white border rounded-2 p-4"><form method="POST" enctype="multipart/form-data" action="{{ route('admin.events.update', $event) }}">@csrf @method('PUT')
<div class="row g-3"><div class="col-md-8"><label for="title" class="form-label">Nom de l’événement</label><input id="title" name="title" value="{{ old('title', $event->title) }}" class="form-control @error('title') is-invalid @enderror" required>@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4"><label for="type" class="form-label">Type</label><input id="type" name="type" value="{{ old('type', $event->type) }}" class="form-control"></div>
<div class="col-12"><label for="description" class="form-label">Description</label><textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $event->description) }}</textarea></div>
@if($event->image_url)<div class="col-12"><img src="{{ $event->image_url }}" alt="Affiche actuelle" class="rounded border" style="max-width: 320px; max-height: 180px; object-fit: cover;"></div>@endif
<div class="col-md-6"><label for="image_file" class="form-label">Remplacer l’affiche par un fichier</label><input id="image_file" type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="form-control @error('image_file') is-invalid @enderror">@error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label for="image_url" class="form-label">Ou utiliser une URL d’affiche</label><input id="image_url" type="url" name="image_url" value="{{ old('image_url', $event->image_url) }}" class="form-control @error('image_url') is-invalid @enderror">@error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label for="starts_at" class="form-label">Début</label><input id="starts_at" type="datetime-local" name="starts_at" value="{{ old('starts_at', $event->starts_at?->format('Y-m-d\\TH:i')) }}" class="form-control"></div><div class="col-md-6"><label for="ends_at" class="form-label">Fin</label><input id="ends_at" type="datetime-local" name="ends_at" value="{{ old('ends_at', $event->ends_at?->format('Y-m-d\\TH:i')) }}" class="form-control"></div>
<div class="col-12 form-check form-switch ms-2"><input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $event->is_active))><label for="is_active" class="form-check-label">Événement actif</label></div></div>
<div class="d-flex justify-content-end gap-2 mt-4"><a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">Annuler</a><button class="btn btn-dark">Enregistrer</button></div></form></div></div>
@endsection

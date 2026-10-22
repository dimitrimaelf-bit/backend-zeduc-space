@extends('layouts.mmm')

@section('titre', 'Modifier une catégorie')

@section('contenu')
<div class="container-fluid px-0"><div class="mb-4"><h1 class="h3 fw-bold">Modifier une catégorie</h1></div><div class="bg-white border rounded-2 p-4"><form method="POST" action="{{ route('admin.categories.update', $category) }}">@csrf @method('PUT')
<div class="row g-3"><div class="col-md-6"><label for="name" class="form-label">Nom</label><input id="name" name="name" value="{{ old('name', $category->name) }}" class="form-control @error('name') is-invalid @enderror" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-3"><label for="sort_order" class="form-label">Ordre d’affichage</label><input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="form-control @error('sort_order') is-invalid @enderror" required>@error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch mb-2"><input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $category->is_active))><label for="is_active" class="form-check-label">Catégorie active</label></div></div></div>
<div class="d-flex justify-content-end gap-2 mt-4"><a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Annuler</a><button class="btn btn-dark">Enregistrer</button></div></form></div></div>
@endsection

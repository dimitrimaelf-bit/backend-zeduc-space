@extends('layouts.mmm')

@section('titre', 'Modifier un article du menu')

@section('contenu')
<div class="container-fluid px-0">
    <div class="mb-4"><h1 class="h3 fw-bold">Modifier l’article</h1><p class="text-muted">{{ $item->name }}</p></div>
    <div class="bg-white border rounded-2 p-4">
        <form method="POST" action="{{ route('admin.menu.update', $item) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label for="name" class="form-label">Nom</label><input id="name" name="name" value="{{ old('name', $item->name) }}" class="form-control @error('name') is-invalid @enderror" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="category_id" class="form-label">Catégorie</label><select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror"><option value="">Sans catégorie</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string) old('category_id', $item->category_id) === (string) $category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="price_fcfa" class="form-label">Prix (FCFA)</label><input id="price_fcfa" type="number" min="0" step="1" name="price_fcfa" value="{{ old('price_fcfa', $item->price_fcfa) }}" class="form-control @error('price_fcfa') is-invalid @enderror" required>@error('price_fcfa')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="image_url" class="form-label">URL de l’image</label><input id="image_url" type="url" name="image_url" value="{{ old('image_url', $item->image_url) }}" class="form-control @error('image_url') is-invalid @enderror">@error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label for="description" class="form-label">Description</label><textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>@error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 form-check form-switch ms-2"><input id="is_available" type="checkbox" name="is_available" value="1" class="form-check-input" @checked(old('is_available', $item->is_available))><label for="is_available" class="form-check-label">Disponible à la commande</label></div>
                <div class="col-md-6 form-check form-switch ms-2"><input id="is_daily_special" type="checkbox" name="is_daily_special" value="1" class="form-check-input" @checked(old('is_daily_special', $item->is_daily_special))><label for="is_daily_special" class="form-check-label">Plat du jour</label></div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4"><a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">Annuler</a><button class="btn btn-dark" type="submit">Enregistrer</button></div>
        </form>
    </div>
</div>
@endsection

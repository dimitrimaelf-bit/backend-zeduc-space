@extends('layouts.mmm')

@section('titre', 'Gestion du menu - ZeDuc@Space')

@section('contenu')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
        <div><h1 class="h3 fw-bold mb-1">Gestion du menu</h1><p class="text-muted mb-0">Articles, disponibilité et plats du jour.</p></div>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-dark">Ajouter un article</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form method="GET" action="{{ route('admin.menu.index') }}" class="row g-2 mb-3">
        <div class="col-sm-6"><label class="visually-hidden" for="menu-search">Rechercher</label><input id="menu-search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nom ou description"></div>
        <div class="col-sm-4"><label class="visually-hidden" for="menu-category">Catégorie</label><select id="menu-category" name="category_id" class="form-select"><option value="">Toutes les catégories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="col-sm-2 d-grid"><button class="btn btn-outline-secondary" type="submit">Filtrer</button></div>
    </form>
    <div class="table-responsive bg-white border rounded-2">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th class="ps-3">Article</th><th>Catégorie</th><th>Prix</th><th>Disponibilité</th><th>Plat du jour</th><th class="text-end pe-3">Actions</th></tr></thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="ps-3"><div class="d-flex align-items-center gap-3">@if($item->image_url)<img src="{{ $item->image_url }}" alt="{{ $item->name }}" width="48" height="48" class="rounded object-fit-cover">@endif<div><div class="fw-semibold">{{ $item->name }}</div><small class="text-muted">{{ $item->description }}</small></div></div></td>
                        <td>{{ $item->category?->name ?? 'Sans catégorie' }}</td>
                        <td>{{ number_format($item->price_fcfa, 0, ',', ' ') }} FCFA</td>
                        <td><span class="badge {{ $item->is_available ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $item->is_available ? 'Disponible' : 'Indisponible' }}</span></td>
                        <td>{{ $item->is_daily_special ? 'Oui' : '—' }}</td>
                        <td class="text-end pe-3"><div class="d-inline-flex gap-2"><a href="{{ route('admin.menu.edit', $item) }}" class="btn btn-sm btn-outline-dark">Modifier</a><form method="POST" action="{{ route('admin.menu.destroy', $item) }}" onsubmit="return confirm('Supprimer cet article du menu ?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Supprimer {{ $item->name }}">Supprimer</button></form></div></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucun article ne correspond à cette recherche.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection

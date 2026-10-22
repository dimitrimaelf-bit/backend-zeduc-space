@extends('layouts.mmm')

@section('titre', 'Catégories du menu')

@section('contenu')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start mb-4"><div><h1 class="h3 fw-bold">Catégories du menu</h1><p class="text-muted">Classement et disponibilité des catégories.</p></div><a href="{{ route('admin.categories.create') }}" class="btn btn-dark">Ajouter une catégorie</a></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive bg-white border rounded-2"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th class="ps-3">Ordre</th><th>Nom</th><th>Statut</th><th class="text-end pe-3">Actions</th></tr></thead><tbody>
        @forelse($categories as $category)
            <tr><td class="ps-3">{{ $category->sort_order }}</td><td class="fw-semibold">{{ $category->name }}</td><td><span class="badge {{ $category->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td><td class="text-end pe-3"><div class="d-inline-flex gap-2"><a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-dark">Modifier</a><form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Supprimer cette catégorie ?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Supprimer</button></form></div></td></tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Aucune catégorie enregistrée.</td></tr>
        @endforelse
    </tbody></table></div>
</div>
@endsection

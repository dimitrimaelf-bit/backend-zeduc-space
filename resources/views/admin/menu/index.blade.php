@extends('layouts.admin')

@section('title', 'Gestion du Menu - Mon Miam Miam')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Carte & Plats du Menu</h3>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-brand">
        <i class="fa-solid fa-plus me-1"></i> Ajouter un Plat
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">Image</th>
                        <th scope="col">Nom du Plat</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menuItems as $item)
                        <tr>
                            <td class="ps-4">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-utensils"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $item->name }}</td>
                            <td><span class="badge bg-secondary">{{ $item->category->name ?? 'Non classé' }}</span></td>
                            <td class="fw-bold text-success">{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($item->is_available)
                                    <span class="badge bg-success-subtle text-success border border-success">Disponible</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger">Indisponible</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.menu.edit', $item) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plat ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-utensils fs-3 d-block mb-2"></i>
                                Aucun plat enregistré au menu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
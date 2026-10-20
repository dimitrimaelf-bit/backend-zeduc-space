@extends('layouts.admin')

@section('title', 'Gestion des Promotions - Mon Miam Miam')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Offres & Promotions</h3>
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-brand">
        <i class="fa-solid fa-plus me-1"></i> Créer une Promotion
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">Code Promo</th>
                        <th scope="col">Réduction (%)</th>
                        <th scope="col">Date de Début</th>
                        <th scope="col">Date de Fin</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promo)
                        <tr>
                            <td class="ps-4 fw-bold text-danger">{{ $promo->code }}</td>
                            <td class="fw-semibold">{{ $promo->discount_percentage }}%</td>
                            <td>{{ $promo->start_date }}</td>
                            <td>{{ $promo->end_date }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.promotions.edit', $promo) }}" class="btn btn-sm btn-outline-warning" title="Modifier"><i class="fa-solid fa-pen"></i></a>
                                    <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST" onsubmit="return confirm('Supprimer cette promotion ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-tags fs-3 d-block mb-2"></i> Aucune promotion active.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
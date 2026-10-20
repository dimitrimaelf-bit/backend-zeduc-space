@extends('layouts.admin')

@section('title', 'Gestion des Réclamations - Mon Miam Miam')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Réclamations Clients</h3>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">Client</th>
                        <th scope="col">Sujet</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Date</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $claim->user->name ?? 'Anonyme' }}</td>
                            <td>{{ $claim->subject }}</td>
                            <td>
                                @if($claim->status == 'résolu')
                                    <span class="badge bg-success">Résolu</span>
                                @else
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @endif
                            </td>
                            <td>{{ $claim->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.claims.edit', $claim) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-reply me-1"></i> Traiter
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-circle-check fs-3 d-block mb-2"></i> Aucune réclamation à traiter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
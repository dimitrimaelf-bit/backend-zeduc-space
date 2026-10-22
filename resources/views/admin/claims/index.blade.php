@extends('layouts.mmm')

@section('titre', 'Gestion des réclamations')

@section('contenu')
<div class="container-fluid px-0">
    <div class="mb-4"><h1 class="h3 fw-bold mb-1">Gestion des réclamations</h1><p class="text-muted mb-0">Consultez les demandes des étudiants et répondez-y.</p></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form method="GET" action="{{ route('admin.claims.index') }}" class="row g-2 mb-3">
        <div class="col-sm-7"><label class="visually-hidden" for="claim-search">Rechercher</label><input id="claim-search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Sujet, étudiant ou numéro de commande"></div>
        <div class="col-sm-3"><label class="visually-hidden" for="claim-status">Statut</label><select id="claim-status" name="status" class="form-select"><option value="">Tous les statuts</option>@foreach(['open' => 'Ouverte', 'in_progress' => 'En cours', 'resolved' => 'Résolue', 'rejected' => 'Rejetée'] as $value => $label)<option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>@endforeach</select></div>
        <div class="col-sm-2 d-grid"><button class="btn btn-outline-secondary">Filtrer</button></div>
    </form>
    <div class="table-responsive bg-white border rounded-2">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th class="ps-3">Commande</th><th>Étudiant</th><th>Sujet</th><th>Date</th><th>Statut</th><th class="text-end pe-3">Action</th></tr></thead>
            <tbody>
                @forelse($claims as $claim)
                    <tr><td class="ps-3">{{ $claim->order?->order_number ?? '—' }}</td><td>{{ $claim->user?->first_name }} {{ $claim->user?->last_name }}</td><td>{{ $claim->subject }}</td><td>{{ $claim->created_at?->format('d/m/Y H:i') }}</td><td><span class="badge text-bg-{{ $claim->status === 'resolved' ? 'success' : ($claim->status === 'rejected' ? 'secondary' : 'warning') }}">{{ ['open' => 'Ouverte', 'in_progress' => 'En cours', 'resolved' => 'Résolue', 'rejected' => 'Rejetée'][$claim->status] ?? $claim->status }}</span></td><td class="text-end pe-3"><a class="btn btn-sm btn-outline-dark" href="{{ route('admin.claims.edit', $claim) }}">Traiter</a></td></tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune réclamation trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $claims->links() }}</div>
</div>
@endsection

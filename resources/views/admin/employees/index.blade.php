@extends('layouts.mmm')

@section('titre', 'Gestion des employés et gérants - ZeDuc@Space')

@section('contenu')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Gestion des employés et gérants</h1>
            <p class="text-muted mb-0">Gérez les comptes du personnel; les employés et gérants sont affichés par défaut.</p>
        </div>
        <a href="{{ route('admin.employees.create', ['role' => $roleFilter === 'all' ? 'employee' : $roleFilter]) }}" class="btn btn-dark">Ajouter un compte</a>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form method="GET" action="{{ route('admin.employees.index') }}" class="row g-2 mb-3">
        <div class="col-sm-5"><label class="visually-hidden" for="staff-search">Rechercher</label><input id="staff-search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nom ou adresse courriel"></div>
        <div class="col-sm-3"><label class="visually-hidden" for="staff-role">Type de compte</label><select id="staff-role" name="role" class="form-select"><option value="all" @selected($roleFilter === 'all')>Employés et gérants</option><option value="employee" @selected($roleFilter === 'employee')>Employés</option><option value="gerant" @selected(in_array($roleFilter, ['gerant', 'manager'], true))>Gérants</option></select></div>
        <div class="col-sm-2"><label class="visually-hidden" for="staff-active">Statut</label><select id="staff-active" name="active" class="form-select"><option value="">Tous les statuts</option><option value="1" @selected(request('active') === '1')>Actifs</option><option value="0" @selected(request('active') === '0')>Inactifs</option></select></div>
        <div class="col-sm-2 d-grid"><button class="btn btn-outline-secondary" type="submit">Filtrer</button></div>
    </form>

    <div class="table-responsive bg-white border rounded-2">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th class="ps-3">Nom</th><th>Email</th><th>Téléphone</th><th>Rôle</th><th>Statut</th><th>Créé le</th><th class="text-end pe-3">Actions</th></tr></thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone ?: '—' }}</td>
                        <td>{{ $employee->role?->label ?? $role->label }}</td>
                        <td><span class="badge {{ $employee->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $employee->is_active ? 'Actif' : 'Inactif' }}</span></td>
                        <td>{{ $employee->created_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-end pe-3"><a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-outline-dark" aria-label="Modifier {{ $employee->first_name }}">Modifier</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Aucun compte trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $employees->links() }}</div>
</div>
@endsection

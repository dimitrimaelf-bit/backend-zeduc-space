@extends('layouts.Gerant')

@section('title', 'Historique des shifts - Espace Gérant')

@section('content')
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-uppercase small fw-bold mb-1 text-secondary">Équipe • Pointages</p>
        <h1 class="h2 fw-bold mb-0">Historique des shifts</h1>
    </div>
    <span class="text-muted small">{{ $shifts->total() }} shifts enregistrés</span>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="bg-white border rounded-3 p-3 h-100">
            <div class="small text-uppercase text-muted fw-semibold">Shifts sur cette recherche</div>
            <div class="fs-3 fw-bold mt-1">{{ $shiftCount }}</div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="bg-white border rounded-3 p-3 h-100">
            <div class="small text-uppercase text-muted fw-semibold">Heures terminées sur cette recherche</div>
            <div class="fs-3 fw-bold mt-1">{{ intdiv($totalMinutes, 60) }}h {{ str_pad((string) ($totalMinutes % 60), 2, '0', STR_PAD_LEFT) }}min</div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('gerant.shifts.index') }}" class="row g-2 align-items-end mb-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <label for="start_date" class="form-label small fw-semibold">Du</label>
        <input id="start_date" name="start_date" type="date" class="form-control" value="{{ $filters['start_date'] ?? '' }}">
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <label for="end_date" class="form-label small fw-semibold">Au</label>
        <input id="end_date" name="end_date" type="date" class="form-control" value="{{ $filters['end_date'] ?? '' }}">
    </div>
    <div class="col-12 col-sm-8 col-lg-3">
        <label for="employee_id" class="form-label small fw-semibold">Employé</label>
        <select id="employee_id" name="employee_id" class="form-select">
            <option value="">Tous les employés</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @selected(($filters['employee_id'] ?? '') == $employee->id)>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-sm-4 col-lg-3 d-flex gap-2">
        <button class="btn btn-dark flex-grow-1" type="submit"><i class="fa-solid fa-filter me-1"></i> Filtrer</button>
        <a class="btn btn-outline-secondary" href="{{ route('gerant.shifts.index') }}" aria-label="Effacer les filtres" title="Effacer les filtres"><i class="fa-solid fa-xmark"></i></a>
    </div>
</form>

<div class="table-responsive bg-white border rounded-3">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col">Collaborateur</th>
                <th scope="col">Date & service</th>
                <th scope="col">Horaires</th>
                <th scope="col">Durée</th>
                <th scope="col">Statut</th>
                <th scope="col" class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shifts as $shift)
                <tr>
                    <td class="fw-semibold">{{ $shift->employee->first_name }} {{ $shift->employee->last_name }}</td>
                    <td>
                        <div>{{ $shift->shift_date->format('d/m/Y') }}</div>
                        <small class="text-muted">{{ $shift->service ?: 'Service non précisé' }}</small>
                    </td>
                    <td>{{ $shift->started_at->format('H:i') }} – {{ $shift->ended_at?->format('H:i') ?? 'En cours' }}</td>
                    <td>
                        @if ($shift->duration_minutes !== null)
                            {{ intdiv($shift->duration_minutes, 60) }}h {{ str_pad((string) ($shift->duration_minutes % 60), 2, '0', STR_PAD_LEFT) }}
                        @else
                            <span class="text-muted">En cours</span>
                        @endif
                    </td>
                    <td>
                        @if ($shift->status === 'validated')
                            <span class="badge text-bg-success">Émargé validé</span>
                        @elseif ($shift->status === 'pending')
                            <span class="badge text-bg-warning">À valider</span>
                        @else
                            <span class="badge text-bg-secondary">En cours</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($shift->status === 'pending')
                            <form method="POST" action="{{ route('gerant.shifts.validate', $shift) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-success" type="submit" title="Valider le shift">
                                    <i class="fa-solid fa-check me-1"></i>Valider
                                </button>
                            </form>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-5">Aucun shift pour ces critères.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $shifts->links() }}</div>
@endsection
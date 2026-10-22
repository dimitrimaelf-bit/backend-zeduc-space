@extends('layouts.mmm')

@section('titre', 'Modifier un membre du personnel')

@section('contenu')
<div class="container-fluid px-0">
    <div class="mb-4"><h1 class="h3 fw-bold">Modifier le compte</h1><p class="text-muted">{{ $employee->first_name }} {{ $employee->last_name }}</p></div>
    <div class="bg-white border rounded-2 p-4">
        <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label for="first_name" class="form-label">Prénom</label><input id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required>@error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="last_name" class="form-label">Nom</label><input id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>@error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="email" class="form-label">Adresse courriel</label><input id="email" type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-control @error('email') is-invalid @enderror" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="phone" class="form-label">Téléphone</label><input id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" class="form-control @error('phone') is-invalid @enderror">@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="is_active" class="form-label">Statut du compte</label><select id="is_active" name="is_active" class="form-select"><option value="1" @selected((bool) old('is_active', $employee->is_active))>Actif</option><option value="0" @selected(!(bool) old('is_active', $employee->is_active))>Inactif</option></select></div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4"><a href="{{ route('admin.employees.index', ['role' => $role->code]) }}" class="btn btn-outline-secondary">Annuler</a><button class="btn btn-dark" type="submit">Enregistrer</button></div>
        </form>
    </div>
</div>
@endsection

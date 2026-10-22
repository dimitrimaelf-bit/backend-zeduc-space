@extends('layouts.mmm')

@section('titre', 'Ajouter un membre du personnel')

@section('contenu')
<div class="container-fluid px-0">
    <div class="mb-4"><h1 class="h3 fw-bold">Ajouter un membre du personnel</h1><p class="text-muted">Créer un compte employé ou gérant.</p></div>
    <div class="bg-white border rounded-2 p-4">
        <form method="POST" action="{{ route('admin.employees.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label for="first_name" class="form-label">Prénom</label><input id="first_name" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" required>@error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="last_name" class="form-label">Nom</label><input id="last_name" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" required>@error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="email" class="form-label">Adresse courriel</label><input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="phone" class="form-label">Téléphone</label><input id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label for="role_code" class="form-label">Rôle</label><select id="role_code" name="role_code" class="form-select" required><option value="employee" @selected(old('role_code', $role->code) === 'employee')>Employé</option><option value="gerant" @selected(in_array(old('role_code', $role->code), ['gerant', 'manager'], true))>Gérant</option></select></div>
                <div class="col-md-6"><label for="password" class="form-label">Mot de passe provisoire</label><input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4"><a href="{{ route('admin.employees.index', ['role' => $role->code]) }}" class="btn btn-outline-secondary">Annuler</a><button class="btn btn-dark" type="submit">Créer le compte</button></div>
        </form>
    </div>
</div>
@endsection

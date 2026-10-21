@extends('layouts.Gerant')
@section('title', 'Membres de la Brigade')

@section('content')
{{-- Formulaire de création rapide d'accès --}}
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <h5 class="fw-bold text-dark mb-3">Attribuer un accès direct au collaborateur</h5>
    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif
    <form class="row g-2" method="POST" action="{{ route('gerant.employees.store') }}">
        @csrf
        <div class="col-12 col-sm-6 col-md-3">
            <input name="first_name" value="{{ old('first_name') }}" type="text" class="form-control form-control-sm" placeholder="Prénom" required>
            @error('first_name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <input name="last_name" value="{{ old('last_name') }}" type="text" class="form-control form-control-sm" placeholder="Nom" required>
            @error('last_name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <input name="phone" value="{{ old('phone') }}" type="tel" class="form-control form-control-sm" placeholder="Téléphone / WhatsApp">
            @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <input name="email" value="{{ old('email') }}" type="email" class="form-control form-control-sm" placeholder="E-mail professionnel" required>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <input name="password" type="password" class="form-control form-control-sm" placeholder="Mot de passe" required>
            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <input name="password_confirmation" type="password" class="form-control form-control-sm" placeholder="Confirmer le mot de passe" required>
        </div>
        <div class="col-12 col-md-4">
            <button type="submit" class="btn btn-gold btn-sm w-100 fw-bold">Créer le compte employé</button>
        </div>
    </form>
</div>

{{-- Cartes de brigade d'employés --}}
<h5 class="fw-bold text-dark mb-3">Membres de la Brigade en direct</h5>
<div class="row g-3">
    @forelse($employees as $employee)
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
                <div>
                    <h6 class="fw-bold text-dark mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h6>
                    <span class="text-muted small d-block mb-3">{{ $employee->email }}</span>
                    <div class="p-2 rounded-3 bg-light mb-2">
                        <span class="fw-bold small d-block text-dark">{{ $employee->is_active ? 'Compte actif' : 'Compte inactif' }}</span>
                        <span class="text-muted small">{{ $employee->phone ?: 'Aucun téléphone renseigné' }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><p class="text-muted">Aucun compte employé pour le moment.</p></div>
    @endforelse
</div>
@endsection
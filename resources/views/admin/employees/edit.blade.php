@extends('layouts.admin')

@section('title', 'Modifier un Employé - Mon Miam Miam')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Modifier : {{ $employee->name }}</h3>
    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-semibold">Adresse Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label fw-semibold">Téléphone</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label fw-semibold">Rôle / Poste <span class="text-danger">*</span></label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="caissier" {{ old('role', $employee->role) == 'caissier' ? 'selected' : '' }}>Caissier</option>
                        <option value="cuisinier" {{ old('role', $employee->role) == 'cuisinier' ? 'selected' : '' }}>Cuisinier</option>
                        <option value="livreur" {{ old('role', $employee->role) == 'livreur' ? 'selected' : '' }}>Livreur</option>
                        <option value="gestionnaire" {{ old('role', $employee->role) == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-light">Annuler</a>
                <button type="submit" class="btn btn-brand"><i class="fa-solid fa-arrows-rotate me-1"></i> Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection
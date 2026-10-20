@extends('layouts.app')
@section('content')
    <div class="dark-background d-flex align-items-center justify-content-center">
        <div class="w-100 p-4" style="max-width: 450px;">
           
            <div class="text-center mb-5">
                <h2 class="text-white fw-normal mb-3">Nouveau mot de passe</h2>
                <p class="text-secondary small">
                    Veuillez saisir votre nouveau mot de passe ci-dessous.
                </p>
            </div>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <!-- Token caché -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label text-light small mb-2">Adresse e-mail</label>
                    <input id="email" name="email" type="email" required value="{{ old('email', $request->email) }}"
                        class="form-control bg-transparent text-white border-secondary shadow-none py-2" style="border-color: #333 !important;">
                    @error('email')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Nouveau mot de passe -->
                <div class="mb-3">
                    <label for="password" class="form-label text-light small mb-2">Nouveau mot de passe</label>
                    <input id="password" name="password" type="password" required
                        class="form-control bg-transparent text-white border-secondary shadow-none py-2" style="border-color: #333 !important;">
                    @error('password')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Confirmation -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label text-light small mb-2">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="form-control bg-transparent text-white border-secondary shadow-none py-2" style="border-color: #333 !important;">
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn w-100 py-3 fw-semibold" style="background-color: #e6d5b8; color: #000;">
                        Réinitialiser le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
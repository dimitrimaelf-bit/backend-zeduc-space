@extends('layouts.app')
@section('content')
    <div class="dark-background d-flex align-items-center justify-content-center">
        <div class="w-100 p-4" style="max-width: 450px;">
           
            <!-- Lien retour -->
            <div class="mb-5">
                <a href="{{ route('login') }}" class="text-decoration-none text-secondary d-flex align-items-center small">
                    <i class="bi bi-arrow-left me-2"></i> Retour à la connexion
                </a>
            </div>
            <!-- Titre et texte -->
            <div class="text-center mb-5">
                <h2 class="text-white fw-normal mb-3">Mot de passe oublié ?</h2>
                <p class="text-secondary small">
                    Entrez votre adresse e-mail ci-dessous. Nous vous enverrons un lien pour réinitialiser votre mot de passe.
                </p>
            </div>
            <!-- Message de succès -->
            @if (session('status'))
                <div class="alert alert-success bg-transparent text-success border-success text-center small mb-4">
                    {{ session('status') }}
                </div>
            @endif
            <!-- Formulaire -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label text-light small mb-2">Adresse e-mail</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-secondary border-secondary border-end-0" style="border-color: #333 !important;">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="form-control bg-transparent text-white border-secondary border-start-0 shadow-none"
                            style="border-color: #333 !important;"
                            placeholder="">
                    </div>
                    {{-- Utilisation de la directive standard Blade pour les erreurs --}}
                    @error('email')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn w-100 py-3 fw-semibold d-flex justify-content-center align-items-center" style="background-color: #e6d5b8; color: #000;">
                        Envoyer le lien
                        <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@extends('layouts.app')
@section('content')
    <div class="auth-background d-flex align-items-center justify-content-center py-5">
       
        <div class="card border-0 shadow-lg p-4 p-md-5" style="max-width: 650px; width: 100%; background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(5px);">
           
            <!-- Navigation onglets -->
            <div class="d-flex w-100 mb-4">
                <div class="btn w-50 rounded-0 border bg-dark text-white fw-semibold py-2">connexion</div>
                <a href="{{ route('register') }}" class="btn w-50 rounded-0 border bg-white text-dark fw-semibold py-2">inscription</a>
            </div>
            <!-- Titre -->
            <div class="mb-4">
                <h3 class="fw-normal text-dark">Content de vous revoir</h3>
                <p class="text-muted small">Connectez-vous pour commander et suivre vos points.</p>
            </div>
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success small mb-4">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
               
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label small text-dark fw-bold">Adresse email</label>
                    <input id="email" name="email" type="email" required autofocus
                        class="form-control rounded-1 border-1 shadow-none py-2" value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Mot de passe -->
                <div class="mb-3">
                    <label for="password" class="form-label small text-dark fw-bold">Mot de passe</label>
                    <input id="password" name="password" type="password" required
                        class="form-control rounded-1 border-1 shadow-none py-2">
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Options -->
                <div class="d-flex justify-content-between align-items-center small text-muted mt-3 mb-4">
                    <div class="form-check">
                        <input class="form-check-input shadow-none" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Rester connecté</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-muted">Mot de passe oublié ?</a>
                    @endif
                </div>
                <!-- Bouton Se connecter -->
                <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-1 mb-3">
                    Se connecter
                </button>
                <!-- Lien création de compte -->
                <div class="text-center mt-3">
                    <p class="text-muted small mb-0">
                        Pas encore de compte ?
                        <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none">Créer un compte</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection
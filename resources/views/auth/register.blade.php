@extends('layouts.app')
@section('content')
    <div class="auth-background d-flex align-items-center justify-content-center py-5">
       
        <!-- Carte avec effet de transparence -->
        <div class="card border-0 shadow-lg p-4 p-md-5" style="max-width: 650px; width: 100%; background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(5px);">
           
            <!-- Navigation onglets -->
            <div class="d-flex w-100 mb-4">
                <a href="{{ route('login') }}" class="btn w-50 rounded-0 border bg-white text-dark fw-semibold py-2">Connexion</a>
                <div class="btn w-50 rounded-0 border bg-dark text-white fw-semibold py-2">Inscription</div>
            </div>
            <p class="text-muted small text-center mb-4">Inscrivez-vous pour commander et suivre vos points.</p>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Le rôle par défaut est étudiant -->
                <input type="hidden" name="role_id" value="student">
               
                <!-- Nom et Prénom -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input id="first_name" name="first_name" type="text" required placeholder="Prénom"
                            class="form-control rounded-1 border-1 shadow-none py-2" value="{{ old('first_name') }}">
                        @error('first_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <input id="last_name" name="last_name" type="text" required placeholder="Nom"
                            class="form-control rounded-1 border-1 shadow-none py-2" value="{{ old('last_name') }}">
                        @error('last_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label small text-dark fw-bold">Adresse email</label>
                    <input id="email" name="email" type="email" required
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

                <!-- Confirmation du mot de passe -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label small text-dark fw-bold">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="form-control rounded-1 border-1 shadow-none py-2">
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label for="phone" class="form-label small text-dark fw-bold">Numéro de Téléphone</label>
                    <input id="phone" name="phone" type="tel" placeholder="6 59 90 12 87"
                        class="form-control rounded-1 border-1 shadow-none py-2" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <label for="delivery_address" class="form-label small text-dark fw-bold">Adresse</label>
                    <input id="delivery_address" name="delivery_address" type="text"
                        class="form-control rounded-1 border-1 shadow-none py-2" value="{{ old('delivery_address') }}">
                    @error('delivery_address')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Code parrain -->
                <div class="mb-4">
                    <label for="referral_code" class="form-label small text-dark fw-bold">Code d'un parrain</label>
                    <input id="referral_code" name="referral_code" type="text" placeholder="(facultatif)"
                        class="form-control rounded-1 border-1 shadow-none py-2" value="{{ old('referral_code') }}">
                    @error('referral_code')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                 
                <!-- Bouton S'inscrire -->
                <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-1">
                    s'inscrire
                </button>
            </form>
        </div>
    </div>
@endsection
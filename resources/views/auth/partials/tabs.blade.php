@props(['active' => 'login'])

<!-- Onglets de navigation pour l'authentification -->
<div class="auth-tabs">
    <a href="{{ route('login') }}" class="{{ $active === 'login' ? 'active' : '' }}">connexion</a>
    <a href="{{ route('register') }}" class="{{ $active === 'register' ? 'active' : '' }}">inscription</a>
</div>
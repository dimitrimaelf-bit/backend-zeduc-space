<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Espace employé') — Mon Miam Miam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/employe.css') }}">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('employe.dashboard') }}">
            <img src="{{ asset('images/mon-miam-miam/logo.png') }}" alt="Logo ZeDuc@Space">
            <span>MON MIAM MIAM<small>Espace employé</small></span>
        </a>
        <div class="sidebar-label">ESPACE DE TRAVAIL</div>
        <nav class="side-nav">
            <a class="{{ request()->routeIs('employe.dashboard') ? 'active' : '' }}" href="{{ route('employe.dashboard') }}"><span>▦</span> Tableau de bord</a>
            <a class="{{ request()->routeIs('employe.commandes.*') ? 'active' : '' }}" href="{{ route('employe.commandes.index') }}"><span>▤</span> Commandes <span class="nav-count">{{ $commandesEnAttente ?? 3 }}</span></a>
            <a class="{{ request()->routeIs('employe.menu.*') ? 'active' : '' }}" href="{{ route('employe.menu.index') }}"><span>♨</span> Menu du restaurant</a>
            <a class="{{ request()->routeIs('employe.reclamations.*') ? 'active' : '' }}" href="{{ route('employe.reclamations.index') }}"><span>✉</span> Réclamations</a>
            <a class="{{ request()->routeIs('employe.statistiques') ? 'active' : '' }}" href="{{ route('employe.statistiques') }}"><span>▥</span> Statistiques</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="help-card"><strong>Besoin d'aide ?</strong><p>Contactez votre gérant en cas de difficulté.</p></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">↪ &nbsp; Se déconnecter</button>
            </form>
        </div>
    </aside>
    <main class="main-panel">
        <header class="topbar">
            <div><div class="eyebrow">ZEDUC@SPACE · RESTAURANT</div><div class="page-heading">@yield('page-heading', 'Tableau de bord')</div></div>
            <div class="profile">
                <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}</div>
                <div><strong>{{ auth()->user()->name ?? 'Espace employé' }}</strong><small>Employé</small></div>
            </div>
        </header>
        @if(session('success'))<div class="alert alert-success mx-4 mt-3">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger mx-4 mt-3">{{ session('error') }}</div>@endif
        <section class="page-content">
            @yield('content')
        </section>
        <footer class="footer">© {{ date('Y') }} ZeDuc@Space · Mon Miam Miam <span>Le climat du goût & du chill.</span></footer>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

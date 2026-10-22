@extends('employe.layouts.app')
@section('title', 'Tableau de bord')
@section('page-heading', 'Bonjour, ' . (auth()->user()->name ?? 'équipe') . ' 👋')
@section('content')
<div class="welcome-banner">
    <div><span class="pill-light">VOTRE SERVICE, EN UN COUP D’ŒIL</span><h1>Prêt à régaler<br>nos clients ?</h1><p>Retrouvez les commandes, le menu et les retours clients au même endroit.</p><a href="{{ route('employe.commandes.index') }}" class="btn btn-gold">Voir les commandes <span>→</span></a></div>
    <img src="{{ asset('images/mon-miam-miam/hero.jpg') }}" alt="Plat de poulet et bananes plantain">
</div>
<div class="section-title-row"><div><h2>Vue d’ensemble</h2><p>Activité du restaurant aujourd’hui</p></div><span class="date-chip">{{ now()->translatedFormat('l d F Y') }}</span></div>
<div class="stats-grid">
    <article class="metric-card"><div class="metric-icon icon-gold">▤</div><span>Commandes à traiter</span><strong>{{ $commandesEnAttente ?? '12' }}</strong><small class="metric-note">À préparer ou à valider</small></article>
    <article class="metric-card"><div class="metric-icon icon-green">✓</div><span>Commandes terminées</span><strong>{{ $commandesTerminees ?? '28' }}</strong><small class="metric-note">Service du jour</small></article>
    <article class="metric-card"><div class="metric-icon icon-red">✉</div><span>Réclamations ouvertes</span><strong>{{ $reclamationsOuvertes ?? '4' }}</strong><small class="metric-note">Demandent votre attention</small></article>
    <article class="metric-card"><div class="metric-icon icon-dark">₣</div><span>Ventes de la semaine</span><strong>{{ number_format($ventesSemaine ?? 184500, 0, ',', ' ') }} <small>FCFA</small></strong><small class="metric-note">Depuis lundi</small></article>
</div>
<div class="dashboard-columns">
    <section class="panel-card">
        <div class="panel-heading"><div><h3>Commandes récentes</h3><p>Les dernières commandes à suivre</p></div><a href="{{ route('employe.commandes.index') }}">Tout voir →</a></div>
        <div class="table-responsive"><table class="table custom-table"><thead><tr><th>Commande</th><th>Client</th><th>Heure</th><th>Statut</th></tr></thead><tbody>
        @forelse(($commandesRecentes ?? collect()) as $commande)
        <tr><td><strong>#{{ $commande->id }}</strong></td><td>{{ $commande->user->name ?? $commande->client_nom ?? 'Client' }}</td><td>{{ optional($commande->created_at)->format('H:i') }}</td><td><span class="status status-pending">{{ $commande->statut ?? 'En attente' }}</span></td></tr>
        @empty
        <tr><td><strong>#1042</strong></td><td>Client sur place</td><td>12:35</td><td><span class="status status-pending">À préparer</span></td></tr>
        <tr><td><strong>#1041</strong></td><td>Client livraison</td><td>12:22</td><td><span class="status status-ready">En préparation</span></td></tr>
        <tr><td><strong>#1040</strong></td><td>Client sur place</td><td>12:10</td><td><span class="status status-done">Terminée</span></td></tr>
        @endforelse
        </tbody></table></div>
    </section>
    <section class="panel-card menu-highlight">
        <div class="panel-heading"><div><h3>À la carte</h3><p>Quelques incontournables</p></div><a href="{{ route('employe.menu.index') }}">Gérer le menu →</a></div>
        <div class="dish-row"><img src="{{ asset('images/mon-miam-miam/poulet.jpg') }}" alt="Poulet braisé"><div><strong>Poulet braisé</strong><small>Plat populaire</small></div><span class="dish-dot"></span></div>
        <div class="dish-row"><img src="{{ asset('images/mon-miam-miam/poisson.jpg') }}" alt="Poisson braisé"><div><strong>Poisson braisé</strong><small>Spécialité maison</small></div><span class="dish-dot"></span></div>
        <div class="dish-row"><img src="{{ asset('images/mon-miam-miam/ndole.jpg') }}" alt="Ndolé"><div><strong>Ndolé & accompagnement</strong><small>Saveur locale</small></div><span class="dish-dot"></span></div>
    </section>
</div>
@endsection

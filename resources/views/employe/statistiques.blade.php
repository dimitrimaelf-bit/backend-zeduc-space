@extends('employe.layouts.app')
@section('title', 'Statistiques hebdomadaires')
@section('page-heading', 'Statistiques hebdomadaires')
@section('content')
<div class="section-title-row"><div><h2>Les chiffres de la semaine</h2><p>Ces indicateurs aident l’équipe à adapter son organisation.</p></div><span class="date-chip">Cette semaine</span></div>
<div class="stats-grid">
<article class="metric-card"><div class="metric-icon icon-gold">▤</div><span>Commandes reçues</span><strong>{{ $totalCommandes ?? 126 }}</strong><small class="metric-note">Sur la semaine</small></article>
<article class="metric-card"><div class="metric-icon icon-green">✓</div><span>Commandes terminées</span><strong>{{ $commandesTerminees ?? 118 }}</strong><small class="metric-note">Servies ou livrées</small></article>
<article class="metric-card"><div class="metric-icon icon-dark">₣</div><span>Chiffre d’affaires</span><strong>{{ number_format($ventesSemaine ?? 684500,0,',',' ') }} <small>FCFA</small></strong><small class="metric-note">Cumul hebdomadaire</small></article>
<article class="metric-card"><div class="metric-icon icon-red">✉</div><span>Réclamations reçues</span><strong>{{ $totalReclamations ?? 9 }}</strong><small class="metric-note">À suivre avec attention</small></article>
</div>
<div class="panel-card mt-4"><div class="panel-heading"><div><h3>Activité par jour</h3><p>Nombre de commandes — données à fournir par le contrôleur</p></div></div>
<div class="weekly-bars">
@php $jours = $commandesParJour ?? ['Lun'=>18,'Mar'=>22,'Mer'=>15,'Jeu'=>27,'Ven'=>31,'Sam'=>35,'Dim'=>12]; $max = max(array_values((array)$jours) ?: [1]); @endphp
@foreach($jours as $jour => $valeur)<div class="bar-column"><strong>{{ $valeur }}</strong><div class="bar-track"><div class="bar-fill" style="height: {{ max(8, ($valeur / max($max,1))*100) }}%"></div></div><span>{{ $jour }}</span></div>@endforeach
</div><p class="chart-footnote">Les chiffres affichés par défaut sont des exemples visuels. Branchez les statistiques réelles depuis le contrôleur.</p></div>
@endsection

@extends('employe.layouts.app')
@section('title', 'Menu du restaurant')
@section('page-heading', 'Menu du restaurant')
@section('content')
<div class="section-title-row"><div><h2>Disponibilité des plats</h2><p>Effectuez des mises à jour temporaires : plat épuisé ou plat du jour.</p></div></div>
<div class="notice-box">ℹ️ Les changements effectués ici servent à informer les clients de la disponibilité des plats.</div>
<div class="food-grid">
@php $photos = ['poulet.jpg','poisson.jpg','ndole.jpg']; @endphp
@forelse($plats ?? [] as $i => $plat)
<article class="food-card"><img src="{{ asset('images/mon-miam-miam/'.($plat->image ?? $photos[$i % 3])) }}" alt="{{ $plat->nom }}"><div class="food-card-body"><div class="food-title-row"><h3>{{ $plat->nom }}</h3><strong>{{ number_format($plat->prix ?? 0,0,',',' ') }} F</strong></div><p>{{ $plat->description ?? 'Spécialité de la maison.' }}</p><span class="status {{ ($plat->disponible ?? true) ? 'status-ready' : 'status-pending' }}">{{ ($plat->disponible ?? true) ? 'Disponible' : 'Épuisé' }}</span>
<form class="availability-form" method="POST" action="{{ route('employe.menu.update', $plat) }}">@csrf @method('PATCH')<label class="form-check-label"><input class="form-check-input me-2" type="checkbox" name="disponible" value="1" @checked($plat->disponible ?? true)> Disponible</label><button class="btn btn-outline-gold btn-sm">Mettre à jour</button></form></div></article>
@empty
@foreach([['Poulet braisé','poulet.jpg'],['Poisson braisé','poisson.jpg'],['Ndolé','ndole.jpg']] as $dish)
<article class="food-card"><img src="{{ asset('images/mon-miam-miam/'.$dish[1]) }}" alt="{{ $dish[0] }}"><div class="food-card-body"><div class="food-title-row"><h3>{{ $dish[0] }}</h3><strong>Prix à configurer</strong></div><p>Image de présentation du menu.</p><span class="status status-ready">Exemple visuel</span><p class="muted mt-3 mb-0">Connectez cette carte aux données du menu pour modifier sa disponibilité.</p></div></article>
@endforeach
@endforelse
</div>
@endsection

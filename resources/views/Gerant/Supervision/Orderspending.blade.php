<!-- Hérite du layout maître situé dans resources/views/layouts/gerant.blade.php -->
@extends('layouts.gerant')
@section('title', 'Commandes en préparation')
@section('sidebar-image', 'Poisson-Supervision.jpg')
@section('sidebar-opacity', '0.5')
@section('content')
{{-- En-tête flexible responsive --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <p class="text-uppercase fw-bold small mb-1 text-muted">CUISINE • SERVICE EN TEMPS RÉEL</p>
        <h1 class="h2 fw-bold text-dark mb-0">Commandes en préparation</h1>
    </div>
    <div class="text-md-end">
        <span class="text-muted text-uppercase d-block small">CADENCE MOYENNE</span>
        <span class="fw-bold text-dark fs-5">14 min / bon</span>
    </div>
</div>

<x-order-tabs active="supervision" />

{{-- Grille de cartes de commandes --}}
<div class="row g-3">
    @for($i = 1045; $i <= 1047; $i++)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
                <div>
                    {{-- Numéro du bon & Temps d'attente --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">#{{ $i }}</h5>
                        <span class="badge bg-light text-muted border fw-normal"><i class="fa-regular fa-clock me-1"></i>12 min</span>
                    </div>
                    
                    {{-- Liste des plats du bon --}}
                    <div class="d-flex justify-content-between mb-2 small"><span class="fw-bold text-dark">2 × Ndolé Royal</span><span class="text-muted">Crevettes</span></div>
                    <div class="d-flex justify-content-between mb-2 small"><span class="fw-bold text-dark">1 × Poulet DG</span><span class="text-muted">Piment doux</span></div>
                </div>
                
                {{-- Pied de carte avec action --}}
                <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top">
                    <span class="text-uppercase text-muted fw-bold small">EN CUISSON</span>
                    <button class="btn btn-gold btn-sm rounded-2 px-3">PASSER AU PASSE</button>
                </div>
            </div>
        </div>
    @endfor
</div>
@endsection
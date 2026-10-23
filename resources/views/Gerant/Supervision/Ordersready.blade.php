@extends('layouts.gerant')
@section('title', 'Prêtes au passe')
@section('sidebar-image', 'Poisson-Supervision.jpg')
@section('sidebar-opacity', '0.5')
@section('content')
{{-- En-tête flexible responsive --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <p class="text-uppercase fw-bold small mb-1 text-muted">OFFICE • CONTRÔLE PASSE</p>
        <h1 class="h2 fw-bold text-dark mb-0">Prêtes au passe</h1>
    </div>
    <div class="bg-white p-2 px-3 rounded-3 shadow-sm border align-self-start align-self-md-auto">
        <span class="text-muted text-uppercase d-block small">TEMPS MOYEN PASSE</span>
        <span class="fw-bold text-dark fs-5">2m 40s</span>
    </div>
</div>

<x-order-tabs active="pretes" />

{{-- Grille des plats prêts à être servis ou livrés --}}
<div class="row g-3">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                {{-- Entête commande --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="fw-bold text-dark mb-0">#1042</h4>
                    <span class="badge bg-light text-dark border fw-normal"><i class="fa-solid fa-chair text-muted me-1"></i>Table 2</span>
                </div>
                
                {{-- Serveur assigné --}}
                <div class="p-2 px-3 rounded-3 bg-light mb-3">
                    <span class="text-uppercase text-muted d-block small">ASSIGNÉ À</span>
                    <span class="fw-bold text-dark small">Serveur Rodrigue</span>
                </div>
                
                {{-- Détail du plat --}}
                <div class="d-flex justify-content-between align-items-center mb-1 small"><span class="fw-bold text-dark">1× Poisson braisé</span><span class="text-muted">Chaud</span></div>
            </div>
            
            {{-- Action de validation --}}
            <button class="btn btn-gold w-100 btn-sm rounded-3 py-2 mt-3"><i class="fa-regular fa-circle-check me-1"></i>SERVIR EN SALLE</button>
        </div>
    </div>
</div>
@endsection
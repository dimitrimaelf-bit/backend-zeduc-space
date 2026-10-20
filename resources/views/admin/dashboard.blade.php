@extends('layouts.mmm')

@section('titre', 'Supervision Générale - Administrateur')

@section('contenu')
<div class="container my-4">
    <h1 class="mb-4">Supervision Générale - Administrateur</h1>

    {{-- Cartes d'indicateurs principaux --}}
    <div class="grille">
        <div class="carte">
            <h3>Chiffre d'Affaires Total</h3>
            <p class="total">{{ number_format($stats['total_sales'] ?? 0, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="carte">
            <h3>Total Commandes</h3>
            <p class="total">{{ $stats['total_orders'] ?? 0 }}</p>
        </div>
        <div class="carte">
            <h3>Employés Actifs</h3>
            <p class="total">{{ $stats['total_employees'] ?? 0 }}</p>
        </div>
        <div class="carte">
            <h3>Réclamations en Attente</h3>
            <p class="total" style="color: #dc3545;">{{ $stats['pending_claims'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Programme de Fidélité et Parrainage --}}
    <h2 class="mt-4 mb-3">Programme de Fidélité & Parrainage</h2>
    <div class="grille">
        <div class="carte">
            <h3>Total Points Distribués</h3>
            <p class="total" style="color: #cfbd97;">
                {{ number_format($loyaltyStats['total_points_awarded'] ?? 0, 0, ',', ' ') }} pts
            </p>
        </div>
        <div class="carte">
            <h3>Parrainages Réussis</h3>
            <p class="total">{{ $loyaltyStats['total_referrals'] ?? 0 }}</p>
        </div>
    </div>
</div>
@endsection
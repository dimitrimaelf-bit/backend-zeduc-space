@extends('layouts.Gerant')
@section('title', 'Statistiques & Performance')

@section('content')
{{-- En-tête responsive avec filtre de période --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <p class="text-uppercase fw-bold small mb-1" style="color: var(--zeduc-gold, #cfbd97);">• ANALYSE D'ACTIVITÉ</p>
        <h1 class="h2 fw-bold text-dark mb-0">Statistiques & Performance</h1>
    </div>
    <div class="btn-group btn-group-sm w-100 w-sm-auto">
        <button class="btn btn-outline-dark">Aujourd'hui</button>
        <button class="btn btn-dark active">Cette semaine</button>
        <button class="btn btn-outline-dark">Ce mois</button>
    </div>
</div>

{{-- Section 1 : Métriques Globales (Ventes & Commandes) --}}
<h5 class="fw-bold text-dark mb-3">Ventes & Volume de Commandes</h5>
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card title="CHIFFRE D'AFFAIRES ENCAISSÉ" :value="number_format($sales['paid_total_fcfa'], 0, ',', ' ')" unit="FCFA" subtitle="Total des paiements encaissés" />
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card title="PAIEMENT MOYEN" :value="number_format($sales['average_fcfa'], 0, ',', ' ')" unit="FCFA" subtitle="Par paiement encaissé" />
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card title="TOTAL COMMANDES" :value="$orders['total']" unit="bons" subtitle="Toutes les commandes" />
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card title="COMMANDES LIVRÉES" :value="$orders['by_status']['delivered']" unit="livraisons" subtitle="Statut livré" />
    </div>
</div>

{{-- Section 2 : Programmes de Fidélité & Parrainage --}}
<h5 class="fw-bold text-dark mb-3">Engagement & Marketing (Fidélité & Parrainage)</h5>
<div class="row g-3 mb-4">
    {{-- Fidélité --}}
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card title="POINTS FIDÉLITÉ DISPONIBLES" :value="number_format($loyalty['balance_points_total'], 0, ',', ' ')" unit="pts" subtitle="Solde total des comptes" />
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card title="COMPTES FIDÉLITÉ" :value="$loyalty['accounts']" unit="actifs" subtitle="Comptes enregistrés" />
    </div>
    {{-- Parrainage --}}
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card title="PARRAINAGES RÉCOMPENSÉS" :value="$referrals['by_status']['rewarded']" unit="filleuls" subtitle="Parrainages avec récompense" />
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <x-stat-card title="CODES DE PARRAINAGE ACTIFS" :value="$referrals['active_codes']" unit="codes" subtitle="Codes actuellement actifs" />
    </div>
</div>

{{-- Section 3 : Performance détaillée des canaux & Récompenses --}}
<div class="row g-4 mb-4">
    {{-- Conversion des offres promo / fidélité --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h6 class="fw-bold text-dark mb-3">Utilisation des Récompenses</h6>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                    <span class="fw-bold d-block text-dark small">Plats gratuits (Points Fidélité)</span>
                    <span class="text-muted small">Échangés au passe</span>
                </div>
                <span class="fw-bold text-dark">0 plat</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                    <span class="fw-bold d-block text-dark small">Bons de réduction Parrainage</span>
                    <span class="text-muted small">Code 500 F & 1000 F</span>
                </div>
                <span class="fw-bold text-dark">0 utilisé</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2">
                <div>
                    <span class="fw-bold d-block text-dark small">Taux de réachat des Filleuls</span>
                    <span class="text-muted small">Commandes renouvelées</span>
                </div>
                <span class="fw-bold text-success">0 %</span>
            </div>
        </div>
    </div>

    {{-- Ventilation des Ventes par Canal --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h6 class="fw-bold text-dark mb-3">Répartition par Canal de Commande</h6>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="small text-muted fw-bold">Sur place (Table)</span>
                <span class="fw-bold text-dark">0 %</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="small text-muted fw-bold">À emporter</span>
                <span class="fw-bold text-dark">0 %</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2">
                <span class="small text-muted fw-bold">Livraison à domicile</span>
                <span class="fw-bold text-dark">0 %</span>
            </div>
        </div>
    </div>
</div>

{{-- Section 4 : Top Plats & Rentabilité --}}
<h5 class="fw-bold text-dark mb-3">Top Plats & Rentabilité</h5>
<div class="row g-3">
    @php
        $topPlats = [
            ['nom' => 'Poulet pané', 'prix' => '2 500 F', 'vol' => '230 commandes'],
            ['nom' => 'Poisson braisé', 'prix' => '2 500 F', 'vol' => '133 commandes'],
            ['nom' => 'Poulet DG', 'prix' => '2 000 F', 'vol' => '124 commandes']
        ];
    @endphp

    @foreach($topPlats as $plat)
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="bg-secondary" style="height: 120px;"></div>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">{{ $plat['nom'] }}</h6>
                        <span class="fw-bold text-dark">{{ $plat['prix'] }}</span>
                    </div>
                    <span class="text-muted small d-block mt-1">{{ $plat['vol'] }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
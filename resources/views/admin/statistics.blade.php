@extends('layouts.mmm')

@section('titre', 'Statistiques - ZeDuc@Space')

@push('styles')
<style>
    .statistics-page { color: #252821; }
    .statistics-header { border-bottom: 1px solid #deded8; padding-bottom: 20px; }
    .statistics-header h1 { font-size: 25px; font-weight: 700; margin: 0; }
    .statistics-header p { color: #69706a; margin: 5px 0 0; font-size: 13px; }
    .period-tabs { display: inline-flex; border: 1px solid #d5d8d2; border-radius: 6px; padding: 3px; gap: 2px; background: #fff; }
    .period-tabs a { color: #535b54; text-decoration: none; padding: 7px 12px; border-radius: 4px; font-size: 13px; }
    .period-tabs a.active { color: #fff; background: #254b3b; }
    .statistics-card { height: 100%; border: 1px solid #e2e4de; border-radius: 6px; background: #fff; padding: 17px; }
    .statistics-label { color: #667067; font-size: 12px; margin-bottom: 9px; }
    .statistics-value { font-size: 24px; line-height: 1.2; font-weight: 700; }
    .statistics-note { font-size: 11px; color: #717871; margin-top: 8px; }
    .positive { color: #26734d; }
    .negative { color: #b3443d; }
    .statistics-section { margin: 27px 0 12px; font-size: 17px; font-weight: 700; }
    .statistics-panel { border: 1px solid #e2e4de; border-radius: 6px; background: #fff; padding: 18px; }
    .growth-chart { display: block; width: 100%; height: 300px; overflow: visible; }
    .chart-grid-line { stroke: #e8ebe6; stroke-width: 1; }
    .chart-line { fill: none; stroke: #28724e; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round; }
    .chart-area { fill: url(#growthFill); }
    .chart-point { fill: #fff; stroke: #28724e; stroke-width: 2; }
    .chart-label { fill: #737971; font-size: 11px; }
    .chart-legend { display: flex; justify-content: space-between; gap: 12px; margin-top: 10px; color: #69706a; font-size: 12px; }
    .chart-key { display: inline-flex; align-items: center; gap: 7px; }
    .chart-key::before { content: ''; width: 18px; height: 3px; border-radius: 2px; background: #28724e; }
    .status-table { font-size: 13px; margin: 0; }
    .status-table th { color: #667067; font-size: 11px; text-transform: uppercase; }
    .status-track { width: 100%; height: 8px; background: #eef1ec; border-radius: 4px; overflow: hidden; }
    .status-fill { height: 100%; background: #32765a; border-radius: 4px; }
    @media (max-width: 700px) {
        .statistics-header { align-items: flex-start !important; flex-direction: column; }
        .admin-main-content { padding: 18px 12px; }
        .growth-chart { height: 220px; }
        .chart-label { font-size: 10px; }
    }
</style>
@endpush

@section('contenu')
@php
    $statusLabels = [
        'pending' => 'En attente',
        'confirmed' => 'Confirmées',
        'preparing' => 'En préparation',
        'ready' => 'Prêtes',
        'delivered' => 'Livrées',
        'completed' => 'Terminées',
        'cancelled' => 'Annulées',
    ];
    $statusTotal = max(1, (int) $orderStatuses->sum());
@endphp
<div class="statistics-page">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="statistics-header d-flex justify-content-between align-items-center gap-3">
        <div>
            <h1>Statistiques détaillées</h1>
            <p>{{ $periodLabel }} · Actualisation automatique toutes les 60 secondes</p>
        </div>
        <nav class="period-tabs" aria-label="Période des statistiques">
            <a href="{{ route($statsRouteName, ['period' => 'day']) }}" @class(['active' => $period === 'day'])>Jour</a>
            <a href="{{ route($statsRouteName, ['period' => 'week']) }}" @class(['active' => $period === 'week'])>Semaine</a>
            <a href="{{ route($statsRouteName, ['period' => 'month']) }}" @class(['active' => $period === 'month'])>Mois</a>
        </nav>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="statistics-card">
                <div class="statistics-label">Ventes encaissées</div>
                <div class="statistics-value">{{ number_format($stats['total_sales'], 0, ',', ' ') }} FCFA</div>
                <div class="statistics-note">
                    @if($salesChange !== null)
                        <span class="{{ $salesChange >= 0 ? 'positive' : 'negative' }}">{{ $salesChange > 0 ? '+' : '' }}{{ $salesChange }}%</span> par rapport à la période précédente
                    @else
                        Comparaison indisponible
                    @endif
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="statistics-card">
                <div class="statistics-label">Commandes créées</div>
                <div class="statistics-value">{{ number_format($stats['total_orders'], 0, ',', ' ') }}</div>
                <div class="statistics-note">
                    @if($ordersChange !== null)
                        <span class="{{ $ordersChange >= 0 ? 'positive' : 'negative' }}">{{ $ordersChange > 0 ? '+' : '' }}{{ $ordersChange }}%</span> par rapport à la période précédente
                    @else
                        Comparaison indisponible
                    @endif
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="statistics-card">
                <div class="statistics-label">Points attribués</div>
                <div class="statistics-value">{{ number_format($loyaltyStats['period_points_awarded'], 0, ',', ' ') }}</div>
                <div class="statistics-note">Fidélité et parrainage sur la période</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="statistics-card">
                <div class="statistics-label">Parrainages récompensés</div>
                <div class="statistics-value">{{ number_format($loyaltyStats['period_referrals'], 0, ',', ' ') }}</div>
                <div class="statistics-note">{{ number_format($loyaltyStats['total_referrals'], 0, ',', ' ') }} au total</div>
            </article>
        </div>
    </div>

    <h2 class="statistics-section">Croissance des ventes · 30 derniers jours</h2>
    <section class="statistics-panel" aria-label="Courbe de croissance quotidienne des ventes">
        @php
            $chartWidth = 900;
            $chartHeight = 280;
            $chartPaddingX = 38;
            $chartPaddingY = 24;
            $plotWidth = $chartWidth - ($chartPaddingX * 2);
            $plotHeight = $chartHeight - ($chartPaddingY * 2);
            $chartMax = max(1, (int) ceil($maxDailySales / 1000) * 1000);
            $points = $dailyActivity->values()->map(function ($day, $index) use ($dailyActivity, $chartPaddingX, $plotWidth, $chartPaddingY, $plotHeight, $chartMax) {
                $x = $chartPaddingX + ($index / max(1, $dailyActivity->count() - 1)) * $plotWidth;
                $y = $chartPaddingY + $plotHeight - (($day['sales'] / $chartMax) * $plotHeight);
                return ['x' => $x, 'y' => $y, 'day' => $day];
            });
            $linePoints = $points->map(fn ($point) => $point['x'] . ',' . $point['y'])->implode(' ');
            $areaPoints = $chartPaddingX . ',' . ($chartPaddingY + $plotHeight) . ' ' . $linePoints . ' ' . ($chartPaddingX + $plotWidth) . ',' . ($chartPaddingY + $plotHeight);
        @endphp
        <svg class="growth-chart" viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" role="img" aria-label="Ventes totales sur 30 jours : {{ number_format($chartSalesTotal, 0, ',', ' ') }} FCFA">
            <defs>
                <linearGradient id="growthFill" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0%" stop-color="#28724e" stop-opacity=".2" />
                    <stop offset="100%" stop-color="#28724e" stop-opacity="0" />
                </linearGradient>
            </defs>
            @foreach([0, 1, 2, 3, 4] as $gridIndex)
                @php
                    $gridY = $chartPaddingY + ($gridIndex / 4) * $plotHeight;
                    $gridValue = (int) round($chartMax * (1 - ($gridIndex / 4)));
                @endphp
                <line class="chart-grid-line" x1="{{ $chartPaddingX }}" y1="{{ $gridY }}" x2="{{ $chartPaddingX + $plotWidth }}" y2="{{ $gridY }}" />
                <text class="chart-label" x="{{ $chartPaddingX - 7 }}" y="{{ $gridY + 4 }}" text-anchor="end">{{ number_format($gridValue, 0, ',', ' ') }}</text>
            @endforeach
            <polygon class="chart-area" points="{{ $areaPoints }}" />
            <polyline class="chart-line" points="{{ $linePoints }}" />
            @foreach($points as $point)
                <circle class="chart-point" cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="3">
                    <title>{{ $point['day']['label'] }} : {{ number_format($point['day']['sales'], 0, ',', ' ') }} FCFA, {{ $point['day']['orders'] }} commande(s)</title>
                </circle>
            @endforeach
            @foreach([0, 7, 14, 21, 29] as $labelIndex)
                <text class="chart-label" x="{{ $points[$labelIndex]['x'] }}" y="{{ $chartHeight - 3 }}" text-anchor="middle">{{ $points[$labelIndex]['day']['label'] }}</text>
            @endforeach
        </svg>
        <div class="chart-legend">
            <span class="chart-key">Ventes encaissées par jour</span>
            <span>{{ number_format($chartSalesTotal, 0, ',', ' ') }} FCFA · {{ number_format($chartOrdersTotal, 0, ',', ' ') }} commandes</span>
        </div>
    </section>

    <h2 class="statistics-section">Répartition des commandes · {{ $periodLabel }}</h2>
    <div class="table-responsive statistics-panel p-0">
        <table class="table table-hover align-middle status-table">
            <thead><tr><th class="ps-3">Statut</th><th class="text-end">Commandes</th><th class="px-3" style="width: 38%">Part de la période</th></tr></thead>
            <tbody>
                @foreach($statusLabels as $status => $label)
                    @php $count = (int) ($orderStatuses[$status] ?? 0); @endphp
                    <tr>
                        <td class="ps-3">{{ $label }}</td>
                        <td class="text-end">{{ number_format($count, 0, ',', ' ') }}</td>
                        <td class="px-3"><div class="status-track"><div class="status-fill" style="width: {{ min(100, ($count / $statusTotal) * 100) }}%"></div></div></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="statistics-section">Crédit manuel de points</h2>
    <form method="POST" action="{{ route('admin.loyalty.adjust') }}" class="statistics-panel">
        @csrf
        <p class="text-muted small">Utilise cette action pour corriger un solde après validation. Chaque crédit est inscrit à l’historique du compte étudiant.</p>
        <div class="row g-3 align-items-end">
            <div class="col-md-4"><label for="student-email" class="form-label">Courriel de l’étudiant</label><input id="student-email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-3"><label for="points" class="form-label">Points à créditer</label><input id="points" type="number" name="points" min="1" max="1000000" value="{{ old('points') }}" class="form-control @error('points') is-invalid @enderror" required>@error('points')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-5"><label for="description" class="form-label">Motif</label><input id="description" name="description" value="{{ old('description') }}" maxlength="255" class="form-control @error('description') is-invalid @enderror" required>@error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-12 d-flex justify-content-end"><button class="btn btn-dark" type="submit">Valider le crédit</button></div>
        </div>
    </form>
</div>
<script>
    window.setTimeout(() => window.location.reload(), 60000);
</script>
@endsection

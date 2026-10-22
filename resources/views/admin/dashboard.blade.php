@extends('layouts.mmm')

@section('titre', 'Supervision Générale - Administrateur')

@push('styles')
<style>
    .dashboard-page { color: #252821; }
    .dashboard-heading { border-bottom: 1px solid #deded8; padding-bottom: 20px; }
    .dashboard-heading h1 { font-size: 25px; font-weight: 700; margin: 0; }
    .dashboard-heading p { color: #69706a; margin: 5px 0 0; font-size: 13px; }
    .period-tabs { display: inline-flex; border: 1px solid #d5d8d2; border-radius: 6px; padding: 3px; gap: 2px; background: #fff; }
    .period-tabs a { color: #535b54; text-decoration: none; padding: 7px 12px; border-radius: 4px; font-size: 13px; }
    .period-tabs a.active { color: #fff; background: #254b3b; }
    .metric-card { height: 100%; border: 1px solid #e2e4de; border-radius: 6px; background: #fff; padding: 17px; }
    .metric-label { color: #667067; font-size: 12px; margin-bottom: 9px; }
    .metric-value { font-size: 24px; line-height: 1.2; font-weight: 700; }
    .metric-note { font-size: 11px; color: #717871; margin-top: 8px; }
    .trend-positive { color: #26734d; }
    .trend-negative { color: #b3443d; }
    .section-heading { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin: 27px 0 12px; }
    .section-heading h2 { font-size: 17px; font-weight: 700; margin: 0; }
    .section-heading span { font-size: 12px; color: #737971; }
    .activity-panel { border: 1px solid #e2e4de; border-radius: 6px; background: #fff; padding: 18px; }
    .activity-grid { display: grid; grid-template-columns: repeat(7, minmax(30px, 1fr)); gap: 12px; align-items: end; min-height: 160px; }
    .activity-day { text-align: center; font-size: 11px; color: #687169; }
    .activity-track { height: 112px; display: flex; align-items: end; justify-content: center; margin-bottom: 8px; background: linear-gradient(to top, #f2f4f0 1px, transparent 1px); }
    .activity-bar { width: min(28px, 75%); min-height: 3px; border-radius: 3px 3px 0 0; background: #32765a; }
    .activity-amount { color: #343b35; font-size: 10px; margin-bottom: 3px; }
    .data-table { font-size: 13px; }
    .data-table th { color: #667067; font-weight: 600; font-size: 11px; text-transform: uppercase; }
    .status-label { display: inline-block; border-radius: 3px; padding: 4px 7px; background: #eef1ec; color: #47534a; font-size: 11px; }
    @media (max-width: 700px) {
        .dashboard-heading { align-items: flex-start !important; flex-direction: column; }
        .admin-main-content { padding: 18px 12px; }
        .activity-grid { gap: 5px; }
        .activity-amount { font-size: 9px; }
    }
</style>
@endpush

@section('contenu')
<div class="dashboard-page">
    <div class="dashboard-heading d-flex justify-content-between align-items-center gap-3">
        <div>
            <h1>Supervision générale</h1>
            <p>{{ $periodLabel }} · Données actualisées automatiquement</p>
        </div>
        <nav class="period-tabs" aria-label="Période des statistiques">
            <a href="{{ route('admin.dashboard', ['period' => 'day']) }}" @class(['active' => $period === 'day'])>Jour</a>
            <a href="{{ route('admin.dashboard', ['period' => 'week']) }}" @class(['active' => $period === 'week'])>Semaine</a>
            <a href="{{ route('admin.dashboard', ['period' => 'month']) }}" @class(['active' => $period === 'month'])>Mois</a>
        </nav>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card">
                <div class="metric-label">Ventes encaissées · {{ $periodLabel }}</div>
                <div class="metric-value">{{ number_format($stats['total_sales'], 0, ',', ' ') }} FCFA</div>
                <div class="metric-note">
                    @if($salesChange !== null)
                        <span class="{{ $salesChange >= 0 ? 'trend-positive' : 'trend-negative' }}">{{ $salesChange > 0 ? '+' : '' }}{{ $salesChange }}%</span>
                        par rapport à la période précédente
                    @else
                        Comparaison indisponible (aucune vente précédente)
                    @endif
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card">
                <div class="metric-label">Commandes · {{ $periodLabel }}</div>
                <div class="metric-value">{{ number_format($stats['total_orders'], 0, ',', ' ') }}</div>
                <div class="metric-note">
                    @if($ordersChange !== null)
                        <span class="{{ $ordersChange >= 0 ? 'trend-positive' : 'trend-negative' }}">{{ $ordersChange > 0 ? '+' : '' }}{{ $ordersChange }}%</span>
                        par rapport à la période précédente
                    @else
                        Comparaison indisponible (aucune commande précédente)
                    @endif
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card">
                <div class="metric-label">Employés actifs</div>
                <div class="metric-value">{{ number_format($stats['total_employees'], 0, ',', ' ') }}</div>
                <div class="metric-note">Comptes employés actifs</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card">
                <div class="metric-label">Réclamations ouvertes</div>
                <div class="metric-value">{{ number_format($stats['pending_claims'], 0, ',', ' ') }}</div>
                <div class="metric-note">À traiter par l’équipe</div>
            </article>
        </div>
    </div>

    <div class="section-heading">
        <h2>Activité des sept derniers jours</h2>
        <span>Ventes encaissées, en FCFA</span>
    </div>
    <section class="activity-panel" aria-label="Ventes quotidiennes des sept derniers jours">
        <div class="activity-grid">
            @foreach($dailyActivity as $day)
                <div class="activity-day">
                    <div class="activity-amount">{{ number_format($day['sales'], 0, ',', ' ') }}</div>
                    <div class="activity-track" title="{{ $day['orders'] }} commande(s)">
                        <div class="activity-bar" style="height: {{ $day['sales'] > 0 ? max(4, ($day['sales'] / $maxDailySales) * 100) : 3 }}%"></div>
                    </div>
                    <div>{{ $day['label'] }}</div>
                    <div>{{ $day['orders'] }} cmd</div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="section-heading">
        <h2>Fidélité et parrainage</h2>
        <span>Activité sur la période et cumuls globaux</span>
    </div>
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-xl-3"><article class="metric-card"><div class="metric-label">Points attribués · {{ $periodLabel }}</div><div class="metric-value">{{ number_format($loyaltyStats['period_points_awarded'], 0, ',', ' ') }}</div><div class="metric-note">Points fidélité et parrainage</div></article></div>
        <div class="col-12 col-sm-6 col-xl-3"><article class="metric-card"><div class="metric-label">Points attribués · total</div><div class="metric-value">{{ number_format($loyaltyStats['total_points_awarded'], 0, ',', ' ') }}</div><div class="metric-note">Depuis le lancement</div></article></div>
        <div class="col-12 col-sm-6 col-xl-3"><article class="metric-card"><div class="metric-label">Parrainages récompensés · {{ $periodLabel }}</div><div class="metric-value">{{ number_format($loyaltyStats['period_referrals'], 0, ',', ' ') }}</div><div class="metric-note">Récompense attribuée pendant la période</div></article></div>
        <div class="col-12 col-sm-6 col-xl-3"><article class="metric-card"><div class="metric-label">Parrainages récompensés · total</div><div class="metric-value">{{ number_format($loyaltyStats['total_referrals'], 0, ',', ' ') }}</div><div class="metric-note">Depuis le lancement</div></article></div>
    </div>

    <div class="section-heading">
        <h2>Commandes récentes</h2>
        <a href="{{ route('admin.menu.index') }}" class="small text-decoration-none">Gestion du menu</a>
    </div>
    <div class="table-responsive bg-white border rounded-2">
        <table class="table table-hover align-middle mb-0 data-table">
            <thead><tr><th class="ps-3">Commande</th><th>Client</th><th>Date</th><th>Statut</th><th class="text-end pe-3">Montant</th></tr></thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $order->order_number }}</td>
                        <td>{{ $order->user?->first_name }} {{ $order->user?->last_name }}</td>
                        <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                        <td><span class="status-label">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                        <td class="text-end pe-3">{{ number_format($order->total_fcfa, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucune commande enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    window.setTimeout(() => window.location.reload(), 60000);
</script>
@endsection
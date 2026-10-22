@extends('layouts.gerant')
@section('title', 'Commandes livrées')
@section('sidebar-image', 'Poisson-Supervision.jpg')
@section('sidebar-opacity', '0.5')
@section('content')
{{-- En-tête responsive --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
    <h1 class="h2 fw-bold text-dark mb-0">Commandes livrées & clôturées</h1>
    <div class="text-muted small"><span class="fw-bold text-dark">48 commandes servies</span> • <span class="fw-bold text-dark">184 400 FCFA</span></div>
</div>

<x-order-tabs active="livrees" />

{{-- Table scrollable horizontalement sur petits écrans --}}
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light border-bottom">
                <tr class="text-uppercase text-muted small">
                    <th class="py-3 ps-4">COMMANDE</th>
                    <th>DESTINATION / CLIENT</th>
                    <th>ARTICLES</th>
                    <th>MONTANT</th>
                    <th class="pe-4 text-end">STATUT</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-bottom">
                    <td class="py-3 ps-4"><span class="fw-bold text-dark fs-6">#1041</span></td>
                    <td><span class="fw-bold text-dark d-block">Table 09</span><span class="text-muted small">Awa Traoré</span></td>
                    <td><span class="text-dark">Poisson braisé</span></td>
                    <td><span class="fw-bold text-dark">2 300 F</span></td>
                    <td class="pe-4 text-end"><span class="badge bg-light text-dark border rounded-pill px-3 py-1">• Livrée</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
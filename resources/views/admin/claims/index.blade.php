@extends('layouts.mmm')

@section('titre', 'Gestion des réclamations - ZeDuc@Space')

@section('contenu')
<div class="claims-wrapper">

    <!-- En-tête -->
    <div class="mb-4">
        <h1 class="font-serif fw-bold text-dark mb-1" style="font-size: 32px; font-family: Georgia, serif;">Gestion des réclamations</h1>
        <p class="text-muted mb-0" style="font-size: 14px;">Consultez et traitez les réclamations des étudiants.</p>
    </div>

    <!-- Filtres -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white">
                <div class="input-group border-0">
                    <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Rechercher..." style="font-size: 13.5px;">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white">
                <div class="input-group border-0">
                    <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-funnel"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Filter on order ID ou nom étudiant" style="font-size: 13.5px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead class="bg-light text-muted">
                    <tr style="font-size: 12px;">
                        <th class="py-3 px-4">ID de commande</th>
                        <th class="py-3 px-3">Nom de l'étudiant</th>
                        <th class="py-3 px-3">Date</th>
                        <th class="py-3 px-3">Type de réclamation</th>
                        <th class="py-3 px-3">Statut</th>
                        <th class="py-3 px-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-bottom">
                        <td class="py-3 px-4 fw-bold">#883490</td>
                        <td class="py-3 px-3">Alice Nguema</td>
                        <td class="py-3 px-3 text-muted">16/09 14:30</td>
                        <td class="py-3 px-3">Plat froid</td>
                        <td class="py-3 px-3"><span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill">En attente</span></td>
                        <td class="py-3 px-4 text-end"><button class="btn btn-sm text-white px-3" style="background-color: #b89d68;">Répondre</button></td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="py-3 px-4 fw-bold">#883472</td>
                        <td class="py-3 px-3">Sophie M.</td>
                        <td class="py-3 px-3 text-muted">15/09 19:20</td>
                        <td class="py-3 px-3">Cheveu dans le plat</td>
                        <td class="py-3 px-3"><span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill">Urgent</span></td>
                        <td class="py-3 px-4 text-end"><button class="btn btn-sm text-white px-3" style="background-color: #b89d68;">Répondre</button></td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="py-3 px-4 fw-bold">#883440</td>
                        <td class="py-3 px-3">Kevin T.</td>
                        <td class="py-3 px-3 text-muted">14/09 21:00</td>
                        <td class="py-3 px-3">Portion incorrecte</td>
                        <td class="py-3 px-3"><span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">Résolu</span></td>
                        <td class="py-3 px-4 text-end"><button class="btn btn-sm btn-secondary px-3">Voir</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
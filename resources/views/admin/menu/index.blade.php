@extends('layouts.mmm')

@section('titre', 'Gestion du menu - ZeDuc@Space')

@section('contenu')
<div class="menu-wrapper">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="font-serif fw-bold text-dark mb-1" style="font-size: 32px; font-family: Georgia, serif;">Gestion du menu</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Créez, modifiez ou supprimez les plats et boissons du menu</p>
        </div>
        <button class="btn text-white px-3 py-2 fw-medium shadow-sm" style="background-color: #b89d68; border-radius: 8px; font-size: 14px;">
            + Ajouter un plat
        </button>
    </div>

    <!-- Barre de recherche -->
    <div class="card border-0 shadow-sm rounded-3 p-2 bg-white mb-4">
        <div class="input-group border-0">
            <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Rechercher un plat (nom, catégorie...)" style="font-size: 13.5px;">
        </div>
    </div>

    <!-- Tableau de liste -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white mb-4">
        <div class="p-3 border-bottom">
            <h5 class="fw-bold mb-0" style="font-family: Georgia, serif;">Liste des plats et boissons</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead class="bg-light text-muted">
                    <tr style="font-size: 12px;">
                        <th class="py-3 px-4">Nom du plat / boisson</th>
                        <th class="py-3 px-3">Catégorie</th>
                        <th class="py-3 px-3">Prix</th>
                        <th class="py-3 px-3">Statut</th>
                        <th class="py-3 px-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-bottom">
                        <td class="py-3 px-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-secondary-subtle" style="width: 50px; height: 50px; background-size: cover; background-position: center; background-image: url('https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=150&q=80');"></div>
                                <div>
                                    <strong class="d-block text-dark">Plat de résistance</strong>
                                    <small class="text-muted">Poulet braisé entier</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-3 text-muted">Plats principaux</td>
                        <td class="py-3 px-3 fw-medium">7 500 FCFA</td>
                        <td class="py-3 px-3"><span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">● Disponible</span></td>
                        <td class="py-3 px-4 text-end">
                            <button class="btn btn-light btn-sm me-1"><i class="bi bi-trash"></i></button>
                            <button class="btn btn-sm text-white px-3" style="background-color: #b89d68;">Modifier</button>
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="py-3 px-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-secondary-subtle" style="width: 50px; height: 50px; background-size: cover; background-position: center; background-image: url('https://images.unsplash.com/photo-1546171753-97d7676e4602?auto=format&fit=crop&w=150&q=80');"></div>
                                <div>
                                    <strong class="d-block text-dark">Boisson</strong>
                                    <small class="text-muted">Jus de Bissap</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-3 text-muted">Boissons</td>
                        <td class="py-3 px-3 fw-medium">1 500 FCFA</td>
                        <td class="py-3 px-3"><span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">● Disponible</span></td>
                        <td class="py-3 px-4 text-end">
                            <button class="btn btn-light btn-sm me-1"><i class="bi bi-trash"></i></button>
                            <button class="btn btn-sm text-white px-3" style="background-color: #b89d68;">Modifier</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top text-muted" style="font-size: 12px;">
            <span>Affichage de 1 à 6 sur 15 éléments</span>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary disabled">Previous</button>
                <button class="btn btn-sm btn-secondary" style="background-color: #b89d68; border-color: #b89d68;">1</button>
                <button class="btn btn-sm btn-outline-secondary">2</button>
                <button class="btn btn-sm btn-outline-secondary">3</button>
                <button class="btn btn-sm btn-outline-secondary">Next</button>
            </div>
        </div>
    </div>

</div>
@endsection
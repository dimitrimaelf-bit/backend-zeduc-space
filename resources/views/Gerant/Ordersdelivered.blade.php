<!-- Hérite du layout maître situé dans resources/views/layouts/gerant.blade.php -->
@extends('layouts.gerant')

<!-- Définit le titre de la page affiché dans l'onglet du navigateur -->
@section('title', 'Commandes livrées & clôturées - Espace Gérant')

<!-- Ouvre la section 'content' injectée dans le layout maître -->
@section('content')

<!-- Bloc de styles CSS spécifiques pour adapter les couleurs au design (Primaire: #CFBD97, Secondaire: #000000) -->
<style>
    /* Style personnalisé pour les boutons primaires (#CFBD97) avec du texte noir (#000000) */
    .btn-primary-custom {
        background-color: #CFBD97 !important;
        border-color: #CFBD97 !important;
        color: #000000 !important;
        font-weight: 600;
    }
    /* Effet au survol du curseur sur les boutons primaires */
    .btn-primary-custom:hover {
        background-color: #bfae88 !important;
        border-color: #bfae88 !important;
        color: #000000 !important;
    }
    /* Style pour l'onglet actif principal (Fond blanc avec texte foncé) */
    .nav-tab-active {
        background-color: #ffffff !important;
        color: #000000 !important;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    /* Style pour les filtres de catégories actifs (Fond noir secondaire #000000) */
    .btn-filter-active {
        background-color: #000000 !important;
        color: #ffffff !important;
        border-color: #000000 !important;
    }
    /* Style pour les filtres de catégories inactifs */
    .btn-filter {
        background-color: #e8e2d9 !important;
        color: #555555 !important;
        border: none !important;
    }
    /* Style du badge 'Livrée' avec fond primaire (#CFBD97) ou beige léger */
    .badge-delivered {
        background-color: #f3eee6;
        color: #8c7b60;
        border: 1px solid #e2d9cc;
    }
    /* Icônes de destination en conteneur carré arrondi */
    .destination-icon-box {
        width: 36px;
        height: 36px;
        background-color: #f7f5f2;
        border-radius: 8px;
    }
</style>

<!-- BARRE D'ONGLETS PRINCIPAUX DU HAUT (En cours, Prêtes au passe, Livrées) -->
<div class="p-1 bg-light rounded-3 d-inline-flex gap-1 mb-4 border">
    
    <!-- Onglet 1 : En cours -->
    <a href="{{ route('gerant.orders.index') }}" class="btn btn-sm text-muted px-3 py-1 rounded-2 d-flex align-items-center gap-2 text-decoration-none">
        <span>EN COURS</span>
        <!-- Badge compteur 5 -->
        <span class="badge bg-secondary-subtle text-dark rounded-circle" style="font-size: 0.7rem;">5</span>
    </a>

    <!-- Onglet 2 : Prêtes au passe -->
    <a href="#" class="btn btn-sm text-muted px-3 py-1 rounded-2 d-flex align-items-center gap-2 text-decoration-none">
        <span>PRÊTES AU PASSE</span>
        <!-- Badge compteur 3 -->
        <span class="badge bg-secondary-subtle text-dark rounded-circle" style="font-size: 0.7rem;">3</span>
    </a>

    <!-- Onglet 3 : Livrées (Actif) -->
    <button class="btn btn-sm nav-tab-active px-3 py-1 rounded-2 d-flex align-items-center gap-2 border-0">
        <!-- Puce dorée représentant la sélection active -->
        <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background-color: #CFBD97;"></span>
        <span>• LIVRÉES</span>
        <!-- Badge avec compteur 48 -->
        <span class="badge bg-light text-dark rounded-circle border" style="font-size: 0.7rem;">48</span>
    </button>

<!-- Ferme la barre d'onglets haut -->
</div>

<!-- SECTION EN-TÊTE : Titre principal & Résumé des métriques -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    
    <!-- Titre 'Commandes livrées & clôturées' -->
    <h1 class="h2 fw-bold text-dark mb-0" style="font-family: serif;">Commandes livrées & clôturées</h1>

    <!-- Métriques synthétiques à droite (48 commandes servies • 184 400 FCFA • 0 litige) -->
    <div class="text-muted small mt-2 mt-md-0" style="font-size: 0.8rem;">
        <span class="fw-bold text-dark">48 commandes servies</span>
        <span class="mx-1">•</span>
        <span class="fw-bold text-dark">184 400 FCFA</span>
        <span class="mx-1">•</span>
        <span>0 litige</span>
    </div>

<!-- Ferme la section en-tête -->
</div>

<!-- BARRE DE RECHERCHE ET REGROUPEMENT DE FILTRES -->
<div class="row g-3 justify-content-between align-items-center mb-4">
    
    <!-- Champ de recherche réactif (#ticket, client, table...) -->
    <div class="col-12 col-md-5 col-lg-4">
        <!-- Input group avec icône loupe -->
        <div class="input-group bg-white rounded-3 shadow-sm border">
            <!-- Icône de recherche -->
            <span class="input-group-text bg-transparent border-0 text-muted ps-3">
                <i class="fa-solid fa-magnifying-glass" style="font-size: 0.85rem;"></i>
            </span>
            <!-- Input text principal -->
            <input type="text" class="form-control border-0 bg-transparent py-2 shadow-none" placeholder="Rechercher #ticket, client, table..." style="font-size: 0.85rem;">
        </div>
    <!-- Ferme la colonne du champ de recherche -->
    </div>

    <!-- Groupe des filtres de type de commande (Toutes, Sur place, À emporter, Livraison) -->
    <div class="col-12 col-md-7 col-lg-6 d-flex justify-content-md-end gap-2 flex-wrap">
        <!-- Filtre 'Toutes (48)' actif (Fond noir #000000) -->
        <button class="btn btn-filter-active btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">Toutes (48)</button>
        <!-- Filtre 'Sur place (31)' -->
        <button class="btn btn-filter btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">Sur place (31)</button>
        <!-- Filtre 'À emporter (11)' -->
        <button class="btn btn-filter btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">À emporter (11)</button>
        <!-- Filtre 'Livraison (6)' -->
        <button class="btn btn-filter btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">Livraison (6)</button>
    <!-- Ferme la colonne des filtres -->
    </div>

<!-- Ferme la barre de recherche et filtres -->
</div>

<!-- TABLEAU DES COMMANDES LIVRÉES -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <!-- Enveloppe responsive pour petit écran -->
    <div class="table-responsive">
        <!-- Table Bootstrap sans bordures intérieures agressives -->
        <table class="table align-middle mb-0">
            <!-- En-tête du tableau -->
            <thead class="bg-light border-bottom">
                <tr class="text-uppercase text-muted" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">
                    <th class="py-3 ps-4">COMMANDE & HEURE</th>
                    <th class="py-3">DESTINATION / CLIENT</th>
                    <th class="py-3">ARTICLES & SPÉCIFICATIONS</th>
                    <th class="py-3">MONTANT</th>
                    <th class="py-3 pe-4 text-end">STATUT & ACTIONS</th>
                </tr>
            </thead>
            <!-- Corps du tableau -->
            <tbody class="border-top-0">

                <!-- ==================== LIGNE 1 : #1041 ==================== -->
                <tr class="border-bottom">
                    <!-- Numéro de la commande -->
                    <td class="py-3 ps-4">
                        <span class="fw-bold text-dark fs-6">#1041</span>
                    </td>
                    <!-- Destination & Nom du client -->
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="destination-icon-box d-flex align-items-center justify-content-center text-muted">
                                <i class="fa-solid fa-chair" style="font-size: 0.85rem;"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">Table 09</span>
                                <span class="text-muted" style="font-size: 0.75rem;">Awa Traoré</span>
                            </div>
                        </div>
                    </td>
                    <!-- Articles de la commande -->
                    <td>
                        <span class="text-dark" style="font-size: 0.85rem;">Poisson braisé</span>
                    </td>
                    <!-- Montant de la commande -->
                    <td>
                        <span class="fw-bold text-dark fs-6">2300 F</span>
                    </td>
                    <!-- Statut 'Livrée' & Flèche d'action -->
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex align-items-center gap-3">
                            <span class="badge badge-delivered rounded-pill px-3 py-1 fw-normal" style="font-size: 0.75rem;">
                                • Livrée
                            </span>
                            <button class="btn btn-link text-muted p-0 shadow-none"><i class="fa-solid fa-chevron-right" style="font-size: 0.85rem;"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- ==================== LIGNE 2 : #1040 ==================== -->
                <tr class="border-bottom">
                    <!-- Numéro de la commande -->
                    <td class="py-3 ps-4">
                        <span class="fw-bold text-dark fs-6">#1040</span>
                    </td>
                    <!-- Destination & Nom du client -->
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="destination-icon-box d-flex align-items-center justify-content-center text-muted">
                                <i class="fa-solid fa-motorcycle" style="font-size: 0.85rem;"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">Livraison coursier</span>
                                <span class="text-muted" style="font-size: 0.75rem;">Marc Blanchard</span>
                            </div>
                        </div>
                    </td>
                    <!-- Articles de la commande -->
                    <td>
                        <span class="text-dark" style="font-size: 0.85rem;">2× okok salé, 2× Jus planète pomme</span>
                    </td>
                    <!-- Montant de la commande -->
                    <td>
                        <span class="fw-bold text-dark fs-6">3000 F</span>
                    </td>
                    <!-- Statut 'Livrée' & Flèche d'action -->
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex align-items-center gap-3">
                            <span class="badge badge-delivered rounded-pill px-3 py-1 fw-normal" style="font-size: 0.75rem;">
                                • Livrée
                            </span>
                            <button class="btn btn-link text-muted p-0 shadow-none"><i class="fa-solid fa-chevron-right" style="font-size: 0.85rem;"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- ==================== LIGNE 3 : #1039 ==================== -->
                <tr class="border-bottom">
                    <!-- Numéro de la commande -->
                    <td class="py-3 ps-4">
                        <span class="fw-bold text-dark fs-6">#1039</span>
                    </td>
                    <!-- Destination & Nom du client -->
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="destination-icon-box d-flex align-items-center justify-content-center text-muted">
                                <i class="fa-solid fa-bag-shopping" style="font-size: 0.85rem;"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">À emporter</span>
                                <span class="text-muted" style="font-size: 0.75rem;">Sophie Koné</span>
                            </div>
                        </div>
                    </td>
                    <!-- Articles de la commande -->
                    <td>
                        <span class="text-dark" style="font-size: 0.85rem;">Bolognaise</span>
                    </td>
                    <!-- Montant de la commande -->
                    <td>
                        <span class="fw-bold text-dark fs-6">1000 F</span>
                    </td>
                    <!-- Statut 'Livrée' & Flèche d'action -->
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex align-items-center gap-3">
                            <span class="badge badge-delivered rounded-pill px-3 py-1 fw-normal" style="font-size: 0.75rem;">
                                • Livrée
                            </span>
                            <button class="btn btn-link text-muted p-0 shadow-none"><i class="fa-solid fa-chevron-right" style="font-size: 0.85rem;"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- ==================== LIGNE 4 : #1038 ==================== -->
                <tr class="border-bottom">
                    <!-- Numéro de la commande -->
                    <td class="py-3 ps-4">
                        <span class="fw-bold text-dark fs-6">#1038</span>
                    </td>
                    <!-- Destination & Nom du client -->
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="destination-icon-box d-flex align-items-center justify-content-center text-muted">
                                <i class="fa-solid fa-chair" style="font-size: 0.85rem;"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">Table 03</span>
                                <span class="text-muted" style="font-size: 0.75rem;">Thierry Delmas</span>
                            </div>
                        </div>
                    </td>
                    <!-- Articles de la commande -->
                    <td>
                        <span class="text-dark" style="font-size: 0.85rem;">Poulet pané</span>
                    </td>
                    <!-- Montant de la commande -->
                    <td>
                        <span class="fw-bold text-dark fs-6">2000 F</span>
                    </td>
                    <!-- Statut 'Livrée' & Flèche d'action -->
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex align-items-center gap-3">
                            <span class="badge badge-delivered rounded-pill px-3 py-1 fw-normal" style="font-size: 0.75rem;">
                                • Livrée
                            </span>
                            <button class="btn btn-link text-muted p-0 shadow-none"><i class="fa-solid fa-chevron-right" style="font-size: 0.85rem;"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- ==================== LIGNE 5 : #1037 ==================== -->
                <tr class="border-bottom">
                    <!-- Numéro de la commande -->
                    <td class="py-3 ps-4">
                        <span class="fw-bold text-dark fs-6">#1037</span>
                    </td>
                    <!-- Destination & Nom du client -->
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="destination-icon-box d-flex align-items-center justify-content-center text-muted">
                                <i class="fa-solid fa-door-closed" style="font-size: 0.85rem;"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">Salon Alcôve</span>
                                <span class="text-muted" style="font-size: 0.75rem;">Cabinet Alary & Ass.</span>
                            </div>
                        </div>
                    </td>
                    <!-- Articles de la commande -->
                    <td>
                        <span class="text-dark" style="font-size: 0.85rem;">Ndolé royal</span>
                    </td>
                    <!-- Montant de la commande -->
                    <td>
                        <span class="fw-bold text-dark fs-6">1500 F</span>
                    </td>
                    <!-- Statut 'Livrée' & Flèche d'action -->
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex align-items-center gap-3">
                            <span class="badge badge-delivered rounded-pill px-3 py-1 fw-normal" style="font-size: 0.75rem;">
                                • Livrée
                            </span>
                            <button class="btn btn-link text-muted p-0 shadow-none"><i class="fa-solid fa-chevron-right" style="font-size: 0.85rem;"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- ==================== LIGNE 6 : #1036 ==================== -->
                <tr>
                    <!-- Numéro de la commande -->
                    <td class="py-3 ps-4">
                        <span class="fw-bold text-dark fs-6">#1036</span>
                    </td>
                    <!-- Destination & Nom du client -->
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="destination-icon-box d-flex align-items-center justify-content-center text-muted">
                                <i class="fa-solid fa-chair" style="font-size: 0.85rem;"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">Table 14</span>
                                <span class="text-muted" style="font-size: 0.75rem;">Éléonore Rostand</span>
                            </div>
                        </div>
                    </td>
                    <!-- Articles de la commande -->
                    <td>
                        <span class="text-dark" style="font-size: 0.85rem;">Poulet mayo</span>
                    </td>
                    <!-- Montant de la commande -->
                    <td>
                        <span class="fw-bold text-dark fs-6">2500 F</span>
                    </td>
                    <!-- Statut 'Livrée' & Flèche d'action -->
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex align-items-center gap-3">
                            <span class="badge badge-delivered rounded-pill px-3 py-1 fw-normal" style="font-size: 0.75rem;">
                                • Livrée
                            </span>
                            <button class="btn btn-link text-muted p-0 shadow-none"><i class="fa-solid fa-chevron-right" style="font-size: 0.85rem;"></i></button>
                        </div>
                    </td>
                </tr>

            <!-- Ferme le corps du tableau -->
            </tbody>
        <!-- Ferme la table -->
        </table>
    <!-- Ferme le conteneur responsive table -->
    </div>
<!-- Ferme la carte du tableau -->
</div>

<!-- BARRE DE PAGINATION ET INFOS DE BAS DE TABLEAU -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-muted small" style="font-size: 0.75rem;">
    
    <!-- Information sur les éléments affichés -->
    <div class="mb-2 mb-md-0">
        <span>• Affichage des 6 dernières commandes • 48 commandes archivées au total ce midi</span>
    </div>

    <!-- Contrôles de pagination (Précédent, Page 1/8, Suivant) -->
    <div class="d-flex align-items-center gap-3">
        <!-- Bouton Précédent (Inactif/Grisé) -->
        <span class="text-muted opacity-50">Précédent</span>
        <!-- Indicateur de page actuelle -->
        <span class="fw-bold text-dark">1 / 8</span>
        <!-- Bouton Suivant (Actif) -->
        <a href="#" class="text-dark text-decoration-none fw-semibold">Suivant</a>
    </div>

<!-- Ferme la barre de pagination -->
</div>

<!-- Pied de page avec le bouton de retour arrière en bas à gauche -->
<div class="mt-4 pt-2">
    <!-- Bouton flèche de retour vers la vue supervision -->
    <a href="{{ route('gerant.orders.index') }}" class="btn btn-link text-secondary p-0 fs-4">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
<!-- Ferme le footer -->
</div>

<!-- Ferme la section content -->
@endsection
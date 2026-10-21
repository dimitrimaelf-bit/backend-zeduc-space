<!-- Hérite du layout maître situé dans resources/views/layouts/gerant.blade.php -->
@extends('layouts.gerant')

<!-- Définit le titre de la page affiché dans l'onglet du navigateur -->
@section('title', 'Commandes en préparation - Espace Gérant')

<!-- Ouvre la section 'content' injectée dans le layout maître -->
@section('content')

<!-- Bloc de styles CSS spécifiques pour adapter les couleurs au design (Primaire: #CFBD97, Secondaire: #000000) -->
<style>
    /* Boutons personnalisés avec la couleur primaire (#CFBD97) et du texte noir (#000000) */
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
    /* Style pour l'onglet actif principal (Fond noir secondaire #000000) */
    .nav-tab-active {
        background-color: #000000 !important;
        color: #ffffff !important;
        font-weight: 600;
    }
    /* Style pour les onglets de sous-filtrage secondaires actifs (Fond noir #000000) */
    .btn-subfilter-active {
        background-color: #000000 !important;
        color: #ffffff !important;
        border-color: #000000 !important;
    }
    /* Style pour les onglets secondaires inactifs */
    .btn-subfilter {
        background-color: #e8e2d9 !important;
        color: #555555 !important;
        border: none !important;
    }
    /* Petit point d'indicateur en direct clignotant ou fixe */
    .live-dot {
        height: 6px;
        width: 6px;
        background-color: #d97706;
        border-radius: 50%;
        display: inline-block;
    }
</style>

<!-- EN-TÊTE DE LA PAGE (Titre + Métriques Cuisine) -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    
    <!-- Bloc de gauche : Titre et Sous-titre -->
    <div>
        <!-- Sous-titre supérieur 'CUISINE • SERVICE EN TEMPS RÉEL' -->
        <p class="text-uppercase fw-bold small mb-1 text-muted" style="letter-spacing: 0.5px; font-size: 0.75rem;">
            CUISINE • SERVICE EN TEMPS RÉEL
        </p>
        <!-- Titre principal 'Commandes en préparation' -->
        <h1 class="h2 fw-bold text-dark mb-0" style="font-family: serif;">Commandes en préparation</h1>
    <!-- Ferme le bloc de gauche -->
    </div>

    <!-- Bloc de droite : Indicateurs clés (Cadence moyenne & Flux cuisine) -->
    <div class="d-flex align-items-center gap-4 mt-3 mt-md-0">
        
        <!-- Métrique 1 : Cadence moyenne -->
        <div class="text-end">
            <!-- Libellé du KPI -->
            <span class="text-muted text-uppercase d-block" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">CADENCE MOYENNE</span>
            <!-- Valeur chiffrée '14 min / bon' -->
            <span class="fw-bold text-dark fs-5">14 min / bon</span>
        <!-- Ferme la métrique 1 -->
        </div>

        <!-- Métrique 2 : Flux cuisine -->
        <div class="text-end">
            <!-- Libellé du KPI -->
            <span class="text-muted text-uppercase d-block" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">FLUX CUISINE</span>
            <!-- Valeur chiffrée 'Normal • 5 actifs' -->
            <span class="fw-bold text-dark fs-5">Normal • 5 actifs</span>
        <!-- Ferme la métrique 2 -->
        </div>

    <!-- Ferme le bloc de droite -->
    </div>

<!-- Ferme l'en-tête -->
</div>

<!-- NIVEAU 1 : BARRE D'ONGLETS PRINCIPAUX (En cours, Prêtes au passe, Livrées) ET MISE À JOUR -->
<div class="d-flex justify-content-between align-items-center mb-3">
    
    <!-- Groupe d'onglets d'état de commande -->
    <div class="d-flex gap-2">
        <!-- Onglet 1 : En cours (Actif - Fond noir #000000) -->
        <button class="btn nav-tab-active btn-sm px-3 py-1 rounded-3 d-flex align-items-center gap-2">
            <span>En cours</span>
            <!-- Badge avec compteur 5 -->
            <span class="badge bg-light text-dark rounded-circle" style="font-size: 0.7rem;">5</span>
        </button>

        <!-- Onglet 2 : Prêtes au passe -->
        <button class="btn btn-light text-muted btn-sm px-3 py-1 rounded-3 d-flex align-items-center gap-2 bg-transparent border-0">
            <span>Prêtes au passe</span>
            <!-- Badge avec compteur 3 -->
            <span class="badge bg-light text-dark rounded-circle border" style="font-size: 0.7rem;">3</span>
        </button>

        <!-- Onglet 3 : Livrées -->
        <button class="btn btn-light text-muted btn-sm px-3 py-1 rounded-3 d-flex align-items-center gap-2 bg-transparent border-0">
            <span>Livrées</span>
            <!-- Badge avec compteur 48 -->
            <span class="badge bg-light text-dark rounded-circle border" style="font-size: 0.7rem;">48</span>
        </button>
    <!-- Ferme le groupe d'onglets -->
    </div>

    <!-- Indicateur de mise à jour directe à droite -->
    <div class="d-flex align-items-center gap-1 text-muted small" style="font-size: 0.75rem;">
        <!-- Puce point de statut -->
        <span class="live-dot me-1"></span>
        <!-- Texte explicatif -->
        <span>Mise à jour directe</span>
    <!-- Ferme l'indicateur -->
    </div>

<!-- Ferme le niveau 1 -->
</div>

<!-- NIVEAU 2 : SOUS-FILTRES (Tout, Sur place, Livraison) -->
<div class="d-flex gap-2 mb-4">
    <!-- Filtre 'Tout (5)' actif -->
    <button class="btn btn-subfilter-active btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">Tout (5)</button>
    <!-- Filtre 'Sur place (3)' -->
    <button class="btn btn-subfilter btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">Sur place (3)</button>
    <!-- Filtre 'Livraison (2)' -->
    <button class="btn btn-subfilter btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">Livraison (2)</button>
<!-- Ferme le niveau 2 -->
</div>

<!-- GRILLE DES BONS DE COMMANDES (3 Colonnes sur PC, 2 sur tablette, 1 sur mobile) -->
<div class="row g-3">

    <!-- ==================== BON DE COMMANDE #1045 ==================== -->
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Carte de commande blanche avec coins arrondis et ombre subtile -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <!-- Bloc Supérieur du Bon -->
            <div>
                <!-- En-tête du bon : Numéro + Temps d'attente chrono -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Numéro de la commande (#1045) -->
                    <h5 class="fw-bold text-dark mb-0">#1045</h5>
                    <!-- Chronomètre d'attente (12 min) -->
                    <span class="badge bg-light text-muted border fw-normal d-flex align-items-center gap-1">
                        <i class="fa-regular fa-clock"></i> 12 min
                    </span>
                </div>

                <!-- Ligne d'article 1 : 2 x Ndolé Royal + détails -->
                <div class="d-flex justify-content-between align-items-start mb-2" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">2 × Ndolé Royal</span>
                    <span class="text-muted text-end" style="font-size: 0.75rem;">Crevettes, plantain</span>
                </div>

                <!-- Ligne d'article 2 : 1 x Poulet DG + détails -->
                <div class="d-flex justify-content-between align-items-start mb-2" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">1 × Poulet DG</span>
                    <span class="text-muted text-end" style="font-size: 0.75rem;">Piment doux</span>
                </div>

                <!-- Ligne d'article 3 : 2 x Bissap frais + détails -->
                <div class="d-flex justify-content-between align-items-start mb-3" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">2 × Bissap frais</span>
                    <span class="text-muted text-end" style="font-size: 0.75rem;">Menthe douce</span>
                </div>
            <!-- Ferme le bloc supérieur -->
            </div>

            <!-- Pied du bon : Statut de cuisson + Bouton Passer au passe (#CFBD97) -->
            <div class="d-flex justify-content-between align-items-center pt-2 border-top-0 mt-3">
                <!-- Statut de l'étape de préparation -->
                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">EN CUISSON</span>
                <!-- Action pour envoyer en livraison/passe -->
                <button class="btn btn-primary-custom btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">
                    PASSER AU PASSE
                </button>
            </div>
        <!-- Ferme la carte #1045 -->
        </div>
    <!-- Ferme la colonne #1045 -->
    </div>

    <!-- ==================== BON DE COMMANDE #1046 ==================== -->
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Carte de commande blanche -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <!-- Bloc Supérieur du Bon -->
            <div>
                <!-- En-tête du bon : Numéro + Type + Temps -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <!-- Numéro du bon -->
                        <h5 class="fw-bold text-dark mb-0">#1046</h5>
                        <!-- Type de commande (Emporté) -->
                        <span class="text-muted" style="font-size: 0.75rem;">Emporté</span>
                    </div>
                    <!-- Chronomètre d'attente (9 min) -->
                    <span class="badge bg-light text-muted border fw-normal d-flex align-items-center gap-1">
                        <i class="fa-regular fa-clock"></i> 9 min
                    </span>
                </div>

                <!-- Ligne d'article 1 : 1 x Capitaine braisé -->
                <div class="d-flex justify-content-between align-items-start mb-3" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">1 × Capitaine braisé</span>
                </div>
            <!-- Ferme le bloc supérieur -->
            </div>

            <!-- Pied du bon : Statut 'DRESSAGE' + Bouton Passer au passe -->
            <div class="d-flex justify-content-between align-items-center pt-2 border-top-0 mt-3">
                <!-- Statut de l'étape -->
                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">DRESSAGE</span>
                <!-- Bouton d'action primaire (#CFBD97) -->
                <button class="btn btn-primary-custom btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">
                    PASSER AU PASSE
                </button>
            </div>
        <!-- Ferme la carte #1046 -->
        </div>
    <!-- Ferme la colonne #1046 -->
    </div>

    <!-- ==================== BON DE COMMANDE #1047 ==================== -->
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Carte de commande blanche -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <!-- Bloc Supérieur du Bon -->
            <div>
                <!-- En-tête du bon : Numéro + Type + Temps -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <!-- Numéro du bon -->
                        <h5 class="fw-bold text-dark mb-0">#1047</h5>
                        <!-- Type de commande (Sur place) -->
                        <span class="text-muted" style="font-size: 0.75rem;">Sur place</span>
                    </div>
                    <!-- Chronomètre d'attente (6 min) -->
                    <span class="badge bg-light text-muted border fw-normal d-flex align-items-center gap-1">
                        <i class="fa-regular fa-clock"></i> 6 min
                    </span>
                </div>

                <!-- Ligne d'article 1 : 3 x Poulet pané -->
                <div class="d-flex justify-content-between align-items-start mb-3" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">3 × Poulet pané</span>
                </div>
            <!-- Ferme le bloc supérieur -->
            </div>

            <!-- Pied du bon : Statut 'GRILLADE' + Bouton Passer au passe -->
            <div class="d-flex justify-content-between align-items-center pt-2 border-top-0 mt-3">
                <!-- Statut de l'étape -->
                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">GRILLADE</span>
                <!-- Bouton d'action primaire (#CFBD97) -->
                <button class="btn btn-primary-custom btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">
                    PASSER AU PASSE
                </button>
            </div>
        <!-- Ferme la carte #1047 -->
        </div>
    <!-- Ferme la colonne #1047 -->
    </div>

    <!-- ==================== BON DE COMMANDE #1048 ==================== -->
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Carte de commande blanche -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <!-- Bloc Supérieur du Bon -->
            <div>
                <!-- En-tête du bon : Numéro + Type + Temps -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <!-- Numéro du bon -->
                        <h5 class="fw-bold text-dark mb-0">#1048</h5>
                        <!-- Type de commande (Sur place) -->
                        <span class="text-muted" style="font-size: 0.75rem;">Sur place</span>
                    </div>
                    <!-- Chronomètre d'attente (2 min) -->
                    <span class="badge bg-light text-muted border fw-normal d-flex align-items-center gap-1">
                        <i class="fa-regular fa-clock"></i> 2 min
                    </span>
                </div>

                <!-- Ligne d'article 1 : 1 x Pastels au Poisson + détails -->
                <div class="d-flex justify-content-between align-items-start mb-3" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">1 × Pastels au Poisson</span>
                    <span class="text-muted text-end" style="font-size: 0.75rem;">Sauce tomate relevée</span>
                </div>
            <!-- Ferme le bloc supérieur -->
            </div>

            <!-- Pied du bon : Statut 'DÉMARRÉ' + Bouton Passer au passe -->
            <div class="d-flex justify-content-between align-items-center pt-2 border-top-0 mt-3">
                <!-- Statut de l'étape -->
                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">DÉMARRÉ</span>
                <!-- Bouton d'action primaire (#CFBD97) -->
                <button class="btn btn-primary-custom btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">
                    PASSER AU PASSE
                </button>
            </div>
        <!-- Ferme la carte #1048 -->
        </div>
    <!-- Ferme la colonne #1048 -->
    </div>

    <!-- ==================== BON DE COMMANDE #1049 ==================== -->
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Carte de commande blanche -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <!-- Bloc Supérieur du Bon -->
            <div>
                <!-- En-tête du bon : Numéro + Type + Temps -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <!-- Numéro du bon -->
                        <h5 class="fw-bold text-dark mb-0">#1049</h5>
                        <!-- Type de commande (Livraison) -->
                        <span class="text-muted" style="font-size: 0.75rem;">Livraison</span>
                    </div>
                    <!-- Chronomètre d'attente (1 min) -->
                    <span class="badge bg-light text-muted border fw-normal d-flex align-items-center gap-1">
                        <i class="fa-regular fa-clock"></i> 1 min
                    </span>
                </div>

                <!-- Ligne d'article 1 : 2 x Jus naturels + détails -->
                <div class="d-flex justify-content-between align-items-start mb-3" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">2 × Jus naturels</span>
                    <span class="text-muted text-end" style="font-size: 0.75rem;">Finition baobab</span>
                </div>
            <!-- Ferme le bloc supérieur -->
            </div>

            <!-- Pied du bon : Statut 'REÇUE' + Bouton Passer au passe -->
            <div class="d-flex justify-content-between align-items-center pt-2 border-top-0 mt-3">
                <!-- Statut de l'étape -->
                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">REÇUE</span>
                <!-- Bouton d'action primaire (#CFBD97) -->
                <button class="btn btn-primary-custom btn-sm rounded-2 px-3 py-1" style="font-size: 0.75rem;">
                    PASSER AU PASSE
                </button>
            </div>
        <!-- Ferme la carte #1049 -->
        </div>
    <!-- Ferme la colonne #1049 -->
    </div>

<!-- Ferme la grille des bons de commandes -->
</div>

<!-- Ouvre le pied de page de la vue avec la flèche de retour arrière en bas à gauche -->
<div class="mt-5 pt-3">
    <!-- Bouton icône flèche de retour -->
    <a href="{{ route('gerant.orders.index') }}" class="btn btn-link text-secondary p-0 fs-4">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
<!-- Ferme le pied de page -->
</div>

<!-- Ferme la section content -->
@endsection
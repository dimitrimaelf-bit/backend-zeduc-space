<!-- Hérite du layout maître situé dans resources/views/layouts/gerant.blade.php -->
@extends('layouts.gerant')

<!-- Définit le titre de la page affiché dans l'onglet du navigateur -->
@section('title', 'Prêtes au passe - Espace Gérant')

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
    /* Style pour l'onglet actif principal 'Prêtes au passe' (Fond blanc avec texte foncé ou surbrillance) */
    .nav-tab-active {
        background-color: #ffffff !important;
        color: #000000 !important;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    /* Arrière-plan pour les blocs gris clair des affectations (Serveur, Client, Coursier) */
    .bg-assignment-box {
        background-color: #f7f5f2 !important;
    }
    /* Style pour les pastilles/points de statut de temps */
    .time-status-dot {
        height: 6px;
        width: 6px;
        background-color: #CFBD97;
        border-radius: 50%;
        display: inline-block;
    }
</style>

<!-- EN-TÊTE DE LA PAGE (Titre + Métriques Passe) -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    
    <!-- Bloc de gauche : Titre et Sous-titre -->
    <div>
        <!-- Sous-titre supérieur 'OFFICE • CONTRÔLE PASSE' -->
        <p class="text-uppercase fw-bold small mb-1 text-muted" style="letter-spacing: 0.5px; font-size: 0.75rem;">
            OFFICE • CONTRÔLE PASSE
        </p>
        <!-- Titre principal 'Prêtes au passe' -->
        <h1 class="h2 fw-bold text-dark mb-1" style="font-family: serif;">Prêtes au passe</h1>
        <!-- Description courte 'Plats dressés prêts pour le service en salle ou le départ coursier.' -->
        <p class="text-muted small mb-0" style="font-size: 0.8rem;">
            Plats dressés prêts pour le service en salle ou le départ coursier.
        </p>
    <!-- Ferme le bloc de gauche -->
    </div>

    <!-- Bloc de droite : Indicateurs clés (Temps moyen passe & Cadence service) -->
    <div class="d-flex align-items-center gap-3 mt-3 mt-md-0 bg-white p-2 px-3 rounded-3 shadow-sm border">
        
        <!-- Métrique 1 : Temps moyen passe -->
        <div class="text-start pe-3 border-end">
            <!-- Libellé du KPI -->
            <span class="text-muted text-uppercase d-block" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">TEMPS MOYEN PASSE</span>
            <!-- Valeur chiffrée '2m 40s' -->
            <span class="fw-bold text-dark fs-5">2m 40s</span>
        <!-- Ferme la métrique 1 -->
        </div>

        <!-- Métrique 2 : Cadence service -->
        <div class="text-start ps-1">
            <!-- Libellé du KPI -->
            <span class="text-muted text-uppercase d-block" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">CADENCE SERVICE</span>
            <!-- Valeur chiffrée 'Fluide' -->
            <span class="fw-bold text-dark fs-5">Fluide</span>
        <!-- Ferme la métrique 2 -->
        </div>

    <!-- Ferme le bloc de droite -->
    </div>

<!-- Ferme l'en-tête -->
</div>

<!-- BARRE D'ONGLETS PRINCIPAUX (En cours, Prêtes au passe, Livrées) -->
<div class="p-1 bg-light rounded-3 d-inline-flex gap-1 mb-4 border">
    
    <!-- Onglet 1 : En cours -->
    <a href="{{ route('gerant.orders.index') }}" class="btn btn-sm text-muted px-3 py-1 rounded-2 d-flex align-items-center gap-2 text-decoration-none">
        <span>En cours</span>
        <!-- Badge avec compteur 5 -->
        <span class="badge bg-secondary-subtle text-dark rounded-circle" style="font-size: 0.7rem;">5</span>
    </a>

    <!-- Onglet 2 : Prêtes au passe (Actif) -->
    <button class="btn btn-sm nav-tab-active px-3 py-1 rounded-2 d-flex align-items-center gap-2 border-0">
        <span>Prêtes au passe</span>
        <!-- Badge avec compteur 3 -->
        <span class="badge bg-dark text-white rounded-circle" style="font-size: 0.7rem;">3</span>
    </button>

    <!-- Onglet 3 : Livrées -->
    <button class="btn btn-sm text-muted px-3 py-1 rounded-2 d-flex align-items-center gap-2 border-0">
        <span>Livrées</span>
        <!-- Badge avec compteur 48 -->
        <span class="badge bg-secondary-subtle text-dark rounded-circle" style="font-size: 0.7rem;">48</span>
    </button>

<!-- Ferme la barre d'onglets -->
</div>

<!-- GRILLE DES BONS DE COMMANDES PRÊTES (3 Colonnes sur PC, 1 sur mobile) -->
<div class="row g-3">

    <!-- ==================== BON PRÊT #1042 (SUR PLACE) ==================== -->
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Carte de commande blanche avec coins arrondis -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <!-- Bloc Supérieur -->
            <div>
                <!-- En-tête du bon : Titre COMMANDE #1042 + Badge Table 2 -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-uppercase text-muted d-block" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">COMMANDE</span>
                        <h4 class="fw-bold text-dark mb-0">#1042</h4>
                    </div>
                    <!-- Badge de la table -->
                    <span class="badge bg-light text-dark border fw-normal d-flex align-items-center gap-1 px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-chair text-muted"></i> Table 2
                    </span>
                </div>

                <!-- Bloc d'affectation : Serveur Rodrigue -->
                <div class="p-2 px-3 rounded-3 bg-assignment-box mb-3 d-flex align-items-center gap-2">
                    <i class="fa-regular fa-user text-muted" style="font-size: 0.85rem;"></i>
                    <div>
                        <span class="text-uppercase text-muted d-block" style="font-size: 0.6rem; font-weight: 700; letter-spacing: 0.5px;">ASSIGNÉ À</span>
                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">Serveur Rodrigue</span>
                    </div>
                </div>

                <!-- Section Détail des mets -->
                <span class="text-uppercase text-muted d-block mb-2" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">DÉTAIL DES METS</span>
                
                <!-- Article 1 : 1x Poisson braisé (Chaud) -->
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">1× Poisson braisé</span>
                    <span class="text-muted" style="font-size: 0.75rem;">Chaud</span>
                </div>

                <!-- Article 2 : 1x Plantains mûrs (Garniture) -->
                <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">1× Plantains mûrs</span>
                    <span class="text-muted" style="font-size: 0.75rem;">Garniture</span>
                </div>
            <!-- Ferme le bloc supérieur -->
            </div>

            <!-- Pied du bon : Temps d'attente au passe + Bouton SERVIR EN SALLE -->
            <div class="mt-3">
                <!-- Statut de préparation depuis X minutes avec puces -->
                <div class="d-flex align-items-center gap-2 text-muted mb-2" style="font-size: 0.75rem;">
                    <i class="fa-regular fa-clock"></i>
                    <span>Prêt depuis 3 min</span>
                    <span class="time-status-dot ms-auto"></span>
                </div>
                <!-- Bouton d'action primaire (#CFBD97) -->
                <button class="btn btn-primary-custom w-100 btn-sm rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-regular fa-circle-check"></i>
                    <span>SERVIR EN SALLE</span>
                </button>
            </div>
        <!-- Ferme la carte #1042 -->
        </div>
    <!-- Ferme la colonne #1042 -->
    </div>

    <!-- ==================== BON PRÊT #1043 (À EMPORTER) ==================== -->
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Carte de commande blanche -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <!-- Bloc Supérieur -->
            <div>
                <!-- En-tête du bon : Titre COMMANDE #1043 + Badge À emporter -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-uppercase text-muted d-block" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">COMMANDE</span>
                        <h4 class="fw-bold text-dark mb-0">#1043</h4>
                    </div>
                    <!-- Badge À emporter -->
                    <span class="badge bg-light text-dark border fw-normal d-flex align-items-center gap-1 px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-bag-shopping text-muted"></i> À emporter
                    </span>
                </div>

                <!-- Bloc d'affectation : Client en comptoir Patrick M. -->
                <div class="p-2 px-3 rounded-3 bg-assignment-box mb-3 d-flex align-items-center gap-2">
                    <i class="fa-regular fa-address-card text-muted" style="font-size: 0.85rem;"></i>
                    <div>
                        <span class="text-uppercase text-muted d-block" style="font-size: 0.6rem; font-weight: 700; letter-spacing: 0.5px;">CLIENT EN COMPTOIR</span>
                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">Patrick M.</span>
                    </div>
                </div>

                <!-- Section Détail des mets -->
                <span class="text-uppercase text-muted d-block mb-2" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">DÉTAIL DES METS</span>
                
                <!-- Article 1 : okok salé (Emballé) -->
                <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">okok salé</span>
                    <span class="text-muted" style="font-size: 0.75rem;">Emballé</span>
                </div>
            <!-- Ferme le bloc supérieur -->
            </div>

            <!-- Pied du bon : Temps d'attente au passe + Bouton REMETTRE AU CLIENT -->
            <div class="mt-3">
                <!-- Statut de préparation depuis X minutes -->
                <div class="d-flex align-items-center gap-2 text-muted mb-2" style="font-size: 0.75rem;">
                    <i class="fa-regular fa-clock"></i>
                    <span>Prêt depuis 5 min</span>
                    <span class="bg-secondary-subtle rounded-circle ms-auto" style="width:6px; height:6px;"></span>
                </div>
                <!-- Bouton d'action primaire (#CFBD97) -->
                <button class="btn btn-primary-custom w-100 btn-sm rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-box-archive"></i>
                    <span>REMETTRE AU CLIENT</span>
                </button>
            </div>
        <!-- Ferme la carte #1043 -->
        </div>
    <!-- Ferme la colonne #1043 -->
    </div>

    <!-- ==================== BON PRÊT #1044 (LIVRAISON) ==================== -->
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Carte de commande blanche -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">
            <!-- Bloc Supérieur -->
            <div>
                <!-- En-tête du bon : Titre COMMANDE #1044 + Badge Livraison -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-uppercase text-muted d-block" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">COMMANDE</span>
                        <h4 class="fw-bold text-dark mb-0">#1044</h4>
                    </div>
                    <!-- Badge Livraison -->
                    <span class="badge bg-light text-dark border fw-normal d-flex align-items-center gap-1 px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-motorcycle text-muted"></i> Livraison
                    </span>
                </div>

                <!-- Bloc d'affectation : Coursier assigné Davy -->
                <div class="p-2 px-3 rounded-3 bg-assignment-box mb-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-helmet-safety text-muted" style="font-size: 0.85rem;"></i>
                    <div>
                        <span class="text-uppercase text-muted d-block" style="font-size: 0.6rem; font-weight: 700; letter-spacing: 0.5px;">COURSIER ASSIGNÉ</span>
                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">Davy</span>
                    </div>
                </div>

                <!-- Section Détail des mets -->
                <span class="text-uppercase text-muted d-block mb-2" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">DÉTAIL DES METS</span>
                
                <!-- Article 1 : roti de poulet (Scellé) -->
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">roti de poulet</span>
                    <span class="text-muted" style="font-size: 0.75rem;">Scellé</span>
                </div>

                <!-- Article 2 : 1x Frites (Chaud) -->
                <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">1× Frites</span>
                    <span class="text-muted" style="font-size: 0.75rem;">Chaud</span>
                </div>
            <!-- Ferme le bloc supérieur -->
            </div>

            <!-- Pied du bon : Temps d'attente au passe + Bouton REMETTRE AU COURSIER -->
            <div class="mt-3">
                <!-- Statut de préparation depuis X minutes -->
                <div class="d-flex align-items-center gap-2 text-muted mb-2" style="font-size: 0.75rem;">
                    <i class="fa-regular fa-clock"></i>
                    <span>Prêt depuis 1 min</span>
                    <span class="time-status-dot ms-auto"></span>
                </div>
                <!-- Bouton d'action primaire (#CFBD97) -->
                <button class="btn btn-primary-custom w-100 btn-sm rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-person-biking"></i>
                    <span>REMETTRE AU COURSIER</span>
                </button>
            </div>
        <!-- Ferme la carte #1044 -->
        </div>
    <!-- Ferme la colonne #1044 -->
    </div>

<!-- Ferme la grille des bons de commandes -->
</div>

<!-- Ligne d'informations secondaires et signature de bas de page -->
<div class="d-flex justify-content-between align-items-center mt-4 text-muted" style="font-size: 0.75rem;">
    <!-- Signal sonore descriptif -->
    <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-volume-low"></i>
        <span>Signal sonore discret activé lors de l'arrivée au passe</span>
    </div>
    <!-- Marque bas de page -->
    <span class="text-uppercase tracking-wider fw-bold" style="letter-spacing: 1px; font-size: 0.65rem;">
        ZEDUC HAUTE-HOSPITALITÉ
    </span>
</div>

<!-- Pied de page avec le bouton de retour en bas à gauche -->
<div class="mt-4 pt-2">
    <!-- Bouton flèche de retour vers la vue supervision -->
    <a href="{{ route('gerant.orders.index') }}" class="btn btn-link text-secondary p-0 fs-4">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
<!-- Ferme le footer -->
</div>

<!-- Ferme la section content -->
@endsection
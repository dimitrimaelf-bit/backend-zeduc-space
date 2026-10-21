<!-- Hérite de la structure du layout maître situé dans resources/views/layouts/gerant.blade.php -->
@extends('layouts.gerant')

<!-- Définit le titre spécifique de l'onglet du navigateur pour cette vue -->
@section('title', 'Supervision - Espace Gérant')

<!-- Début de la section de contenu principal qui sera injectée dans le @yield('content') du layout -->
@section('content')

<!-- Styles CSS spécifiques à cette vue pour respecter exactement la maquette et les couleurs demandées -->
<style>
    /* Couleur primaire personnalisée d'arrière-plan (#CFBD97) */
    .bg-primary-custom {
        background-color: #CFBD97 !important;
    }
    /* Couleur primaire personnalisée pour le texte (#CFBD97) */
    .text-primary-custom {
        color: #CFBD97 !important;
    }
    /* Boutons personnalisés avec la couleur primaire (#CFBD97) et texte noir */
    .btn-primary-custom {
        background-color: #CFBD97 !important;
        border-color: #CFBD97 !important;
        color: #000000 !important;
        font-weight: 600;
    }
    /* Effet au survol des boutons primaires */
    .btn-primary-custom:hover {
        background-color: #bfae88 !important;
        color: #000000 !important;
    }
    /* Style personnalisé pour les cartes d'arbitrage */
    .bg-arbitrage-card {
        background-color: #f1ede8;
    }
</style>

<!-- En-tête de la page Supervision -->
<div class="mb-4">
    <!-- Petit sous-titre supérieur 'EN DIRECT • SERVICE EN COURS' -->
    <p class="text-uppercase fw-bold small mb-1" style="color: #8c7b60; letter-spacing: 0.5px;">
        • EN DIRECT • SERVICE EN COURS
    </p>
    <!-- Titre principal de la vue 'Supervision' -->
    <h1 class="display-6 fw-bold text-dark mb-0">Supervision</h1>
<!-- Ferme le bloc d'en-tête -->
</div>

<!-- Rangée des 3 cartes de statistiques supérieures (Ventes, Commandes actives, Alertes) -->
<div class="row g-3 mb-4">
    
    <!-- Carte 1 : Ventes du jour -->
    <div class="col-12 col-md-4">
        <!-- Carte blanche arrondie sans bordure avec ombre légère -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <!-- Libellé de la statistique -->
            <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">VENTES DU JOUR</span>
            <!-- Valeur chiffrée avec unité FCFA -->
            <h2 class="fw-bold my-2 text-dark">
                185 000 <span class="fs-6 text-muted fw-normal">FCFA</span>
            </h2>
        <!-- Ferme la carte 1 -->
        </div>
    <!-- Ferme la colonne 1 -->
    </div>

    <!-- Carte 2 : Commandes actives -->
    <div class="col-12 col-md-4">
        <!-- Carte blanche arrondie sans bordure avec ombre légère -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <!-- Libellé de la statistique -->
            <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">COMMANDES ACTIVES</span>
            <!-- Valeur chiffrée avec statut -->
            <h2 class="fw-bold my-2 text-dark">
                7 <span class="fs-6 text-muted fw-normal">en préparation</span>
            </h2>
        <!-- Ferme la carte 2 -->
        </div>
    <!-- Ferme la colonne 2 -->
    </div>

    <!-- Carte 3 : Alertes & Réclamations -->
    <div class="col-12 col-md-4">
        <!-- Carte blanche arrondie sans bordure avec position-relative pour le point rouge -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative">
            <!-- Point rouge d'alerte urgente en haut à droite -->
            <span class="position-absolute top-0 end-0 translate-middle p-1 bg-danger border border-light rounded-circle mt-3 me-3"></span>
            <!-- Libellé de la statistique -->
            <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">ALERTES & RÉCLAMATIONS</span>
            <!-- Valeur chiffrée en rouge pour urgence -->
            <h2 class="fw-bold text-danger my-2">
                02 <span class="fs-6 text-muted fw-normal">urgentes</span>
            </h2>
        <!-- Ferme la carte 3 -->
        </div>
    <!-- Ferme la colonne 3 -->
    </div>

<!-- Ferme la rangée des cartes statistiques -->
</div>

<!-- Disposition principale : 2 Colonnes (Gauche = Liste des commandes / Droite = Arbitrages & En service) -->
<div class="row g-4">
    
    <!-- COLONNE GAUCHE (Flux des commandes) -->
    <div class="col-12 col-lg-8">
        
        <!-- Barre de filtres d'état (En cours, Prêtes au passe, Livrées) -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <!-- Groupe de boutons de filtres -->
            <div class="d-flex gap-2">
                <!-- Filtre sélectionné 'En cours 7' (Fond noir secondaire #000000) -->
                <button class="btn btn-dark text-white rounded-3 px-3 py-1 btn-sm fw-bold">
                    En cours <span class="badge bg-secondary ms-1">7</span>
                </button>
                <!-- Filtre 'Prêtes au passe 3' -->
                <button class="btn btn-light text-muted rounded-3 px-3 py-1 btn-sm">
                    Prêtes au passe <span class="badge bg-light text-dark ms-1">3</span>
                </button>
                <!-- Filtre 'Livrées 48' -->
                <button class="btn btn-light text-muted rounded-3 px-3 py-1 btn-sm">
                    Livrées <span class="badge bg-light text-dark ms-1">48</span>
                </button>
            <!-- Ferme le groupe de boutons de filtres -->
            </div>
            <!-- Indicateur de tri automatique à droite -->
            <span class="text-muted small">Tri automatique</span>
        <!-- Ferme la barre de filtres -->
        </div>

        <!-- LISTE DES CARTES DE COMMANDES -->
        <div class="d-flex flex-column gap-3">
            
            <!-- Commande 1 : Awa Traoré -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <!-- Flexbox alignant les informations du client et les actions -->
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Partie gauche : Carré image/espace + Informations client -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Carré d'illustration neutre -->
                        <div class="bg-light rounded-3" style="width: 50px; height: 50px;"></div>
                        <!-- Détails client et plat -->
                        <div>
                            <!-- Nom du client et Badge de la table -->
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-dark">Awa Traoré</h6>
                                <span class="badge bg-light text-dark border fw-normal">Table 4</span>
                            </div>
                            <!-- Nom du plat commandé -->
                            <p class="text-muted small mb-0 mt-1">Poulet DG</p>
                        </div>
                    </div>
                    <!-- Partie droite : Temps d'attente + Bouton d'action -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Statut de temps en rouge -->
                        <span class="text-danger small fw-bold">8 min attente</span>
                        <!-- Bouton d'action 'Marquer prête' aux couleurs primaires (#CFBD97) -->
                        <button class="btn btn-primary-custom btn-sm rounded-3 px-3">Marquer prête</button>
                    </div>
                </div>
            <!-- Ferme la commande 1 -->
            </div>

            <!-- Commande 2 : Junior Eto'o -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <!-- Flexbox alignant les informations du client et les actions -->
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Partie gauche : Carré image/espace + Informations client -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Carré d'illustration neutre -->
                        <div class="bg-light rounded-3" style="width: 50px; height: 50px;"></div>
                        <!-- Détails client et plat -->
                        <div>
                            <!-- Nom du client et Badge du mode Livraison -->
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-dark">Junior Eto'o</h6>
                                <span class="badge bg-light text-dark border fw-normal">Livraison</span>
                            </div>
                            <!-- Nom du plat commandé -->
                            <p class="text-muted small mb-0 mt-1">Poulet pané</p>
                        </div>
                    </div>
                    <!-- Partie droite : Statut 'Au passe' + Bouton d'action -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Statut neutre d'avancement -->
                        <span class="badge bg-light text-dark fw-normal px-2 py-1">Au passe</span>
                        <!-- Bouton d'action 'Notifier retrait' aux couleurs primaires (#CFBD97) -->
                        <button class="btn btn-primary-custom btn-sm rounded-3 px-3">Notifier retrait</button>
                    </div>
                </div>
            <!-- Ferme la commande 2 -->
            </div>

            <!-- Commande 3 : Brice K. -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <!-- Flexbox alignant les informations du client et les actions -->
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Partie gauche : Carré image/espace + Informations client -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Carré d'illustration neutre -->
                        <div class="bg-light rounded-3" style="width: 50px; height: 50px;"></div>
                        <!-- Détails client et plat -->
                        <div>
                            <!-- Nom du client et Badge du mode À emporter -->
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-dark">Brice K.</h6>
                                <span class="badge bg-light text-dark border fw-normal">À emporter</span>
                            </div>
                            <!-- Nom du plat commandé -->
                            <p class="text-muted small mb-0 mt-1">Ndolé Royal</p>
                        </div>
                    </div>
                    <!-- Partie droite : Statut 'En préparation' + Bouton d'action -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Statut neutre d'avancement -->
                        <span class="badge bg-light text-dark fw-normal px-2 py-1">En préparation</span>
                        <!-- Bouton d'action 'Marquer prêt' aux couleurs primaires (#CFBD97) -->
                        <button class="btn btn-primary-custom btn-sm rounded-3 px-3">Marquer prêt</button>
                    </div>
                </div>
            <!-- Ferme la commande 3 -->
            </div>

        <!-- Ferme le conteneur de liste des commandes -->
        </div>

    <!-- Ferme la colonne gauche -->
    </div>

    <!-- COLONNE DROITE (Arbitrages & Équipe en service) -->
    <div class="col-12 col-lg-4">
        
        <!-- BLOC 1 : ARBITRAGES -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <!-- En-tête du bloc Arbitrages avec badge prioritaire -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0 text-dark">Arbitrages</h5>
                <!-- Badge '2 prioritaires' rouge clair -->
                <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">2 prioritaires</span>
            </div>

            <!-- Liste des éléments d'arbitrage -->
            <div class="d-flex flex-column gap-3">
                
                <!-- Carte d'arbitrage 1 : Table 6 Cuisson -->
                <div class="p-3 rounded-3 bg-arbitrage-card">
                    <!-- Détail du problème et du prix -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold small text-dark">Table 6 • Cuisson</span>
                        <span class="small text-muted fw-bold">9 500 F</span>
                    </div>
                    <!-- Boutons d'action Valider / Rejeter -->
                    <div class="row g-2">
                        <div class="col-8">
                            <button class="btn btn-primary-custom btn-sm w-100 rounded-2 py-1">Valider</button>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-light btn-sm w-100 rounded-2 py-1 text-muted">Rejeter</button>
                        </div>
                    </div>
                <!-- Ferme la carte d'arbitrage 1 -->
                </div>

                <!-- Carte d'arbitrage 2 : Oubli boisson -->
                <div class="p-3 rounded-3 bg-arbitrage-card">
                    <!-- Détail du problème et du prix -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold small text-dark">#1038 • Oubli boisson</span>
                        <span class="small text-muted fw-bold">1 500 F</span>
                    </div>
                    <!-- Boutons d'action Valider / Rejeter -->
                    <div class="row g-2">
                        <div class="col-8">
                            <button class="btn btn-primary-custom btn-sm w-100 rounded-2 py-1">Valider</button>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-light btn-sm w-100 rounded-2 py-1 text-muted">Rejeter</button>
                        </div>
                    </div>
                <!-- Ferme la carte d'arbitrage 2 -->
                </div>

            <!-- Ferme la liste des arbitrages -->
            </div>
        <!-- Ferme le bloc Arbitrages -->
        </div>

        <!-- BLOC 2 : EN SERVICE (Personnel affecté) -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <!-- En-tête du bloc En service -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0 text-dark">En service</h5>
                <span class="small text-muted">3 référents</span>
            </div>

            <!-- Table des membres du personnel en poste -->
            <div class="d-flex flex-column gap-2">
                <!-- Membre 1 : Cuisine chaude -->
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span class="small text-muted">Cuisine chaude</span>
                    <span class="small fw-bold text-dark">Mamadou D.</span>
                </div>
                <!-- Membre 2 : Passe & Envoi -->
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span class="small text-muted">Passe & Envoi</span>
                    <span class="small fw-bold text-dark">Sarah T.</span>
                </div>
                <!-- Membre 3 : Salle -->
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span class="small text-muted">Salle</span>
                    <span class="small fw-bold text-dark">Christian K.</span>
                </div>
            <!-- Ferme la table du personnel -->
            </div>
        <!-- Ferme le bloc En service -->
        </div>

    <!-- Ferme la colonne droite -->
    </div>

<!-- Ferme la rangée principale -->
</div>

<!-- Bouton flottant 'Retour à l'accueil' en bas à droite (Couleur primaire #CFBD97) -->
<div class="d-flex justify-content-end mt-5">
    <a href="#" class="btn btn-primary-custom rounded-pill px-4 py-2 shadow-sm fs-5">
        Retour à l'accueil
    </a>
</div>

<!-- Fin de la section content -->
@endsection
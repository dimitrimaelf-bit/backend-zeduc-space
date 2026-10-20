@extends('layouts.mmm')

@section('titre', 'Configuration Générale - ZeDuc@Space')

@section('contenu')
<div class="settings-wrapper">

    <!-- Titre principal -->
    <div class="mb-4">
        <h1 class="font-serif fw-bold text-dark mb-1" style="font-size: 30px; font-family: Georgia, serif;">Configuration Générale de l'Application</h1>
        <p class="text-muted mb-0" style="font-size: 13.5px;">Gérez les paramètres de votre restaurant, du programme de fidélité et de parrainage, ainsi que les aspects légaux et RGPD.</p>
    </div>

    <!-- Onglets -->
    <div class="bg-white p-2 rounded-3 shadow-sm d-inline-flex gap-2 mb-4">
        <button class="btn text-white px-3 py-2 fw-medium" style="background-color: #a38959; font-size: 13px;"><i class="bi bi-calendar-event me-2"></i>Restaurant & Horaires</button>
        <button class="btn btn-light text-muted px-3 py-2 fw-medium" style="font-size: 13px;"><i class="bi bi-star me-2"></i>Fidélité & Parrainage</button>
        <button class="btn btn-light text-muted px-3 py-2 fw-medium" style="font-size: 13px;"><i class="bi bi-shield-check me-2"></i>RGPD / Légal</button>
    </div>

    <!-- Carte 1 : Horaires -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1 me-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background-color: #a38959; width: 28px; height: 28px;">1</span>
                    <h5 class="fw-bold text-dark mb-0">Horaires de service du restaurant</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-uppercase text-muted fw-bold" style="font-size: 10.5px;">Heure d'ouverture</label>
                        <input type="text" class="form-control rounded-3 py-2" value="08:00" style="font-size: 13px;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-uppercase text-muted fw-bold" style="font-size: 10.5px;">Heure de fermeture</label>
                        <input type="text" class="form-control rounded-3 py-2" value="22:00" style="font-size: 13px;">
                    </div>
                </div>
                <small class="text-muted d-block mt-2" style="font-size: 11.5px;">Bloque automatiquement la prise de commande en ligne en dehors de ces heures.</small>
            </div>
            <div class="bg-light rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 90px;">
                <i class="bi bi-clock fs-2 text-secondary"></i>
            </div>
        </div>
    </div>

    <!-- Carte 2 : Règles de fidélité -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1 me-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background-color: #a38959; width: 28px; height: 28px;">2</span>
                    <h5 class="fw-bold text-dark mb-0">Règles de fidélité & remises</h5>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-uppercase text-muted fw-bold" style="font-size: 10.5px;">Dépense pour 1 point cumulé</label>
                        <div class="input-group">
                            <input type="text" class="form-control rounded-start-3" value="1000" style="font-size: 13px;">
                            <span class="input-group-text bg-light text-muted" style="font-size: 12px;">FCFA</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-uppercase text-muted fw-bold" style="font-size: 10.5px;">Nombre de points requis pour remise</label>
                        <div class="input-group">
                            <input type="text" class="form-control rounded-start-3" value="15" style="font-size: 13px;">
                            <span class="input-group-text bg-light text-muted" style="font-size: 12px;">Points</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 90px;">
                <i class="bi bi-star fs-2 text-secondary"></i>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="d-flex justify-content-end gap-2 mt-4">
        <button class="btn btn-outline-secondary px-4 py-2" style="border-radius: 8px; font-size: 13px;">Annuler</button>
        <button class="btn text-white px-4 py-2" style="background-color: #a38959; border-radius: 8px; font-size: 13px;">
            <i class="bi bi-floppy me-2"></i>Enregistrer les Modifications
        </button>
    </div>

</div>
@endsection
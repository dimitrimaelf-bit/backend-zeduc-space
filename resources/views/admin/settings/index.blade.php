@extends('layouts.admin')

@section('title', 'Paramètres Généraux - Mon Miam Miam')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Paramètres Généraux de l'Application</h3>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="site_name" class="form-label fw-semibold">Nom de l'Etablissement</label>
                    <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'Mon Miam Miam') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_phone" class="form-label fw-semibold">Téléphone de Contact</label>
                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="delivery_fee" class="form-label fw-semibold">Frais de Livraison Standard (FCFA)</label>
                    <input type="number" class="form-control" id="delivery_fee" name="delivery_fee" value="{{ old('delivery_fee', $settings['delivery_fee'] ?? 500) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="opening_hours" class="form-label fw-semibold">Horaires d'ouverture</label>
                    <input type="text" class="form-control" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $settings['opening_hours'] ?? '08h00 - 20h00') }}">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-brand">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Sauvegarder les Paramètres
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
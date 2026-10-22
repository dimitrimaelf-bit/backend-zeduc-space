@extends('layouts.mmm')

@section('titre', 'Configuration générale - ZeDuc@Space')

@section('contenu')
@php
    $settingValue = fn ($key, $fallback) => trim($settings->get($key)?->setting_value ?? $fallback);
@endphp
<div class="container-fluid px-0">
    <div class="mb-4"><h1 class="h3 fw-bold mb-1">Configuration générale</h1><p class="text-muted">Horaires du restaurant et règles du programme de fidélité.</p></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <h2 class="h5 fw-bold mb-3">Restaurant & horaires</h2>
    <div class="row g-3 mb-4">
        @foreach(['restaurant_open' => ['Heure d’ouverture', '08:00'], 'restaurant_close' => ['Heure de fermeture', '22:00']] as $key => [$label, $fallback])
            <div class="col-md-6"><form method="POST" action="{{ route('admin.settings.update', $key) }}" class="bg-white border rounded-2 p-3">@csrf @method('PUT')<label for="{{ $key }}" class="form-label">{{ $label }}</label><div class="input-group"><input id="{{ $key }}" type="time" name="setting_value" value="{{ old('setting_value', $settingValue($key, $fallback)) }}" class="form-control @error('setting_value') is-invalid @enderror" required><button class="btn btn-dark" type="submit">Enregistrer</button></div>@error('setting_value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</form></div>
        @endforeach
    </div>

    <h2 class="h5 fw-bold mb-3">Fidélité & parrainage</h2>
    <div class="row g-3">
        @foreach([
            'loyalty_fcfa_per_point' => ['Dépense pour gagner un point', '1000', 'FCFA'],
            'loyalty_points_per_1000_fcfa_discount' => ['Points requis pour une remise', '15', 'points'],
            'loyalty_discount_fcfa' => ['Montant de la remise', '1000', 'FCFA'],
            'loyalty_points_expiry_months' => ['Durée de validité des points', '12', 'mois'],
        ] as $key => [$label, $fallback, $unit])
            <div class="col-md-6"><form method="POST" action="{{ route('admin.settings.update', $key) }}" class="bg-white border rounded-2 p-3">@csrf @method('PUT')<label for="{{ $key }}" class="form-label">{{ $label }}</label><div class="input-group"><input id="{{ $key }}" type="number" min="1" step="1" name="setting_value" value="{{ old('setting_value', $settingValue($key, $fallback)) }}" class="form-control @error('setting_value') is-invalid @enderror" required><span class="input-group-text">{{ $unit }}</span><button class="btn btn-dark" type="submit">Enregistrer</button></div>@error('setting_value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</form></div>
        @endforeach
    </div>

    <h2 class="h5 fw-bold mt-5 mb-3">Confidentialité & légal</h2>
    <p class="text-muted small">Ces liens et cette version sont utilisés pour tracer le consentement aux cookies.</p>
    <div class="row g-3">
        @foreach([
            'privacy_policy_url' => ['Lien vers la politique de confidentialité', '', 'url', false],
            'terms_of_service_url' => ['Lien vers les conditions de service', '', 'url', false],
            'cookie_policy_version' => ['Version de la politique cookies', '1.0', 'text', true],
        ] as $key => [$label, $fallback, $type, $required])
            <div class="col-md-6"><form method="POST" action="{{ route('admin.settings.update', $key) }}" class="bg-white border rounded-2 p-3">@csrf @method('PUT')<label for="{{ $key }}" class="form-label">{{ $label }}</label><div class="input-group"><input id="{{ $key }}" type="{{ $type }}" name="setting_value" value="{{ old('setting_value', $settingValue($key, $fallback)) }}" class="form-control @error('setting_value') is-invalid @enderror" @if($required) required @endif><button class="btn btn-dark" type="submit">Enregistrer</button></div>@error('setting_value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</form></div>
        @endforeach
    </div>
</div>
@endsection

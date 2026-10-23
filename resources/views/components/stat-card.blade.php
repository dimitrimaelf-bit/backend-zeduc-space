{{-- Carte de métrique réutilisable dans les vues Blade. --}}
@props(['title', 'value', 'unit' => '', 'subtitle' => '', 'alert' => false])

<div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative">
    {{-- Pastille rouge d'alerte urgente si spécifiée --}}
    @if($alert)
        <span class="position-absolute top-0 end-0 translate-middle p-1 bg-danger border border-light rounded-circle mt-3 me-3"></span>
    @endif
    
    <span class="text-muted small fw-bold text-uppercase">{{ $title }}</span>
    <h2 class="fw-bold my-2 {{ $alert ? 'text-danger' : 'text-dark' }}">
        {{ $value }} <span class="fs-6 text-muted fw-normal">{{ $unit }}</span>
    </h2>
    
    @if($subtitle)
        <span class="small text-muted">{{ $subtitle }}</span>
    @endif
</div>
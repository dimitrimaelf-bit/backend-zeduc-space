{{-- Composant de navigation responsive entre les états de commandes --}}
@props(['active' => 'supervision', 'counts' => ['encours' => 0, 'pretes' => 0, 'livrees' => 0]])

<div class="d-flex flex-wrap gap-2 mb-4">
    {{-- Bouton Commandes en cours --}}
    <a href="{{ route('gerant.orders.index') }}" class="btn btn-sm rounded-3 px-3 py-2 fw-bold {{ $active === 'supervision' ? 'btn-dark' : 'btn-light text-muted' }}">
        En cours <span class="badge bg-secondary ms-1">{{ $counts['encours'] }}</span>
    </a>
    
    {{-- Bouton Commandes prêtes au passe --}}
    <a href="{{ route('gerant.orders.index', ['status' => 'ready']) }}" class="btn btn-sm rounded-3 px-3 py-2 fw-bold {{ $active === 'pretes' ? 'btn-dark' : 'btn-light text-muted' }}">
        Prêtes au passe <span class="badge bg-secondary ms-1">{{ $counts['pretes'] }}</span>
    </a>
    
    {{-- Bouton Commandes livrées --}}
    <a href="{{ route('gerant.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm rounded-3 px-3 py-2 fw-bold {{ $active === 'livrees' ? 'btn-dark' : 'btn-light text-muted' }}">
        Livrées <span class="badge bg-secondary ms-1">{{ $counts['livrees'] }}</span>
    </a>
</div>
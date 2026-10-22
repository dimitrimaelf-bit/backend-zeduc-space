@extends('layouts.mmm')

@section('titre', 'Promotions - ZeDuc@Space')

@push('styles')
<style>
    .badge-scheduled { color: #604b32; background-color: #eee3d1; border: 1px solid #d9c7aa; }
</style>
@endpush

@section('contenu')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4"><div><h1 class="h3 fw-bold mb-1">Promotions & événements</h1><p class="text-muted mb-0">Créez et publiez les offres et affiches du restaurant.</p></div><div class="d-flex gap-2"><a href="{{ route('admin.promotions.create') }}" class="btn btn-dark">Créer une promotion</a><a href="{{ route('admin.events.create') }}" class="btn btn-outline-dark">Créer un événement</a></div></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <p class="text-muted">{{ $currentlyRunningPromotionCount }} en cours maintenant · {{ $activePromotionCount }} activée(s) ou programmée(s).</p>
    <div class="row g-3">
        @forelse($promotions as $promotion)
            <div class="col-12 col-md-6 col-xl-4">
                <article class="bg-white border rounded-2 overflow-hidden h-100">
                    @if($promotion->image_url)<img src="{{ $promotion->image_url }}" alt="Affiche : {{ $promotion->title }}" class="w-100" style="height: 190px; object-fit: cover;">@else<div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height: 190px;">Aucune affiche</div>@endif
                    <div class="p-3"><div class="d-flex justify-content-between gap-2"><h2 class="h5 fw-bold">{{ $promotion->title }}</h2><span class="badge {{ !$promotion->is_active ? 'text-bg-secondary' : (($promotion->starts_at && $promotion->starts_at->isFuture()) ? 'badge-scheduled' : (($promotion->ends_at && $promotion->ends_at->isPast()) ? 'text-bg-warning' : 'text-bg-success')) }}">{{ !$promotion->is_active ? 'Désactivée' : (($promotion->starts_at && $promotion->starts_at->isFuture()) ? 'Programmée' : (($promotion->ends_at && $promotion->ends_at->isPast()) ? 'Terminée' : 'En cours')) }}</span></div>
                        <p class="text-muted small">{{ $promotion->description ?: 'Aucune description.' }}</p>
                        <p class="small mb-3">{{ $promotion->starts_at?->format('d/m/Y H:i') ?? 'Sans date de début' }} → {{ $promotion->ends_at?->format('d/m/Y H:i') ?? 'Sans date de fin' }}</p>
                        <div class="d-flex justify-content-end gap-2"><a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-sm btn-outline-dark">Modifier</a><form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}" onsubmit="return confirm('Supprimer cette promotion ?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button></form></div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12"><div class="bg-white border rounded-2 p-4 text-center text-muted">Aucune promotion enregistrée.</div></div>
        @endforelse
    </div>
    <div class="mt-3">{{ $promotions->links() }}</div>

    <div class="d-flex justify-content-between align-items-center mt-5 mb-3"><h2 class="h5 fw-bold mb-0">Événements</h2><a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-outline-dark">Ajouter un événement</a></div>
    <div class="row g-3">
        @forelse($events as $event)
            <div class="col-12 col-md-6 col-xl-4"><article class="bg-white border rounded-2 overflow-hidden h-100">
                @if($event->image_url)<img src="{{ $event->image_url }}" alt="Affiche : {{ $event->title }}" class="w-100" style="height: 190px; object-fit: cover;">@else<div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height: 190px;">Aucune affiche</div>@endif
                <div class="p-3"><div class="d-flex justify-content-between gap-2"><h3 class="h5 fw-bold">{{ $event->title }}</h3><span class="badge {{ $event->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $event->is_active ? 'Activé' : 'Désactivé' }}</span></div><p class="text-muted small">{{ $event->type ?: 'Événement' }} · {{ $event->description ?: 'Aucune description.' }}</p><p class="small">{{ $event->starts_at?->format('d/m/Y H:i') ?? 'Sans date de début' }} → {{ $event->ends_at?->format('d/m/Y H:i') ?? 'Sans date de fin' }}</p><div class="d-flex justify-content-end gap-2"><a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-dark">Modifier</a><form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Supprimer cet événement ?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Supprimer</button></form></div></div>
            </article></div>
        @empty
            <div class="col-12"><div class="bg-white border rounded-2 p-4 text-center text-muted">Aucun événement enregistré.</div></div>
        @endforelse
    </div>
    <div class="mt-3">{{ $events->links() }}</div>
</div>
@endsection

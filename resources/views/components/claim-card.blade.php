@props(['claim', 'active' => false])

@php
    $statusClasses = [
        'open' => 'bg-danger-subtle text-danger',
        'in_progress' => 'bg-warning-subtle text-warning-emphasis',
        'resolved' => 'bg-success-subtle text-success',
        'rejected' => 'bg-secondary-subtle text-secondary',
    ];
@endphp

<div class="card border-0 shadow-sm rounded-4 p-3 bg-white position-relative {{ $active ? 'border-start border-4 border-warning' : 'opacity-75' }}">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold mb-0 text-dark">{{ $claim->user?->first_name }} {{ $claim->user?->last_name }}</h6>
                <span class="badge bg-light text-dark border rounded-2 px-2 py-1 extra-small">{{ $claim->order?->order_number }}</span>
            </div>
            <span class="text-muted small d-block mt-1">{{ $claim->subject }}</span>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
        <span class="text-muted extra-small"><i class="bi bi-clock me-1"></i> {{ $claim->created_at?->diffForHumans() }}</span>
        <span class="badge {{ $statusClasses[$claim->status] ?? 'bg-light text-dark' }} fw-bold rounded-2 px-2 py-1 extra-small">{{ str_replace('_', ' ', ucfirst($claim->status)) }}</span>
    </div>
</div>
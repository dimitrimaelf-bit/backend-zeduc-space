@extends('layouts.mmm')

@section('titre', 'Paiements à confirmer - ZeDuc@Space')

@section('contenu')
<div class="container-fluid px-0">
    <div class="mb-4"><h1 class="h3 fw-bold mb-1">Paiements à confirmer</h1><p class="text-muted mb-0">Confirme uniquement les paiements espèces réellement encaissés.</p></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive bg-white border rounded-2">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th class="ps-3">Commande</th><th>Client</th><th>Créée le</th><th>Montant attendu</th><th class="text-end pe-3">Confirmation</th></tr></thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $payment->order?->order_number ?? '—' }}</td>
                        <td>{{ $payment->order?->user?->first_name }} {{ $payment->order?->user?->last_name }}</td>
                        <td>{{ $payment->created_at?->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($payment->amount_fcfa, 0, ',', ' ') }} FCFA</td>
                        <td class="text-end pe-3"><form method="POST" action="{{ route('admin.payments.confirm-cash', $payment) }}" onsubmit="return confirm('Confirmer que le montant a été réellement encaissé ?')">@csrf<button class="btn btn-sm btn-dark" type="submit">Confirmer encaissé</button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucun paiement espèces en attente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $payments->links() }}</div>
</div>
@endsection

@extends('layouts.app') <!-- Utilise le layout principal de l'application pour garder la structure globale -->

@section('content')
<div class="container mt-5">
    <h2>Mon Miam Miam Panier</h2>

    {{-- Affichage d'un message d'alerte si une erreur survient (ex: panier vide ou stock insuffisant) --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Vérification si le panier est vide ou non --}}
    @if(empty($cart))
        <p>Votre panier est vide. <a href="{{ route('etudiant.menu.index') }}">Retourner au menu</a></p>
    @else
        {{-- Tableau récapitulatif des articles présents dans le panier --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Plat</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['price_fcfa'] }} FCFA</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['price_fcfa'] * $item['quantity'] }} FCFA</td>
                        <td>
                            {{-- Formulaire pour décrémenter ou supprimer un article du panier --}}
                            <form action="{{ route('etudiant.cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                <button type="submit" class="btn btn-sm btn-danger">-1</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Affichage du montant total brut du panier --}}
        <h4>Total à payer : {{ $total }} FCFA</h4>

        {{-- Formulaire de validation finale de la commande --}}
        <form action="{{ route('etudiant.order.store') }}" method="POST" class="mt-4">
            @csrf
            
            {{-- Sélection du mode de service (Sur place ou Livraison) --}}
            <div class="mb-3">
                <label for="service_mode" class="form-label">Mode de service</label>
                <select name="service_mode" id="service_mode" class="form-select" required>
                    <option value="on_site">Sur place</option>
                    <option value="delivery">Livraison</option>
                </select>
            </div>
            
            {{-- Saisie de l'adresse de livraison (nécessaire si le mode livraison est choisi) --}}
            <div class="mb-3">
                <label for="delivery_address" class="form-label">Adresse de livraison (si applicable)</label>
                <input type="text" name="delivery_address" id="delivery_address" class="form-control">
            </div>
        
            {{-- Bloc de gestion de la fidélité mis à jour pour le palier de 5 points --}}
            <div class="mb-3 form-check">
                @if($balance >= 5)
                    {{-- Si l'étudiant a au moins 5 points, la case est active et cochable --}}
                    <input type="checkbox" class="form-check-input" name="use_points" id="use_points" value="1">
                    <label class="form-check-label" for="use_points">
                        Utiliser mes points (5 pts = 1 000 FCFA de réduction)
                    </label>
                    @php
                        $blocsDe5 = floor($balance / 5);
                        $pointsRestants = $balance % 5;
                    @endphp
                    <small class="d-block text-success">
                        Tu as {{ $balance }} points ({{ $blocsDe5 }} bloc{{ $blocsDe5 > 1 ? 's' : '' }} de 5 disponibles).
                        @if($pointsRestants > 0)
                            {{ $pointsRestants }} point{{ $pointsRestants > 1 ? 's' : '' }} restant{{ $pointsRestants > 1 ? 's' : '' }} pour le prochain bloc.
                        @endif
                    </small>
                @else
                    {{-- Si l'étudiant a moins de 5 points, la case est désactivée et affiche les points manquants --}}
                    <input type="checkbox" class="form-check-input" id="use_points" disabled>
                    <label class="form-check-label text-muted" for="use_points">
                        Utiliser mes points (5 pts = 1 000 FCFA de réduction)
                    </label>
                    <small class="d-block text-danger">Seuil non atteint : il te manque {{ max(0, 5 - $balance) }} points.</small>
                @endif
            </div>

            {{-- Sélection de la méthode de paiement --}}
            <div class="mb-3">
                <label for="payment_method" class="form-label fw-bold">Méthode de paiement</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="mobile_money">Mobile Money (Orange Money / MTN MoMo)</option>
                    <option value="cash">Espèces (Paiement sur place)</option>
                </select>
                <small class="form-text text-muted">
                    Pour le test en mode simulation, utilise le numéro <strong>0707070707</strong> avec OTP <strong>123456</strong> (Orange Money) ou <strong>0707070707</strong> (MTN MoMo).
                </small>
            </div>

            {{-- Bouton de soumission du formulaire de commande --}}
            <button type="submit" class="btn btn-success">Valider la commande</button>
        </form>
    @endif
</div>
@endsection
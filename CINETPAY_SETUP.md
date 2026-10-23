# Configuration et Test de CinetPay - Mobile Money (Orange Money / MTN MoMo)

## 1. Inscription sur CinetPay

1. Allez sur https://cinetpay.com
2. Créez un compte
3. Dans votre dashboard, vous obtiendrez :
   - `APIKEY` 
   - `SITE_ID`

## 2. Configuration des variables d'environnement

Dans votre fichier `.env`, ajoutez ces lignes :

```env
CINETPAY_APIKEY=votre_apikey_ici
CINETPAY_SITE_ID=votre_site_id_ici
```

**IMPORTANT :** Assurez-vous que votre site est en **MODE TEST** dans le dashboard CinetPay. Tant que vous êtes en TEST, aucun vrai argent ne sera débité.

## 3. Numéros de TEST pour simuler les paiements

CinetPay fournit des numéros de test qui réussissent toujours :

### Paiements qui réussissent :
- **Orange Money :** `0707070707` ou `0505050505` avec OTP `123456`
- **MTN MoMo :** `0707070707`

### Pour simuler un échec :
- Utilisez le numéro : `0606060606`

### Montant minimum en TEST :
- Le montant minimum pour les tests est de **100 XAF**

## 4. Fonctionnement du système

### Backend (Déjà implémenté)

Le contrôleur `PaiementController` contient :
- `initierCinetPay()` : Initialise la transaction CinetPay et redirige l'étudiant vers la page de paiement
- `retourPaiement()` : Gère le retour de l'étudiant après paiement
- `notify()` : Webhook appelé par CinetPay (en production)

### Routes configurées (web.php)

```php
Route::match(['get', 'post'], '/paiement/retour', [PaiementController::class, 'retourPaiement'])->name('cinetpay.return');
Route::post('/paiement/notify', [PaiementController::class, 'notify'])->name('cinetpay.notify');
```

### Frontend (Déjà implémenté)

Le formulaire du panier (`resources/views/etudiant/cart/index.blade.php`) contient maintenant :
- Un sélecteur de méthode de paiement (Mobile Money / Espèces)
- Une aide indiquant les numéros de test à utiliser

## 5. Test du système (Étapes)

1. **Configurez les clés CinetPay** dans votre fichier `.env`
2. **Lancez le serveur Laravel** : `php artisan serve`
3. **Connectez-vous** en tant qu'étudiant
4. **Ajoutez des plats** au panier
5. **Allez au panier** et validez la commande
6. **Sélectionnez "Mobile Money"** comme méthode de paiement
7. **Cliquez sur "Valider la commande"**
8. **Vous serez redirigé** vers la page de paiement CinetPay
9. **Entrez le numéro de test** : `0707070707`
10. **Si demandé, entrez l'OTP** : `123456`
11. **Validez le paiement**
12. **Vous serez redirigé** sur votre site avec un message de succès
13. **La commande sera marquée** comme "confirmed" en base de données

## 6. Vérification en base de données

Après un paiement réussi, vous pouvez vérifier :

### Table `payments`
- `provider` = 'cinetpay'
- `provider_transaction_id` = ID de transaction CinetPay
- `method` = 'mobile_money'
- `status` = 'paid'
- `paid_at` = date/heure du paiement

### Table `orders`
- `status` = 'confirmed'

## 7. Dépannage

### Erreur "Commande enregistrée, mais la redirection vers CinetPay a échoué"
- Vérifiez que vos clés `CINETPAY_APIKEY` et `CINETPAY_SITE_ID` sont correctes
- Vérifiez que vous avez une connexion internet
- Consultez les logs Laravel : `php artisan log:tail`

### Le paiement échoue avec le numéro de test
- Assurez-vous que votre compte CinetPay est en mode TEST
- Vérifiez que le montant est d'au moins 100 XAF
- Essayez avec le numéro `0707070707` (fiable pour les tests)

### Le webhook notify n'est pas appelé (localhost)
- C'est normal en développement (localhost n'est pas accessible depuis l'extérieur)
- Le système utilise `retourPaiement()` pour vérifier le statut du paiement
- En production, le webhook fonctionnera normalement

## 8. Passage en production

Pour passer en production :
1. Activez le mode PRODUCTION dans votre dashboard CinetPay
2. Assurez-vous que votre site est accessible depuis internet (pas localhost)
3. Utilisez de vrais numéros de téléphone
4. Vérifiez que le webhook `notify_url` est accessible

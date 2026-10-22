MON MIAM MIAM — ESPACE EMPLOYÉ INTÉGRÉ (Laravel)

Ce paquet rassemble dans une seule structure :
- les vues Blade : tableau de bord, commandes (liste + détail), menu, réclamations (liste + détail), statistiques et connexion ;
- le layout commun et la feuille CSS ;
- les images du restaurant ;
- les contrôleurs Web qui alimentent les vues ;
- un exemple de routes à intégrer dans routes/web.php.

INSTALLATION
1. Sauvegardez votre projet Laravel.
2. Copiez app/Http/Controllers/Employee/ dans le même dossier de votre application.
3. Copiez resources/views/employe/ dans resources/views/employe/ de votre application.
4. Copiez resources/views/auth/login.blade.php si vous souhaitez utiliser cette page de connexion (ne remplacez pas une page existante sans sauvegarde).
5. Copiez public/css/ et public/images/ dans le dossier public/ de Laravel.
6. Ouvrez routes/web_employee.php.example et fusionnez son contenu dans routes/web.php. Ne copiez pas le fichier comme route autonome sans l'enregistrer dans Laravel.
7. Installez/configurez l'authentification Laravel et le middleware role:employee. Si votre application utilise un autre middleware de rôle, adaptez la route.
8. Vérifiez les modèles App\Models\Order, MenuItem et Claim, leurs relations et les colonnes ci-dessous.

HYPOTHÈSES DE SCHÉMA À VÉRIFIER
- Order : user(), items(), statut, montant_total, client_nom, type_commande, completed_at, created_at.
- MenuItem : nom, prix, description, image, disponible.
- Claim : user(), order(), objet ou sujet, message ou description, reponse, statut, handled_by, created_at.
Les vues/contrôleurs sont harmonisés sur les noms français statut, montant_total, disponible et reponse. Si votre base de données utilise status, total_fcfa, is_available ou response, créez des accessors/mutators ou adaptez les contrôleurs et les vues avant utilisation.

AUTHENTIFICATION
La page de connexion emploie route('login') et le layout route('logout'). Ces routes doivent exister dans votre système d'authentification Laravel.

IMPORTANT
Le ZIP ne contient pas l'application Laravel complète (vendor, .env, modèles, migrations, système d'authentification). Il s'agit du module employé intégré à ajouter à un projet Laravel existant. Testez les routes et les changements de statut en environnement de développement avant toute mise en production.

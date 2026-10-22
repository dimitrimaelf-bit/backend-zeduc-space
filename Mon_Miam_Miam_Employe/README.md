# Mon Miam Miam — vues de l'espace employé

## Contenu
- `resources/views/employe/layouts/app.blade.php` : layout commun et navigation.
- `resources/views/employe/dashboard.blade.php` : tableau de bord.
- `resources/views/employe/commandes/` : liste et détail/validation des commandes.
- `resources/views/employe/menu/index.blade.php` : disponibilité temporaire des plats.
- `resources/views/employe/reclamations/` : liste et traitement des réclamations.
- `resources/views/employe/statistiques.blade.php` : statistiques hebdomadaires.
- `resources/views/auth/login.blade.php` : formulaire de connexion (à adapter à votre authentification).
- `public/images/mon-miam-miam/` : images fournies (logo, hero, ndolé, poisson, poulet).
- `public/css/employe.css` : mise en forme responsive avec la charte noir et or (#000000, #cfbd97).

## Installation
Copiez le contenu du dossier `resources/views/` dans le `resources/views/` de votre application Laravel, et le contenu de `public/` dans le dossier `public/` de votre projet. Fusionnez les dossiers sans supprimer vos fichiers existants.

## Routes attendues
Les vues utilisent les noms de routes suivants : `employe.dashboard`, `employe.commandes.index`, `employe.commandes.show`, `employe.commandes.update`, `employe.menu.index`, `employe.menu.update`, `employe.reclamations.index`, `employe.reclamations.show`, `employe.reclamations.update`, `employe.statistiques`, ainsi que `login`, `logout`.

Les routes, contrôleurs, modèles et migrations ne sont pas inclus dans ce paquet. Les noms des propriétés (par exemple `statut`, `montant_total`, `disponible`) sont des hypothèses de présentation à adapter à votre schéma réel. Les valeurs d'exemple du tableau de bord et des statistiques doivent être remplacées par des données réelles envoyées par les contrôleurs.

## Images
Les images sont appelées par `asset('images/mon-miam-miam/...')`, elles doivent donc être placées sous `public/images/mon-miam-miam/`.

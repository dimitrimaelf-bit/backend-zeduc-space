!-- Indique au navigateur qu'il s'agit d'un document HTML5 moderne -->
<!DOCTYPE html>
<!-- Ouvre la balise HTML et spécifie que le contenu de la page est en français -->
<html lang="fr">
<!-- Ouvre l'en-tête du document contenant les métadonnées, titres et fichiers CSS -->
<head>
    <!-- Définit le jeu de caractères en UTF-8 pour gérer les accents et émojis -->
    <meta charset="UTF-8">
    <!-- Configure le viewport pour rendre le site responsive sur mobiles et tablettes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Injection dynamique du titre via Blade, avec 'Espace Gérant' comme valeur par défaut -->
    <title>@yield('title', 'Espace Gérant')</title>

    <!-- Charge la feuille de style CSS principale de Framework Bootstrap 5 depuis le CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Charge la bibliothèque d'icônes FontAwesome 6 depuis le CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Ouvre la balise de style pour déclarer les règles CSS personnalisées -->
    <style>
        /* Cible la balise body de la page */
        body {
            /* Applique une couleur de fond beige clair sur l'ensemble de l'écran */
            background-color: #f5f0eb;
        /* Ferme la règle CSS pour la balise body */
        }
        /* Cible la classe .sidebar de la barre latérale */
        .sidebar {
            /* Fixe la largeur exacte de la barre latérale à 260 pixels */
            width: 260px;
            /* Applique une couleur de fond sombre (presque noir) */
            background-color: #1e1b18;
            /* Assure que la barre latérale occupe au minimum 100% de la hauteur de l'écran */
            min-height: 100vh;
        /* Ferme la règle CSS pour .sidebar */
        }
        /* Cible les liens de navigation contenus dans la barre latérale */
        .sidebar .nav-link {
            /* Applique une couleur de texte gris clair aux liens */
            color: #a0a0a0;
            /* Arrondit les coins des boutons de menu de 0.5rem (8px) */
            border-radius: 0.5rem;
            /* Ajoute un espacement interne : 0.75rem en haut/bas et 1rem à gauche/droite */
            padding: 0.75rem 1rem;
            /* Anime les changements de styles (survol) sur 0.2 seconde */
            transition: all 0.2s ease-in-out;
        /* Ferme la règle CSS pour .sidebar .nav-link */
        }
        /* Cible les liens de navigation au passage de la souris */
        .sidebar .nav-link:hover {
            /* Change la couleur de fond en un gris/brun légèrement plus clair */
            background-color: #2a2622;
            /* Passe la couleur du texte en blanc au survol */
            color: #ffffff;
        /* Ferme la règle CSS de survol */
        }
        /* Cible le lien de la page actuellement active dans le menu */
        .sidebar .nav-link.active {
            /* Applique un fond sombre distinctif pour marquer l'onglet actif */
            background-color: #38322c;
            /* Force la couleur du texte actif en blanc */
            color: #ffffff;
            /* Met le texte en semi-gras */
            font-weight: 600;
        /* Ferme la règle CSS pour l'état actif */
        }
        /* Cible la classe spécifique du badge 'ESPACE GÉRANT' */
        .badge-gerant {
            /* Applique un fond marron foncé derrière le badge */
            background-color: #3a3530;
            /* Applique une couleur d'écriture jaune/ambre */
            color: #ffc107;
            /* Réduit la taille du texte à 0.7rem */
            font-size: 0.7rem;
            /* Espace légèrement les lettres de 0.5 pixel */
            letter-spacing: 0.5px;
        /* Ferme la règle CSS pour le badge gérant */
        }
    <!-- Ferme la balise style des CSS personnalisés -->
    </style>
<!-- Ferme la balise d'en-tête head du document -->
</head>
<!-- Ouvre le corps du document avec un conteneur flex horizontal (d-flex), scroll masqué (overflow-hidden) et 100% de la hauteur écran (vh-100) -->
<body class="d-flex overflow-hidden vh-100">

    <!-- Ouvre la balise sémantique aside pour la barre latérale, configurée en colonne flex verticale avec espacement p-3 -->
    <aside class="sidebar text-white d-flex flex-column justify-content-between p-3 flex-shrink-0">
        <!-- Ouvre une div englobant la partie supérieure de la sidebar (En-tête + Menu) -->
        <div>
            <!-- Ouvre le bloc d'en-tête avec une marge basse mb-4 -->
            <div class="mb-4">
                <!-- Titre principal de la marque 'ZeDuc@Space' stylisé avec la classe h5 -->
                <h1 class="h5 font-weight-bold mb-1">ZeDuc@Space</h1>
                <!-- Ouvre la balise span du badge stylisé avec nos classes de badge Bootstrap et personnalisées -->
                <span class="badge badge-gerant text-uppercase px-2 py-1 rounded">
                    <!-- Texte affiché dans le badge -->
                    ESPACE GÉRANT
                <!-- Ferme la balise span du badge -->
                </span>
                <!-- Paragraphe indiquant le service actif avec texte réduit (small) et atténué (text-muted) -->
                <p class="small text-muted mt-2 mb-0">
                    <!-- Texte descriptif du service suivi du statut 'En Cours' en vert et gras -->
                    SERVICE DÎNER • <span class="text-success fw-bold">En Cours</span>
                <!-- Ferme le paragraphe de statut du service -->
                </p>
            <!-- Ferme la div du bloc d'en-tête de la sidebar -->
            </div>

            <!-- Ouvre la balise de navigation avec disposition des boutons en liste verticale flex -->
            <nav class="nav nav-pills flex-column gap-1">

                <!-- Ouvre le lien vers la route de supervision avec contrôle dynamique de la classe 'active' selon la route courante -->
                <a href="{{ route('gerant.orders.index') }}" 
                   class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('gerant.orders.*') ? 'active' : '' }}">
                    <!-- Balise d'icône FontAwesome pour la liste de contrôle (couleur jaune/warning) -->
                    <i class="fa-solid fa-list-check text-warning"></i>
                    <!-- Libellé textuel de l'onglet -->
                    <span>Supervision</span>
                <!-- Ferme la balise du lien de supervision -->
                </a>

                <!-- Ouvre le lien vers la route des employés avec contrôle de la classe active -->
                <a href="#" 
                   class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('gerant.employees.*') ? 'active' : '' }}">
                    <!-- Balise d'icône FontAwesome représentant des utilisateurs -->
                    <i class="fa-solid fa-users"></i>
                    <!-- Libellé textuel de l'onglet -->
                    <span>Employés</span>
                <!-- Ferme la balise du lien employés -->
                </a>

                <!-- Ouvre le lien vers la route des statistiques avec contrôle de la classe active -->
                <a href="{{ route('gerant.statistics.index') }}" 
                   class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('gerant.statistics.*') ? 'active' : '' }}">
                    <!-- Balise d'icône FontAwesome représentant un graphique linéaire -->
                    <i class="fa-solid fa-chart-line"></i>
                    <!-- Libellé textuel de l'onglet -->
                    <span>Statistiques</span>
                <!-- Ferme la balise du lien statistiques -->
                </a>

                <!-- Ouvre le lien vers la route des réclamations avec contrôle de la classe active -->
                <a href="{{ route('gerant.claims.index') }}" 
                   class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('gerant.claims.*') ? 'active' : '' }}">
                    <!-- Balise d'icône FontAwesome représentant un panneau d'avertissement -->
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <!-- Libellé textuel de l'onglet -->
                    <span>Réclamations</span>
                <!-- Ferme la balise du lien réclamations -->
                </a>

            <!-- Ferme la balise nav du menu de navigation -->
            </nav>
        <!-- Ferme la div englobant la partie supérieure de la sidebar -->
        </div>

        <!-- Ouvre le bloc inférieur de la sidebar (profil + déconnexion) séparé par une bordure supérieure (border-top) -->
        <div class="border-top border-secondary pt-3 d-flex align-items-center justify-content-between">
            <!-- Ouvre le conteneur des informations de l'utilisateur -->
            <div>
                <!-- Nom de l'utilisateur connecté affiché en gras (fw-bold) -->
                <p class="small mb-0 fw-bold">Gérant Zeduc Space</p>
                <!-- Libellé du rôle/service en texte petit de 0.75rem -->
                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Service du Soir</p>
            <!-- Ferme le conteneur des informations utilisateur -->
            </div>

            <!-- Formulaire HTML pour envoyer la requête HTTP POST de déconnexion -->
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                <!-- Directive de sécurité Blade générant le jeton CSRF obligatoire pour valider la soumission POST -->
                @csrf
                <!-- Bouton de soumission du formulaire stylisé en lien simple sans soulignement -->
                <button type="submit" class="btn btn-link text-secondary text-decoration-none p-1 hover-danger">
                    <!-- Icône FontAwesome représentant une porte de sortie (logout) -->
                    <i class="fa-solid fa-right-from-bracket"></i>
                <!-- Ferme le bouton de soumission -->
                </button>
            <!-- Ferme le formulaire de déconnexion -->
            </form>
        <!-- Ferme la div du bloc inférieur de la sidebar -->
        </div>

    <!-- Ferme la balise aside de la barre latérale -->
    </aside>

    <!-- Ouvre le conteneur principal de droite occupant tout l'espace restant (flex-grow-1) avec scroll vertical (overflow-auto) -->
    <div class="flex-grow-1 d-flex flex-column overflow-auto">

        <!-- Ouvre l'en-tête supérieur (header) avec bordure basse et espacements px-4 py-3 -->
        <header class="px-4 py-3 bg-transparent d-flex justify-content-between align-items-center border-bottom">
            <!-- Div vide servant d'espaceur flexible pour repousser les éléments utilisateur vers la droite -->
            <div></div>

            <!-- Ouvre le conteneur des outils de l'en-tête (Recherche, Notifications, Profil) alignés avec un espace gap-4 -->
            <div class="d-flex align-items-center gap-4">
                <!-- Bouton icône pour la fonction de recherche globale -->
                <button class="btn btn-link text-secondary p-0"><i class="fa-solid fa-magnifying-glass"></i></button>

                <!-- Bouton icône pour le centre de notifications, positionné en relatif pour le badge -->
                <button class="btn btn-link text-secondary p-0 position-relative">
                    <!-- Icône d'une cloche de notification -->
                    <i class="fa-regular fa-bell"></i>
                    <!-- Pastille rouge absolue indiquant la présence de notifications non lues -->
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                <!-- Ferme le bouton de notification -->
                </button>

                <!-- Ouvre le bloc d'alignement texte à droite pour le nom et poste du directeur -->
                <div class="text-end">
                    <!-- Nom du directeur en gras -->
                    <p class="small fw-bold mb-0 lh-1">Jean-Christophe D.</p>
                    <!-- Titre du poste en texte grisé et petit -->
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Directeur d'Exploitation</p>
                <!-- Ferme la div des informations profil header -->
                </div>
            <!-- Ferme le conteneur d'outils de l'en-tête -->
            </div>
        <!-- Ferme la balise header d'en-tête -->
        </header>

        <!-- Ouvre la balise main qui contient le cœur de chaque page spécifique avec une marge interne p-4 -->
        <main class="p-4 flex-grow-1">
            <!-- Directive Blade réservant l'emplacement pour y injecter le contenu de la vue fille -->
            @yield('content')
        <!-- Ferme la balise principale main -->
        </main>

    <!-- Ferme la div du conteneur principal droit -->
    </div>

    <!-- Charge le bundle JavaScript complet de Bootstrap 5 depuis le CDN (nécessaire pour les dropdowns, modales, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Ferme la balise body du document -->
</body>
<!-- Ferme la balise html racine -->
</html>
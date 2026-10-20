<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration - Mon Miam Miam')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --brand-primary: #E74C3C;
            --brand-dark: #1E293B;
            --brand-light: #F8FAFC;
            --brand-accent: #F39C12;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--brand-light);
        }

        /* Sidebar personnalisée selon la charte */
        .sidebar {
            min-height: 100vh;
            background-color: var(--brand-dark);
        }

        .brand-title {
            color: #FFFFFF;
            font-weight: 700;
        }

        .brand-title span {
            color: var(--brand-primary);
        }

        .sidebar .nav-link {
            color: #CBD5E1;
            margin-bottom: 6px;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--brand-primary);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            width: 20px;
        }

        /* Bouton principal personnalisé */
        .btn-brand {
            background-color: var(--brand-primary);
            color: #fff;
            border: none;
        }

        .btn-brand:hover {
            background-color: #C0392B;
            color: #fff;
        }

        .main-content {
            padding: 24px;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- 1. SIDEBAR / MENU LATÉRAL -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
                <div class="text-center my-3">
                    <h4 class="brand-title m-0">Mon <span>Miam Miam</span></h4>
                    <small class="text-muted fs-7">Espace Admin</small>
                </div>
                <hr class="text-secondary my-3">
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">
                            <i class="fa-solid fa-list me-2"></i> Catégories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.menu.index') }}">
                            <i class="fa-solid fa-utensils me-2"></i> Plats du Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.employees.index') }}">
                            <i class="fa-solid fa-users me-2"></i> Employés
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.promotions.index') }}">
                            <i class="fa-solid fa-tags me-2"></i> Promotions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.claims.index') }}">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Réclamations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.settings.index') }}">
                            <i class="fa-solid fa-gear me-2"></i> Paramètres
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- 2. CONTENU PRINCIPAL -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                
                <!-- HEADER SUPÉRIEUR -->
                <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom pt-3">
                    <h2 class="h4 text-dark fw-bold">Panneau de Contrôle</h2>
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-semibold text-secondary"><i class="fa-solid fa-user-shield me-1"></i> Admin</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline mb-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fa-solid fa-right-from-bracket me-1"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </header>

                <!-- MESSAGES DE NOTIFICATION (ALERTS) -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- ZONE D'INJECTION DU CONTENU DES AUTRES VUES -->
                @yield('content')

            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
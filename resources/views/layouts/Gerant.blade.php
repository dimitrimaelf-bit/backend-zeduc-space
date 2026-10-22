{{-- Indique au navigateur qu'il s'agit d'un document HTML5 moderne --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Espace Gérant')</title>

    {{-- CSS Bootstrap 5 & FontAwesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f5f0eb;
        }
        
        /* Configuration de la barre laterale responsive */
        .sidebar {
            width: 100%;
            background-color: #1e1b18;
        }

        /* La barre laterale reste dans le flux pour ne pas recouvrir le contenu. */
        @media (min-width: 768px) {
            .sidebar {
                width: 280px;
                min-width: 280px;
                min-height: 100vh;
            }
        }

        .manager-content {
            min-width: 0;
        }

        .sidebar .nav-link {
            color: #a0a0a0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link:hover {
            background-color: #2a2622;
            color: #ffffff;
        }

        .sidebar .nav-link.active {
            background-color: #38322c;
            color: #ffffff;
            font-weight: 600;
        }

        .badge-gerant {
            background-color: #3a3530;
            color: #ffc107;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="m-0 p-0">

<div class="d-flex flex-column flex-md-row min-vh-100">

    {{-- BARRE SUPERIEURE SUR MOBILE (Affichée uniquement < md) --}}
    <div class="d-md-none bg-dark text-white p-3 d-flex justify-content-between align-items-center border-bottom border-secondary">
        <div>
            <span class="h6 mb-0 fw-bold">ZeDuc@Space</span>
            <span class="badge badge-gerant ms-2">GÉRANT</span>
        </div>
        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSidebar" aria-expanded="false">
            <i class="fa-solid fa-bars fs-5"></i>
        </button>
    </div>

    {{-- SIDEBAR (Pliable sur mobile, toujours visible sur PC) --}}
    <aside id="mobileSidebar" class="sidebar collapse d-md-flex text-white flex-column justify-content-between p-3 flex-shrink-0"
           style="background: linear-gradient(rgba(20, 20, 20, {{ View::yieldContent('sidebar-opacity', '0.5') }}), rgba(20, 20, 20, {{ View::yieldContent('sidebar-opacity', '0.5') }})), url('{{ asset('images/' . View::yieldContent('sidebar-image', 'poisson-supervision.jpg')) }}');
                  background-size: cover;
                  background-position: center;
                  background-repeat: no-repeat;
                  z-index: 1000;">

        <div>
            {{-- En-tête Sidebar --}}
            <div class="mb-4 d-none d-md-block">
                <h1 class="h5 font-weight-bold mb-1">ZeDuc@Space</h1>
                <span class="badge badge-gerant text-uppercase px-2 py-1 rounded">
                    ESPACE GÉRANT
                </span>
                <p class="small text-info mt-2 mb-0">
                    SERVICE DÎNER • <span class="text-success fw-bold">En Cours</span>
                </p>
            </div>

            {{-- Navigation --}}
            <nav class="nav nav-pills flex-column gap-1">

                <a href="{{ route('gerant.orders.index') }}" 
                   class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('gerant.orders.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check text-warning"></i>
                    <span>Supervision</span>
                </a>

                <a href="{{ route('gerant.employees.index') }}" 
                   class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('gerant.employees.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Employés</span>
                </a>

                <a href="{{ route('gerant.statistics.index') }}" 
                   class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('gerant.statistics.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Statistiques</span>
                </a>

                <a href="{{ route('gerant.claims.index') }}" 
                   class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('gerant.claims.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Réclamations</span>
                </a>

            </nav>
        </div>

        {{-- Profil + Déconnexion --}}
        <div class="border-top border-secondary pt-3 mt-3 d-flex align-items-center justify-content-between">
            <div>
                <p class="small mb-0 fw-bold">Gérant Zeduc Space</p>
                <p class="small mb-0 fw-bold" style="color: #f3a683; font-size: 0.8rem;">Service du Soir</p>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-link text-secondary text-decoration-none p-1">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- CONTENU PRINCIPAL --}}
    <div class="manager-content flex-grow-1 d-flex flex-column min-vh-100">

        {{-- En-tête supérieur --}}
        <header class="px-4 py-3 bg-white d-flex justify-content-between align-items-center border-bottom shadow-sm">
            <div></div>

            <div class="d-flex align-items-center gap-4">
                <div class="text-end">
                    <p class="small fw-bold mb-0 lh-1">Aurelie Poutougnigni.</p>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Gérante</p>
                </div>
            </div>
        </header>

        {{-- Contenu injecté --}}
        <main class="p-3 p-md-4 flex-grow-1">
            @yield('content')
        </main>

    </div>

</div>

    {{-- Script Bootstrap 5 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
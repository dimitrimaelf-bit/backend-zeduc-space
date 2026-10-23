{{-- Layout principal de l'application --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Mon Miam Miam'))</title>
    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --zeduc-gold: #cfbd97;
            --zeduc-black: #0f0f0f;
            --zeduc-gold-hover:#bfae88;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #cfbd97;
            color: var(--zeduc-black);
        }

        .text-gold { color: var(--zeduc-gold) !important; }
        .bg-gold{ background-color: var(--zeduc-gold) !important; }

        .btn-gold {
            background-color: var(--zeduc-gold);
            border: 1px solid var(--zeduc-gold);
            color: #0f0f0f;
            font-weight: 600;
        }
        .btn-gold:hover { background-color: #e6c35c; color: #0f0f0f; }

        .border-gold { border: 1px solid var(--zeduc-gold) !important; }

        .navbar-zeduc {
            background-color: #000000;
            padding: .75rem 0;
        }
        .navbar-zeduc .nav-link { color: #ffffff; }
        .navbar-zeduc .nav-link.active,
        .navbar-zeduc .nav-link:hover { color: var(--zeduc-gold) !important; }

        .hero-section {
            position: relative;
            background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.85)), #1a1a1a;
            padding: 4rem 0;
        }

        .text-secondary { color: #b5b5b5 !important; }

        /* Fonds spécifiques aux pages d'authentification */
        .auth-background {
            background-image: url('/images/background-miam.jpg'); /* Assurez-vous que l'image existe dans public/images/ */
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            opacity: 0.4;
            z-index: -1;
        }
        /* Background sombre pour mot de passe oublié */
        .dark-background {
            background-color: #0f0f0f;
            min-height: 100vh;
        }

        .gold { color: var(--zeduc-gold) !important; }
        .home-page { background-color: #0f0f0f; color: #ffffff; }
    </style>
</head>
<body class="@yield('body-class')">

    <!-- Contenu principal -->
    @yield('full-width')
    @yield('content')
    <!-- Bootstrap JS Bundle (inclut Popper) -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
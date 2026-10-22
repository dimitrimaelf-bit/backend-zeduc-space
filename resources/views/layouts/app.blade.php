<! -- Layout principal de l'application -- >

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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
       
        /* Background personnalisé pour les pages d'authentification */
        .auth-background {
            background-image: url('/images/background-miam.jpg'); /* Assurez-vous que l'image existe dans public/images/ */
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }
        /* Background sombre pour mot de passe oublié */
        .dark-background {
            background-color: #0f0f0f;
            min-height: 100vh;
        }
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
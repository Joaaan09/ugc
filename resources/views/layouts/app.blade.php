<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Aplicación</title>
    <!-- Aquí puedes incluir tus estilos CSS, como Bootstrap o tu propio CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <head>
    <!-- Otros enlaces... -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

</head>
<body>
    <div class="container">
        <!-- Barra de navegación (si es necesario) -->
        <nav>
            <a href="{{ route('influencer.index') }}">Influencers</a>
        </nav>

        <!-- Aquí se inyecta el contenido de cada vista -->
        @yield('content')
    </div>

    <!-- Aquí puedes agregar scripts JS -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

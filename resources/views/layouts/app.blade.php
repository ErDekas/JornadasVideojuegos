<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('events.index') }}">Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('speakers.index') }}">Ponentes</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @if(Session::has('user'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.show') }}">Mi Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/logout') }}">Cerrar sesión</a>
                            
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3>Eventos</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('events.index') }}" class="text-white">Ver eventos</a></li>
                        <li><a href="{{ route('speakers.index') }}" class="text-white">Ponentes</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h3>Cuenta</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('login') }}" class="text-white">Iniciar sesión</a></li>
                        <li><a href="{{ route('register') }}" class="text-white">Registrarse</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h3>Contacto</h3>
                    <p>Email: info@eventosapp.com</p>
                    <p>Teléfono: (123) 456-7890</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
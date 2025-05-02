<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>Lorem</title>
    </head>
    <body>
        <header class="header-container">
            <div class="header-content">                                                                                                                            
                <h2 class="title-logo"><a href="{{ route('dashboard') }}">Lorem</a></h2>
                <ul class="nav-list">
                    <li><a href="{{ route('user.list') }}" class="nav-link">Usuários</a></li>
                    <li><a href="{{ route('user.create') }}" class="nav-link">Cadastrar</a></li>
                    <li><a href="{{ route('dashboard') }}" class="nav-link">Sair</a></li>
                </ul>
            </div>
        </header>
        <main class="main-container">
            @yield('content')
        </main>
    </body>
</html>
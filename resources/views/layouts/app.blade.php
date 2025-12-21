<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel 12 Filament + Prestashop 8')</title>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js'])
     <link rel="stylesheet" href="{{ asset('css/filament-overrides.css') }}"> --}}

     @vite([
    'resources/css/app.css',
    'resources/css/filament-overrides.css',
    'resources/js/app.js',
])


</head>
<body>
    @include('components.nav')

    <div class="container-fluid mt-4">
        <div class="row">
            @include('components.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')

            </main>
        </div>
    </div>
</body>
</html>

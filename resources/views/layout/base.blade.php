<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack("metasa")
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Taskly</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @stack("head")    
</head>
<body>
    <x-toast-manager></x-toast-manager>
    <x-modal-manager></x-modal-manager>
    <x-page-loader></x-page-loader>
    @yield('body')
    @stack('component')
    @include("components.notification-script")
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.1/echo.min.js"></script> --}}
    @stack('page')
</body>
</html>

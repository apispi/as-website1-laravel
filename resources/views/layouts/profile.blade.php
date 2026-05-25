<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Profile - ApiSpi')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/profile.js'])
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9NX96RC3FF"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-9NX96RC3FF');</script>
</head>
<body style="margin:0; background:#0a0805;">
    @yield('content')
</body>
</html>

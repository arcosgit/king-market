<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @routes
    @inertiaHead
  </head>
  <body class="bg-dark text-white">
    @inertia
  </body>
</html>

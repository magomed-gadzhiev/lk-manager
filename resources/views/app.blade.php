<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Личный кабинет менеджера</title>
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <?php
        $manifestPath = public_path('build/manifest.json');
        $isLocal = app()->environment('local');
    ?>

    @if (file_exists($manifestPath))
        {{-- Production (built) or local with build present --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif ($isLocal)
        {{-- Local fallback to Vite dev server to avoid Vite manifest exception when not built --}}
        <script type="module" src="http://localhost:5173/@@vite/client"></script>
        <link rel="stylesheet" href="http://localhost:5173/resources/css/app.css" />
        <script type="module" src="http://localhost:5173/resources/js/app.js"></script>
    @else
        {{-- In non-local env, require built assets to be present --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
<div id="app"></div>
<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

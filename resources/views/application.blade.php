<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacy</title>
</head>

<body id="app" class="font-sans antialiased text-gray-800 dark:text-white bg-zinc-100 dark:bg-gray-900/60">
    @vite('resources/ts/app.ts')
    @vite('resources/css/app.css')
</body>

</html>